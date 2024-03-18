<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Pagamentos Pendentes" :notice="$notice" :nomeEdital="$notice->number">
            @can('isAdmin')
            <x-manager.internal-navbar-itens home="admin.notices.pending-payments.report" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por Número de Inscrição" />
            @endcan
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="grid grid-cols-12 gap-4 py-5">
                    <div class="col-span-6 px-5" id="div_bt_update">
                        <a href="{{route('admin.pagtesouro-settings.payment.update-payment-status',['notice'=>$notice])}}" id="bt_update"
                           class="inline-flex items-center float-left bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-1 rounded"
                           title="Anular">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                            </svg>
                            Atualizar status
                        </a>
                    </div>
                    <div class="col-span-6 px-5">
                        <form action="#" method="GET" target="_blank" >
                            <input type="hidden" name="html" value="1">
                            <x-jet-secondary-button type="submit" x-on:click="console.log(newTab)" class="inline-flex  items-center float-right font-bold py-1 px-1 rounded" >
                                Gerar Relatório em Html
                            </x-jet-secondary-button>
                        </form>
                    </div>
                    <div class="col-span-4">
                    <span class="aguarde hidden inline-flex items-center bg-gray-400 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                                            Aguarde...</span>
                    </div>
                </div>

                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Inscrição
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status Pagamento
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nome
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Campus-Curso
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                E-mail
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tel
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Data insc.
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subs)

                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->subscription_number}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->situacao_codigo ? $subs->situacao_codigo : 'NÃO GERADO'}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->user_name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->campus_name}} - {{$subs->course_name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->email}}
                            </td>
                            <td>{{ $subs->phone_number }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $subs->created_at)->format("d-m-Y H:i")}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $subscriptions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>
<script>
    $(document).ready(function() {
    $("#div_bt_update a").click(function() {
        $("#div_bt_update a").addClass("hidden");
        $(".aguarde").removeClass("hidden");
    });
});
</script>
