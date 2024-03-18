<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Campus">
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">        

        <div class="grid grid-cols">
            <div class="px-5 py-5 overflow-hidden md:col-span-3">
                <x-jet-validation-errors class="mb-4" />
                <form id="form-campus" action="{{route('admin.campuses.store')}}" method="POST">
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Dados</h2>
                    </div>
                    @csrf                    
                    <div class="col-span-6  ">
                        <x-jet-label value="Nome" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="name"
                            :value="old('name') ?? $campus->name" required autofocus autocomplete="name" />
                    </div>
                    <div class="col-span-6  ">
                        <x-jet-label value="{{ __('Email') }}" />
                        <x-jet-input class="block mt-1 w-full" type="email" name="email"
                            :value="old('email') ?? $campus->email" />
                    </div>
                    <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                        <x-jet-label value="{{ __('Site') }}" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="site"
                            :value="old('site') ?? $campus->site" required />
                    </div>
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Endereço</h2>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                            <x-jet-label value="{{ __('Rua/Avenida') }}" />
                            <x-jet-input class="block mt-1 w-full" type="text" name="street"
                                :value="old('street') ?? $campus->street" required />
                        </div>

                        <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                            <x-jet-label value="{{ __('Número') }}" />
                            <x-jet-input class="block mt-1 w-full" type="text" name="text"
                                :value="old('number') ?? $campus->number" required />
                        </div>
                    </div>

                    <div class="col-span-6 ">
                        <x-jet-label value="{{ __('Bairro') }}" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="district"
                            :value="old('district') ?? $campus->district" />
                    </div>

                    <div class="col-span-6 ">
                        <x-jet-label value="{{ __('CEP') }}" />
                        <x-jet-input class="block mt-1 w-full mask-cep" type="text" name="zip_code"
                            :value="old('zip_code') ?? $campus->zip_code" />
                    </div>

                    <div class="col-span-6 ">
                        <x-jet-label value="{{ __('Telefone') }}" />
                        <x-jet-input class="block mt-1 w-full mask-phone" type="text" name="phone_number"
                            :value="old('phone_number') ?? $campus->phone_number" />
                    </div>

                    @livewire('city-options',['cityId'=> $campus->city_id])

                    <div class="flex items-center justify-end mt-4">
                        @if($campus->id)
                            @method('put')
                            <button type="submit" formaction="{{ route('admin.campuses.update',['campus'=>$campus]) }}"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                                Atualizar
                            </button>
                            @else
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                                Cadastrar
                            </button>
                            @endif                
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    


</x-manager.app-layout>
