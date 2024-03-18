@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')
<div class="flex flex-col items-center">
    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-sm">Total de Candidatos Por Cidade no Campus</div>
        <div class="divide-y"></div>
        @if($campus)
            <div class="text-sm uppercase font-bold">Campus {{$campus->name }}</div>
        @else
            Apresentando todos os campus    
        @endif
    </x-report-header>
    <table class="table-auto">
        <thead>
            <tr>     
                <th>
                    UF
                </th>           
                <th>
                    Cidade
                </th>                
                <th>
                    Total
                </th>
            </tr>
        </thead>
        <tbody class="text-center uppercase">
            @foreach ($resultSet as $result)
            <tr>     
                <td class="px-2 py-2">
                    {{$result->state}}
                </td>           
                <td class="px-2 py-2">
                    {{$result->city}}
                </td>                                
                <td class="px-2 py-2">
                    {{$result->total}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection