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
                    Número de Inscrição
                </th>
                <th>
                    Nome do candidato
                </th>
                <th>
                    Número para sorteio
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
            @foreach ($classificationList->sortBy('global_position') as $item)
            <tr>
                <td>{{$item->subscription_number}}</td>
                <td class="uppercase">{{$item->user_name}}</td>
                <td>{{$item->lottery_number}}</td>
                <td >{{$item->global_position}}</td>
                @if ($firstCallApprovedList->contains(fn($value, $key) => $value->id === $item->subscription_id && $value->is_wide_concurrency))  
                    <td class="uppercase">Aprovado</td>
                @else
                    @if($hasCalls)
                        <td class="uppercase">Lista de Espera</td>
                    @else
                        <td class="uppercase">Classificado</td>
                    @endif
                @endif
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
            @if(!$inCall || !$inCall->is_wide_concurrency)
            <tr>
                <td>{{$item->subscription_number}}</td>
                <td class="uppercase">{{$item->user_name}}</td>    
                <td>{{$item->lottery_number}}</td>                           
                <td>{{$item->distribution_of_vacancy_position - $countApproved}}</td>                                    
                <td>
                @if ($firstCallApprovedList->contains(fn($value, $key) => $value->id === $item->subscription_id && !$value->is_wide_concurrency))  
                    APROVADO              
                @elseif(!$item->is_ppi_checked)
                    INDEFERIDO PPI
                @else
                    @if($hasCalls)
                    LISTA DE ESPERA
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