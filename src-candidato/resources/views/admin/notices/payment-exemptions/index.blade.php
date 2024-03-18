<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Gerenciar Solicitações de Isenção de Pagamento" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens home="admin.notices.payment-exemptions.index" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por CPF ou Número de Inscrição" />
        </x-manager.header>
    </x-slot>

    <div id="subscription-table" x-data="subscription()">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <table class="min-w-full leading-normal manager-table">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Nome</th>
                            <th>Data de Nascimento</th>
                            <th>Nº RG</th>
                            <th>Emissor RG</th>
                            <th>CPF</th>
                            <th>Nome da Mãe</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                        <tr id="s{{ $subscription->id }}">
                            <td>{{ $subscription->subscription_number }}</td>
                            <td>{{ $subscription->user->name }}</td>
                            <td>{{ $subscription->user->birth_date }}</td>
                            <td>{{ $subscription->user->rg }}</td>
                            <td>{{ $subscription->user->rg_emmitter }}</td>
                            <td>{{ $subscription->user->cpf }}</td>
                            <td>{{ $subscription->user->mother_name  }}</td>
                            <td>{{ $subscription->paymentExemption->status }}</td>
                            <td>
                                <button type="button" x-on:click="openModal({{$subscription->id}})" class="blue-button">
                                    Ver Solicitação
                                </button>
                                <button type="button" x-on:click="openTxt({{$subscription->id}})" class="blue-button">
                                    Baixar Txt
                                </button>
                            </td>
                        </tr>
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
                        <p class="bg-red-300 p-2 rounded" x-text="paymentExemptionStatus"></p>
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
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-6 md:col-span-6">
                                    <x-jet-label value="Nome" />
                                    <p class="text-base font-bold" x-text="subscription.user.name"></p>
                                </div>
                                <div class="col-span-6 sm:col-span-6 md:col-span-6">
                                    <x-jet-label value="CPF" />
                                    <p class="text-base font-bold" x-text="subscription.user.cpf"></p>
                                </div>
                            </div>
                        </div>
                        @can('isAdmin')
                            <form >
                                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6 sm:col-span-6 md:col-span-6">
                                            <x-jet-label for="sex" value="Sexo" />
                                            <x-select-box id="sex" name="sex"
                                                x-model="subscription.user.sex">
                                                <option value=""></option>
                                                <option value="F">Feminino</option>
                                                <option value="M">Masculino</option>
                                            </x-select-box>
                                        </div>
                                        <div class="col-span-6 sm:col-span-6 md:col-span-6">
                                            <x-jet-label value="Data de Emissão do RG" />
                                            <x-jet-input class="block mt-1 w-full" type="date" name="rg_issue_date"
                                                x-model="subscription.user.rg_issue_date" required />
                                        </div>
                                        <div class="col-span-6 sm:col-span-6 md:col-span-6">
                                            <x-jet-label value="Nº de Identificação Social" />
                                            <x-jet-input class="block mt-1 w-full" type="number" name="social_identification_number"
                                                x-model="subscription.user.social_identification_number" required />
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="px-4 py-5 bg-white sm:p-3 shadow sm:rounded-tl-md sm:rounded-tr-md">
                                <div class="grid grid-cols-3 gap-4 border">
                                    <div class="col-span-6 sm:col-span-6 md:col-span-6">
                                        <a x-on:click=openDocumentIdFront()
                                            class="bg-green-300 hover:bg-green-400 text-green-800 font-bold py-1 px-4 rounded inline-flex items-center"
                                            target="_blank">
                                            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" /></svg>
                                            <span>RG - Frente</span>
                                        </a>
                                    </div>
                                    <div class="col-span-6 sm:col-span-6 md:col-span-6">
                                        <a x-on:click=openDocumentIdBack()
                                            class="bg-green-300 hover:bg-green-400 text-green-800 font-bold py-1 px-4 rounded inline-flex items-center"
                                            target="_blank">
                                            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" /></svg>
                                            <span>RG - Verso</span>
                                        </a>
                                    </div>
                                    <div class="col-span-6 sm:col-span-6 md:col-span-6">
                                        <a x-on:click=openDocumentForm()
                                            class="bg-green-300 hover:bg-green-400 text-green-800 font-bold py-1 px-4 rounded inline-flex items-center"
                                            target="_blank">
                                            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" /></svg>
                                            <span>Formulário</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="flex border-red-700 border bg-red-100 p-4">
                                <form >
                                    <x-jet-label value="Motivo do indeferimento" />
                                    <x-textarea class="w-full" name="rejected_reason" x-model="subscription.payment_exemption.rejected_reason" />
                                    <x-jet-button type="submit" form="reject" x-on:click.prevent="reject"
                                        class="bg-red-700 hover:bg-red-300 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
                                        form="resource-feedback-form">Indeferir
                                    </x-jet-button>
                                </form>
                            </div>
                        @endcan
                        </div>

                    </div>

                    <!--Footer-->
                    <div class="flex justify-end pt-2">
                        @can('isAdmin')
                        {{-- x-show="subscription.is_homologated === null" --}}
                        <form>
                            <x-jet-button type="submit" form="homologar" x-on:click.prevent="updatePersonalData"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                                form="resource-feedback-form">Atualizar Dados Pessoais
                            </x-jet-button>
                        </form>
                        <form>
                            <x-jet-button type="submit" form="cancelar" x-on:click.prevent="accept"
                                class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
                                form="resource-feedback-form">Deferir
                            </x-jet-button>
                        </form>
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
                    payment_exemption: {}
                },
                paymentExemptionStatus: '',
                open: false,
                openModal: function(id) {
                    axios.get("subscriptions/"+id+"/payment-exemption")
                        .then((response)=>{
                            this.subscription = response.data
                            this.paymentExemptionStatus = this.subscription.payment_exemption.status;
                            this.open = true
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu')
                        })
                },
                openTxt: function(id) {

                    axios.get("subscriptions/"+id+"/payment-exemption")
                        .then((response)=>{
                            this.subscription = response.data
                            if (this.subscription.user.sex
                                && this.subscription.user.rg_issue_date
                                && this.subscription.user.social_identification_number){
                                url = 'subscriptions/' + id + '/payment-exemption/view-txt'
                                window.open(url,'_blank');
                            }else{
                                window.alert('Edite os dados antes de baixar o TXT');
                            }
                        })
                        .catch((response)=>{
                            window.alert('Ops, um erro ocorreu')
                        })
                },
                openDocumentIdFront: function() {
                    url = 'subscriptions/' + this.subscription.id + '/payment-exemption/view-id-front'
                    window.open(url,'_blank');
                },
                openDocumentIdBack: function() {
                    url = 'subscriptions/' + this.subscription.id + '/payment-exemption/view-id-back'
                    window.open(url,'_blank');
                },
                openDocumentForm: function() {
                    url = 'subscriptions/' + this.subscription.id + '/payment-exemption/view-form'
                    window.open(url,'_blank');
                },
                updatePersonalData: function() {
                    axios.put("subscriptions/"+this.subscription.id+"/payment-exemption/update-personal-data",{
                        'sex': this.subscription.user.sex,
                        'rg_issue_date': this.subscription.user.rg_issue_date,
                        'social_identification_number': this.subscription.user.social_identification_number,
                    })
                        .then((response)=>{
                            this.subscription = response.data
                            window.alert("Os dados foram atualizados com sucesso")
                            location.reload(true)
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })
                },
                accept: function() {
                    axios.put("subscriptions/"+this.subscription.id+"/payment-exemption/accept")
                        .then((response)=>{
                            this.subscription = subscription
                            this.paymentExemptionStatus = response.data.payment_exemption.status;
                            location.reload(true)
                            window.alert("Isenção de pagamento deferida com suceso")
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })
                },
                reject: function() {
                    if (!this.subscription.payment_exemption.rejected_reason) return window.alert("Você deve preencher o campo Motivo de indeferimento")
                    console.log(this.subscription.user.sex);
                    axios.put("subscriptions/"+this.subscription.id+"/payment-exemption/reject",{
                        'rejected_reason': this.subscription.payment_exemption.rejected_reason
                    })
                        .then((response)=>{
                            this.subscription = subscription
                            this.paymentExemptionStatus = response.data.payment_exemption.status;
                            location.reload(true)
                            window.alert("Isenção de pagamento indeferida com sucesso")
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu, nada foi alterado')
                        })
                },
            }
        }
    </script>

    @endpush

</x-manager.app-layout>
