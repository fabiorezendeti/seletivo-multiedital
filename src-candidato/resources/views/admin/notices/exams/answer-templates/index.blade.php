<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="{{$exam->title}}" :notice="$notice" :nomeEdital="$notice->number" >
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="p-5">
            <x-jet-validation-errors class="mb-4" />
            <form action="{{ route('admin.notices.exams.answers.store', ['notice'=>$notice,'exam'=>$exam, 'answer'=>$answer]) }}" method="POST">
                @csrf
                <h2 class="text-xl">Cadastro de Questão</h2>
                <hr>

                <div class="grid grid-cols-12">
                    <div class="col-span-2 p-5">
                        <x-jet-label value="N° da Questão" />
                        <x-jet-input class="w-32" type="number" name="question_number" maxlenght="3"  required :value="old('question_number') ?? $answer->question_number" />
                    </div>
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Alternativa Correta" />
                        <x-jet-input class="w-32" type="text" name="right_answer" maxlenght="3" autofocus required :value="old('right_answer') ?? $answer->right_answer" />
                    </div>
                    <div class="col-span-2 p-5">
                        <x-jet-label value="Peso" />
                        <x-jet-input class="w-32" type="number" step="0.1" name="weight" placeholder="1,0" maxlenght="3"  required :value="old('weight') ?? $answer->weight" />
                    </div>
                    <div class="col-span-4 p-5">
                        <x-jet-label value="Área do Conhecimento" />
                        <x-select-box name="area_id" required>
                            @foreach ($knowledgeAreas as $area)
                                <option value="{{$area->id}}" {{--@if($modality->id === $notice->modality_id) selected="selected" @endif--}} >{{$area->name}}</option>
                            @endforeach
                        </x-select-box>
                    </div>
                    <div class="col-span-2 p-5">
                        <button type="submit"
                            class="inline-flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 m-4 border-b-4 border-green-700 hover:green-blue-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg> Cadastrar
                        </button>
                    </div>

            </form>
        </div>
    </div>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="mx-5">Questão</span>
                            </th>
                            <th class="py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="mx-5">Alternativa Correta</span>
                            </th>
                            <th class="py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="mx-5">Peso</span>
                            </th>
                            <th class="py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="mx-5">Área do Conhecimento</span>
                            </th>
                            <th class="py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <span class="mx-5">Alterar / Apagar / Anular</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($answers as $answer)
                        @if($answer->is_canceled)
                        <tr class="text-red-500">
                        @else
                        <tr>
                        @endif
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$answer->question_number}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$answer->right_answer}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$answer->weight}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$answer->area->name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex justify-start">

                                    <div class="mx-5">
                                        <a href="{{route('admin.notices.exams.answers.edit',['notice'=>$notice,'exam'=>$exam, 'answer'=>$answer])}}" id="bt_list"
                                            class="float-right bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-1 rounded"
                                            title="Alterar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                    </div>

                                    <div class="mx-5">
                                        <form action="{{ route('admin.notices.exams.answers.destroy', ['notice'=>$notice,'exam'=>$exam, 'answer'=>$answer])}}" method="POST"
                                        style="display: inline;">
                                            @csrf
                                            @method('delete')
                                            <button type="submit"
                                            class="float-right bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-1 rounded"
                                            title="Apagar Questão">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="mx-5">
                                        @if($answer->is_canceled)
                                        <span class="inline-flex items-center bg-gray-400 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Anulada</span>
                                        @else
                                        <a href="{{route('admin.notices.exams.answers.cancel',['notice'=>$notice,'exam'=>$exam, 'answer'=>$answer])}}" id="bt_list"
                                            class="float-right bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-1 rounded"
                                            title="Anular">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </a>
                                        @endif
                                    </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $answers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>
