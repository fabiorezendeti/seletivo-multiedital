@extends('layouts.candidate.head')
@section('body')

<body class="antialiased flex flex-col h-screen justify-between">

  <x-noscript />
  @include('layouts.barra')
  <!--a11y-->
  <div id="a11y" class="w-full bg-green-800 mx-auto px-4">
    <div class="grid grid-cols-12 container mx-auto">
      <div class="col-span-1 col-start-12 text-right">
        <button id="bt-contrast" class="bg-grey-light hover:bg-grey text-grey-darkest font-bold py-1 px-1 rounded inline-flex items-center">
          <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span class="sr-only">Alto contraste</span>
        </button>
      </div>
    </div>
  </div>
    
  <!--menu-->
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
  <!--menu-->
  <div class="mb-auto bg-white py-5 font-sans text-green-600 w-full px-5">
    <div class="container mx-auto">
      @yield('content-app')
    </div>
  </div>
  <footer class="container mx-auto py-5 px-10 text-sm">
    <div class="text-green-900 font-sans max-w-3xl">
      <p>O {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }} possui <i>Campus</i> nas cidades de:</p>
      <p>{{ App\Repository\ParametersRepository::getValueByName('cidades_campus') }}.
      </p>
    </div>
    <div class="text-white font-sans mt-5">
      <p><b>{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }} - REITORIA</b></p>
      <p>{{ App\Repository\ParametersRepository::getValueByName('endereco_reitoria') }}</p>
      <p>Fone {{ App\Repository\ParametersRepository::getValueByName('fone_instituicao') }} | E-mail: {{ App\Repository\ParametersRepository::getValueByName('email_instituicao') }}</p>
      <p class="pt-3">
        <a href="https://www.facebook.com/ifc.oficial/" target="_blank" class="float-left mr-2"><img
            src="{{asset('img/facebook-5-24.png')}}" alt="ícone do facebook"/></a>
        <a href="https://www.instagram.com/ifc.oficial/" target="_blank" class="float-left mr-2"><img
            src="{{asset('img/instagram-5-24.png')}}" alt="ícone do instagram"/></a>
        <span class="">ifc.oficial </span>
      </p>
    </div>
  </footer>
  <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/candidate.js')}}"></script>
  @livewireScripts
  @stack('js')
  @stack('modals')
</body>
@endsection