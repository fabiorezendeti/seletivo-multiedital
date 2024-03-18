<?php

namespace App\Http\Controllers\Candidate;

use App\Commands\SubscriptionFreeze;
use App\Exceptions\SubscriptionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscription;
use App\Models\Process\ExamResource;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use App\Models\Process\SelectionCriteria;
use App\Models\Process\SpecialNeed;
use App\Models\Process\Subscription;
use App\Models\User;
use App\Models\Pagtesouro\PaymentRequest;
use App\Notifications\Subscribe;
use App\Repository\EnrollmentCallRepository;
use App\Repository\SubscriptionRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as FacadesRequest;


class SubscriptionController extends Controller
{

    private SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function create(Notice $notice, $offer, FacadesRequest $request)
    {
        $uploadMaxSize = (int) ini_get("upload_max_filesize") - 1;
        $offer = $notice->offers()->with([
            'distributionVacancies' => function ($q) {
                $q->with('affirmativeAction');
            }
        ])
        ->findOrFail($offer);

        $selectionCriterias = SelectionCriteria::whereIn(
            'id', $offer->distributionVacancies->unique('selection_criteria_id')->pluck('selection_criteria_id')
        )->get();

        $subscription = Auth::user()->subscriptions()->where('notice_id', $notice->id)->first() ?? new Subscription();

        $modalities = ['Ensino Médio Regular', 'Ensino Médio Técnico', 'Ensino Médio via Certificação do ENEM', 'Ensino Médio via Certificação do Encceja'];
        $specialNeeds = SpecialNeed::isActivated()->get();
        $examResources = ExamResource::isActivated()->get();
        return view('candidate.subscription.create', compact(
            'offer',
            'notice',
            'modalities',
            'subscription',
            'selectionCriterias',
            'uploadMaxSize',
            'specialNeeds',
            'examResources'
        ));
    }

    public function requestRecourse(Subscription $subscription, Request $request)
    {
        $this->validate($request, [
            'position' => ['required', 'integer'],
            'justify'  => ['required']
        ], [], [
            'position'  => 'Colocação na Classificação Preliminar',
            'justify'   => 'Justificativa'
        ]);

        $subscription->setPreliminaryClassificationRecourse(Carbon::now(), $request->position, $request->justify);
        $subscription->save();
        return redirect()->route('candidate.subscription.show', ['subscription' => $subscription])->with('success', 'Seu Recurso de Classificação Preliminar foi registrado com sucesso');
    }

    public function show(Subscription $subscription, EnrollmentCallRepository $enrollmentCallRepository)
    {
        $hasCalls = Gate::check('printCallStatus',$subscription);
        $callResults = ($hasCalls) ? $enrollmentCallRepository->getCallsBySubscription($subscription) : collect();
        $calls = ($hasCalls) ? $enrollmentCallRepository->callsByNotice($subscription->notice) : collect();
        $tableItens = [];
        foreach ($calls as $call) {
            $tableItens[$call] = $callResults->where('call_number',$call)->first();
        }
        $paymentRequest = PaymentRequest::where('subscription_id', $subscription->id)->first();
        return view('candidate.subscription.ticket', compact(
            'subscription',
            'tableItens',
            'paymentRequest'
        ));
    }

    public function showInterest(Subscription $subscription)
    {
        try {
            $subscription->is_homologated = true;
            $subscription->save();
            return redirect()->route('candidate.subscription.show', ['subscription' => $subscription])
                ->with('success','Sua inscrição foi homologada, agora você está concorrendo à vaga pelo SISU');
        } catch (Exception $exception) {
            return redirect()->route('candidate.subscription.show', ['subscription' => $subscription])
                ->with('error','Um erro ocorreu na manifestação de interesse');
        }

    }

    private function checkIfOfferExists(Offer $offer, Request $request, Notice $notice)
    {
            if ($offer->notice_id !== $notice->id) return false;
            $distributionVacancy = $offer->distributionVacancies()
                ->where('selection_criteria_id',$request->selection_criteria)
                ->find($request->distribution_of_vacancies);
            if (!$distributionVacancy) return false;
            return true;
    }


    private function notify(User $user, Offer $offer, Notice $notice, Subscription $subscription)
    {
        $user = Auth::user();
        $subscribe = new Subscribe(
            $offer->courseCampusOffer->campus->name,
            $offer->courseCampusOffer->course->name,
            $notice->number,
            $subscription->distributionOfVacancy->affirmativeAction->slug,
            $subscription->distributionOfVacancy->selectionCriteria->details,
            $user->name,
            $notice->candidate_additional_instructions ?? ''
        );
        $user->notify($subscribe);
    }

    public function store(Notice $notice, Offer $offer, StoreSubscription $request, SubscriptionFreeze $subscriptionFreeze)
    {
        $file = $request->file('documento_comprovacao');
        $fileName = null;
        if (!$this->checkIfOfferExists($offer, $request, $notice)) {
            throw new SubscriptionException('O critério de seleção selecionado não é compatível com a oferta');
        }
        $subscription = Subscription::where('notice_id', $notice->id)->where('user_id', Auth::id())->first() ?? new Subscription();
        $this->validateFile($request, $subscription);
        DB::beginTransaction();
        try {
            $subscription = $this->subscriptionRepository->updateOrCreateSubscription($subscription, $notice, $request, $offer);
            DB::commit();
            // AGENDA NOTIFICAÇÃO POR EMAIL PARA AVALIAÇÃO DO SERVIÇO
            $protocolo = $subscription->subscription_number;
            $cpf = Auth::user()->cpf;
            $cod_servico = $notice->modality->id == 3 ? env('AVALIACAO_SERVICO_INSCRICAO_SUPERIOR') : env('AVALIACAO_SERVICO_INSCRICAO_MEDIO');
            try{
                $SR = new ServiceRatingController($protocolo, $cpf, $cod_servico, "Ei! psiu... O que achou do nosso processo de inscrição? 🤩 - Portal do Candidato","Inscrição {$subscription->subscription_number} no Processo Seletivo {$notice->number} - {$notice->description}");
                $SR->exec();
            }catch (\Exception $e){
                Log::error($e->getMessage(),['Avaliação do Serviço']);
            }
            //FIM
            $this->notify(Auth::user(), $offer, $notice, $subscription);
            return redirect()->route('candidate.subscription.show', ['subscription' => $subscription]);
        } catch (SubscriptionException $exception) {
            DB::rollBack();
            return back()->with('error', $exception->getMessage());
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), ['SUBSCRIPTION', 'Line' . $exception->getLine()]);
            return back()->with('error', "Um erro ocorreu ao realizar sua inscrição");
        }
    }

    private function validateFile(Request $request, Subscription $subscription)
    {
        if ($request->selection_criteria > 2) {
            $uploadMaxSize = (int) ini_get("upload_max_filesize") * 1024 ;
            $mimeTypes = 'jpeg,pdf,png';
            $rules = ($subscription->hasSupportingDocuments()
                && $subscription->distributionOfVacancy->selection_criteria_id == $request->selection_criteria)
                ? ['nullable', 'file', "mimes:$mimeTypes","max:$uploadMaxSize"]
                : ['required', 'file', "mimes:$mimeTypes","max:$uploadMaxSize"];
            $this->validate($request, [
                'documento_comprovacao' => $rules,
            ], ['documento_comprovacao.*' => 'Um erro ocorreu ao carregar o arquivo, verifique se o tamanho e formato do arquivo correspondem ao correto'], ['documento_comprovacao'   => 'Boletim / Documento de Comprovação']);
        }
    }

    /**
     * Imprimir confirmação de Local de Prova
     *
     * @param  Notice $notice
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function printExamLocationRoom(Subscription $subscription)
    {
        return view('candidate.subscription.print-exam-location-room',compact('subscription'));
    }
}
