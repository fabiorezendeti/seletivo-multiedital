<?php

namespace App\Http\Controllers\Candidate;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Process\Notice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Pagtesouro\Parameters;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Providers\RouteServiceProvider;
use App\Repository\ParametersRepository;
use App\Models\Pagtesouro\PaymentRequest;

class PagTesouroController extends Controller
{

    private ParametersRepository $parametersRepository;
    private Parameters $pagTesouroParameters;

    public function __construct(ParametersRepository $parametersRepository)
    {
        $this->parametersRepository = $parametersRepository;
        $this->pagTesouroParameters = $this->parametersRepository->getPagTesouroParameters();
    }

    public function index(Subscription $subscription)
    {
        return view('candidate.subscription.payment.index', compact('subscription'));
    }

    public function store(Request $request, Subscription $subscription)
    {
        $token = $this->pagTesouroParameters->pagtesouro_token;
        $urlRetorno = route('candidate.subscription.show', ['subscription' => $subscription]);
        $urlNotificacao = route('subscription.payment.update', ['subscription' => $subscription]);
        $data = [
            'codigoServico' => $this->pagTesouroParameters->pagtesouro_cod_servico,
            'referencia' => $subscription->subscription_number,
            'vencimento' =>  str_replace('/', '', $subscription->notice->payment_date->format('d/m/Y')),
            'cnpjCpf' => preg_replace('/[^0-9]/', "", $subscription->user->cpf),
            'nomeContribuinte' => $subscription->user->name,
            'valorPrincipal' => $subscription->notice->registration_fee,
            'modoNavegacao' => 2,
            'urlRetorno' => $urlRetorno,
            'urlNotificacao' => $urlNotificacao
        ];

        $response = Http::withToken($token)
            ->post($this->pagTesouroParameters->pagtesouro_url_solicitacao_pagamento, $data);

        if ($response->successful()) {
            $response = json_decode($response->body());

            $paymentRequest = PaymentRequest::updateOrCreate(
                ['subscription_id' => $subscription->id],
                [
                    'subscription_id' => $subscription->id,
                    'request' => json_encode($data),
                    'idPagamento' => $response->idPagamento,
                    'situacao_codigo' => $response->situacao->codigo,
                    'proxima_url' => $response->proximaUrl
                ]
            );

            return redirect()->route('candidate.subscription.payment.show', compact('subscription', 'paymentRequest'));
        } else {
            Log::error($response->body(), ['paymentRequest']);
            $response = json_decode($response->body());
            return redirect()->route('candidate.subscription.payment', ['subscription' => $subscription])
                ->with('error', $response[0]->codigo.' - Recebemos um erro na tentativa de pagamento: '.$response[0]->descricao);
        }
    }

    public function show(Subscription $subscription, PaymentRequest $paymentRequest, string $erro = null)
    {
        return view('candidate.subscription.payment.show', compact('subscription', 'paymentRequest', 'erro'));
    }

    /**
     * Function acessada pelo pagtesouro para informar que houve alguma mudança de situação de pagamento
     */
    public function update(Request $request, Subscription $subscription)
    {
        try {
            $paymentRequest = PaymentRequest::where('idPagamento', $request->idPagamento)->firstOrFail();
            $paymentStatus = $this->getPaymentStatus($paymentRequest);
            $paymentRequest->situacao_codigo = $paymentStatus->situacao->codigo;
            $paymentRequest->save();
            if (!$subscription->is_homologated && $paymentRequest->situacao_codigo == 'CONCLUIDO') {
                $subscription->is_homologated = true;
                $subscription->save();
            }
            return response($paymentStatus->situacao->codigo, 200)
                ->header('Content-Type', 'application/json');
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), ['PagTesoutroController']);
            $error = array('codigo' => "500", 'descricao' => $exception->getMessage());
            return response(array($error), 500)
                ->header('Content-Type', 'application/json');
        }
    }

    public function viewPaymentStatus(Subscription $subscription, PaymentRequest $paymentRequest)
    {
        try {
            $paymentStatus = $this->getPaymentStatus($paymentRequest);
            $paymentRequest = PaymentRequest::where('idPagamento', $paymentStatus->idPagamento)->firstOrFail();

            //segundo a documentação, pode ocorrer falha na atualização automática do pagtesouro, por isso eu atualizo aqui também.
            $paymentRequest->situacao_codigo = $paymentStatus->situacao->codigo;
            $paymentRequest->save();
            if (!$subscription->is_homologated && $paymentRequest->situacao_codigo == 'CONCLUIDO') {
                $subscription->is_homologated = true;
                $subscription->save();
            }

            return view('candidate.subscription.payment.payment-status', compact('subscription', 'paymentRequest', 'paymentStatus'));
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), ['paymentRequest']);
            return redirect()->route('candidate.subscription.payment', ['subscription' => $subscription])
                ->with('error', 'Recebemos um erro ao tentar consultar a situação do pagamento');
        }
    }

    protected function getPaymentStatus(PaymentRequest $paymentRequest)
    {
        $parameters = $this->parametersRepository->getPagTesouroParameters();
        $token = $parameters->pagtesouro_token;

        $response = Http::withToken($token)
            ->get($parameters->pagtesouro_url_consulta_pagamento . "/" . $paymentRequest->idPagamento);

        if ($response->successful()) {
            $response = json_decode($response->body());
            return ($response);
        } else {
            throw new Exception("Erro ao consultar pagamento {$response->body()}", 500);
        }
    }
}
