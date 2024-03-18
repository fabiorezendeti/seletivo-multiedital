@extends('admin.reports.html.template')

@section('content')
<div class="flex flex-col items-center">
    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-sm font-bold">Resultado  da Aferição PPI(Pretos, Pardos ou Indígenas)</div>
        <div class="divide-y"></div>
        <div class="text-sm font-bold">Campus {{$campus->name }}</div>
    </x-report-header>
    <table class="table-auto">
        <thead>
            <tr>
                <th>
                    Inscrição
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Ação Afirmativa
                </th>
                <th>
                    Campus
                </th>
                <th>
                    Curso
                </th>
                <th>
                    Status Aferição
                </th>
            </tr>
        </thead>
        <tbody class="text-center uppercase">
            @foreach ($subscriptions as $subs)
            <tr>
                <td class="px-2 py-2">
                    {{$subs->subscription_number}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->user->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->distributionOfVacancy->affirmativeAction->description}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->distributionOfVacancy->offer->courseCampusOffer->campus->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->distributionOfVacancy->offer->courseCampusOffer->course->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->afericao_ppi_status }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
