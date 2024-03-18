@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')
    @foreach($examLocations as $examLocation)
        @foreach($examLocation->examRoomBookings as $room)
            <div class="container mx-auto" style="page-break-after: always">
            <div class="flex flex-col items-center">
                <x-report-header>
                    <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
                    <div class="text-2xl font-bold">Relatório de Ensalamento</div>
                    <div class="divide-y-2"></div>
                    <div class="text-2xl mt-4">Campus {{$examLocation->campus->name }}</div>
                    <div class="text-2xl">{{$examLocation->local_name }} - {{$room->name }}</div>
                    {{-- <div class="text-xl">Curso </div> --}}
                </x-report-header>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th>Nº Inscrição</th>
                        <th>Nome do Candidato</th>
                        <th>Curso</th>
                    </tr>
                </thead>
                <tbody class="text-center uppercase">
                    @foreach($room->subscriptions as $subscription)
                        <tr>
                            <td class="text-center">{{ $subscription->subscription_number }}</td>
                            <td class="text-center">{{ $subscription->user->name }}</td>
                            <td class="text-center">{{ $subscription->distributionOfVacancy->offer->courseCampusOffer->course->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endforeach
    @endforeach
@endsection
