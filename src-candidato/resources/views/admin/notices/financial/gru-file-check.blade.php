<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Pagamentos realizados por GRU" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens home="admin.notices.read-gru-file.index" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por candidato" />
        </x-manager.header>
    </x-slot>

    <div class=" overflow-x-auto">
        <div class="grid grid-cols">
            <div class="px-5 py-5  inline-block min-w-full  overflow-hidden  md:col-span-3">
                <div class="col-span-6">
                    <h2 class="border-gray-700 border-b-2">Homologações Automáticas</h2>
                    <p>As homologações abaixo foram realizadas de maneira automática</p>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Inscrição Lida
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    CPF Lido
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Valor Lido
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Data Lida
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Homologado?
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                            <tr class="border-b-2 border-gray-200">
                                <td class="p-3">{{$payment->subscription}}</td>
                                <td class="p-3">{{$payment->cpf }}</td>
                                <td class="p-3">{{$payment->value }}</td>
                                <td class="p-3">{{$payment->date}}</td>
                                <td class="p-3">{{$payment->possibleSubscription->homologation_status ?? null }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                <div class="col-span-6">
                    <h2 class="border-gray-700 border-b-2">Inscrições que já estavam homologadas anteriormente</h2>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Inscrição Lida
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    CPF Lido
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Valor Lido
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Data Lida
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Homologado?
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($previousHomologate as $previous)
                            <tr class="border-b-2 border-gray-200">
                                <td class="p-3">{{$previous->subscription}}</td>
                                <td class="p-3">{{$previous->cpf }}</td>
                                <td class="p-3">{{$previous->value }}</td>
                                <td class="p-3">{{$previous->date}}</td>
                                <td class="p-3">{{$previous->possibleSubscription->homologation_status ?? null }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-span-6">
                    <h2 class="border-gray-700 border-b-2">Inscrições não encontrado</h2>
                    <p>Você pode conferir a lista e realizar homologações manuais caso julgar necessário</p>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Inscrição Lida
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    CPF Lido
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Valor Lido
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Data Lida
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Inscrição sugerida
                                </th>
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Homologada?
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notFound as $not)
                            <tr class="border-b-2 border-gray-200">
                                <td class="p-3">{{$not->subscription}}</td>
                                <td class="p-3">{{$not->cpf }}</td>
                                <td class="p-3">{{$not->value }}</td>
                                <td class="p-3">{{$not->date}}</td>
                                <td class="p-3">{{$not->possibleSubscription->subscription_number ?? 'Nenhuma' }}</td>
                                <td class="p-3">{{ $hom = $not->possibleSubscription->homologation_status ?? null }}
                                    @if($hom == 'Inscrito')
                                    <br>
                                    <button type="button"
                                        class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                                        data-id="{{$not->possibleSubscription->id}}"
                                        onclick="homologate(this)">Homologar</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <h2 class="border-gray-700 border-b-2">Linhas com erro</h2>
                <ul>
                    @forelse($errors as $error)
                    <li>{{$error}}</li>
                    @empty
                    <li>Não encontradas linhas com erro</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

    @push('js')

    <script>
        function homologate(evt) {            
            let subscription_id = evt.getAttribute('data-id')            
            axios.put("subscriptions/" + subscription_id + "/homologate")
                .then((response) => {
                    console.log(response.data)                                        
                    evt.parentNode.innerText = response.data.homologation_status
                })
                .catch((response) => {
                    console.log(response)
                    window.alert('Um erro ocorreu e não foi possível homologar a inscrição')
                })
        }
    </script>

    @endpush

</x-manager.app-layout>