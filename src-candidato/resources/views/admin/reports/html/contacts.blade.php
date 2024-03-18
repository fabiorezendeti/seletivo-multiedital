@extends('admin.reports.html.template')

@section('content')
<div class="flex flex-col items-center">
    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-sm">Candidatos com inscrições homologadas com contato</div>
        <div class="divide-y"></div>
        <div class="text-sm">Campus {{$campus->name }}</div>
    </x-report-header>
</div>
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
                    E-mail
                </th>
                <th>
                    Telefone
                </th>
                <th>
                    Telefone Alt.
                </th>
                <th>
                    Cidade
                </th>
                <th>
                    UF
                </th>
                <th>
                    Curso
                </th>
                <th>
                    Ação Afirmativa
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
                    {{$subs->user->email}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->user->contact->phone_number ?? null}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->user->contact->alternative_phone_number ?? null}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->user->contact->city->name ?? null}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->user->contact->city->state->slug ?? null}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->distributionOfVacancy->offer->courseCampusOffer->course->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->distributionOfVacancy->affirmativeAction->slug}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection