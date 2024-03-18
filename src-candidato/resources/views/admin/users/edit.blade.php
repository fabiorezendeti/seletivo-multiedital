<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Usuários">
            <x-manager.internal-navbar-itens home="admin.users.index" search-placeholder="Buscar por nome, cpf ou e-mail" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="grid grid-cols-4">
            <div class="px-5 py-5 overflow-hidden md:col-span-3">
                <h2 class="text-lg">Dados básicos</h2>
                <hr>
                <x-jet-validation-errors class="mb-4" />
                <form action="{{ route('admin.users.update',['user'=>$user]) }}" method="POST">
                    @csrf
                    @method('put')
                    <div class="col-span-6  ">
                        <x-jet-label value="Nome Completo" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $user->name" required autofocus autocomplete="name" />
                    </div>
                    <div class="col-span-6  ">
                        <x-jet-label value="Nome Social (opcional)" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="social_name" :value="old('social_name') ?? $user->social_name" />
                    </div>
                    <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                        <x-jet-label value="{{ __('Email') }}" />
                        <x-jet-input class="block mt-1 w-full" type="email" name="email" :value="old('email') ?? $user->email" required />
                    </div>
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="email_confirmation" value="Confirmação de Email" />
                        <x-jet-input id="email_confirmation" type="email" name="email_confirmation" class="mt-1 block w-full" value="{{old('email_confirmation') ?? $user->email}}" />
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>
                    <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                        @can('updateCPF',$user)
                        <x-jet-label value="CPF Ex: 023.456.789-10" />
                        <x-jet-input class="mask-cpf block mt-1 w-full" type="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="000.000.000-45" name="cpf" :value="old('cpf') ?? $user->cpf" required />
                        @endcan
                    </div>

                    <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                        <x-jet-label value="{{ __('RG') }}" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="rg" minlength="3" maxlength="20" :value="old('rg') ?? $user->rg" required />
                    </div>

                    <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                        <x-jet-label value="{{ __('Emissor do RG') }}" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="rg_emmitter" minlength="4" maxlength="40" :value="old('rg_emmitter') ?? $user->rg_emmitter" required />
                    </div>

                    <div class="col-span-6 ">
                        <x-jet-label value="Nome completo da sua Mãe" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="mother_name" :value="old('mother_name') ?? $user->mother_name" />
                    </div>
                    <div class="col-span-6 md:col-span-3 xl:col-span-1">
                        <x-jet-label value="Data de nascimento" />
                        <x-jet-input class="mask-date block mt-1 w-full" type="data" placeholder="dd/mm/aaaa" name="birth_date" :value="old('birth_date') ?? $user->birth_date->format('d/m/Y')" required />
                    </div>
                    <div x-data="{open: false}">
                        <div class="col-span-6 md:col-span-3 xl:col-span-1">
                            <x-jet-label for="is_foreign" value="Estrangeiro?" />
                            <input name="is_foreign" type="radio" @if(!old('is_foreign')) checked="checked" @endif value="0" x-on:click="open = 0" class="mt-2 form-checkbox h-8 w-8 text-gray-500" />
                            Não
                            <input name="is_foreign" type="radio" @if(old('is_foreign')==1) checked="checked" @endif value="1" x-on:click="open = 1 " class="form-checkbox mt-2 h-8 w-8 text-gray-500" />
                            Sim
                            <x-jet-input-error for="is_foreign" class="mt-2" />
                        </div>
                        <div class="col-span-6 md:col-span-3 xl:col-span-1" x-bind:class="{'hidden': open !== 1}">
                            <x-jet-label for="nationality" value="Nacionalidade" />
                            <x-jet-input id="nationality" name="nationality" x-bind:required="open == 1" type="text" class="mt-1 block w-full" :value="old('nationality')" maxlength="255" />
                            <x-jet-input-error for="nationality" class="mt-2" />
                        </div>
                    </div>
                    <div class="col-span-6 sm:col-span-6 md:col-span-3 mt-5 bg-yellow-200 p-5">
                        <x-manager.justify-update label="Justificativa da Alteração" class="mt-5" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                            Atualizar
                        </button>
                    </div>
                </form>
            </div>
            <div class="px-5 py-5 bg-yellow-100">
                <h2 class="text-lg">Opções Gerais</h2>
                <hr>
                @livewire('admin.user-options',['user'=>$user])
                @can('deleteUser',$user)
                <form action="{{ route('admin.users.destroy',['user'=>$user]) }}" method="POST">
                    @csrf
                    @method('delete')
                    <div class="border-2 border-red-300 rounded text-red-700 mt-2 p-2 text-center">
                        <x-manager.justify-update label="Justificativa da Exclusão" />
                        <button type="submit" class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded" type="button">
                            Excluir usuário
                        </button>
                    </div>
                </form>
                @endcan
            </div>
        </div>
    </div>


    <div id="user-edit">
        <div class="overflow-x-auto bg-red-300">
            <div class="inline-block min-w-full px-5 py-5 overflow-hidden">
                @livewire('admin.user-permission',['uuid'=>$user->uuid])
            </div>
        </div>
    </div>


</x-manager.app-layout>