@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')

<div class="w-full container mx-auto flex flex-wrap pt-10 pb-10" id="header">
    <div class="w-1/5">
        <div id="logo-header"
            class="rounded-full bg-white float-left border-green-100 border  w-32 h-32 bg-opacity-75 flex items-center">
            <div class="text-white no-underline hover:no-underline  w-32 text-2xl md:text-4xl">
                <img class="logo-color " src="{{asset('img/logo_ifc_v_color.png')}}"
                    alt="Instituto Federal Catarinense">
                <img class="logo-white w-32" src="{{asset('img/logo_ifc_v_white.png')}}"
                    alt="Instituto Federal Catarinense" style="display: none">
            </div>
        </div>
    </div>
    <div class="text-4xl text-right float-right w-4/5" id="notice-title">
        <h1 class="block">Edital {{ $notice->number }}</h1>
        <p class="text-xl"> {{ $notice->description }}</p>
    </div>
</div>
</div>

<div class="w-full container mx-auto bg-white rounded-lg border-green-300 border">
    <h2 class="p-2 text-xl bg-green-500 rounded-t-lg md:text-white border border-green-500">Sobre o Edital</h2>
    <p class="p-2">{{ $notice->details }}</p>
</div>

<div class="w-full mt-5  container mx-auto bg-white rounded-lg border-green-300 border">
    <h2 class="p-2 text-xl bg-green-500 rounded-t-lg md:text-white border border-green-500">Sobre o Cursos</h2>
    <p class="p-2">
        Nós ofertamos neste edital um total de <span class="font-bold"> {{ $notice->offers()->count() }} </span> cursos,
        distribuidos em
        <span class="font-bold"> {{ $notice->offers()->get()->sum('total_vacancies') }} </span> vagas e obtivemos um total de
        <span class="font-bold">
            {{ $notice->subscriptions()->count() }}
        </span> inscritos, sendo homologados <span class="font-bold">
            {{ $notice->subscriptions()->isHomologated()->count() }}
        </span>, distribuídos nas ações afirmativas conforme a 
        <a href="#table-1">Tabela 1</a>
        . A distribuição destes candidatos em cada chamada e por critério de seleção são
         apresentados na <a href="#table-2">Tabela 2</a>, incluíndo o quantitativo de matrículados em cada chamada.
    </p>    
    <p class="p-2">
        Foram chamados <span class="font-bold">{{ $ppiCount->count() }}</span> candidatos para aferição PPI
        candidatos
        sendo
        <span class="font-bold"> {{ $ppiCount->where('is_ppi_checked',true)->count() }}
        </span> deferidos
        e <span class="font-bold">
            {{ $ppiCount->where('is_ppi_checked',false)->count() }}
        </span>
        indeferidos.
    </p>
    <div class="p-10">
        <table class="table-auto w-full" id="table-1">
            <caption>Tabela 1 - Números de inscritos de cada ação afirmativa</caption>
            <thead>
                <th>Ação afirmativa</th>
                <th>Homologados</th>
            </thead>
            <tbody>
                @foreach ($notice->totalOfSubscriptionsByAffirmativeActions() as $affirmative)
                <tr>
                    <td>{{ $affirmative->description }} </td>
                    <td class="text-right">{{ $affirmative->total }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-10">
        <table class="table-auto w-full" id="table-2">
            <caption>Tabela 2 - Total de candidatos convocados e matrículados por chamada</caption>
            <thead>
                <th>Número da chamada</th>
                <th>Total de Convocados</th>
                <th>Total de Matriculados</th>
            </thead>
            <tbody>
                @foreach ($totalCandidatesPerCall as $call)
                <tr class="text-center">
                    <td colspan="3" class="bg-gray-800 md:text-white font-bold">Método de seleção: {{ $call['selectionCriteria']->description }}</td>
                </tr>
                @foreach ($call['calls'] as $theCall)
                <tr>
                    <td>{{ $theCall->call_number }}</td>
                    <td>{{ $theCall->total }}</td>
                    <td>{{ $theCall->total_matriculado }} </td>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>

    </div>
</div>


@endsection


@push('css')

<style>
    body {
        background-image: url('/images/bkg_candidato.jpg');
    }

    @media only screen {
        #header {
            color: #fff;
        }
    }

    @media print {
        #notice-title {
            padding: 45px;
        }
    }
</style>

@endpush