@extends('admin.reports.html.template')

@section('content')
<div class="flex flex-col items-center">
    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-xl">Candidatos Convocados em Ações Afirmativas PPI(Pretos, Pardos ou Indígenas)</div>
        <div class="divide-y"></div>
        <div class="text-xl">Campus {{$campus->name }}</div>
    </x-report-header>
    <table class="table-auto">
        <thead>
            <tr>                               
                <th>
                    Nome
                </th>
                <th>
                    Campus
                </th>
                <th>
                    Curso
                </th>
                <th>
                    Ação Afirmativa
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
            </tr>
        </thead>
        <tbody class="text-center uppercase">
            @foreach ($subscriptions as $subs)
            <tr>                
                <td class="px-2 py-2">
                    {{$subs->user->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->distributionOfVacancy->offer->courseCampusOffer->campus->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->distributionOfVacancy->offer->courseCampusOffer->course->name}}
                </td>
                <td class="px-2 py-2">
                    {{$subs->distributionOfVacancy->affirmativeAction->slug}}
                </td>                
                <td class="px-2 py-2">{{ $subs->user->email }}</td>
                <td class="px-2 py-2">{{ $subs->user->contact->phone_number }} </td>
                <td class="px-2 py-2">{{ $subs->user->contact->alternative_phone_number }} </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
