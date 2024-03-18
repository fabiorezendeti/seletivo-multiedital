<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Portal do Candidato - {{ App\Repository\ParametersRepository::getValueByName('sigla_instituicao') }} - @yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@800&amp;display=swap" rel="stylesheet">
    
    @livewireStyles    
    @stack('css')
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/candidate.css') }}">
  
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.6.0/dist/alpine.js" defer></script>
</head>
    
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                    <div class="px-4 text-lg text-green-700 border-r border-green-800 tracking-wider ">
                        @yield('code')
                    </div>
                    <div class="ml-4 text-lg text-green-700 uppercase tracking-wider">
                        @yield('message')
                    </div>
                    
                </div>
                <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                    <div class="ml-4 text-xs text-white tracking-wider italic">
                        <p>@yield('details')</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
    
</html>





