<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Tipos de Documentos">
            <x-manager.internal-navbar-itens tip="Tipos de Documentos" home="admin.document-types.index" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="p-5">
            <x-jet-validation-errors class="mb-4" />
            <form action="{{ route('admin.document-types.store') }}" method="POST">
                @csrf
                <h2 class="text-xl">Cadastro de Tipo de Documentos</h2>
                <hr>
                <div class="grid grid-cols-4">
                    <div class="col col-span-2 p-5">
                        <x-jet-label value="Nome do Documento" />
                        <x-jet-input name="title" max-lenght="200" required class="w-full"
                            :value="old('title') ?? $documentType->title" />
                        <x-jet-label value="Contexto" />
                        <x-select-box name="context" required>
                            @foreach ($documentType->getContexts() as $context)
                            <option value="{{$context}}" @if($context == $documentType->context) selected="selected" @endif>{{$context}}</option>
                            @endforeach
                        </x-select-box>
                        <x-jet-label value="Descrição" />
                        <textarea name="description" id="description" rows="10" autofocus required
                            class="form-input rounded-md shadow-sm w-full">{{ old('description') ?? $documentType->description }}</textarea>
                        <x-jet-label value="Ordem de apresentação" />
                        <x-jet-input type="number" name="order" required class="w-full"
                            :value="old('order', $documentType->order) " />
                        <label class="block items-center mt-2">
                            <input type="checkbox" class="form-checkbox h-8 w-8 mb-4" name="required" value="1"
                                @if($documentType->required) checked @endif>
                            <span class="ml-2 ">É obrigatório para todos?</span>
                        </label>
                        <!--
                        <x-jet-label value="Obrigar por sexo" />
                        <x-select-box name="sex">
                            <option value="">-- Para todos os sexos --</option>
                            @foreach ($documentType->getSexs() as $key=>$sex)
                            <option value="{{$key}}" @if($key == $documentType->sex) selected="selected" @endif>{{$sex}}</option>
                            @endforeach
                        </x-select-box>
                        <x-jet-label value="Obrigar por idade" />
                        <x-select-box name="age">
                            <option value="">-- Para todas as idades --</option>
                            @foreach ($documentType->getAges() as $key=>$age)
                             <option value="{{$key}}" @if($key == $documentType->age) selected="selected" @endif>{{$age}}</option>
                            @endforeach
                        </x-select-box>
                        -->
                        <label class="block items-center mt-2">
                            <input type="checkbox" class="form-checkbox h-8 w-8 mb-4" name="active" value="1"
                                @if($documentType->active) checked @endif>
                            <span class="ml-2 ">Ativo?</span>
                        </label>                        
                    </div>
                    <div class="col-span-2 p-5">
                        <h2 class="text-xl">Associar Ações Afirmativas (ou não)</h2>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <h2>ATENÇÃO</h2>
                            <p>Se você não selecionar nenhuma ação afirmativa o documento será exigido para todos os
                                candidatos, para exigir o envio
                                de documento para candidatos de uma ação afirmativa em específico você precisa marcar a
                                respectiva caixa
                            </p>
                        </div>
                        @foreach ($affirmativeActions as $affirmativeAction)
                        <label class="block items-center mt-2">
                            <input type="checkbox" class="form-checkbox h-8 w-8 mb-4" name="affirmative_actions[]"
                                value="{{ $affirmativeAction->id }}"
                                @if($documentType->affirmativeActions->contains($affirmativeAction)) checked @endif >
                            <span class="ml-2"
                                title="{{ $affirmativeAction->description }}">{{$affirmativeAction->slug}}</span>
                            {{ $affirmativeAction->modalities->implode('description',',') }}
                        </label>
                        @endforeach
                    </div>

                    <div class="col-span-4">
                        @if($documentType->id)
                        @method('put')
                        <button type="submit"
                            formaction="{{ route('admin.document-types.update',['document_type'=>$documentType]) }}"
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