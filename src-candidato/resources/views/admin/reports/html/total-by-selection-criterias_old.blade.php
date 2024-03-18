@extends('admin.reports.html.template')

@section('content')
<div class="container mx-auto">
    <div class="flex flex-col items-center">
        <div><img src="{{ asset('/images/republica_brasao_cor_rgb.jpg') }}" class="w-52" alt="Brasão da República">
        </div>
        <div class="uppercase text-sm">MINISTÉRIO DA EDUCAÇÃO</div>
        <div class="uppercase text-sm">SECRETARUA DE EDUCACAO PROFISSIONAL E TECNOLÓGIA</div>
        <div class="uppercase text-sm">INSTITUTO FEDERAL CATARINENSE </div>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-sm">Candidatos com inscrições homologadas com contato</div>
        <div class="divide-y"></div>
        <div class="text-sm">Campus</div>
    </div>
    <table class="w-full">
        <thead>
            <tr>
                <th rowspan="2">
                    Campus
                </th>
                <th rowspan="2">
                    Curso
                </th>
                @foreach ($selectionCriteriaList as $selectionCriteria)
                <th colspan="2">
                    {{ $selectionCriteria }}
                </th>
                @endforeach
            </tr>
            <tr>
                @foreach ($selectionCriteriaList as $selectionCriteria)
                <th>
                    Ins
                </th>
                <th>
                    Hom
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="text-center uppercase">
            @foreach ($dataArray as $campusKey => $campus)
            @foreach ($campus as $coursesKey => $course)
            <tr>
                <td class="px-2 py-2">
                    {{ $campusKey }}
                </td>
                <td class="px-2 py-2">
                    {{ $coursesKey }}
                </td>
                @foreach ($course as $selectionCriteriaKey => $selectionCriteria)
                @foreach ($selectionCriteriaList as $item)
                @if ( $selectionCriteriaKey == $item)
                <td class="px-2 py-2">
                    {{ $selectionCriteria['subscription'] }}
                </td>
                <td class="px-2 py-2">
                    {{ $selectionCriteria['homologated'] }}
                </td>
                @endif
                @endforeach
                @endforeach
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection