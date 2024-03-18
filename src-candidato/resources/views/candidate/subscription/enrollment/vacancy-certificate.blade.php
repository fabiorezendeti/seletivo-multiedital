@extends('layouts.candidate.app')
@section('content-app')
<div id="print-area">
    {!! $html !!}
    <div class="col-span-12 px-5 py-5 text-center no-print">
        <a href="#" onClick="window.print()"
            class="bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
            <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                    clip-rule="evenodd" />
            </svg> <span>Salvar</span>
        </a>
        <a href="{{route('candidate.subscription.show',['subscription'=>$subscription])}}" class="bg-orange-500 hover:bg-orange-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1 no-print>
            <svg class=" fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"
                clip-rule="evenodd" />
            </svg>
            <span class="uppercase">Voltar</span>
        </a>
    </div>
</div>
@endsection


@include('candidate.subscription.print-styles')