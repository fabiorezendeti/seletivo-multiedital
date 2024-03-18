@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')

    @foreach ($offers as $offer)
        <div class="container mx-auto" style="page-break-after: always">
        <div class="flex flex-col items-center">
            <x-report-header>
                <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
                <div class="text-2xl font-bold">LISTA DE MATRICULADOS</div>
                <div class="text-2xl font-bold">{{ $offer->getString() }}</div>
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
                        Nº Chamada
                    </th>
                    <th>
                        Posição
                    </th>
                    <th>
                        Ação Afirmativa
                    </th>
                    <th>
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="text-center uppercase">
                @foreach ($enrollmentCallRepository->getRegisteredListByOfferAndCriteriaAndStatusOfAllCalls($offer, $selectionCriteria, $status) as $subscription)
                    <tr>
                        <td>{{ $subscription->subscription_number }}</td>
                        <td>{{ $subscription->user->name }}</td>
                        <td>{{ $subscription->call_number  }} </td>
                        <td>{{ $subscription->call_position  }} </td>
                        <td>{{ $subscription->affirmative_action_slug }}</td>
                        <td>{{ ucfirst($subscription->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endforeach

@endsection
