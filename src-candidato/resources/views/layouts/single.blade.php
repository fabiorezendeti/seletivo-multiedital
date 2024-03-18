<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  @livewireStyles

  <!-- Scripts -->
  <script defer="defer" src="{{ asset('js/alpine_2.6.0.js') }}" type="text/javascript"></script>
  <script defer="defer" src="{{ asset('js/barra.js') }}" type="text/javascript"></script>
</head>

<body class="font-sans antialiased bg-gray-200">
  @include('layouts.barra')

  <!--Nav-->
  <nav id="header" class="shadow-md bg-blue-900 w-full z-30 top-0 text-white py-1 lg:py-6">
    <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 px-2 py-2 lg:py-6">
      <div id="logo-header" class="pl-4 flex items-center">
        <a class="text-white no-underline hover:no-underline font-bold text-2xl lg:text-4xl" href="#">
          <img class="w-40" src="{{asset('img/logo_ifc.png')}}"/> </a> </div> <!--mobile-menu-->
          <div class="block absolute right-0 lg:hidden pr-4">
            <button id="nav-toggle" class="flex items-center px-3 py-2 border rounded text-white border-white hover:text-gray-800 hover:border-blue-500 appearance-none focus:outline-none"> <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <title>Menu</title>
                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
              </svg>
            </button>
          </div>

          <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 text-black p-4 lg:p-0 z-20" id="nav-content">
            <div class="list-reset lg:flex justify-end flex-1 items-center mr-10">
              <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 text-blue-100 hover:text-white mr-4">
                Seção 1
              </a>
              <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 text-blue-100 hover:text-white mr-4">
                Seção 2
              </a>
              <a href="#responsive-header" class="block mt-4 lg:inline-block lg:mt-0 text-blue-100 hover:text-white">
                Seção 3
              </a>
            </div>
          </div>
      </div>
  </nav>
  <!--edital-->
  <div id="edital-header" class="shadow-md bg-blue-800 w-full top-0 text-white py-1 ">
    <div class="w-full container mx-auto flex flex-wrap items-center justify-start mt-0 px-2 py-2 ">
      <a href="#" id="bt_list" class="mx-3 h-5 w-5 float-right bg-blue-500 hover:bg-blue-400 text-white font-bold py-1 px-1 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
      </a>
      <span class="text-white font-bold text-2xl">@yield('edital_name')</span>
    </div>
  </div>


  <div class="container bg-white border-0 border-b-4 border-gray-300 rounded-md mx-auto px-4 my-5">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-2">
        <span class="text-xl md:text-4xl text-gray-400">@yield('model_name')</span>
      </div>
      <!--tiulo-->
      <div class="col-span-3 md:col-span-2 py-2 ml-2 mt-1">
        <!--açoes-->
        <a href="#" id="bt_list" class="mx-3 inline bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
          Listar
        </a>
        <a href="#" id="bt_create" class="mx-3 inline bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
          Novo
        </a>
      </div>
      <div class="col-span-6 md:col-span-2 flex h-10 md:ml-20 mt-2 justify-items-auto">
        <!--busca-->
        <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-2/3 rounded-lg p-2" type="text" placeholder="Busca">
        <a href="#" id="bt_search" class="bg-white hover:none text-gray-800 font-bold py-2 px-2 mb-1 rounded inline-flex items-center" style="margin-left: -40px;margin-top: 5px;">
          <svg class="text-gray-200 hover:text-blue-800 fill-current w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
          </svg>
        </a>
      </div>
    </div>

  </div>
  </div>

  <div class="container bg-white border-0 border-b-4 border-gray-300 rounded-md mx-auto my-5 min-h-full">
    @yield('content')
  </div>

  @livewireScripts
  <!--JavaScript at end of body for optimized loading-->
  <script type="text/javascript" src="{{asset('js/app.js')}}"></script>

</body>

</html>
