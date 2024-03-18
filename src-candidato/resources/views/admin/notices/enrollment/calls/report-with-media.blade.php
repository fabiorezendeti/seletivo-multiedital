@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')

<div class="container mx-auto" style="page-break-after: always">
    <div class="flex flex-col items-center">
        <x-report-header>
            <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
            <div class="text-2xl font-bold">Lista de Aprovados em {{ $callNumber }}º chamada</div>
            <div class="text-2xl font-bold">{{$offer->getString() }}</div>
            <div class="divide-y-2"></div>
        </x-report-header>
    </div>
    <table class="w-full">
        <thead>
            <tr>
                <th>
                    Número de Inscrição
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Posição
                </th>
                <th>
                    Ação Afirmativa
                </th>
                <th>
                    Média
                </th>
                <th>
                    Status
                </th>
            </tr>
        </thead>
        <tbody class="text-center uppercase">
            @foreach ($approvedList as $subscription)
                <tr>
                    <td>{{ $subscription->subscription_number }}</td>
                    <td>{{ $subscription->user->name }}</td>
                    <td>{{ $subscription->call_position  }} </td>                    
                    <td>{{ $subscription->affirmative_action_slug }}</td>
                    <td>{{ $subscription->media }}</td>
                    <td>Aprovado**</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    ** Os candidatos aprovados nesta chamada terão sua matrícula confirmada somente após conferência da documentação enviada 
</div>

@endsection
