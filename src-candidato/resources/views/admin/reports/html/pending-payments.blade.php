@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')



    <div class="container mx-auto" style="page-break-after: always">
        <div class="flex flex-col items-center">
            <x-report-header>
                <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
                <div class="text-2xl font-bold">Pagamentos Pendentes</div>
                <div class="divide-y-2"></div>
                <div class="text-2xl mt-4"></div>
            </x-report-header>
        </div>
        <table class="w-full">
            <thead>
            <tr>
                <th>Nº Inscrição</th>
                <th>Status Pgto</th>
                <th>Nome</th>
                <th>Campus/Curso</th>
                <th>Tel</th>
                <th>Email</th>
                <th>Data</th>
            </tr>
            </thead>
            <tbody class="text-center uppercase">
            @foreach ($subscriptions as $subs)
                <tr>
                    <td>{{$subs->subscription_number}}</td>
                    <td>{{$subs->situacao_codigo ? $subs->situacao_codigo : 'NÃO GERADO'}}</td>
                    <td class="text-center">{{$subs->user_name}}</td>
                    <td class="text-center">{{$subs->campus_name}} - {{$subs->course_name}}</td>
                    <td>{{ $subs->phone_number }}</td>
                    <td>{{$subs->email}}</td>
                    <td class="text-center">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $subs->created_at)->format("d-m-Y H:i")}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


@endsection
