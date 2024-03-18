@extends('layouts.candidate.head')
@section('body')
<body class="antialiased">
    <x-noscript />
    @include('layouts.barra')
    <style>
        .login_unico{
            display: inline-block;
            width: initial;
            margin-bottom: initial;
            height: 50px;
            border-radius: 30px;
            padding-left: 30px;
            padding-right: 30px;
            font-weight: 700;
            border-width: 2px;
            border-style: solid;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
            padding: 0.5rem 1rem;
            font-size: 1.12rem;
            line-height: 1.5;
            color: #fff;
            background-color: #2969BD;
            border-color: #2969BD;
        }
        .login_unico:hover{
            color: #fff;
            background-color: #22589e;
            border-color: #205293;
        }
        .h-divider {
            margin: auto;
            margin-top: 60px;
            width: 80%;
            position: relative;
        }

        .h-divider .shadow {
            overflow: hidden;
            height: 20px;
            box-shadow: none;
        }

        .h-divider .shadow:after {
            content: '';
            display: block;
            margin: -25px auto 0;
            width: 100%;
            height: 25px;
            border-radius: 125px/12px;
            box-shadow: 0 0 8px black;
        }

        .h-divider .text {
            width: 100px;
            height: 45px;
            padding: 10px;
            position: absolute;
            bottom: 100%;
            margin-bottom: -33px;
            left: 50%;
            margin-left: -60px;
            border-radius: 100%;
            box-shadow: 0 2px 4px #999;
            background: white;
        }

        .h-divider .text i {
            position: absolute;
            top: 4px;
            bottom: 4px;
            left: 4px;
            right: 4px;
            border-radius: 100%;
            border: 1px dashed #aaa;
            text-align: center;
            line-height: 50px;
            font-style: normal;
            color: #999;
        }

        .h-divider .text2 {
            width: 70px;
            height: 70px;
            position: absolute;
            bottom: 100%;
            margin-bottom: -35px;
            left: 50%;
            margin-left: -25px;
            border-radius: 100%;
            box-shadow: 0 2px 4px #999;
            background: white;
        }

        .h-divider img {
            position: absolute;
            margin: 4px;
            max-width: 60px;
            border-radius: 100%;
            border: 1px dashed #aaa;
        }

        .bt-reports{
            margin-bottom: initial;
            border-radius: 30px;
            padding-left: 30px;
            padding-right: 30px;
            font-weight: 700;
            border-width: 2px;
            border-style: solid;
            justify-content: center;
            align-items: center;
            white-space: nowrap;
            padding: 0.5rem 1rem;
            font-size: 1.12rem;
            line-height: 1.5;
            color: #fff;
            background-color: #309053;
            border-color: #1e8b2d;
        }

        .bt-reports:hover {
            color: #fff;
            background-color: #1a8842;
            border-color: #20802d;
        }
    </style>
    <div class="-mt-20">
    <nav id="header" class="w-full  text-white font-sans font-bold min-h-40 shadow-md">
    <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-2 md:py-6">
      <div id="logo-header" class="rounded-full bg-white w-16 h-16 md:w-32 md:h-32 bg-opacity-75 flex items-center">
        <a class="text-white no-underline hover:no-underline   text-2xl md:text-4xl" href="/">
          <img class="logo-color w-16 md:w-36" src="{{asset('img/logo_ifc_v_color.png')}}" alt="{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}">
          <img class="logo-white w-16 md:w-36" src="{{asset('img/logo_ifc_v_white.png')}}" alt="{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}" style="display: none">
        </a>
      </div>
      <div id="lettering" class="flex justify-start text-2xl md:text-4xl md:ml-6 md:mr-1 mr-20">
        <span class="font-open-sans text-portal mr-2 md:mr-5 ">PORTAL DO CANDIDATO</span>
      
      </div>
      

      <div
        class="w-full flex-grow md:flex md:items-center md:w-auto hidden md:block mt-2 md:mt-0 text-black p-4 md:p-0 z-20"
        id="nav-content">
        <div class="list-reset md:flex justify-end flex-1 items-center mr-10">

        </div>

      </div>
  </nav>
        <div class="flex">

            <div id="box-center" class="m-auto text-2xl md:text-6xl">

                   

                <div id="buttons" class="text-xs sm:text-xs md:text-xl flex justify-center mt-10">
                    @if(env('LOGIN_UNICO_ENABLE'))
                        <a href="{{route('login_url')}}" class="login_unico">Entrar com GOV.BR</a>
                    @else
                        @guest
                            <a href="/login" class="mx-5 px-6 py-3 md:mx-10 md:px-12 md:py-5 inline dark-green hover:bg-green-500 text-white font-bold py-2 px-4 border-b-4 border-green-900 hover:border-green-700 rounded">ENTRAR</a>
                            <a href="/register" class="mx-5 py-3 md:mx-10 md:py-5 inline medium-green hover:bg-green-500 text-white font-bold py-2 px-4 border-b-4 border-green-800 hover:border-green-600 rounded">CADASTRAR-SE</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="mx-5 px-6 py-3 md:mx-10 md:px-12 md:py-5 inline dark-green hover:bg-green-500 text-white font-bold py-2 px-4 border-b-4 border-green-900 hover:border-green-700 rounded">Meu Portal</a>
                        @endguest
                    @endif
                </div>
                <div class="h-divider">
                    <div class="shadow"></div>
                </div>
                <div class="text-xs sm:text-xs md:text-xl flex justify-center">
                    <span>Área pública:</span>
                </div>
                <div class="text-xs sm:text-xs md:text-xl flex justify-center mt-5">
                    <a href="{{route('relatorio-processo-seletivo')}}" class="bt-reports inline-flex items-center justify-center p-5 text-base font-medium text-gray-500 rounded-lg bg-gray-50 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
                        <svg aria-hidden="true" class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z"></path>
                            <path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z"></path></svg>
                        <span class="w-full">Resultado de Processo Seletivo</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
@endsection
