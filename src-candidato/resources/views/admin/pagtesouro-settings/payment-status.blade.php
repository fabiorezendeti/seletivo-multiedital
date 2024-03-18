<x-manager.app-layout>
    <x-slot name="header">
        <div id="config-header" class="shadow-md bg-blue-800 w-full top-0 text-white py-1 ">
            <div class="container mx-auto flex flex-wrap items-center justify-start mt-0 px-2 py-2 ">
              <ul>
                <li
                class="block md:inline md:float-left px-4 text-blue-200 hover:text-white cursor-pointer font-bold text-base tracking-wide">
                <a href="{{ route('admin.pagtesouro-settings.index') }}">
                  Checagem de Ambiente
                </a>
              </li>
              </ul>
            </div>
        </div>
        <x-manager.header tip="Pagtesouro > Checagem">
            <x-manager.internal-navbar-itens />
        </x-manager.header>
    </x-slot>
<span class="text-2xl font-open-sans uppercase font-bold text-gray-400 px-5">Situação do Pagamento - PagTesouro</span>
<div>
        
    <div class="grid grid-cols-6 gap-4 py-5">
        
        <div class="col-span-6 md:col-span-3 lg:col-span-3 max-w-md ml-5 mb-10">
            <div class="max-w-lg rounded bg-yellow-100 overflow-hidden shadow-md border-b-4 border-t-4 border-blue-700 hover:border-blue-500 rounded">
                <div class="px-3">
                <div class="font-bold text-grey text-xl mb-2"></div>
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
                
                @elseif($paymentStatus->situacao->codigo == 'CONCLUIDO')
                    <div class="px-6 pt-4 pb-2 mb-2">
                        <a class="bg-blue-500 hover:bg-blue-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-full">
                            Pagamento concluído!
                        </a>
                    </div>
                            
                @endif
                
            </div>
            <div class="col-span-4">
                <form id="status-pagtesouro-form"
                action="{{ route('admin.pagtesouro-settings.payment.payment-status') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="idPagamento" value="{{$paymentRequest->idPagamento}}">
                <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="uppercase">Consultar novamente</span>
                </button>
            </form>
            </div>
        </div>    

        
        <div class="col-span-6 text-gray-500 mt-10 px-10">
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

</x-manager.app-layout>
