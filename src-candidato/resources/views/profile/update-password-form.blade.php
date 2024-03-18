<x-jet-form-section submit="updatePassword">
    <x-slot name="title">
        Atualizar @if(env('LOGIN_UNICO_ENABLE')) PIN @else Senha @endif
    </x-slot>

    <x-slot name="description">
        @if(env('LOGIN_UNICO_ENABLE'))
            Redefina aqui neste campo o seu PIN, lembre de usar um PIN seguro com no mínimo 8 caracteres
        @else
            Redefina aqui neste campo a sua senha, lembre de usar uma senha segura com no mínimo 8 caracteres
        @endif
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="current_password" value="{{env('LOGIN_UNICO_ENABLE') ? 'PIN' : 'Senha'}} Atual" />
            <x-jet-input id="current_password" type="password" class="mt-1 block w-full" wire:model.defer="state.current_password" autocomplete="current-password" />
            <x-jet-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="password" value="{{env('LOGIN_UNICO_ENABLE') ? 'Novo PIN' : 'Nova Senha'}}" />
            <x-jet-input id="password" type="password" class="mt-1 block w-full" wire:model.defer="state.password" autocomplete="new-password" />
            <x-jet-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="password_confirmation" value="{{env('LOGIN_UNICO_ENABLE') ? 'Confirme o Novo PIN' : 'Confirme a Nova Senha'}}" />
            <x-jet-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model.defer="state.password_confirmation" autocomplete="new-password" />
            <x-jet-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            Ok! Atualizado
        </x-jet-action-message>

        <x-jet-button>
            Salvar
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
