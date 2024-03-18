<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Candidatos Convocados - Ações Afirmativas PPI" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens  :routeVars="['notice'=>$notice]"
                 />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5" x-data="{ newTab: false, campus: {{ request('campus') ?? 0 }} }" x-init="newTab = false">
                    <form action="#" id="theform" method="GET" x-bind:target="(newTab) ? '_blank' : '_self'">
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
                        <x-jet-secondary-button type="submit">
                            Gerar
                        </x-jet-secondary-button>
                        <x-jet-secondary-button type="submit" formtarget="_blank" name="contacts" value="1">
                            Gerar com contatos (apenas HTML)
                        </x-jet-secondary-button>
                    </form>
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
                                CPF
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nome
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ação Afirmativa
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Campus
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Curso
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
                                {{$subs->user->getObsfucatedCPF()}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->user->name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->distributionOfVacancy->affirmativeAction->description}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->distributionOfVacancy->offer->courseCampusOffer->campus->name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->distributionOfVacancy->offer->courseCampusOffer->course->name}}
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
