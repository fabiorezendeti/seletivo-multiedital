<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

    <script defer="defer" src="{{ asset('js/alpine_2.6.0.js') }}" type="text/javascript"></script>
    <script defer="defer" src="{{ asset('js/barra.js') }}" type="text/javascript"></script>

    <link rel="stylesheet" href="{{ asset('css/cookiebar_govbr.css') }}">
</head>
    @yield('body')
</html>
