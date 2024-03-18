<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Portal do Candidato - {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@800&amp;display=swap" rel="stylesheet">

    @livewireStyles
    @stack('css')
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/candidate.css') }}">

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.6.0/dist/alpine.js" defer></script>
    <script defer="defer" src="//barra.brasil.gov.br/barra_2.0.js" type="text/javascript"></script>
</head>



<body class="antialiased flex flex-col h-screen justify-between">
    <x-noscript />
    <div id="barra-brasil" class="no-print" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;">
        <ul id="menu-barra-temp" class="no-print" style="list-style:none;">
            <li
                style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED">
                <a href="http://brasil.gov.br"
                    style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do
                    Governo Brasileiro</a>
            </li>
        </ul>
    </div>

    
    <nav id="header" class="no-print w-full z-30 text-white font-sans font-bold min-h-40 shadow-md">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-2 md:py-6">
            <div id="logo-header"
                class="rounded-full bg-white w-16 h-16 md:w-32 md:h-32 bg-opacity-75 flex items-center">
                <a class="text-white no-underline hover:no-underline   text-2xl md:text-4xl" href="/dashboard">
                    <img class="w-16 md:w-36" src="{{asset('img/logo_ifc_v_color.png')}}"
                        alt="{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }}">
                </a>
            </div>
            <div id="lettering" class="flex justify-start text-2xl md:text-4xl md:ml-6 md:mr-1 mr-20">
                <span class="font-open-sans text-portal mr-2 md:mr-5 ">@yield('type','Sorteio')</span>
                <span class="font-open-sans  text-portal md:mt-1">@yield('notice')</span>
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

            <div class="w-full flex-grow md:flex md:items-center md:w-auto hidden  mt-2 md:mt-0 text-black p-4 md:p-0 z-20"
                id="nav-content">
                <div class="list-reset md:flex justify-end flex-1 items-center mr-10">
                    <ul class="">
                        <li
                            class="block md:inline md:loat-left md:pb-3 px-4 text-green-100 hover:text-white cursor-pointer   text-base tracking-wide">
                            <a></a>
                        </li>
                        @yield('top-menu-items')
                    </ul>
                </div>                
            </div>
        </div>
    </nav>
    <!--menu-->
    <div class="mb-auto py-5 font-sans text-green-600 w-full px-5">
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
            <p><b>{{ App\Repository\ParametersRepository::getValueByName('nome_instituicao_curto') }} - REITORIA</b></p>
            <p>{{ App\Repository\ParametersRepository::getValueByName('endereco_reitoria') }}</p>
            <p>Fone {{ App\Repository\ParametersRepository::getValueByName('fone_instituicao') }} | E-mail: {{ App\Repository\ParametersRepository::getValueByName('email_instituicao') }}</p>
            <p class="pt-3">
                <a href="https://www.facebook.com/ifc.oficial/" target="_blank" class="float-left mr-2">
                    <img src="{{asset('img/facebook-5-24.png')}}" alt="ícone do facebook"  /></a>
                <a href="https://www.instagram.com/ifc.oficial/" target="_blank" class="float-left mr-2">
                    <img src="{{asset('img/instagram-5-24.png')}}" alt="ícone do instagram"/></a>
                <span class="">ifc.oficial </span>
            </p>
        </div>
    </footer>
    @stack('modals')
    @livewireScripts
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/lottery.js')}}"></script>
    @stack('js')
</body>

</html>