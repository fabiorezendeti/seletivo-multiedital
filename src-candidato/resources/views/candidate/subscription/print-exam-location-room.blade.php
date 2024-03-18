@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold no-print">Consulta de Local de Prova</span>
<div class="grid grid-cols-12 gap-4 md:py-5 text-gray-600">
    <div class="col-span-12 md:col-span-12 border border-t-4 border-gray-400 rounded-md px-5 py-2">
            <div class="grid grid-cols-12 gap-4 md:py-5 text-gray-6000">
                <div class="col-span-12 sm:col-span-6 md:col-span-6">
                    <img class="w-52" src="{{asset('img/logo_ifc_h_color.png')}}" alt="Instituto Federal Catarinense" />
                </div>
                <div class="col-span-12 sm:col-span-6 text-center md:col-span-6 md:text-right">
                    <h2 class="text-sm md:text-lg font-bold uppercase pt-5">Edital {{ $subscription->notice->number }}</h2>
                </div>

                <div class="col-span-12 bg-gray-200 text-center">
                    <h2 class="text-md uppercase">Inscrição N° <b>{{ $subscription->getSubscriptionNumber() }}</b></h2>
                </div>

                <div class="col-span-12  border border-gray-400 px-5 py-5">
                    <h2 class="font-bold uppercase mb-3"><span class="borde-0 border-l-2 border-gray-400 pl-4"></span>
                        Informações do Local de Prova
                    </h2>
                    <p class="text-lg"><b>Local de Prova: </b> {{ $subscription->examRoomBooking->examLocation->local_name }}</p>
                    <p class="text-lg"><b>Endereço: </b> {{ $subscription->examRoomBooking->examLocation->getAddressString() }}</p>
                    <p class="text-lg"><b>Sala de Prova: </b> {{ $subscription->examRoomBooking->name }}</p>
                    <p class="text-lg"><b>Data da Prova: </b> {{ $subscription->notice->exam_date->format('d/m/Y') ?? null }}</p>   
                    <p class="text-lg"><b>Horário da Prova: </b> {{ $subscription->notice->exam_time ?? null }}</p>
                </div>
            </div>
    </div>
</div>

@endsection
