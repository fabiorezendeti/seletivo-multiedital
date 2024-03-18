@extends('layouts.candidate.head')
@section('body')
    <body class="antialiased">
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
    <style>
        select {
            color: black;
            font: caption;
            min-width: 350px;
        }
    </style>
    <x-noscript/>
    @include('layouts.barra')
    <div class="-mt-20">
        <div id="header" class="container mx-auto py-10 pt-24">
            <img class="w-60" style="float: left" src="{{asset('img/logo_ifc_h_color.png')}}" alt="Instituto Federal
            Catarinense"/>
            <a href="{{route('welcome')}}" style="float: right; margin-top: 14px;" class="inline-flex items-center
            justify-center p-5 text-base font-medium">
                <svg aria-hidden="true" class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                          d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"></path>
                </svg>
                <span class="w-full">Voltar</span>
            </a>
        </div>
        <div class="flex mt-10">
            <div id="box-center" class="m-auto text-2xl md:text-3xl">
                <span class="font-open-sans text-portal mr-4 md:mr-8">Resultado de Processo Seletivo</span>
            </div>
        </div>
        <form action="{{route('relatorio-processo-seletivo')}}" id="form_selection_result" method="POST"
              target="_blank">
            @csrf
            @livewire('public-report')

        </form>
    </div>

    @livewireScripts
    </body>
@endsection
