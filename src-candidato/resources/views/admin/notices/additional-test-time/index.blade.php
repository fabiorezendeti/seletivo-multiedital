<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Gerenciar Solicitações de Tempo Adicional de Prova" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens home="admin.notices.additional-test-time.index" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por candidato" />
        </x-manager.header>
    </x-slot>

    <div id="my-table" x-data="recourse()">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <form action="#" method="POST" id="delete-notice">
                    @csrf
                    @method('delete')
                </form>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nº
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nome
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                CPF
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Campus / Curso
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Email
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Deferido?
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->getSubscriptionNumber()  }} </td>
                            <td>{{ $subscription->user->name }}</td>
                            <td>{{ $subscription->user->cpf }}</td>
                            <td>{{ $subscription->distributionOfVacancy->offer->getString() }}</td>
                            <td>{{ $subscription->user->email }}</td>
                            <td>{{ $subscription->additional_test_time_analysis['approved_ptBR'] ?? null }}</td>
                            <td>
                                <button type="button"
                                    class="bg-red-500 mt-3 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full"
                                    x-on:click="openModal($event)"
                                    data-recourse="{{ $subscription->getAttributes()['additional_test_time_analysis'] }}"
                                    >
                                    Feedback
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

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
                            <h2 class="text-xl" x-text="'Inscrição: ' + subscription"></h2>
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
                            <p class="text-base font-bold" x-text="user +  ' - CPF: ' +cpf"></p>
                        </div>

                        <!--Body-->
                        <div class="mt-5">
                            <h3 class="text-red-700 font-bold">Criada em:</h3>
                            <p x-text="recourse.date_ptBR"></p>
                        </div>
                        <form id="recourse-feedback-form" class="border-gray-500 border-t-2">
                            <x-jet-label class="text-xl" value="Resposta ao Recurso" />
                            <x-textarea value=""  x-bind:required="approved == 0"  class="w-full" rows="5" x-model="feedback"></x-textarea>
                            <p class="text-xl">Aprovado?</p>
                            <label class="block items-center">
                                <input type="radio" class="form-radio h-8 w-8 text-green-500" name="approved"
                                    x-model="approved"
                                    value="1">
                                <span class="ml-4 text-lg">Sim</span>
                            </label>
                            <label class="block items-center">
                                <input type="radio" class="form-radio h-8 w-8 text-green-500" name="approved"
                                    x-model="approved"
                                    value="0">
                                <span class="ml-4 text-lg">Não</span>
                            </label>
                        </form>

                        <!--Footer-->
                        <div class="flex justify-end pt-2">
                            <x-jet-button  x-show="recourse.approved == null"  type="submit" x-on:click.prevent="sendForm" form="recourse-feedback-form">Salvar</x-jet-button>
                            <p  x-show="recourse.approved != null" class="bg-red-300 text-red-700 p-5" > Você já emitiu um feedback para esse recurso, não pode ser alterado </p>
                            <button type="button" @click="open = false"
                                class="modal-close px-4 bg-gray-500 p-3 rounded-lg text-white hover:bg-gray-400">Fechar</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


    @push('js')

    <script>
        function recourse(){
            return {
                open: false,
                recourse: {},
                target: null,
                feedback: null,
                approved: false,
                user: null,
                cpf: null,
                subscription: null,
                openModal: function(event) {
                    this.recourse = JSON.parse(event.target.getAttribute('data-recourse'));
                    this.open = true;
                    this.subscription = event.target.parentNode.parentNode.children[0].textContent;
                    this.cpf = event.target.parentNode.parentNode.children[2].textContent;
                    this.user = event.target.parentNode.parentNode.children[1].textContent;
                    this.feedback = this.recourse.feedback ?? null;
                    this.approved = this.recourse.approved ?? false;
                    this.target = event.target.parentNode.parentNode.children[6];
                },
                sendForm: function() {
                    if (this.feedback == null && this.approved == 0) return window.alert("Um feedback é necessário")
                    axios.put("./additional-test-time/"+this.subscription,{feedback: this.feedback, approved: this.approved})
                        .then((response)=>{
                            window.alert('Ok, Seu Feedback foi salvo com sucesso')
                            location.reload(true);
                        })
                        .catch((error)=>{
                            window.alert('Um erro ocorreu')
                            console.log(error.message)
                        })
                }
            }
        }
    </script>

    @endpush

</x-manager.app-layout>
