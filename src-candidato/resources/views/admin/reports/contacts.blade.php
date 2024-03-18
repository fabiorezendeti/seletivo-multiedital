<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Contato dos Candidatos" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens  :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5" x-data="{ newTab: false, campus:  new URLSearchParams(location.search).get('campus')  }" x-init="newTab = false">
                    <form action="#" method="GET" x-bind:target="(newTab) ? '_blank' : '_self'">
                        <label for="campus">Escolha um Campus</label>
                        <x-select-box name="campus" id="campus" required x-model="campus">
                            @foreach ($campuses as $campus)
                            <option value="{{ $campus->id }}"                                
                                >{{ $campus->name }}</option>
                            @endforeach
                        </x-select-box>
                        <label class="block items-center my-2" >
                            <input type="radio" class="form-checkbox h-8 w-8" x-model="newTab" name="type" value="html">
                            <span class="ml-2 ">Gerar em formato HTML</span>                            
                            <input type="radio" class="form-checkbox h-8 w-8" x-model="newTab" name="type" value="csv">
                            <span class="ml-2 ">Gerar em formato CSV</span>                            
                        </label>
                        <x-jet-secondary-button type="submit" x-on:click="console.log(newTab)">
                            Gerar
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
                                E-mail
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Telefone
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Telefone Alt.
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Cidade
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                UF
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Curso
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ação Afirmativa
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
                                {{$subs->user->cpf}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->user->name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->user->email}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->user->contact->phone_number ?? null}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->user->contact->alternative_phone_number ?? null}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->user->contact->city->name ?? null}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->user->contact->city->state->slug ?? null}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->distributionOfVacancy->offer->courseCampusOffer->course->name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$subs->distributionOfVacancy->affirmativeAction->slug}}
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