@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')

<div class="container mx-auto" style="page-break-after: always">
    <div class="flex flex-col items-center">
        <x-report-header>
            <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
            <div class="text-2xl font-bold">LISTA DE CLASSIFICADOS</div>
            <div class="text-2xl font-bold">Curso {{$offer->getString() }}</div>
            <div class="divide-y-2"></div>
        </x-report-header>
    </div>
    <table class="w-full">
        <thead>
            <tr>
                <th>
                    Nº Inscrição
                </th>
                <th>
                    Nome do candidato
                </th>
                @if($notice->hasProva())
                    <th>
                        Mat.
                    </th>
                    <th>
                        Port.
                    </th>
                    <th>
                        Nat.
                    </th>
                    <th>
                        Hum.
                    </th>
                @endif
                <th>
                    @if($notice->hasProva()) Nota @else Média @endif
                </th>
                <th>
                    Data de Nascimento
                </th>
                <th>
                    Posição
                </th>
                <th>
                    Status
                </th>
                <th>
                    Ação Afirmativa
                </th>
            </tr>
        </thead>
        <tbody class="text-center">
            @php
             $hasCalls = $firstCallApprovedList->count()
            @endphp
            @foreach ($classificationList->sortBy(fn($item) => $item->global_position ?? 100000 ) as $item)
            <tr>
                <td>{{$item->subscription_number}}</td>
                <td class="uppercase">{{$item->user_name}}</td>
                @if($notice->hasProva())
                    <td>{{$item->matematica_e_suas_tecnologias}}</td>
                    <td>{{$item->linguagens_codigos_e_tecnologias}}</td>
                    <td>{{$item->ciencias_da_natureza_e_suas_tecnologias}}</td>
                    <td>{{$item->ciencias_humanas_e_suas_tecnologias}}</td>
                    <td>{{$item->nota}}</td>
                @else
                    <td>{{$item->media}}</td>
                @endif
                <td>{{$item->birth_date}}</td>
                <td>{{$item->global_position}}</td>
                <td @if($item->is_tied) class="bg-yellow-500" @endif>
                    @if ($firstCallApprovedList->contains(fn($value, $key) => $value->id === $item->subscription_id && $value->is_wide_concurrency))
                    Aprovado
                    @elseif($item->elimination)
                    Desclassificado
                    @else
                        @if($hasCalls)
                            Lista de Espera
                        @else
                            CLASSIFICADO
                        @endif
                    @endif
                </td>
                <td>AC</td>
            </tr>
            @endforeach

            @foreach ($classificationListWithoutWideCompetition
            ->sortBy('affirmative_action_slug')
            ->groupBy('affirmative_action_slug') as $group)
            @foreach ($group->sortBy('global_position') as $item)
            @php
            $inCall = $firstCallApprovedList->where('id',$item->subscription_id)->first();
            $countApproved = $firstCallApprovedList
                ->where('distribution_of_vacancies_id',$item->subscription_distribution_of_vacancy_id)->count() -
                $firstCallApprovedList
                ->where('distribution_vacancy_need_id',$item->subscription_distribution_of_vacancy_id)->count();

            @endphp
            @if( (!$inCall || !$inCall->is_wide_concurrency) && $item->distribution_of_vacancies_id != null )
            <tr>
                <td>{{$item->subscription_number}}</td>
                <td class="uppercase">{{$item->user_name}}</td>
                @if($notice->hasProva())
                    <td>{{$item->matematica_e_suas_tecnologias}}</td>
                    <td>{{$item->linguagens_codigos_e_tecnologias}}</td>
                    <td>{{$item->ciencias_da_natureza_e_suas_tecnologias}}</td>
                    <td>{{$item->ciencias_humanas_e_suas_tecnologias}}</td>
                    <td>{{$item->nota}}</td>
                @else
                    <td>{{$item->media}}</td>
                @endif
                <td>{{$item->birth_date}}</td>
                <td>{{$inCall->call_position ?? $item->distribution_of_vacancy_position - $countApproved}}</td>
                <td  @if($item->is_tied) class="bg-yellow-500" @endif>
                @if ($firstCallApprovedList->contains(fn($value, $key) => $value->id === $item->subscription_id && (!$value->is_wide_concurrency )))
                    Aprovado
                @elseif(!$item->is_ppi_checked)
                    INDEFERIDO PPI
                @elseif($item->elimination)
                    Desclassificado
                @else
                    @if($hasCalls)
                        Lista de Espera
                    @else
                        CLASSIFICADO
                    @endif
                @endif
                </td>
                <td>{{$item->affirmative_action_slug}}</td>
            </tr>
            @endif
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>

@endsection
