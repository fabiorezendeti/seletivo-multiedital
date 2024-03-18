@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')

<div class="container mx-auto" style="page-break-after: always">
    <div class="flex flex-col items-center">
        <x-report-header>
            <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
            <div class="text-2xl font-bold">Lista de Aprovados em {{ $callNumber }}º chamada com contato</div>            
            @if($offer->id)
            <div class="text-2xl font-bold">{{ $offer->getString()}}</div>            
            @endif
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
                    E-mail
                </th>
                <th>
                    Fone
                </th>
                <th>
                    Fone 2
                </th>
                <th>
                    Ação afirmativa
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
                    <td>{{ $subscription->user->email }}</td>        
                    <td>{{ $subscription->user->contact->phone_number }}</td>        
                    <td>{{ $subscription->user->contact->alternative_phone_number }}</td>    
                    <td>{{ $subscription->affirmative_action_slug }}</td>                    
                    <td>{{ $subscription->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
