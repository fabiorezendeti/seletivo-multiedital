<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Pagtesouro\PaymentRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pagtesouro\Parameters;
use App\Models\Process\Notice;
use App\Models\Process\Subscription;
use App\Repository\ParametersRepository;
use App\Notifications\TestAdminPagtesouro;
use Illuminate\Support\Facades\DB;

class PagtesouroSettingsController extends Controller
{
    private ParametersRepository $parametersRepository;
    private Parameters $pagTesouroParameters;

    public function __construct(ParametersRepository $parametersRepository)
    {
        $this->parametersRepository = $parametersRepository;
        $this->pagTesouroParameters = $this->parametersRepository->getPagTesouroParameters();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user(); 
        $envPagtesouro = $this->getEnvPagtesouroName();
        return view('admin.pagtesouro-settings.index', compact('user','envPagtesouro'));
    }

    public function notify(PaymentRequest $paymentRequest)
    {
        $user = Auth::user();
        $testAdminPagtesouro = new TestAdminPagtesouro(
            $user->name,
            $paymentRequest->idPagamento,
            $paymentRequest->situacao_codigo,
            $paymentRequest->tipo,
            $paymentRequest->valor
        );
        $user->notify($testAdminPagtesouro);
        
    }

    protected function getPaymentStatus($idPagamento, $token, $urlConsultaPagamento)
    {
        $response = Http::withToken($token)
            ->get($urlConsultaPagamento . "/" . $idPagamento);
        if ($response->successful()) {
            $response = json_decode($response->body());
            return ($response);
        } else {
            throw new Exception("Erro ao consultar pagamento {$response->body()}", 500);
        }
    }


    /*
    * Método usado na checagem de ambiente. Faz um pagamento sem armazenar no banco.
    */
    public function noStore(Request $request)
    {
        $token = $this->pagTesouroParameters->pagtesouro_token;
        
        $urlConsultaPagamento = $this->pagTesouroParameters->pagtesouro_url_consulta_pagamento;
        $data = [
            'codigoServico' => $this->pagTesouroParameters->pagtesouro_cod_servico,
            'referencia' => $request->payment_reference,
            'vencimento' =>  str_replace('/', '', $request->payment_date),
            'cnpjCpf' => preg_replace('/[^0-9]/', "", $request->payment_cpf),
            'nomeContribuinte' => $request->payment_name,
            'valorPrincipal' => $request->payment_price,
            'modoNavegacao' => 2,
        ];

        $response = Http::withToken($token)
            ->post($this->pagTesouroParameters->pagtesouro_url_solicitacao_pagamento, $data);

        if ($response->successful()) {
            $response = json_decode($response->body());

            $paymentRequest = new PaymentRequest(
                [
                    'subscription_id' => $request->payment_reference,
                    'request' => json_encode($data),
                    'idPagamento' => $response->idPagamento,
                    'situacao_codigo' => $response->situacao->codigo,
                    'proxima_url' => $response->proximaUrl
                ]
            );
            $envPagtesouro = $this->getEnvPagtesouroName();
            return view('admin.pagtesouro-settings.show', compact('paymentRequest','envPagtesouro'));
        } else {
            Log::error($response->body(), ['paymentRequest']);
            $response = json_decode($response->body());
            return redirect()->route('admin.pagtesouro-settings.index')
                ->with('error', $response[0]->codigo.' - Recebemos um erro na tentativa de pagamento: '.$response[0]->descricao);
        }
    }

    public function show(PaymentRequest $paymentRequest, string $erro = null)
    {
        $envPagtesouro = $this->getEnvPagtesouroName();
        return view('admin.pagtesouro-settings.show', compact('paymentRequest', 'envPagtesouro', 'erro'));
    }

    public function updatePaymentStatus(Request $request, Notice $notice){
        
        $paymentRequestsSet = DB::table('payment_requests')
        ->select('payment_requests.id')
        ->join('subscriptions', 'subscriptions.id', '=', 'payment_requests.subscription_id')
        ->where('subscriptions.notice_id', $notice->id)
        ->where('payment_requests.situacao_codigo','!=','CONCLUIDO')->get();
        
        $affectedCounter = 0;

        foreach ($paymentRequestsSet as $paymentRequest) { 
            $paymentRequest = PaymentRequest::find($paymentRequest->id);
            $subscription = Subscription::find($paymentRequest->subscription_id);
            $response = Http::withToken($this->pagTesouroParameters->pagtesouro_token)
            ->get($this->pagTesouroParameters->pagtesouro_url_consulta_pagamento . "/" . $paymentRequest->idPagamento);
            if ($response->successful()) {
                $paymentStatus = json_decode($response->body());
                if($paymentRequest->situacao_codigo != $paymentStatus->situacao->codigo){
                    $affectedCounter++;
                    $paymentRequest->situacao_codigo = $paymentStatus->situacao->codigo;
                    $paymentRequest->save();
                    if (!$subscription->is_homologated && $paymentRequest->situacao_codigo == 'CONCLUIDO') {
                        $subscription->is_homologated = true;
                        $subscription->save();
                    }
                }
            } /*else {
                throw new Exception("Erro ao consultar pagamento {$response->body()}", 500);
            }*/
        }
            return redirect()->route('admin.notices.pending-payments.report', ['notice' => $notice])
            ->with('success', 'A atualização de status ocorreu para '. $affectedCounter .' requisições de pagamento.');
    }

    public function viewPaymentStatus(Request $request)
    {
        try {
            
            $paymentStatus = $this->getPaymentStatus($request->idPagamento, $this->pagTesouroParameters->pagtesouro_token, 
                            $this->pagTesouroParameters->pagtesouro_url_consulta_pagamento);
            
            $paymentRequest = new PaymentRequest(
                [
                    'idPagamento' => $request->idPagamento,
                    'situacao_codigo' => '',
                    'tipo' => '',
                    'valor' => ''
                ]
            );
            $paymentRequest->tipo = $paymentStatus->tipoPagamentoEscolhido;
            $paymentRequest->valor = sprintf('R$ %s', number_format($paymentStatus->valor, 2));
            $paymentRequest->situacao_codigo = $paymentStatus->situacao->codigo;
            $this->notify($paymentRequest);
            return view('admin.pagtesouro-settings.payment-status', compact('paymentRequest', 'paymentStatus'));
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), ['paymentRequest']);
            return redirect()->route('admin.pagtesouro-settings.index')
                ->with('error', 'Recebemos um erro ao tentar consultar a situação do pagamento');
        }
    }

    public function edit()
    {
        $pagTesouroParameters = $this->pagTesouroParameters;
        return view('admin.pagtesouro-settings.config.edit', compact('pagTesouroParameters'));
    }

    public function update(Request $request)
    {        
        try {
            $data = $request->all();
            $this->parametersRepository->setPagTesouroParameters($data);

            return redirect()->route(
                'admin.pagtesouro-settings.config.edit')->with('success','Parâmetros salvos com sucesso');
        } catch (Exception $exception) {
            return redirect()->route(
                'admin.pagtesouro-settings.config.edit')->with('error','Um erro ocorreu ao salvar os parâmetros');
        }
    }

    /*
    * Exibe o nome do ambiente ativo do pagtesouro
    */
    public function getEnvPagtesouroName(){
        if(str_starts_with($this->pagTesouroParameters->pagtesouro_url_solicitacao_pagamento,'https://valpagtesouro.')){
            return "homologação";
        }else if(str_starts_with($this->pagTesouroParameters->pagtesouro_url_solicitacao_pagamento,'https://pagtesouro.')){
            return "produção";
        }
        return "desconhecido";
    }
}
