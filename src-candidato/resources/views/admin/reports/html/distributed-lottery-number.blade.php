@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')

    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }} - {{ $notice->description }}</div>
        <div class="text-sm font-bold"></div>
        <div class="divide-y"></div>
        <div class="text-sm font-bold">
            {{ $offer->getString() }} -  {{ $offer->courseCampusOffer->shift->description }}</div>
    </x-report-header>
    <table class="w-full">
        <thead>
            <tr>
                <th>
                    Inscrição
                </th>
                <th>
                    CPF
                </th>
                <th>
                    Nome do Candidato
                </th>
                <th>
                    Nº do Sorterio
                </th>
            </tr>
        </thead>
        <tbody class="text-center uppercase">
            @foreach ($subscriptions as $subscription)
            <tr>
                <td class="px-2 py-2">
                    {{$subscription->subscription_number}}
                </td>
                <td class="px-2 py-2">
                    {{$subscription->user->getObsfucatedCPF()}}
                </td>
                <td class="px-2 py-2">
                    {{$subscription->user->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subscription->lottery_number}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection