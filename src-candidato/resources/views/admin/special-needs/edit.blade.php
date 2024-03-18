<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Necessidade Específica">
            <x-manager.internal-navbar-itens tip="Necessidade Específica" home="admin.special-needs.index"
                />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="p-5">
            <x-jet-validation-errors class="mb-4" />
            <form action="{{ route('admin.special-needs.store') }}" method="POST">
                @csrf                
                <h2 class="text-xl">Cadastro de Necessidade Específica</h2>
                <hr>
                <div class="grid grid-cols-3">
                @can('updateDescription',$specialNeed)
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Descrição" />
                        <x-jet-input type="text" name="description" maxlenght="150" autofocus required :value="old('description') ?? $specialNeed->description" />
                    </div>                
                @endcan                
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Ativo" />
                        <input type="checkbox" value="1" name="activated" @if(old('activated') or $specialNeed->activated) checked="checked"  @endif />
                    </div>                    
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Exige detalhamento (o candidato deverá completar quando selecionada esta necessidade específica)" />
                        <input type="checkbox" value="1" name="require_details" @if(old('require_details') or $specialNeed->require_details) checked="checked"  @endif />
                    </div>
                    <div class="col-span-3">
                        @if($specialNeed->id)
                        @method('put')
                        <button type="submit" formaction="{{ route('admin.special-needs.update',['special_need'=>$specialNeed]) }}"
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