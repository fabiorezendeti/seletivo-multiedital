<div x-data="{open: true}">
    <div x-show="open" id="modal" x-bind:class="{'opacity-0 pointer-events-none': open === false}"
        class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

        <div
            class="modal-container bg-white w-11/12 md:max-w-lg mx-auto rounded shadow-lg z-50 overflow-y-auto overscroll-auto">
            <div x-on:click="open = false"
                class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                    viewBox="0 0 18 18">
                    <path
                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                    </path>
                </svg>
            </div>

            <!-- Add margin if you want to see some of the overlay behind the modal-->
            <div class="modal-content py-4 text-left px-6 overflow-y-auto {{$height ?? 'h-auto'}}">
                <!--Title-->
                <div class="flex justify-between items-center pb-3">
                    <h2>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </h2>
                    <p class="text-xl font-bold">Lembrete</p>
                    <div class="modal-close cursor-pointer z-50" @click="open = false">
                        <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 18 18">
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                            </path>
                        </svg>
                    </div>
                </div>

                <!--Body-->
                <div>
                    <p>O pagamento da taxa de inscrição é importante para efetivar sua inscrição.</p>
                </div>

                <!--Footer-->
                <div class="flex justify-end pt-2">
                    <button type="button" @click="open = false"
                        class="modal-close px-4 bg-gray-500 p-3 rounded-lg text-white hover:bg-gray-400">Fechar</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="col-span-12  border bg-red-700 text-white px-10 py-10 text-sm no-print">
    <h2 class="text-lg font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-red-200 pl-4"></span>
        Taxa de Inscrição
        {{-- Pagamento da Inscrição --}}
    </h2>
    @if($subscription->notice->pagtesouro_activated)
        <p><b>Formas de pagamento:</b> Boleto, Cartão ou Pix. </p>
    @else
        <p><b>Formas de pagamento:</b> GRU - Guia de recolhimento da união. </p>
    @endif
    <p><b>Valor:</b> {{ $subscription->notice->registration_fee }} </p>
    <p><b>Pagamento até:</b> {{ $subscription->notice->payment_date->format('d/m/Y') }} </p>
    <br>
    @can('allowRequestPaymentExemption', $subscription)
        <p>Você também poderá solicitar a isenção da taxa de inscrição até o dia {{$subscription->notice->exemption_request_final_date->format('d/m/Y')}}, clicando em 'Isenção de Pagamento' e preenchendo com os dados necessários.</p>
    @endcan
    @can('paymentAvailable',$subscription->notice)
        @if($subscription->notice->pagtesouro_activated)
            @can('hasPaymentRequest', $subscription)
                <a href="{{route('candidate.subscription.payment.payment-status',['subscription'=>$subscription, 'paymentRequest'=>$paymentRequest ])}}"
                    class="bg-blue-500 hover:bg-blue-400 hover:text-blue-700 text-lg  text-white no-print font-bold py-2 px-3 mt-5  rounded-md inline-flex  my-1 border-b-4 border-blue-700 hover:border-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="ml-2">Consultar</span>
                </a>
            @else
                <a href="{{route('candidate.subscription.payment',['subscription'=>$subscription])}}"
                    class="bg-green-500 hover:bg-green-400 hover:text-green-700 text-lg  text-white no-print font-bold py-2 px-3 mt-5  rounded-md inline-flex  my-1 border-b-4 border-green-700 hover:border-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="ml-2">Pagamento</span>
                </a>
            @endcan
        @else
            @include('candidate.subscription.gru')
        @endif
    @endcan
    @can('allowRequestPaymentExemption', $subscription)
        <a href="{{route('candidate.subscription.payment-exemption.create',['subscription'=>$subscription])}}"
            class="bg-blue-500 hover:bg-blue-400 hover:text-blue-700 text-lg  text-white no-print font-bold py-2 px-3 mt-5  rounded-md inline-flex  my-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
            </svg>
            <span class="ml-2">Isenção de Pagamento</span>
        </a>
    @endcan
    @if($subscription->hasPaymentExemptionDocuments())
        <a href="{{route('candidate.subscription.payment-exemption.show',['subscription'=>$subscription])}}"
            class="bg-blue-500 hover:bg-blue-400 hover:text-blue-700 text-lg  text-white no-print font-bold py-2 px-3 mt-5  rounded-md inline-flex  my-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
            </svg>
            <span class="ml-2">Visualizar solicitação de isenção da taxa de inscrição</span>
        </a>
    @endif
</div>
