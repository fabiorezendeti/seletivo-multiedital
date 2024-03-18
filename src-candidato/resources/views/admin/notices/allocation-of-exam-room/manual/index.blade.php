<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Gerenciar Ensalamento Manual" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens home="admin.notices.allocation-of-exam-room.manual" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por CPF ou Número de Inscrição" />
        </x-manager.header>
    </x-slot>

    <div id="subscription-table" x-data="subscription()">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5">
                    <form action="#" method="GET" >
                        <label for="campus">Escolha um Campus</label>
                        <x-select-box name="campus" id="campus" required>
                            <option value="" hidden="true">Selecione...</option>
                            <option value="">Todos</option>
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->id }}" @if(!is_null($campus_selected_id) && $campus->id == $campus_selected_id) selected="selected" @endif>{{ $campus->name }}</option>
                            @endforeach
                        </x-select-box>
                        <x-jet-secondary-button type="submit">
                            Selecionar
                        </x-jet-secondary-button>
                    </form>
                </div>
                <table class="min-w-full leading-normal manager-table">
                    <thead>
                        <tr>
                            <th>Nº</th>
                            <th>Nome</th>
                            <th>Campus/Curso</th>
                            {{-- <th>Ação Afirmativa</th> --}}
                            {{-- <th>Critério de Seleção</th> --}}
                            <th>CPF</th>
                            {{-- <th>Status</th> --}}
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                        <tr id="s{{ $subscription->id }}">
                            <td>{{ $subscription->subscription_number }}</td>
                            <td>{{ strtoupper($subscription->user->name) }}</td>
                            <td>{{ $subscription->distributionOfVacancy->offer->getString() }}</td>
                            {{-- <td>{{ $subscription->distributionOfVacancy->affirmativeAction->slug }}</td> --}}
                            {{-- <td>{{ $subscription->distributionOfVacancy->selectionCriteria->description }}</td> --}}
                            <td>{{ $subscription->user->cpf }}</td>
                            {{-- <td class="homologation-status">{{ $subscription->homologation_status  }}</td> --}}
                            <td>
                                @if($subscription->examRoomBooking == null)
                                    <button type="button" x-on:click="openModal({{$subscription->id}})" class="blue-button">
                                        Ensalar
                                    </button>
                                @else
                                    <button type="button" x-on:click="openModal({{$subscription->id}})" class="red-button">
                                        Trocar sala
                                    </button>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $subscriptions->appends(['campus' => $campus_selected_id])->links() }}
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

                        <div>
                                <div class="bg-gray-100 p-4 mt-4 mb-4">
                                    <form id="update-exam-room">
                                        <label for="examRoomBooking">Sala de Exame</label>
                                        <x-select-box name="examRoomBooking" id="examRoomBooking" required x-model="subscription.exam_room_booking_id">
                                            <option value=""> Selecione uma Sala </option>
                                            @foreach ($examRoomBookings as $examRoomBooking)
                                            <option :selected="examRoomBooking.id === '{{ $examRoomBooking->id }}'" value="{{ $examRoomBooking->id }}"
                                                >{{ $examRoomBooking->examLocation->local_name." - ".$examRoomBooking->name }}</option>
                                            @endforeach
                                        </x-select-box>
                                    </form>
                                </div>
                        </div>

                    </div>

                    <!--Footer-->
                    <div class="flex justify-end pt-2">
                        <x-jet-button type="submit" form="update-exam-room" x-on:click.prevent="updateExamRoomBooking"
                            class="bg-blue-700 hover:bg-blue-300 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                            Atualizar
                        </x-jet-button>
                        <form>
                            <x-jet-button type="submit" form="remove-exam-room" x-on:click.prevent="removeExamRoomBooking"
                                class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded"
                                form="resource-feedback-form">Remover Sala
                            </x-jet-button>
                        </form>
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
                },
                open: false,
                openModal: function(subscription) {
                    axios.get("subscriptions/"+subscription)
                        .then((response)=>{
                            this.subscription = response.data
                            this.open = true
                        })
                        .catch((response)=>{
                            console.log(response)
                            window.alert('Ops, um erro ocorreu ao obter inscrição')
                        })
                },
                updateExamRoomBooking: function() {
                    axios.put("subscriptions/"+this.subscription.id+"/update-exam-room",{
                        'exam_room_booking_id': this.subscription.exam_room_booking_id
                    })
                        .then((response)=>{
                            this.subscription = response.data
                            window.alert("A Sala de Exame foi atualizada com sucesso!")
                            location.reload()
                        })
                        .catch((error)=>{
                            if(error.response.data.message){
                                window.alert(error.response.data.message);
                            }else {
                                window.alert('Ops, um erro ocorreu, nada foi alterado');
                            }
                        })
                },
                removeExamRoomBooking: function() {
                    axios.delete("subscriptions/"+this.subscription.id+"/remove-exam-room")
                        .then((response)=>{
                            this.subscription = response.data
                            window.alert("A Sala de Exame foi removida com sucesso!")
                            location.reload();
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
