@extends('layouts.candidate.head')
@section('body')
<body class="antialiased flex flex-col h-screen justify-between">
  <x-noscript />
  <div id="barra-brasil" class="no-print" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;">
    <ul id="menu-barra-temp" class="no-print" style="list-style:none;">
      <li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED">
        <a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do
          Governo Brasileiro</a>
      </li>
    </ul>
  </div>
  <!--a11y-->
  <div id="a11y" class="w-full bg-green-800 mx-auto px-4 no-print">
    <div class="grid grid-cols-12 container mx-auto">
      <div class="col-span-1 col-start-12 text-right" style="display: flex; flex-direction: row; flex-wrap: nowrap; justify-content: flex-end;">
        <button id="bt-contrast" title="Exibir em Alto Contraste" class="bg-grey-light hover:bg-grey text-grey-darkest font-bold py-1 px-1 rounded inline-flex items-center">
          <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <span class="sr-only">Alto contraste</span>
        </button>
          <button title="Redefinir Cookies" onclick="_redefinirCookies(); return false;" class="reset-cookies bg-grey-light hover:bg-grey text-grey-darkest font-bold py-1 px-1 rounded inline-flex items-center">
              <span class="sr-only">Redefinir Cookies</span>
          </button>
      </div>
    </div>
  </div>

  <!--menu-->
  <nav id="header" class="no-print w-full z-30 text-white font-sans font-bold min-h-40 shadow-md">
    <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-2 md:py-6">
      <div id="logo-header" class="rounded-full bg-white w-16 h-16 md:w-32 md:h-32 bg-opacity-75 flex items-center">
        <a class="text-white no-underline hover:no-underline   text-2xl md:text-4xl" href="/dashboard">
          <img class="logo-color w-16 md:w-36" src="{{asset('img/logo_ifc_v_color.png')}}" alt="{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}">
          <img class="logo-white w-16 md:w-36" src="{{asset('img/logo_ifc_v_white.png')}}" alt="{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}" style="display: none">
        </a>
        <div id="lettering" class="flex justify-start text-2xl md:text-4xl md:ml-6 md:mr-1 mr-20">
        <span class="font-open-sans text-portal mr-2 md:mr-5 ">PORTAL DO CANDIDATO</span>
        
      </div>
      </div>
     
      <!--mobile-menu-->
      <div class="block absolute z-40 right-0 md:hidden pr-4">
        <button id="nav-toggle"
          class="flex items-center px-3 py-2 border rounded text-white border-white hover:text-gray-800 hover:border-green-500 hover:text-green-500 appearance-none focus:outline-none">
          <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <title>Menu</title>
            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
          </svg>
        </button>
      </div>

      <div
        class="w-full flex-grow md:flex md:items-center md:w-auto hidden md:block mt-2 md:mt-0 text-black p-4 md:p-0 z-20"
        id="nav-content">
        <div class="list-reset md:flex justify-end flex-1 items-center mr-10">
          <ul class="">
            <li
              class="block md:inline md:loat-left md:pb-3 px-4 text-green-100 hover:text-white cursor-pointer   text-base tracking-wide">
              <a></a>
            </li>

            <li
              class="block md:inline md:float-left md:pb-3 px-4 border-b-2 border-green-500 hover:border-green-700 text-green-100 hover:text-white cursor-pointer   text-base tracking-wide">
            <a href="{{ route('dashboard') }}" class="uppercase">
                Inscrições
              </a>
            </li>


            <!--profile-->
            <li
              class="hidden md:inline md:float-left sm:z-50 text-green-100 hover:text-white cursor-pointer   text-base tracking-wide">
              <div class="">
                <x-jet-dropdown align="right" width="48">
                  <x-slot name="trigger">
                    <button
                      class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                      <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                        alt="{{ Auth::user()->name }}" title="Meu Perfil" />
                    </button>
                  </x-slot>

                  <x-slot name="content">

                    <x-jet-dropdown-link href="/user/profile">
                      {{ __('Profile') }}
                    </x-jet-dropdown-link>

                    <x-jet-dropdown-link href="/user/contact">
                      Dados de Contato
                    </x-jet-dropdown-link>

                    @can('managerArea')
                    <x-jet-dropdown-link href="/manager">
                      Gerência
                    </x-jet-dropdown-link>
                    @endcan

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf

                      <x-jet-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                      this.closest('form').submit();">
                        {{ __('Logout') }}
                      </x-jet-dropdown-link>
                    </form>
                  </x-slot>
                </x-jet-dropdown>
              </div>
            </li>

          </ul>
        </div>

        <!-- Profile mobile -->
        <div class="md:hidden mt-4 pt-4 pb-1 border-t border-gray-200">
          <div class="flex items-center px-4">
            <div class="flex-shrink-0">
              <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                alt="{{ Auth::user()->name }}" />
            </div>

            <div class="ml-3">
              <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
              <div class="font-medium text-sm text-gray-100">{{ Auth::user()->email }}</div>
            </div>
          </div>

          <div class="mt-3 space-y-1">
            <!-- Account Management -->
            <x-jet-responsive-nav-link class="text-green-100" href="/user/profile"
              :active="request()->routeIs('profile.show')">
              {{ __('Profile') }}
            </x-jet-responsive-nav-link>


            <x-jet-responsive-nav-link class="text-green-100" href="/user/contact">
              Dados de Contato
            </x-jet-responsive-nav-link>

            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
            <x-jet-responsive-nav-link href="/user/api-tokens" :active="request()->routeIs('api-tokens.index')">
              {{ __('API Tokens') }}
            </x-jet-responsive-nav-link>
            @endif

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
              @csrf

              <x-jet-responsive-nav-link class="text-green-100" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                {{ __('Logout') }}
              </x-jet-responsive-nav-link>
            </form>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <!--menu-->
  <div class="mb-auto bg-white py-5 font-sans text-green-600 w-full px-5">

    <div class="container mx-auto">
      @yield('content-app')
    </div>
  </div>
  <footer class="no-print container mx-auto py-5 px-10 text-sm">
    <div class="text-green-900 font-sans max-w-3xl">
      <p>O {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }} possui <i>Campus</i> nas cidades de:</p>
      <p>{{ App\Repository\ParametersRepository::getValueByName('cidades_campus') }}
      </p>
    </div>
    <div class="text-white font-sans mt-5">
        <div class="dsgov">
            <div class="lgpd-reset-cookies">
                <a href="#" class="reset-cookies" onclick="_redefinirCookies(); return false;">Redefinir Cookies</a>
            </div>
        </div>
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
  <div class="container_govbr">
      <div class="redefinir-cookies"></div>
      <div class="dsgov">
          <div class="br-cookiebar default d-none" tabindex="-1">
              <div class="br-modal">
                  <div class="br-card">
                      <div class="container">
                          <div class="wrapper">
                              <div class="br-modal-header entry-content">
                                  <div class="br-modal-title"></div>
                                  <div class="last-update"></div>
                                  <div class="entry-text"></div>
                              </div>
                              <div class="br-modal-body">
                                  <div class="info-text"></div>
                                  <div class="br-list main-content">
                                  </div>
                              </div>
                          </div>
                          <div class="br-modal-footer actions">
                              <button class="br-button small btn-manage" type="button" aria-label="Definir Cookies"></button>
                              <button class="br-button secondary small reject-all" type="button" aria-label="Rejeitar">Rejeitar</button>
                              <button class="br-button secondary small btn-accept" type="button" aria-label="Aceitar"></button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  @stack('modals')
  @livewireScripts
  <!--JavaScript at end of body for optimized loading-->
  <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/candidate.js')}}"></script>
  <!-- CookieBar GovBR-->
  <script type="text/javascript">
    var url_politica_privacidade = "{{env('PAGINA_POLITICA_PRIVACIDADE')}}";
  </script>
  <script type="text/javascript" src="{{ asset('js/cookiebar_1_3_55.js') }}" ></script>
  <script type="text/javascript" src="{{ asset('js/application_cookiebar_1_3_55.js') }}"></script>
  <script id="lgpd-cookie-handling" type="text/javascript" src="{{asset('js/lgpd_cookie_handling_1_3_55.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/block_cookies.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/changeCookieImage.js') }}"></script>
  <!-- FIM -->
  @stack('js')


</body>
@endsection
