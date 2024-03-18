@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold no-print">Sua inscrição</span>
<div class="grid grid-cols-12 gap-4 md:py-5 text-gray-600">
    <div class="col-span-12 md:col-span-12 border border-t-4 border-gray-400 rounded-md px-5 py-2">
            <div class="grid grid-cols-12 gap-4 md:py-5 text-gray-6000">
                <div class="col-span-12 sm:col-span-6 md:col-span-6">
                    <img class="w-52" src="{{asset('img/logo_ifc_h_color.png')}}" alt="{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}" />
                </div>
                <div class="col-span-12 sm:col-span-6 text-center md:col-span-6 md:text-right">
                    <h2 class="text-sm md:text-lg font-bold uppercase pt-5">Edital {{ $subscription->notice->number }}
                    <a href="{{ $subscription->notice->link }}"
                                        class="no-print bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                                        <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                                clip-rule="evenodd" />
                                        </svg> <span>Edital</span>
                                    </a></h2>
                </div>

                <div class="col-span-12 bg-gray-200 text-center">
                    <h2 class="text-md uppercase">Isenção de Pagamento da Inscrição N° <b>{{ $subscription->getSubscriptionNumber() }}</b>
                    </h2>
                </div>

                <div class="col-span-12  border border-gray-400 px-5 py-5 text-sm">
                    <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                        Documento de Identidade - Frente
                    </h2>
                    <div style="height: 300px">
                        <iframe src="{{route('candidate.subscription.payment-exemption.viewDocumentIdFront',['subscription'=>$subscription])}}" width="100%" height="100%"
                            frameborder="1"></iframe>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-12 border border-gray-400 px-5 py-5 text-sm">
                    <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                        Documento de Identidade - Verso
                    </h2>
                    <div style="height: 300px">
                        <iframe src="{{route('candidate.subscription.payment-exemption.viewDocumentIdBack',['subscription'=>$subscription])}}" width="100%" height="100%"
                            frameborder="1"></iframe>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-12 border border-gray-400 px-5 py-5 text-sm">
                    <h2 class="text-md font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                        Documento de Solicitação - Formulário
                    </h2>
                    <div style="height: 300px">
                        <iframe src="{{route('candidate.subscription.payment-exemption.viewDocumentForm',['subscription'=>$subscription])}}" width="100%" height="100%"
                            frameborder="1"></iframe>
                    </div>
                </div>
            </div>
    </div>
</div>

@endsection
