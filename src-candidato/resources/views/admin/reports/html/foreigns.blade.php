@extends('admin.reports.html.template')

@section('content')
<div class="container mx-auto" style="page-break-after: always">
    <div class="flex flex-col items-center">
        <x-report-header>
            <div class="uppercase text-sm font-bold">Estrangeiros Inscritos Edital nº{{ $notice->number }}</div>
            <div class="divide-y"></div>
            @if($campus)
                <div class="text-sm uppercase font-bold">Campus {{$campus->name }}</div>
            @endif
        </x-report-header>
    </div>
    <table class="w-full">
        <thead>
            <tr>
                <th>
                    Inscrição
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Curso
                </th>
                <th>
                    Campus
                </th>
                <th>
                    Nacionalidade
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
                    {{$subs->distributionOfVacancy->offer->courseCampusOffer->course->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->distributionOfVacancy->offer->courseCampusOffer->campus->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->user->nationality}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
