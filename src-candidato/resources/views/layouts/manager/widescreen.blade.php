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
  @stack('css')

  <!-- Scripts -->
  <script defer="defer" src="{{ asset('js/alpine_2.6.0.js') }}" type="text/javascript"></script>
  <script defer="defer" src="{{ asset('js/barra.js') }}" type="text/javascript"></script>
</head>

<body class="font-sans antialiased bg-gray-200">
  @include('layouts.barra')
  
  <!--Nav-->
  <x-manager.main-menu></x-manager.main-menu>
  {{ $header }}

  <!-- Page Content -->
  <main>
    <x-success-message />   
    <x-error-message /> 
    <div class="container-widescreen bg-white border-0 border-b-4 border-gray-300 rounded-md mx-auto my-5 min-h-full">
      {{ $slot }}
    </div>
  </main>

  <div class="text-center text-sm">
    Processado em: {{ now()->format('d/m/Y H:i:s') }}
  </div>

  @stack('modals')

  @livewireScripts
  <!--JavaScript at end of body for optimized loading-->
  <script type="text/javascript" src="{{asset('js/app.js')}}"></script>

  @stack('js')

</body>

</html>
