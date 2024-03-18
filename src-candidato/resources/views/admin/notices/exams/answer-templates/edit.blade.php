<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Gerenciar questões" >
            <x-manager.internal-navbar-itens tip="Questões" home="admin.notices.exams.answers.index" :routeVars="['notice'=>$notice, 'exam'=>$exam]" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="p-5">
            <x-jet-validation-errors class="mb-4" />
            <form action="{{ route('admin.notices.exams.answers.update', ['notice'=>$notice, 'exam'=>$exam, 'answer'=>$answer]) }}" method="POST">
                @csrf
                <h2 class="text-xl">Alterar Questão</h2>
                <hr>

                <div class="grid grid-cols-12">
                    <div class="col-span-2 p-5">
                        <x-jet-label value="N° da Questão" />
                        <x-jet-input class="w-32" type="number" name="question_number" maxlenght="3" autofocus required :value="old('question_number') ?? $answer->question_number" />
                    </div>
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Alternativa Correta" />
                        <x-jet-input class="w-32" type="text" name="right_answer" maxlenght="3" autofocus required :value="old('right_answer') ?? $answer->right_answer" />
                    </div>
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Peso" />
                        <x-jet-input class="w-32" type="number" step="0.1" name="weight" placeholder="1,0" maxlenght="3" autofocus required :value="old('weight') ?? $answer->weight" />
                    </div>
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Área do Conhecimento" />
                        <x-select-box name="area_id" required>
                            @foreach ($knowledgeAreas as $area)
                                <option value="{{$area->id}}" @if($answer->area->id === $area->id) selected="selected" @endif >{{$area->name}}</option>
                            @endforeach
                        </x-select-box>
                    </div>
                    @if($answer->is_canceled)
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Anulada" />
                        <input type="checkbox" value="1" name="is_canceled" @if(old('is_canceled') or $answer->is_canceled) checked="checked"  @endif />
                    </div>
                    @endif
                    <div class="col-span-2 p-5">
                        @method('put')
                        <button type="submit"
                            class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 m-4 border-b-4 border-green-700 hover:green-blue-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg> Atualizar
                        </button>
                    </div>
            </form>
        </div>
    </div>

</x-manager.app-layout>
