<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Ações Afirmativas">
            <x-manager.internal-navbar-itens tip="Ações Afirmativas" home="admin.affirmative-actions.index" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="p-5">
            <x-jet-validation-errors class="mb-4" />
            <form action="{{ route('admin.affirmative-actions.store') }}" method="POST">
                @csrf
                <h2 class="text-xl">Cadastro de Ação Afirmativa</h2>
                <hr>
                <div class="grid grid-cols-4">
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Descrição" />
                        <textarea name="description" id="description" autofocus required
                            class="form-input rounded-md shadow-sm w-full">{{ old('description') ?? $affirmativeAction->description }}</textarea>
                    </div>
                    <div class="col p-5">
                        <x-jet-label value="Sigla" />
                        <x-jet-input name="slug" required :value="old('slug') ?? $affirmativeAction->slug" />
                    </div>
                    <div class="col p-5">
                        <x-jet-label value="Prioridade de Classificação" />
                        <x-jet-input type="number" name="classification_priority" required
                            :value="old('classification_priority') ?? $affirmativeAction->classification_priority" />
                        <p class="text-xs text-red-700">
                            Ações Afirmativas com prioridade mais alta de classificação são executadas primeiro
                            no processo de classificação
                        </p>
                    </div>
                    <div class="col-span-2 p-5">
                        <label class="block items-center">
                            <input type="checkbox" class="form-checkbox h-8 w-8 mb-4" name="is_wide_competition"
                                value="1" @if($affirmativeAction->is_wide_competition) checked @endif>
                            <span class="ml-2 ">Marque para identificar essa ação afirmativa como AMPLA
                                CONCORRÊNCIA</span>
                            <x-jet-input-error for="check_affirmative_action" class="mt-2" />
                        </label>
                        <label class="block items-center">
                            <input type="checkbox" class="form-checkbox h-8 w-8 mb-4" name="is_ppi" value="1"
                                @if($affirmativeAction->is_ppi) checked @endif>
                            <span class="ml-2 ">Marque para identificar essa ação afirmativa como PPI</span>
                            <x-jet-input-error for="check_ppi" class="mt-2" />
                        </label>
                    </div>
                    <div class="col-span-2 p-5">
                        Modalidades
                        @foreach ($modalities as $modality)
                        <label class="block items-center">
                            <input type="checkbox" class="form-checkbox h-8 w-8 mb-4" name="modalities[]"
                                @if($affirmativeAction->modalities->contains($modality)) checked @endif
                            value="{{ $modality->id }}">
                            <span class="ml-2 ">{{ $modality->description }}</span>
                        </label>
                        @endforeach
                    </div>                    
                    <div class="col-span-4">
                        @if($affirmativeAction->id)
                        @method('put')
                        <button type="submit"
                            formaction="{{ route('admin.affirmative-actions.update',['affirmative_action'=>$affirmativeAction]) }}"
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
                </div>
            </form>
        </div>
    </div>
    </div>



</x-manager.app-layout>