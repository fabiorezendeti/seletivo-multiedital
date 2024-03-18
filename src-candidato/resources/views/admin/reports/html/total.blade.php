@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')

    @foreach ($campuses as $campus)        
        <div class="container mx-auto" style="page-break-after: always">
        <div class="flex flex-col items-center">
            <x-report-header>
                <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
                <div class="text-2xl font-bold">Relação candidato vaga</div>
                <div class="divide-y-2"></div>
                <div class="text-2xl mt-4">Campus {{$campus->name }}</div>
                <div class="text-xl">Curso </div>
            </x-report-header>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th>
                        Curso
                    </th>                    
                    <th>
                        Inscritos
                    </th>
                    <th>
                        Homologados
                    </th>
                    <th>
                        Total de Vagas
                    </th>                    
                </tr>
            </thead>
            <tbody class="text-center uppercase">
                @foreach ($notice->getNoticeOffersByCampus($campus) as $offer)
                <tr>
                    <td>{{ $offer->courseCampusOffer->course->name }}</td>                    
                    <td class="text-center">{{ $offer->getTotalSubscriptions($selectionCriteria) }}</td>
                    <td class="text-center">{{ $offer->getTotalHomologatedSubscriptions($selectionCriteria) }}</td>
                    <td class="text-center">{{ $offer->total_vacancies }}</td>                    
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>        
    @endforeach

@endsection