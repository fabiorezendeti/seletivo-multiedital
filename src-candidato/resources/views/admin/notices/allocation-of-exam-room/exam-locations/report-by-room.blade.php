@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')
    @foreach($examLocation->examRoomBookings()->where('notice_id','=', $notice->id)->orderBy('name')->orderBy('active')->get() as $roomBooking)
        <div class="container mx-auto" style="page-break-after: always">
        <div class="flex flex-col items-center">
            <x-report-header>
                <div class="text-2xl font-bold">Relatório de Local de Prova</div>
                <div class="text-2xl font-bold">Edital {{$notice->number}}</div>
                <div class="divide-y-2"></div>
            </x-report-header>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th>Sala</th>
                    <th>Local de Prova</th>
                    <th>Campus</th>
                    <th>Capacidade Máxima</th>
                </tr>
            </thead>
            <tbody class="text-center uppercase">
                <tr>
                    <td class="text-center">{{ $roomBooking->name }}</td>
                    <td class="text-center">{{ $examLocation->local_name }}</td>
                    <td class="text-center">{{ $examLocation->campus->name }}</td>
                    <td class="text-center">{{ $roomBooking->capacity }}</td>
                </tr>
            </tbody>
        </table>
        </div>
    @endforeach
@endsection
