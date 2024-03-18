<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Relatório - Totais por Ação Afirmativa" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens home="admin.notices.show" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por campus" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5" x-data="{ newTab: false, campus: {{ request('campus') }} }" x-init="newTab = false">
                    <form action="#" method="GET" x-bind:target="(newTab) ? '_blank' : '_self'">
                        <label for="campus">Escolha um Campus</label>
                        <x-select-box name="campus" id="campus" required x-model="campus">
                            @foreach ($campuses as $campus)
                            <option value="{{ $campus->id }}"
                                >{{ $campus->name }}</option>
                            @endforeach
                        </x-select-box>
                        <label class="block items-center my-2" >
                            <input type="checkbox" class="form-checkbox h-8 w-8" x-model="newTab" name="html" value="1">
                            <span class="ml-2 ">Gerar em formato HTML</span>
                        </label>
                        <x-jet-secondary-button type="submit" x-on:click="console.log(newTab)">
                            Gerar
                        </x-jet-secondary-button>
                    </form>
                </div>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Campus
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Curso
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ação Afirmativa
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Inscritos
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Homologados
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Total de Vagas
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultSet as $result)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm" >
                                    {{ $result->campus }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $result->course }} ( {{ $result->shift }} )
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $result->affirmative }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $result->subscriptions }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $result->homologations }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $result->total }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{ $offers->appends(request()->query())->links() }} --}}
            </div>
        </div>
    </div>
</x-manager.app-layout>
