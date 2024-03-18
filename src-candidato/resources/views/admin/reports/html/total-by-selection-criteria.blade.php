@extends('admin.reports.html.template')

@section('content')
@foreach ($campuses as $campus)
<div class="flex flex-col items-center px-5" style="page-break-after: always">
    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-sm">Inscritos X Homologados por Critério de Seleção</div>
        <div class="divide-y"></div>
        <div class="text-2xl">Campus {{$campus->name }}</div>
    </x-report-header>

    <table class="w-full">
        <thead>
            <tr>
                <th rowspan="2">
                    Curso
                </th>
                @foreach ($selectionCriteriaList as $selectionCriteria)
                <th colspan="2">
                    {{ $selectionCriteria->description }}
                </th>
                @endforeach
            </tr>
            <tr>
                @foreach ($selectionCriteriaList as $selectionCriteria)
                <th>Ins</th>
                <th>Hom</th>
                @endforeach
            </tr>

        </thead>
        <tbody class="text-center uppercase">
            @foreach ($notice->getNoticeOffersByCampus($campus) as $offer)
            <tr>
                <td>{{ $offer->courseCampusOffer->course->name }}</td>
                @foreach ($selectionCriteriaList as $selectionCriteria)
                <td class="text-center">
                    {{ $offer->getTotalSubscriptions($selectionCriteria) }}</td>
                <td class="text-center">
                    {{ $offer->getTotalHomologatedSubscriptions($selectionCriteria) }}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endforeach
@endsection
