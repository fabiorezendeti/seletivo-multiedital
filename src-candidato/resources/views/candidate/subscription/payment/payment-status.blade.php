@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold">Situação do Pagamento - PagTesouro</span>
<div>
        
    <div class="grid grid-cols-6 gap-4 py-5">
        
        <div class="col-span-6 md:col-span-3 lg:col-span-3 max-w-md ml-5 mb-10">
            <div class="max-w-lg rounded bg-yellow-100 overflow-hidden shadow-md border-b-4 border-t-4 border-blue-700 hover:border-blue-500 rounded">
                <div class="px-3">
                <div class="font-bold text-grey text-xl mb-2">inscrição {{$subscription->subscription_number}}</div>
                    <p class="text-gray-700 text-base">
                    <p><b>SITUAÇÃO:</b> <br><span>{{$paymentStatus->situacao->codigo}} em {{Carbon\Carbon::parse($paymentStatus->situacao->data)->format('d/m/Y - H:m')}}</span></p>
                    @if(isset($paymentStatus->tipoPagamentoEscolhido))
                    <p><b>TIPO DE PAGAMENTO:</b> <span>{{$paymentStatus->tipoPagamentoEscolhido}}</span></p>    
                    @endif
                    <p><b>VALOR:</b> <span>R${{number_format($paymentStatus->valor,2,',','.')}}</span></p>
                    </p>
                </div>
                @if($paymentStatus->situacao->codigo == 'SUBMETIDO')
                <div class="flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3" role="alert">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/></svg>
                    <p>Alerta: Pagamentos via boleto (GRU) levam alguns dias para serem homologados. <br>
                        Se você não efetuou o pagamento e deseja gerar novo boleto ou escolher nova forma de pagamento clique no botão abaixo.</p>
                </div>
                
                <div class="px-6 pt-4 pb-2 mb-2">
                    <a href="{{route('candidate.subscription.payment',['subscription'=>$subscription])}}"
                        class="bg-green-500 hover:bg-green-400 hover:text-green-700 text-lg  text-white no-print font-bold py-2 px-3 mt-5  rounded-md inline-flex  my-1 border-b-4 border-green-700 hover:border-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="ml-2">Prosseguir Pagamento</span>
                    </a>
                </div>
                @elseif($paymentStatus->situacao->codigo == 'CONCLUIDO')
                    <div class="px-6 pt-4 pb-2 mb-2">
                        <a class="bg-blue-500 hover:bg-blue-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-full">
                            inscrição homologada!
                        </a>
                    </div>
                @else
                <div class="px-6 pt-4 pb-2 mb-2">
                    <a href="{{route('candidate.subscription.payment',['subscription'=>$subscription])}}"
                        class="bg-green-500 hover:bg-green-400 hover:text-green-700 text-lg  text-white no-print font-bold py-2 px-3 mt-5  rounded-md inline-flex  my-1 border-b-4 border-green-700 hover:border-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="ml-2">Prosseguir Pagamento</span>
                    </a>
                </div>
                @endif
                
            </div>
        </div>    

        
        <div class="col-span-6 text-gray-500 mt-10">
            <h3>Legenda:</h3>
            <p><b>CRIADO:</b> O pagamento foi solicitado, mas não foi exibida a tela do PagTesouro.</p>
            <p><b>INICIADO:</b> O pagamento foi solicitado, mas não foi concluído.</p>
            <p><b>SUBMETIDO:</b> O candidato selecionou uma forma de pagamento e acionou a opção "Pagar".</p>
            <p><b>CONCLUÍDO:</b> O pagamento digital (Pix ou cartão de crédito) foi confirmado.</p>
            <p><b>REJEITADO:</b> O pagamento digital (Pix ou cartão de crédito) foi rejeitado por algum motivo.</p>
            <p><b>CANCELADO:</b> O pagamento foi cancelado após 1h sem ter sido SUBMETIDO pelo contribuinte.</p>
            <br><p>Obs.: pagamentos do tipo BOLETO não admitem as situações CONCLUIDO, REJEITADO e CANCELADO, já que, quando o usuário confirma o pagamento, simplesmente é feita uma submissão ao Portal SIAFI para geração do boleto (GRU Simples). </p>
        </div>
    </div>
  
    <a href="{{route('candidate.subscription.show',['subscription'=>$subscription])}}"
        class="bg-orange-500 hover:bg-orange-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
        <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"
                clip-rule="evenodd" />
        </svg>
        <span class="uppercase">Voltar para Comprovante de Inscrição</span>
    </a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.6/iframeResizer.min.js"></script>

    <script>
        iFrameResize({ heightCalculationMethod: "documentElementOffset" }, ".iframe-epag");
    </script>
@endsection


