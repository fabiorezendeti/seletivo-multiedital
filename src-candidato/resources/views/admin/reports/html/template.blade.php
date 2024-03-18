<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatório</title>
    <link media="print" rel="Alternate" href="relatorio-candidato-contato.pdf">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- @livewireStyles --}}

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.6.0/dist/alpine.js" defer></script>
    <style>
        @media print {            
            @page {
                size: {{ $orientation ?? 'landscape' }}
            }            

            body {
                margin: 0;
                padding: 0;
                line-height: 1.4em;
            }

            th {
                color: #CCC;
                background-color: black;
                -webkit-print-color-adjust: exact;
                text-transform: uppercase;
                text-align: center;
                font-size: 0.875rem;
            }

            tr:nth-child(even) {
                background: #CCC !important;
                -webkit-print-color-adjust: exact;

            }

            tr:nth-child(odd) {
                background: #FFF;
            }
        }

        th {
            color: #CCC;
            background-color: black;
            text-transform: uppercase;
            text-align: center;
            font-size: 0.875rem;
        }

        tr:nth-child(even) {
            background: #CCC
        }

        tr:nth-child(odd) {
            background: #FFF
        }
    </style>
    @stack('css')
</head>

<body>

    @yield('content')

</body>

</html>