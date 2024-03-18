<div>
    <x-jet-input type="number" class="w-16" wire:model.defer="priority" />
    <button class="bg-green-500 hover:bg-green-700 text-xs text-white 
        font-bold py-2 px-3 mr-3 rounded inline-block text-center w-16"
        wire:click="save">
        Salvar
    </button>    
    <x-jet-action-message class="mr-3" on="saved">
        {{ $message }}
    </x-jet-action-message>
</div>