@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')



        <div class="container mx-auto" style="page-break-after: always">
            <div class="flex flex-col items-center">
                <x-report-header>
                    <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
                    <div class="text-2xl font-bold">Relatório Solicitações de Isenções de Pagamento</div>
                    <div class="divide-y-2"></div>
                    <div class="text-2xl mt-4"></div>
                </x-report-header>
            </div>
            <table class="w-full">
                <thead>
                <tr>
                    <th>Nº Inscrição</th>
                    <th>Candidato</th>
                    <th>Campus</th>
                    <th>Curso</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody class="text-center uppercase">
                @foreach ($dados as $d)
                    <tr>
                        <td>{{ $d->inscricao }}</td>
                        <td class="text-center">{{ $d->candidato }}</td>
                        <td class="text-center">{{ $d->campus }}</td>
                        <td class="text-center">{{ $d->curso }}</td>
                        <td class="text-center">{{ is_null($d->status) ? "Não avaliado" : ($d->status ? "Deferido" : "Indeferido")}}</td>
                        <td class="text-center">{{ $d->status == 0 ? $d->motivo_rejeicao : ''  }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


@endsection
