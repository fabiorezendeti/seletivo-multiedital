@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')

    <div class="container mx-auto" style="page-break-after: always">
        <div class="flex flex-col items-center">
            <x-report-header>
                <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
                <div class="text-2xl font-bold">Candidatos com idade superior à 18 anos</div>
                <div class="divide-y-2"></div>
                <div class="text-2xl mt-4"></div>
            </x-report-header>
        </div>
        <table class="w-full">
            <thead>
            <tr>
                <th>Nº Inscrição</th>
                <th>Nome</th>
                <th>Campus/Curso</th>
                <th>Email</th>
                <th>Data de Nascimento</th>
            </tr>
            </thead>
            <tbody class="text-center uppercase">
            @foreach ($subscriptions as $subs)
                <tr>
                    <td>{{$subs->subscription_number}}</td>
                    <td class="text-center">{{$subs->user_name}}</td>
                    <td class="text-center">{{$subs->campus_name}}- {{$subs->course_name}}</td>
                    <td>{{$subs->email}}</td>
                    <td class="text-center">{{\Carbon\Carbon::createFromFormat('Y-m-d', $subs->birth_date)->format("d-m-Y")}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


@endsection
