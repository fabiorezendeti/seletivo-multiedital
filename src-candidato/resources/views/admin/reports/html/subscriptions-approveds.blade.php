@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')
    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-2xl font-bold">Inscrições Homologadas</div>
        <div class="divide-y-2"></div>
    </x-report-header>
    @if(count($results) == 0)
        <div class="flex flex-col items-center">
            <div class="divide-y-2"></div>
            <div class="text-2xl mt-4">Não há dados para exibir com o filtro selecionado!</div>
        </div>
    @endif
    @for($i=0; $i<count($results); $i++)
        <div class="container mx-auto" style="page-break-after: always">
        <div class="flex flex-col items-center">
                <div class="divide-y-2"></div>
                <div class="text-2xl mt-4">Campus {{ $results[$i]->campus }}</div>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th>
                        Inscrição
                    </th>
                    <th>
                        Candidato
                    </th>
                    <th>
                        Curso / Campus
                    </th>
                    <th>
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="text-center uppercase">
                @while(true)
                    <tr>
                        <td>{{ $results[$i]->inscricao }}</td>
                        <td class="text-center">{{ $results[$i]->nome }}</td>
                        <td class="text-center">{{ $results[$i]->curso }} / {{ $results[$i]->campus }}</td>
                        <td class="text-center">{{ $results[$i]->homologado ? 'Homologado' : '-' }}</td>
                    </tr>
                    @php
                        if(($i+1) == count($results)){
                            break;
                        }else if($results[$i+1]->campus != $results[$i]->campus){
                            break;
                        }else{
                            $i = $i + 1;
                        }
                    @endphp
                @endwhile
            </tbody>
        </table>
        </div>
    @endfor

@endsection
