<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ env('APP_NAME') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script defer="defer" src="{{ asset('js/alpine_2.6.0.js') }}" type="text/javascript"></script>
        <script defer="defer" src="{{ asset('js/barra.js') }}" type="text/javascript"></script>

    </head>
    <body>
        <x-noscript />
        @include('layouts.barra')
        <div class="font-sans text-gray-900 antialiased">
            <div class="container mx-auto p-4">
                {{ $slot }}
            </div>
        </div>        
        @livewireScripts
        <script src="{{ asset('js/app.js') }}"></script>                
    </body>
</html>
