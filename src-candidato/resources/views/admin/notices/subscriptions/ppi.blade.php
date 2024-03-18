<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Gerenciar Inscrições PPI" :notice="$notice" :nomeEdital="$notice->number">
            @can('isAdmin')
            <x-manager.internal-navbar-itens home="admin.notices.subscriptions.ppi" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por CPF ou Número de Inscrição" />
            @else
            <x-manager.internal-navbar-itens home="cra.notices.subscriptions.ppi" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por CPF ou Número de Inscrição" />
            @endcan
        </x-manager.header>
    </x-slot>

    <div id="subscription-table" x-data="subscription()">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                @can('subscriptionsIsOpen',$notice)
                <p class="bg-red-300 p-2 rounded">Período de inscrições ainda não se encerrou.</p>
                @endcan
                <div class="w-full text-right p-2">
                    <a class="bg-gray-100 hover:bg-gray-200 p-1 rounded " href="./ppi/download">Baixar lista com contatos (formato csv)</a>
                </div>
                <table class="min-w-full leading-normal manager-table">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Nome</th>
                            <th>Campus/Curso</th>
                            <th>CPF</th>
                            <th>Ação</th>
                            <th>Aferição PPI</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                        <tr id="s{{ $subscription->id }}">
                            <td>{{ $subscription->subscription_number }}</td>
                            <td>{{ $subscription->user->name }}</td>
                            <td>{{ $subscription->distributionOfVacancy->offer->getString() }}</td>
                            <td>{{ $subscription->user->cpf }}</td>
                            <td>{{ $subscription->distributionOfVacancy->affirmativeAction->slug }}</td>
                            <td class="homologation-status">{{ $subscription->afericao_ppi_status }}</td>
                            <td>
                                @can('allowAfericaoPPI',$subscription)
                                <button type="button" x-on:click="openModal({{$subscription->id}})" class="blue-button">
                                    Ver Inscrição
                                </button>
                            </td>
                        </tr>
                        @endcan
                        @endforeach
                    </tbody>
                </table>

            </div>
            {{ $subscriptions->links() }}
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
                        <p class="bg-red-300 p-2 rounded" x-text="subscription.afericao_ppi_status"></p>
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
                    </div>


                    <!--Footer-->
                    <div class="flex justify-end pt-2">
                        <form id="form"
                            x-show="subscription.is_ppi_checked === null || subscription.is_ppi_checked === false">
                            <x-jet-button type="submit" form="deferirPPI" x-on:click.prevent="checkPPI"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                                form="resource-feedback-form">Deferir autodeclaração étnica
                            </x-jet-button>
                        </form>
                        <form id="form" x-show="subscription.is_ppi_checked">
                            <x-jet-button type="submit" form="indeferirPPI" x-on:click.prevent="uncheckPPI"
                                class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
                                form="resource-feedback-form">Indeferir autodeclaração étnica
                            </x-jet-button>
                        </form>
                        <button type="button" @click="open = false"
                            class="modal-close bg-gray-500 hover:bg-gray-400 text-white font-bold py-1 px-4 m-4 border-b-4 border-gray-700 hover:border-gray-500 rounded">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('js')

    <script>
        function subscription() {
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
                },
                open: false,
                openModal: function (subscription) {
                    axios.get(subscription)
                        .then((response) => {
                            this.subscription = response.data
                        })
                        .catch((response) => {
                            console.log(response)
                            window.alert('Ops, um erro ocorreu')
                        })
                    this.open = true
                },
                checkPPI: function () {

                    axios.put(this.subscription.id + "/checkPPI")
                        .then((response) => {
                            console.log(response.data)
                            this.subscription.is_ppi_checked = response.data.is_ppi_checked
                            this.subscription.afericao_ppi_status = response.data.afericao_ppi_status
                            homologationField = document.querySelector("#s" + this.subscription.id + " > .homologation-status")
                            homologationField.innerText = response.data.afericao_ppi_status
                        })
                        .catch((response) => {
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })

                },
                uncheckPPI: function () {
                    axios.put(this.subscription.id + "/uncheckPPI")
                        .then((response) => {
                            console.log(response.data)
                            this.subscription.is_ppi_checked = response.data.is_ppi_checked
                            this.subscription.afericao_ppi_status = response.data.afericao_ppi_status
                            homologationField = document.querySelector("#s" + this.subscription.id + " > .homologation-status")
                            homologationField.innerText = response.data.afericao_ppi_status
                        })
                        .catch((response) => {
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })
                }
            }
        }
    </script>

    @endpush

</x-manager.app-layout>