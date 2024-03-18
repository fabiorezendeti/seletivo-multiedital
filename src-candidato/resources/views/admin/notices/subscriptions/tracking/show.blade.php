@extends('admin.reports.html.template',['orientation'=>'portrait'])

@section('content')
<div class="flex flex-col">
    <x-report-header>
        <div class="uppercase text-sm">Edital {{ $notice->number }}</div>
        <div class="text-sm">Rastreamento de alterações da inscrição</div>
        <div class="divide-y"></div>
        <div class="text-sm">Inscrição número {{$subscription->subscription_number }}</div>
    </x-report-header>
    @foreach ($data as $key=>$item)
    <div class="p-5 mb-5 border border-black">
        <h2 class="text-2xl">{{$key }}</h2>  
        <pre>
        {{ json_encode(json_decode($item['freeze']),JSON_PRETTY_PRINT) }}        
    </pre>      
        <h3 class="text-xl">Dados de navegação enviados</h3>
        @foreach ($item['navigation'] as $nav)        
        <p class="bg-gray-100 p-2 mb-2" >
            Acesso em: {{$nav->created_at}} <br>
            Conteúdo: {{$nav->content}} <br>
            URI: {{$nav->uri}} 
        </p>
        @endforeach
    </div>
    @endforeach
</div>

@endsection