<?php

namespace App\Http\Controllers\Admin;

use App\Models\Organization\Campus;
use App\Models\Process\PaymentExemption;
use App\Repository\CampusRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Audit\Justify;
use App\Models\Security\Audit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Models\Process\Subscription;
use App\Models\Process\Notice;
use App\Models\Process\SelectionCriteria;
use App\Services\Subscription\PaymentExemptionService;
use App\Http\Controllers\Controller;
use App\Repository\ParametersRepository;


class NoticePaymentExemptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Notice $notice, Request $request)
    {

        $subscriptions = $notice->subscriptions();
        $subscriptions->with('paymentExemption');
        if (Gate::denies('isAdmin')) {
            $campuses = Auth::user()->permissions()
                ->select('permissions.campus_id')
                ->where('role_id', 2)
                ->where('user_id', Auth::user()->id)->get();
                $subscriptions->byCampusesByIds($campuses->pluck('campus_id'));
        }
        if ($request->search) {
            $subscriptions->whereHas('user', function ($q) use ($request) {
                $q->where('cpf', $request->search);
            });
            $subscriptions->orWhere('subscription_number', (int)$request->search);
        }
        $subscriptions = $subscriptions->whereNotNull('payment_exemption_id')->orderBy('updated_at')->paginate();
        return view('admin.notices.payment-exemptions.index', compact(
            'notice',
            'subscriptions'
        ));
    }
    public function report(Notice $notice, Request $request)
    {
        if (!$request->html) {
            $CampusRepository = new CampusRepository();
            $campuses = $CampusRepository->getCampusesByNotice($notice);
            return view('admin.reports.payments-exemptions', compact('notice', 'campuses'));
        }
        $dados = Subscription::select(
            'subscriptions.subscription_number AS inscricao',
            'u.name AS candidato',
            'c.name AS campus',
            'courses.name AS curso',
            'p.is_accepted AS status',
            'p.rejected_reason AS motivo_rejeicao'
        )
            ->join('payment_exemptions AS p', 'subscriptions.payment_exemption_id', '=', 'p.id')
            ->join('distribution_of_vacancies AS d', 'subscriptions.distribution_of_vacancies_id','=', 'd.id')
            ->join('offers AS o', 'd.offer_id', '=', 'o.id')
            ->join('course_campus_offers AS cco', 'o.course_campus_offer_id', '=', 'cco.id')
            ->join('courses', 'cco.course_id','=','courses.id')
            ->join('campuses AS c', 'cco.campus_id','=','c.id')
            ->join('users AS u', 'subscriptions.user_id', '=', 'u.id')
            ->whereNotNull('payment_exemption_id')
            ->where('subscriptions.notice_id', '=', $notice->id)
            ->orderBy('c.name', 'ASC')
            ->orderBY('courses.name', 'ASC')
            ->orderBy('u.name', 'ASC');
        if(!empty($request->campus)) $dados = $dados->where('c.id', $request->campus);
        $dados = $dados->get();
        /**
         * ESTRUTURA DOS DADOS ENVIADOS A BLADE:
         * "inscricao" => 2213122933
         * "candidato" => "Ryan Sued"
         * "campus" => "Blumenau"
         * "status" => false
         * "motivo_rejeicao" => "deixou de apresentar os documentos
         */
        return view('admin.reports.html.payment-exemptions', compact('notice', 'dados'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Notice $notice, $id)
    {
        return Subscription::with([
            'user',
            'paymentExemption',
        ])->findOrFail($id)->toJson();
    }

    public function viewDocumentIdFront(Notice $notice, Subscription $subscription, PaymentExemptionService $paymentExemptionService)
    {
        try {
            return $paymentExemptionService->downloadDocumentIdFront($subscription);
        } catch (Exception $exception) {
            return abort('404');
        }
    }

    public function viewDocumentIdBack(Notice $notice, Subscription $subscription, PaymentExemptionService $paymentExemptionService)
    {
        try {
            return $paymentExemptionService->downloadDocumentIdBack($subscription);
        } catch (Exception $exception) {
            return abort('404');
        }
    }

    public function viewDocumentForm(Notice $notice, Subscription $subscription, PaymentExemptionService $paymentExemptionService)
    {
        try {
            return $paymentExemptionService->downloadDocumentForm($subscription);
        } catch (Exception $exception) {
            return abort('404');
        }
    }

    public function accept(Notice $notice, Subscription $subscription)
    {
        $paymentExemption = $subscription->paymentExemption;
        $paymentExemption->is_accepted = true;
        $paymentExemption->rejected_reason = null;
        $save = $paymentExemption->save();
        if ($save){
            $subscription->is_homologated = true;
            $subscription->save();
            return response()->json($subscription, 200);
        }
        return response()->json(['error' => true], 204);
    }


    public function reject(Request $request, Notice $notice, Subscription $subscription)
    {
        $paymentExemption = $subscription->paymentExemption;
        $paymentExemption->is_accepted = false;
        $paymentExemption->rejected_reason = $request->rejected_reason;
        $save = $paymentExemption->save();
        if ($save) return response()->json($subscription, 200);
        return response()->json(['error' => true], 204);
    }

    public function updatePersonalData(Request $request, Notice $notice, Subscription $subscription)
    {
        $user = $subscription->user;
        $user->sex = $request->sex;
        $user->rg_issue_date = $request->rg_issue_date;
        $user->social_identification_number = $request->social_identification_number;

        $save = $user->save();
        if ($save) return response()->json($subscription, 200);
        return response()->json(['error' => true], 204);
    }

    public function viewTxt(Notice $notice, Subscription $subscription, PaymentExemptionService $paymentExemptionService)
    {
        try {
            $parameters = new ParametersRepository();

            $CNPJ = $parameters->getValueByName('cnpj_instituicao');
            $SLUG = $parameters->getValueByName('sigla_instituicao');
            $edital = preg_replace('/(\/[0-9]+)/', '', $notice->number).$SLUG;
            $edital = str_pad($edital, 6, "0", STR_PAD_LEFT);
            $dataAtual = Carbon::now()->format("dmY");

            $seq = $subscription->paymentExemption->id;

            $user = $subscription->user;
            $birthDate = $user->birth_date->format("dmY");
            $rg = preg_replace('/[^0-9]+/','',$user->rg);
            $rg_issue_date = $user->rg_issue_date->format("dmY");
            $cpf = preg_replace('/[^0-9]+/','',$user->cpf);
            $nome_instituicao_completo = $parameters->getValueByName('nome_instituicao_completo');

            $content = "0;$CNPJ;$SLUG;$nome_instituicao_completo;\n";
            $content .= "1;$user->name;$user->social_identification_number;$birthDate;$user->sex;";
            $content .= "$rg;$rg_issue_date;$user->rg_emmitter;$cpf;$user->mother_name;";

            $fileName = $CNPJ.'_'.$edital.'_'.$dataAtual.'_'.$seq.".txt";
            return response($content)
                ->withHeaders([
                    'Content-Type' => 'text/plain',
                    'Cache-Control' => 'no-store, no-cache',
                    'Content-Disposition' => 'attachment; filename='.$fileName,
                ]);
        } catch (Exception $exception) {
            return abort('404');
        }
    }
}
