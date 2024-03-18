@extends('admin.reports.html.template')

@section('content')

        <div class="content-center items-center" style="page-break-after: always">
        <div class="flex flex-col items-center">
            <x-report-header>
                <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
                <div class="text-2xl font-bold">ENDEREÇO DOS INSCRITOS</div>
                <div class="text-2xl font-bold">{{strtoupper($campus->name)}}</div>
                <div class="divide-y-2"></div>
            </x-report-header>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th>
                        INSCRIÇÃO
                    </th>
                    <th>
                        CPF
                    </th>
                    <th>
                        NOME
                    </th>
                    <th>
                        CURSO
                    </th>
                    <th>
                        ENDEREÇO
                    </th>
                </tr>
            </thead>
            <tbody class="text-center uppercase">
                @foreach ($subscriptions as $subscription)
                    <tr>
                    <td>{{ $subscription->subscription_number }}</td>
                    <td>{{ $subscription->user->cpf }}</td>
                    <td>{{ $subscription->user->name  }} </td>
                    <td>{{ $subscription->distributionOfVacancy->offer->courseCampusOffer->course->name  }} </td>
                    <td>{{ $subscription->user->contact->street }}, {{$subscription->user->contact->number }} - {{$subscription->user->contact->district}}, {{$subscription->user->contact->city->name}} - {{$subscription->user->contact->zip_code}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

@endsection
