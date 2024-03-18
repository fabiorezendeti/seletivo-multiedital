<!--https://github.com/tailwindtoolbox/Admin-Template-->

<x-manager.dashboard.app-layout>
    <x-slot name="header">
             
            <x-manager.internal-navbar-itens  />
        
    </x-slot>
    <script>
        var charts = {!! json_encode($charts, JSON_HEX_TAG) !!};
                                //console.log(charts);
        var labelsChartNoticesByModality = charts.chartNoticesByModality.map(function(e) {
        return e.slug;
        });
        var dataChartNoticesByModality = charts.chartNoticesByModality.map(function(e) {
        return e.notices;
        });
        
        var labelsChartSubscriptionsByModality = charts.chartSubscriptionsByModality.map(function(e) {
        return e.slug;
        });
        var dataChartSubscriptionsByModality = charts.chartSubscriptionsByModality.map(function(e) {
        return e.subscriptions/1000;
        });

        var labelsChartSubscriptionsByAffirmativeAction = Object.entries(charts.chartSubscriptionsByAffirmativeAction).map(function(e) {
        return e[1].slug;
        });
        var dataChartSubscriptionsByAffirmativeAction = Object.entries(charts.chartSubscriptionsByAffirmativeAction).map(function(e) {
        return e[1].subscriptions;
        });

        var labelsChartTotalCandidatesByCities = charts.chartTotalCandidatesByCities.map(function(e) {
        return e.city;
        });
        var dataChartTotalCandidatesByCities = charts.chartTotalCandidatesByCities.map(function(e) {
        return e.total;
        });

        var colorslist = ["rgba(255, 99, 132, 0.2)", "rgba(255, 159, 64, 0.2)", "rgba(255, 205, 86, 0.2)", "rgba(75, 192, 192, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(153, 102, 255, 0.2)", "rgba(201, 203, 207, 0.2)"];
        var colorslistSolid = ["rgba(255, 99, 132)", "rgba(255, 159, 64)", "rgba(255, 205, 86)", "rgba(75, 192, 192)", "rgba(54, 162, 235)", "rgba(153, 102, 255)", "rgba(201, 203, 207)","rgba(255, 99, 132, 0.5)", "rgba(255, 159, 64, 0.5)", "rgba(255, 205, 86, 0.5)", "rgba(75, 192, 192, 0.5)", "rgba(54, 162, 235, 0.5)", "rgba(153, 102, 255, 0.5)", "rgba(201, 203, 207, 0.5)"];
    </script>
        <nav aria-label="alternative nav">
            <div class="bg-gray-800 shadow-xl h-20 fixed bottom-0 mt-12 md:relative md:h-screen z-10 w-full md:w-48 content-center">

                <div class="md:mt-32 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
                    <ul class="list-reset flex flex-row md:flex-col pt-3 md:py-3 px-1 md:px-2 text-center md:text-left">
                       <!-- <li class="mr-3 flex-1">
                            <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-pink-500">
                                <i class="fas fa-tasks pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Tasks</span>
                            </a>
                        </li>
                        <li class="mr-3 flex-1">
                            <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-500">
                                <i class="fa fa-envelope pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Messages</span>
                            </a>
                        </li>
                       
                        <li class="mr-3 flex-1">
                            <a href="#" class="block py-1 md:py-3 pl-0 md:pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-red-500">
                                <i class="fa fa-wallet pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-400 md:text-gray-200 block md:inline-block">Payments</span>
                            </a>
                        </li>-->
                        <li class="mr-3 flex-1">
                            <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-blue-600">
                                <i class="fas fa-chart-area pr-0 md:pr-3 text-blue-600"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-white md:text-white block md:inline-block">Estatísticas</span>
                            </a>
                        </li>
                    </ul>
                </div>


            </div>
        </nav>
        <section>
            <div id="main" class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

                <div class="bg-gray-800 pt-3">
                    <div class="rounded-tl-3xl bg-gradient-to-r from-blue-900 to-gray-800 p-4 shadow text-2xl text-white">
                        <h1 class="font-bold pl-2">Estatísticas Globais</h1>
                    </div>
                </div>

                <div class="flex flex-wrap">
                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card-->
                        <div class="bg-gradient-to-b from-pink-200 to-pink-100 border-b-4 border-pink-500 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-pink-600"><i class="fas fa-file fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h2 class="font-bold uppercase text-gray-600">Editais</h2>
                                    <p class="font-bold text-3xl">{{$counters['totalNotices']}}</p>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card-->
                    </div>

                    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card-->
                        <div class="bg-gradient-to-b from-green-200 to-green-100 border-b-4 border-green-600 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-green-600"><i class="fa fa-users fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h2 class="font-bold uppercase text-gray-600">Inscrições</h2>
                                   <p class="font-bold text-3xl">{{$counters['totalSubscriptions']}} </p>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card-->
                    </div>
                    
                    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card-->
                        <div class="bg-gradient-to-b from-yellow-200 to-yellow-100 border-b-4 border-yellow-600 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-yellow-600"><i class="fas fa-user-graduate fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h2 class="font-bold uppercase text-gray-600">Vagas</h2>
                                    <p class="font-bold text-3xl">{{$counters['totalVacancies']}}</p>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card-->
                    </div>
                </div>


                <div class="flex flex-row flex-wrap flex-grow mt-2">

                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h2 class="font-bold uppercase text-gray-600">Editais e Inscritos por Modalidade</h2>
                        </div>
                        <div class="p-5">
                            <canvas id="chartjs-1" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-1"), {
                                    "type": "bar",
                                    "data": {
                                        "labels": labelsChartNoticesByModality,
                                        "datasets": [{
                                            "label": "Editais por Modalidade",
                                            "data": dataChartNoticesByModality,
                                            "fill": false,
                                            "backgroundColor": colorslist[0],
                                            "borderWidth": 1
                                        },
                                        {
                                            "label": "Inscritos (x1000)",
                                            "data": dataChartSubscriptionsByModality,
                                            "type": "line",
                                            "fill": false,
                                            "borderColor": "rgb(54, 162, 235)"
                                        }]
                                    },
                                    "options": {
                                        "scales": {
                                            "yAxes": [{
                                                "ticks": {
                                                    "beginAtZero": true
                                                }
                                            }]
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>

                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h5 class="font-bold uppercase text-gray-600">Inscritos por Ação Afirmativa</h5>
                        </div>
                        <div class="p-5"><canvas id="chartjs-4" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-4"), {
                                    "type": "doughnut",
                                    "data": {
                                        "labels": labelsChartSubscriptionsByAffirmativeAction,
                                        "datasets": [{
                                            "label": "Issues",
                                            "data": dataChartSubscriptionsByAffirmativeAction,
                                            "backgroundColor": colorslistSolid
                                        }]
                                    }
                                });
                            </script>
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>

                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Table Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h2 class="font-bold uppercase text-gray-600">Eventos do mês</h2>
                        </div>
                        <div class="p-5">
                            <table class="w-full p-5 text-gray-700">
                            @empty($calendar)
                                @else
                                    <thead>
                                    <tr>
                                        <th class="text-left text-blue-900">Edital</th>
                                        <th class="text-left text-blue-900">Evento</th>
                                        <th class="text-left text-blue-900">Data</th>
                                    </tr>
                                    </thead>
                                @endempty
                                <tbody>
                                
                                @forelse ($calendar as $event)
                                    @foreach ($event as $date)
                                    <tr>
                                        <td>{{$date->number}}</td>
                                        <td>{{$date->label}}</td>
                                        <td>{{date('d-m-Y', strtotime($date->date))}}</td>
                                    </tr>
                                    @endforeach
                                    @empty
                                    <p class="bg-danger text-gray-600">Sem eventos no mês</p>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!--/table Card-->
                </div>

                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h class="font-bold uppercase text-gray-600">TOP 5 cidades de candidatos</h>
                        </div>
                        <div class="p-5">
                        <canvas id="chartjs-7" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-7"), {
                                    "type": "horizontalBar",
                                    "data": {
                                        "labels": labelsChartTotalCandidatesByCities,
                                        "datasets": [{
                                            "label": "Inscritos",
                                            "data": dataChartTotalCandidatesByCities,
                                            "fill": false,
                                            "backgroundColor": colorslist[0],
                                            "borderWidth": 1
                                        }]
                                    }
                                });
                            </script>
                            
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>

                </div>
            </div>
        </section>
</x-manager.dashboard.app-layout>    