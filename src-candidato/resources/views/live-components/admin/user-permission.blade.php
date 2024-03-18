<x-jet-form-section submit="addPermission">
    <x-slot name="title">
        Gerenciar Permissões
    </x-slot>

    <x-slot name="description">
        Permite adicionar ou remover permissões para um determinado usuário
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-6">
            @foreach ($user->permissions as $permission)
            <div
                class="rounded shadow-md border-b-1 border-blue-700 hover:border-blue-500">
                <div class="px-3 py-4">                    
                    <p class="text-gray-700 text-base">
                        <button type="button" wire:click="revoke({{$permission->id}})"
                            class="bg-red-700 hover:bg-red-900 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full w-10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                        {{ $permission->role->name }} em {{ $permission->campus->name ?? 'Todos os Campus'}}                        
                    </p>
                </div>                
            </div>
            @endforeach
        </div>
        <div class="col-span-6 sm:col-span-6">
            <x-jet-label for="role_id" value="Permissão" />
            <x-select-box name="role_id" id="role_id" wire:model="role_id">
                @foreach ($roles as $role)
                <option value="{{$role->id}}">{{$role}}</option>
                @endforeach
            </x-select-box>
            <x-jet-input-error for="role_id" class="mt-2" />

            <x-jet-label for="campus_id" value="Campus" />
            <x-select-box name="campus_id" id="campus_id" wire:model="campus_id" :disabled="$role_id == 1">
                <option value="0">-- TODOS --</option>
                @foreach ($campuses as $campus)
                <option value="{{$campus->id}}">{{$campus->name}}</option>
                @endforeach
            </x-select-box>
            <x-jet-input-error for="campus_id" class="mt-2" />
            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">
                    Adicionado
                </x-jet-action-message>
                <x-jet-button>
                    Adicionar
                </x-jet-button>
            </x-slot>
        </div>

    </x-slot>


</x-jet-form-section>