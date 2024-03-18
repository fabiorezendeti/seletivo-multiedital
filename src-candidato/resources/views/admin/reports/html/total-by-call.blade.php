@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')

<div class="container mx-auto" style="page-break-after: always">
    <div class="flex flex-col items-center">
        <x-report-header>
            <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
            <div class="text-2xl font-bold">{{ ($type === 'matriculados') ? 'Total de Matrículados' : 'Total de Não Matrículados' }} {{ ($callNumber) ? 'na Chamada 1' : 'em Todas as chamadas' }} </div>
            <div class="divide-y-2"></div>            
        </x-report-header>
    </div>
    <table class="w-full">
        <thead>
            <tr>
                <th>
                    Curso
                </th>
                @foreach ($affirmativeActionList as $affirmativeAction)
                <th>
                    {{ $affirmativeAction->slug }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="text-center uppercase">
            @foreach ($notice->offers->sortBy('id') as $offer)
            <tr>
                <td>{{ $offer->getString() }}</td>
                @foreach ($affirmativeActionList as $affirmativeAction)
                    <td> {{ $callList[$offer->id . '_' . $affirmativeAction->id] ?? 0 }}  </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection