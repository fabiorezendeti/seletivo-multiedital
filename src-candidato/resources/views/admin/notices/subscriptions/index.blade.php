<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Gerenciar Inscrições" :notice="$notice" :nomeEdital="$notice->number">
            @can('isAdmin')
            <x-manager.internal-navbar-itens home="admin.notices.subscriptions.index" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por CPF ou Número de Inscrição" />
            @else
            <x-manager.internal-navbar-itens home="cra.notices.subscriptions.index" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por CPF ou Número de Inscrição" />
            @endcan
        </x-manager.header>
    </x-slot>

    <div id="subscription-table" x-data="subscription()">
        <div class=" overflow-x-auto">
            <div id="filters" class="inline-block min-w-full  overflow-hidden">
                <div class="p-5" x-data="{ newTab: false, offer: {{ request('offer') ?? 0 }} }" x-init="newTab = false">
                    <form action="#" method="GET" x-bind:target="(newTab) ? '_blank' : '_self'">
                        {{-- @cannot('isAdmin') --}}
                            <label for="oferta">Filtrar por Oferta</label>
                            <x-select-box name="offer" id="offer" required x-model="offer">
                                <option value="">-- Todas --</option>
                                @foreach ($offers as $offer)
                                <option value="{{ $offer->id }}">{{ $offer->getString() }}</option>
                                @endforeach
                            </x-select-box>
                        {{-- @endcan --}}

                        <x-jet-secondary-button type="submit">
                            Filtrar
                        </x-jet-secondary-button>
                    </form>
                </div>
            </div>

            <div class="inline-block min-w-full  overflow-hidden">
                <table class="min-w-full leading-normal manager-table">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Nome</th>
                            <th>Campus/Curso</th>
                            <th>Ação Afirmativa</th>
                            <th>Critério de Seleção</th>
                            <th style="min-width: 150px;">CPF</th>
                            <th style="min-width: 165px;">Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                        <tr id="s{{ $subscription->id }}">
                            <td>{{ $subscription->subscription_number }}</td>
                            <td>{{ $subscription->user->name }}</td>
                            <td>{{ $subscription->distributionOfVacancy->offer->getString() }}</td>
                            <td>{{ $subscription->distributionOfVacancy->affirmativeAction->slug }}</td>
                            <td>{{ $subscription->distributionOfVacancy->selectionCriteria->description }}</td>
                            <td>{{ $subscription->user->cpf }}</td>
                            <td class="homologation-status">
                                <div style="display: grid">
                                <span>{{ $subscription->homologation_status == 'Homologado' ? '✓ Homologado' : $subscription->homologation_status }}</span>
                                <span class="average_verified">{{ $subscription->averageWasVerified() ? '✓ Média Verificada' : ''}}</span>
                                </div>
                            </td>
                            <td>
                                <button type="button" x-on:click="openModal({{$subscription->id}})" class="blue-button">
                                    Ver Inscrição
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $subscriptions->appends(request()->query())->links() }}
        </div>



        <div x-show="open" id="modal" x-bind:class="{'opacity-0 pointer-events-none': open === false}"
            class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center">
            <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

            <div
                class="modal-container bg-white w-11/12 md:max-w-lg mx-auto rounded shadow-lg  overflow-y-auto overscroll-auto z-50">
                <div x-on:click="open = false"
                    class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
                    <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 18 18">
                        <path
                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                        </path>
                    </svg>
                </div>

                <!-- Add margin if you want to see some of the overlay behind the modal-->
                <div class="modal-content py-4 text-left z-10 px-6 overflow-y-auto max-h-screen">
                    <!--Title-->
                    <div class="flex justify-between items-center pb-3">
                        <h2 class="text-xl" x-text="subscription.subscription_number">

                        </h2>
                        <p class="bg-red-300 p-2 rounded" x-text="subscription.homologation_status"></p>
                        <p class="bg-red-700 p-2 rounded text-white" x-show="subscription.elimination">ELIMINADO</p>
                        <div class="modal-close cursor-pointer z-30" @click="open = false">
                            <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" viewBox="0 0 18 18">
                                <path
                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex">
                        <p class="text-base font-bold"></p>
                    </div>

                    <!--Body-->
                    <div class="mt-5">
                        <dl>
                            <dt class="font-bold">Nome</dt>
                            <dd x-text="subscription.user.name"></dd>
                            <dt class="font-bold">CPF</dt>
                            <dd x-text="subscription.user.cpf"></dd>
                            <dt class="font-bold">Câmpus</dt>
                            <dd x-text="subscription.distribution_of_vacancy.offer.course_campus_offer.campus.name">
                            </dd>
                            <dt class="font-bold">Curso</dt>
                            <dd x-text="subscription.distribution_of_vacancy.offer.course_campus_offer.course.name">
                            </dd>

                        </dl>

                        <div x-show="subscription.elimination">
                            <dl>
                                <dt class="font-bold">Motivo de eliminação: </dt>
                                <dd x-text="(subscription.elimination) ? subscription.elimination.reason : ''">
                                </dd>
                            </dl>
                        </div>
                        <div>
                            @can('isAdmin')
                            <a x-show="hasDocument()" x-on:click=openBoletim()
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-4 rounded inline-flex items-center"
                                target="_blank">
                                <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" /></svg>
                                <span>Boletim</span>
                            </a>
                            @endcan
                            @can('allowUpdateScores', $notice)
                                @canany(['isAdmin', 'isAcademicRegister'], $notice)
                                    <div x-show="subscription.distribution_of_vacancy.selection_criteria_id > 2" class="bg-gray-100 p-4 mt-4 mb-4">
                                        <form id="update-mean-form">
                                            <h3 class="font-bold">Atualizar Média</h3>
                                            <x-jet-label value="Média do Aluno" />
                                            <x-jet-input type="text" class="w-full" name="media" x-model="subscription.score.media" />
                                            <x-jet-button type="submit" form="update-mean-form" x-on:click.prevent="updateMean"
                                                class="bg-blue-700 hover:bg-blue-300 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-red-500 rounded">
                                                Atualizar Média
                                            </x-jet-button>
                                        </form>
                                    </div>
                                @endcanany
                            @endcan
                            @can('isAcademicRegister')
                            <a x-show="hasDocument()" x-on:click=openBoletim()
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-1 px-4 rounded inline-flex items-center"
                                target="_blank">
                                <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" /></svg>
                                <span>Boletim</span>
                            </a>
                            @endcan
                        </div>

                    </div>
                    @can('isAdmin')
                    <div x-show="!subscription.elimination" class="flex border-red-700 border bg-red-100 p-4">
                        <form >
                            <x-jet-label value="Motivo para eliminação" />
                            <x-textarea class="w-full" name="reason" x-model="eliminateReason" />
                            <x-jet-button type="submit" form="eliminar" x-on:click.prevent="eliminate"
                                class="bg-red-700 hover:bg-red-300 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
                                form="resource-feedback-form">Eliminar Candidato
                            </x-jet-button>
                        </form>
                    </div>
                    @endcan

                    <!--Footer-->
                    <div class="flex justify-end pt-2">
                        <form x-show="subscription.is_homologated === null">
                            <x-jet-button type="submit" form="homologar" x-on:click.prevent="homologate"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                                form="resource-feedback-form">Homologar Manualmente
                            </x-jet-button>
                        </form>
                        <form x-show="subscription.is_homologated">
                            <x-jet-button type="submit" form="cancelar" x-on:click.prevent="cancel"
                                class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
                                form="resource-feedback-form">Cancelar Homologação
                            </x-jet-button>
                        </form>
                        @can('isAdmin')
                        <x-jet-button type="button" x-on:click="trackingInfo"
                            class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-gray-700 hover:border-gray-500 rounded">
                            Auditar Inscrição
                        </x-jet-button>
                        @endcan
                        <button type="button" @click="open = false"
                            class="modal-close bg-gray-500 hover:bg-gray-400 block text-white font-bold py-1 px-4 m-4 border-b-4 border-gray-700 hover:border-gray-500 rounded">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('js')

    <script>
        function subscription(){
            return {
                subscription: {
                    user: {},
                    distribution_of_vacancy: {
                        offer: {
                            course_campus_offer: {
                                course: {},
                                campus: {}
                            }
                        }
                    },
                    elimination: {},
                    score: {}
                },
                eliminateReason: null,
                open: false,
                hasDocument: function() {
                    if (this.subscription.score == null) return false;
                    return this.subscription.score.documento_comprovacao ?? false;
                },
                openModal: function(subscription) {
                    axios.get("subscriptions/"+subscription)
                        .then((response)=>{
                            this.subscription = response.data
                            this.subscription.score = this.subscription.score ?? {media: null}
                            this.open = true
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu')
                        })
                },
                openBoletim: function() {
                    url = 'subscription/' + this.subscription.id + '/view-document'
                    window.open(url,'_blank');
                },
                homologate: function() {
                    axios.put("subscriptions/"+this.subscription.id+"/homologate")
                        .then((response)=>{
                            console.log(response.data)
                            this.subscription.is_homologated = response.data.is_homologated
                            this.subscription.homologation_status = response.data.homologation_status
                            homologationField = document.querySelector("#s"+this.subscription.id + " > .homologation-status")
                            homologationField.innerText = response.data.homologation_status
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })

                },
                eliminate: function() {
                    axios.put("subscriptions/"+this.subscription.id+"/eliminate",{
                        'reason': this.eliminateReason
                    })
                        .then((response)=>{
                            this.subscription.elimination = response.data.elimination
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })
                },
                updateMean: function() {
                    axios.put("subscriptions/"+this.subscription.id+"/update-mean",{
                        'media': this.subscription.score.media
                    })
                        .then((response)=>{
                            this.subscription = response.data
                            window.alert("A média foi atualizada com sucesso")
                            $('#s' + this.subscription.id + ' .average_verified')[0].innerHTML = '✓ Média Verificada'
                        })
                        .catch(error => {
                            if (error.response) {
                                if (error.response.status === 403) {
                                    console.log(error.response)
                                    window.alert(error.response.data.error)
                                } else {
                                    console.log(error.response)
                                    window.alert('Ops, um erro ocorreu, nada foi alterado')
                                }
                            } else if (error.request) {
                                // Erro de solicitação feita, mas sem resposta do servidor
                                console.log(error.request);
                            } else {
                                // Erro na configuração da solicitação
                                console.log('Erro', error.message);
                            }
                        })
                },
                cancel: function() {
                    axios.put("subscriptions/"+this.subscription.id+"/cancel")
                        .then((response)=>{
                            console.log(response.data)
                            this.subscription.is_homologated = response.data.is_homologated
                            this.subscription.homologation_status = response.data.homologation_status
                            homologationField = document.querySelector("#s"+this.subscription.id + " > .homologation-status")
                            homologationField.innerText = response.data.homologation_status
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })
                },
                trackingInfo: function() {
                    window.open("subscriptions/"+this.subscription.id+"/tracking-info",'Inscrições',
                    '_blank',
                    'directories=0,titlebar=0,toolbar=0,location=0,status=0,menubar=0,scrollbars=yes,resizable=yes,width=600,height=480')
                },
            }
        }
    </script>

    @endpush

</x-manager.app-layout>
