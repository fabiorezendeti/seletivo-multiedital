@extends('admin.reports.html.template')

@section('content')

<div class="flex flex-col items-center">
    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-2xl font-bold">Relação de Inscritos e Notas</div>
        <div class="text-2xl font-bold">{{ $offer->getString() }}</div>
        <div class="divide-y-2"></div>
    </x-report-header>
</div>
<table class="table-auto">
    <thead>
        <tr>
            <th class="border-l-2 border-white">
                Número de Inscrição
            </th>
            <th class="border-l-2 border-white">
                Nome
            </th>
            <th class="border-l-2 border-white">Modalidade</th>
            <th class="border-l-2 border-white">Linguagens, Códigos e Tecnologias</th>
            <th class="border-l-2 border-white">Matemática e Suas Tecnologias</th>
            <th class="border-l-2 border-white">Ciências humanas e Suas Tecnologias</th>
            <th class="border-l-2 border-white">Ciências da Natureza e Suas Tecnologias</th>
            <th class="border-l-2 border-white">Redação</th>
            <th class="border-l-2 border-white">Média</th>
        </tr>
    </thead>
    <tbody class="text-center uppercase">
        @foreach ($subscriptions as $subscription)
        <tr>
            <td>{{ $subscription->subscription_number }}</td>
            <td>{{ $subscription->user_name }}</td>
            <td>{{ $subscription->modalidade ?? '--' }}</td>
            <td>{{ $subscription->linguagens_codigos_e_tecnologias ?? '--' }}</td>
            <td>{{ $subscription->matematica_e_suas_tecnologias ?? '--' }}</td>
            <td>{{ $subscription->ciencias_humanas_e_suas_tecnologias ?? '--' }}</td>
            <td>{{ $subscription->ciencias_da_natureza_e_suas_tecnologias ?? '--' }}</td>
            <td>{{ $subscription->redacao ?? '--' }}</td>
            <td>{{ $subscription->media }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection