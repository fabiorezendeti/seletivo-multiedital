<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Modalidades">
            <x-manager.internal-navbar-itens tip="Modalidades" home="admin.modalities.index"
                />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="p-5">
            <x-jet-validation-errors class="mb-4" />
            <form action="{{ route('admin.modalities.store') }}" method="POST">
                @csrf                
                <h2 class="text-xl">Cadastro de Modalidade</h2>
                <hr>
                <div class="grid grid-cols-6">
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Descrição" />
                        <x-jet-input type="text" name="description" maxlenght="150" autofocus required :value="old('description') ?? $modality->description" />
                    </div> 
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Slug (nome curto)" />
                        <x-jet-input type="text" name="slug" maxlenght="50" :value="old('slug') ?? $modality->slug" />
                    </div>                    
                    <div class="col-span-3">
                        @if($modality->id)
                        @method('put')
                        <button type="submit" formaction="{{ route('admin.modalities.update',['modality'=>$modality]) }}"
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