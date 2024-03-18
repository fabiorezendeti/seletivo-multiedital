<div>
    @if(!is_null(session()->get('email')) && \Illuminate\Support\Facades\Auth::user()->email != session()->get('email'))
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" onclick="this.parentNode.removeChild(this)">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Ops!</strong>
            <span class="block sm:inline">Seu email no Login Único está {{session()->get('email')}}, por favor atualize seus dados abaixo:</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
      <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
           viewBox="0 0 20 20"><title>Close</title><path
              d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
    </span>
        </div>
    </div>
    @endif
    <x-jet-form-section submit="updateProfileInformation" x-data="{open: {{ $state['is_foreign'] ? 1 : 0 }}}">
        <x-slot name="title">
            Seus Dados
        </x-slot>

        <x-slot name="description">
            Atualize os dados básicos do seu cadastro
        </x-slot>

        <x-slot name="form">
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="name" value="Nome Completo"/>
                <x-jet-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="state.name"
                             autocomplete="name"/>
                <x-jet-input-error for="name" class="mt-2"/>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="social_name" value="Nome Social"/>
                <x-jet-input id="social_name" type="text" class="mt-1 block w-full"
                             wire:model.defer="state.social_name"/>
                <x-jet-input-error for="social_name" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="birth_date" value="Data de Nascimento"/>
                <x-jet-input id="birth_date" type="text" class="mask-date  mt-1 block w-full"
                             wire:model.defer="state.birth_date"/>
                <x-jet-input-error for="birth_date" class="mt-2"/>
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="email" value="Email"/>
                <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" x-on:/>
                <x-jet-input-error for="email" class="mt-2"/>
            </div>


            <div class="col-span-6 sm:col-span-4" x-init="state.email_confirmation = state.email">
                <x-jet-label for="email_confirmation" value="Confirmação de Email"/>
                <x-jet-input id="email_confirmation" type="email" class="mt-1 block w-full"
                             wire:model.defer="state.email_confirmation"/>
                <x-jet-input-error for="email" class="mt-2"/>
            </div>

{{--            @can('updateCPF',Auth::user())--}}
            @if(Gate::allows('updateCPF', Auth::user()) && env('LOGIN_UNICO_ENABLE') == false)
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="cpf" value="CPF (atenção, isto altera também seu login)"/>
                    <x-jet-input id="cpf" type="cpf" class="mask-cpf mt-1 block w-full" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" wire:model.defer="state.cpf"/>
                    <x-jet-input-error for="cpf" class="mt-2"/>
                </div>
            @else
                <div class="col-span-6 sm:col-span-4">
                    <p>CPF: {{$state['cpf']}}</p>
                </div>
            @endcan
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="rg" value="RG"/>
                <x-jet-input id="rg" type="text" class="mt-1 block w-full" wire:model.defer="state.rg"/>
                <x-jet-input-error for="rg" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="rg_emmitter" value="Emissor do RG"/>
                <x-jet-input id="rg_emmitter" type="text" class="mt-1 block w-full"
                             wire:model.defer="state.rg_emmitter"/>
                <x-jet-input-error for="rg_emmitter" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="is_foreign" value="Estrangeiro?"/>
                <input name="is_foreign" type="radio" value="0" x-on:click="open = 0"
                       class="form-checkbox h-8 w-8 text-gray-500" wire:model.defer="state.is_foreign"/>
                Não
                <input name="is_foreign" type="radio" value="1" x-on:click="open = 1 "
                       class="form-checkbox h-8 w-8 text-gray-500" wire:model.defer="state.is_foreign"/>
                Sim
                <x-jet-input-error for="is_foreign" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4" x-bind:class="{'hidden': open !== 1}">
                <x-jet-label for="nationality" value="Nacionalidade"/>
                <x-jet-input id="nationality" x-bind:required="open == 1" type="text" class="mt-1 block w-full"
                             wire:model.defer="state.nationality"/>
                <x-jet-input-error for="nationality" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="mother_name" value="Nome comple da sua mãe"/>
                <x-jet-input id="mother_name" type="text" class="mt-1 block w-full"
                             wire:model.defer="state.mother_name"/>
                <x-jet-input-error for="mother_name" class="mt-2"/>
            </div>

            @if($state['is_foreign'] === null)
                <div x-data="{modal_open: true}">
                    <div x-show="modal_open" id="modal"
                         x-bind:class="{'opacity-0 pointer-events-none': modal_open === false}"
                         class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
                        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

                        <div
                            class="modal-container bg-white w-11/12 md:max-w-lg mx-auto rounded shadow-lg z-50 overflow-y-auto overscroll-auto">
                            <div x-on:click="modal_open = false"
                                 class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
                                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18"
                                     height="18"
                                     viewBox="0 0 18 18">
                                    <path
                                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                    </path>
                                </svg>
                            </div>

                            <!-- Add margin if you want to see some of the overlay behind the modal-->
                            <div class="modal-content py-4 text-left px-6 overflow-y-auto h-auto">
                                <!--Title-->
                                <div class="flex justify-between items-center pb-3">

                                    <p class="text-xl font-bold">Atualização de Cadastro</p>
                                    <div class="modal-close cursor-pointer z-50" @click="open = false">
                                        <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg"
                                             width="18"
                                             height="18" viewBox="0 0 18 18">
                                            <path
                                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                <!--Body-->
                                <div>
                                    <p>É necessário agora informar se você é estrangeiro ou não.</p>
                                </div>

                                <!--Footer-->
                                <div class="flex justify-end pt-2">
                                    <button type="button" @click="modal_open = false"
                                            class="modal-close px-4 bg-gray-500 p-3 rounded-lg text-white hover:bg-gray-400">
                                        Fechar
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                Ok! Atualizado!
            </x-jet-action-message>

            <x-jet-button>
                Salvar
            </x-jet-button>
        </x-slot>


    </x-jet-form-section>

</div>

@push('js')
    <script>
        window.onload = () => {
            const myInput = document.getElementById('email_confirmation');
            myInput.onpaste = e => e.preventDefault();
        }
    </script>
@endpush
