@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold"></span>

<div class="grid grid-cols-12 gap-4 md:py-5 text-gray-600">
    <div id="left-box" style="max-height: 350px"
        class="overflow-auto col-span-12 md:col-span-4 border border-t-4 border-red-700 rounded-md px-5 py-2">
        <span class="text-xl font-sans font-thin uppercase text-red-700 pb-5 ">
            <svg class="h-6 float-left pt-1 mr-1 -ml-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
            </svg>
            Minhas inscrições 
        </span>
        <hr class="pb-5">
        @forelse ($subscriptions as $subscription)
            <div class="py-5">
                <h2 class="text-lg font-bold text-red-700"><span class="borde-0 border-l-2 border-red-700 pl-4"></span>
                    {{ $subscription->notice->number.' - '.$subscription->notice->description}}
                </h2>
                <div class="grid grid-cols-6 gap-4 md:py-1 text-gray-800">
                    <div class="col-span-6 md:col-span-4">
                        @if($subscription->is_homologated === 1)
                        <span id="status" class="rounded-full py-2 px-4 text-white bg-green-400 text-xs">inscrição
                            deferida</span>
                        @endif
                        <span class="text-xs text-gray-500 ml-4"><b>Curso:</b>
                            {{ $subscription->distributionOfVacancy->offer->courseCampusOffer->course->name }}</span>
                        <span class="text-xs text-gray-500 ml-4"><b>Campus:</b>
                            {{ $subscription->distributionOfVacancy->offer->courseCampusOffer->campus->name }}</span>
                    </div>
                    <div class="col-span-6 md:col-span-2">
                        <a href="{{ route('candidate.subscription.show',['subscription'=>$subscription]) }}"
                            class="bg-red-800 hover:bg-red-900 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                            <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2h-1.528A6 6 0 004 9.528V4z" />
                                <path fill-rule="evenodd"
                                    d="M8 10a4 4 0 00-3.446 6.032l-1.261 1.26a1 1 0 101.414 1.415l1.261-1.261A4 4 0 108 10zm-2 4a2 2 0 114 0 2 2 0 01-4 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span> Detalhes</span>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-600 italic">Você ainda não se inscreveu. Veja os editais abertos e inscreva-se.</p>
        @endforelse
    </div>
    <div id="right-box" class="col-span-12 md:col-span-8">
        <div id="open-registrations"
            class="col-span-12 md:col-span-8 border border-t-4 border-green-700 rounded-md px-5 py-2">
            <span class="text-xl font-sans font-thin uppercase text-green-600 pb-5 ">
                <svg class="h-6 float-left pt-1 mr-1 -ml-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Inscrições abertas
            </span>
            <hr class="py-5">
            @forelse ($noticesOpened as $notice)
            <div class="pb-10">
                <h2 class="text-lg font-bold text-green-600"><span
                        class="borde-0 border-l-2 border-green-600 pl-4"></span>
                    {{ $notice->number.' - '.$notice->description}}
                </h2>

                <div class="grid grid-cols-6 gap-4 md:py-1 text-gray-600">
                    <div class="col-span-6 md:col-span-4">
                        <span class="text-xs text-green-600 ml-4"><b>Período de inscrição:</b>
                            {{ $notice->subscription_initial_date->format('d/m/Y') }} a
                            {{ $notice->subscription_final_date->format('d/m/Y') }} </span>
                        <p class="text-sm ml-4">
                            {{ $notice->details }}
                        </p>
                    </div>
                    <div class="col-span-6 md:col-span-2">
                        <a href="{{ $notice->link }}"
                            class="bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                            <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                    clip-rule="evenodd" />
                            </svg> <span>Edital</span>
                        </a>
                        <a href="{{ route('notice.show', ['notice' => $notice]) }}"
                            class="bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                            <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            <span> Inscrições</span>
                        </a>
                    </div>
                </div>
            </div>
            <hr>
            @empty
            <p class="text-gray-600 italic">No momento não temos nenhum edital com inscrições em aberto.</p>
            @endforelse
        </div>
        <!--fim inscrições abertas-->

        <div id="in-progress"
            class="col-span-12 md:col-span-8 border border-t-4 border-blue-800 rounded-md px-5 py-2 mt-4 md:my-10">
            <span class="text-xl font-sans font-thin uppercase text-blue-800 pb-5 ">
                <svg class="h-6 float-left pt-1 mr-1 -ml-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Editais em andamento
            </span>
            <hr class="pb-5">
            <p class="text-gray-500 italic text-sm">Processos seletivos ainda em andamento, mas com inscrições
                encerradas.</p>
            @foreach ($noticesInProcess as $notice)
            <div class="py-5">
                <h2 class="text-lg font-bold text-blue-800"><span
                        class="borde-0 border-l-2 border-blue-800 pl-4"></span>
                    {{ $notice->number.' - '.$notice->description}}
                </h2>
                <div class="grid grid-cols-6 gap-4 md:py-1 text-gray-600">
                    <div class="col-span-6 md:col-span-4">
                        <p class="text-sm ml-3">Inscrições de {{ $notice->subscription_initial_date->format('d/m/Y') }}
                            até
                            {{ $notice->subscription_final_date->format('d/m/Y') }}</p>
                        <p class="text-sm ml-3">Período de recursos de
                            {{ $notice->classification_review_initial_date->format('d/m/Y') }} até
                            {{ $notice->classification_review_final_date->format('d/m/Y') }}</p>
                        <span class="text-xs text-gray-500 ml-4"><b>Etapa atual:</b> aqui vai o status atual do
                            cronograma do processo </span>
                    </div>
                    <div class="col-span-6 md:col-span-2">
                        <a href="{{ $notice->link }}"
                            class="bg-blue-700 hover:bg-blue-800 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                            <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                    clip-rule="evenodd" />
                            </svg> <span>PDF</span>
                        </a>                        
                    </div>
                </div>
            </div>
            <hr class="py-5">
            @endforeach
        </div>

    </div>

</div>

@endsection