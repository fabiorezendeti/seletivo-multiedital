<?php

namespace App\Http\LiveComponents\Admin\Notice;

use Exception;
use Livewire\Component;
use App\Models\Process\Notice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use App\Repository\OfferRepository;
use Illuminate\Support\Facades\Log;
use App\Repository\CampusRepository;
use App\Actions\Fortify\CreateNewUser;
use App\Commands\SubscriptionFreeze;
use App\Models\Address\City;
use Illuminate\Support\Facades\Storage;
use App\Models\Process\AffirmativeAction;
use App\Models\Process\DistributionOfVacancies;
use App\Models\Process\Offer;
use App\Models\Process\Subscription;
use App\Models\User;
use App\Repository\SubscriptionRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\Notice\SISUImporter as NoticeSISUImporter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

class SisuImporter extends Component
{

    use AuthorizesRequests;

    public Collection $offers;
    public $fileName;
    public bool $allowImport = false;
    public $notice;
    public int $modalityId;
    public $affirmativeActions = [];
    public $affirmativeActionsAvailable;
    public $errors = [];

    private OfferRepository $offerRepository;
    private SubscriptionRepository $subscriptionRepository;

    public function __construct()
    {
        $this->offerRepository = new OfferRepository();
        $this->subscriptionRepository = new SubscriptionRepository(new SubscriptionFreeze());
    }


    public function mount($offers, $modalityId, Notice $notice)
    {
        $this->offers = $offers;
        $this->notice = $notice;
        $this->modalityId = $modalityId;
        $this->allowImport = $this->offers->where('course_campus_offer', '==', null)->count() < 1;
        $this->affirmativeActionsAvailable = AffirmativeAction::whereHas(
            'modalities',
            function ($q) {
                $q->where('id', $this->modalityId);
            }
        )->get()->toArray();
        foreach ($offers->unique('affirmative_action')->pluck('affirmative_action') as $k => $af) {
            $this->affirmativeActions[$k] = [
                'sisu_imported' => $af,
                'affirmative_action_id' => null
            ];
        }
    }

    public function render(CampusRepository $campusRepository)
    {
        return view('live-components.admin.notice.sisu-importer');
    }

    public function import()
    {
        $this->authorize('isAdmin');
        $affirmativeActions = collect($this->affirmativeActions);
        $file = Storage::disk('documents')->path($this->notice->getNoticeSchemaName() . '/' . $this->fileName);

        $importer = new NoticeSISUImporter();
        $importer->readCsvFileFromPath($file);
        $subscriptions = $importer->getSubscriptions();

        set_time_limit(0);
        ini_set('memory_limit', -1);
        $this->allowImport = false;

        $errors = $this->importOffers($affirmativeActions, $subscriptions);
        $this->allowImport = true;
        if ($errors) {
            dd($errors);
        }
        return redirect()->route('admin.notices.show', ['notice' => $this->notice]);
    }


    private function makeSubscription(User $user, array $data, Offer $offer, DistributionOfVacancies $distributionOfVacancies)
    {
        $subscription = $this->subscriptionRepository->updateOrCreateSubscriptionFromSISU($this->notice, $offer, $distributionOfVacancies, $user, $data);
        return;
    }

    private function createUser(array $subscription, Carbon $today): User
    {
        $cpf = $this->convertSISUCPF($subscription['NU_CPF_INSCRITO']);
        $user = User::where('cpf', $cpf)->first();
        if ($user) return $user;

        $email = (User::where('email', $subscription['DS_EMAIL'])->count() > 0) ? $subscription['NU_CPF_INSCRITO'] . '@importado_do_sisu.gov' : $subscription['DS_EMAIL'];

        $userData = [
            'name'          => $subscription['NO_INSCRITO'],
            'email'         => $email,
            'cpf'           => $cpf,
            'rg'            => $subscription['NU_RG'],
            'rg_emmitter'   => 'SISU',
            'social_name'   => $subscription['NO_SOCIAL'],
            'mother_name'   => $subscription['NO_MAE'],
            'password'      => $subscription['CO_INSCRICAO_ENEM'],
            'birth_date'   => Carbon::createFromFormat('Y-m-d H:i:s', $subscription['DT_NASCIMENTO'])->format('d/m/Y'),
            'is_foreign'    => null,
            'nationality'   => null,
            'city'          => City::where('name', $subscription['NO_MUNICIPIO'])->first()->id,
            'street'        => $subscription['DS_LOGRADOURO'],
            'number'        => $subscription['NU_ENDERECO'],
            'district'      => $subscription['NO_BAIRRO'],
            'zip_code'      => $subscription['NU_CEP'],
            'phone_number' => $subscription['NU_FONE1'],
            'alternative_phone_number' => $subscription['NU_FONE2'],
            'has_whatsapp' => false,
            'has_telegram' => false,
            'complement' => $subscription['DS_COMPLEMENTO']
        ];
        $newUser = (new CreateNewUser())->createWithoutValidation($userData);
        $newUser->email_verified_at = $today;
        $newUser->save();
        return $newUser;
    }


    private function convertSISUCPF(string $cpf)
    {
        while (strlen($cpf) < 11) {
            $cpf = '0' . $cpf;
            gettype($cpf);
        }
        $pattern = '/^([[:digit:]]{3})([[:digit:]]{3})([[:digit:]]{3})([[:digit:]]{2})$/';
        $replacement = '$1.$2.$3-$4';
        return preg_replace($pattern, $replacement, $cpf);
    }

    private function importOffers(Collection $affirmativeActions, Collection $subscriptions)
    {

        $today = Carbon::now();
        $error = [];

        $group = $this->offers->where('course_campus_offer', '!=', null)
            ->groupBy('sisu_course_code')->map(function ($row) {
                return [
                    'offers' => $row,
                    'total' => $row->sum('total_vacancy')
                ];
            });

        $selectionCriteria = $this->notice->selectionCriterias()->first();

        foreach ($group as $k => $item) {
            $theOffer = $this->offerRepository->updateByCourseCampusOffer(
                $this->notice,
                $item['offers']->first()['course_campus_offer']['id'],
                ['total_vacancies'  => $item['total']]
            );
            $courseSubscriptions = $subscriptions->where('CO_IES_CURSO', $item['offers']->first()['sisu_course_code']);
            foreach ($item['offers'] as $i) {
                try {
                    $affirmative_action = $affirmativeActions->where('sisu_imported', $i['affirmative_action']);

                    if (!$affirmative_action->first()['affirmative_action_id']) continue;
                    $distribution = $theOffer->distributionVacancies()->updateOrCreate(
                        [
                            'affirmative_action_id' => $affirmative_action->first()['affirmative_action_id'],
                            'selection_criteria_id'  => $selectionCriteria->id,
                        ],
                        [
                            'total_vacancies'  => $i['total_vacancy'],
                        ]
                    );

                    foreach ($courseSubscriptions->where('NO_MODALIDADE_CONCORRENCIA', $i['affirmative_action']) as $subs) {
                        $user = $this->createUser($subs, $today);
                        $this->makeSubscription($user, $subs, $theOffer, $distribution);
                    }
                } catch (Exception $e) {
                    Log::error($e->getMessage(), ['SISUImport', 'distributionVacancy']);
                    Log::error($e->getTraceAsString(), ['SISUImport', 'distributionVacancy']);
                    $error[$subs['CO_INSCRICAO_ENEM']] = 'Linha: ' . $e->getLine() . ' - ' . $e->getMessage();
                }
            };
        }
        return $error;
    }
}
