<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Lista de Chamadas" :nomeEdital="$notice->number" :notice="$notice">
            @can('isAdmin')
            <x-manager.internal-navbar-itens tip="BUtton Tip" home="admin.notices.calls.index" create="admin.notices.calls.create" :routeVars="['notice'=>$notice]" />
            @else
            <x-manager.internal-navbar-itens tip="BUtton Tip" home="cra.notices.calls.index" :routeVars="['notice'=>$notice]" />
            @endcan
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <x-jet-validation-errors class="mb-4" />
                <x-error-message />
                <form action="#" method="POST" id="delete-action">
                    @csrf
                    @method('delete')
                </form>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Critério de Seleção
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100  text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Número da Chamada
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100  text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Início das Matrículas</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100  text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Fim das Matrículas</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($callCounting as $call)
                        <form action="{{ route('admin.notices.calls.update',['notice'=>$notice,'call'=>$call['last_call_number']]) }}" id="{{$call['selectionCriteria']->description}}" method="POST">
                            @method('put')
                            @csrf
                            <input type="hidden" name="selection_criteria_id" value="{{$call['selectionCriteria']->id}}">
                        </form>
                        <tr>
                            <td>{{$call['selectionCriteria']->description}}</td>
                            <td class="p-2">{{$call['last_call_number']}}</td>
                            <td class="p-2">
                                <x-jet-input class="block mt-1 w-full" type="date" name="enrollment_start_date" form="{{$call['selectionCriteria']->description}}" :disabled="Gate::denies('isAdmin')" :value="($call['enrollmentSchedule']) ? $call['enrollmentSchedule']->start_date->format('Y-m-d') : null" />
                            </td>
                            <td class="p-2">
                                <x-jet-input class="block mt-1 w-full" type="date" name="enrollment_end_date" form="{{$call['selectionCriteria']->description}}" :disabled="Gate::denies('isAdmin')" :value="($call['enrollmentSchedule']) ? $call['enrollmentSchedule']->end_date->format('Y-m-d') : null" />
                            </td>
                            <td class="p-2 text-left">
                                @can('isAdmin')
                                @if ($call['last_call_number'] > 0)       
                                <div>
                                <button type="submit" title="Atualizar data de matrícula" form="{{$call['selectionCriteria']->description}}" class="bg-green-500 hover:bg-green-700 text-xs text-white font-bold p-2 m-2 rounded">
                                    Atualizar Cronograma
                                </button>                                                                                    
                                </div>     
                                <div>
                                <a href="{{ route('admin.notices.calls.show', [
                                    'notice'=>$notice,
                                    'call' => $call['last_call_number'],
                                    'selection_criteria_id' => $call['selectionCriteria']->id
                                    ]) }}" class="bg-blue-500 hover:bg-blue-700 text-xs text-white font-bold p-2 m-2 rounded">
                                    Visualizar
                                </a>
                                <a href="{{ route('admin.notices.calls.indexToRegister', [
                                    'notice'=>$notice,
                                    'call' => $call['last_call_number'],
                                    'selection_criteria_id' => $call['selectionCriteria']->id
                                    ]) }}" class="bg-blue-500 hover:bg-blue-700 text-xs text-white font-bold p-2 m-2 rounded">
                                    Matricular
                                </a>
                                <button type="submit" form="delete-action" formaction="{{ route('admin.notices.calls.destroy', [
                                    'notice'=>$notice,
                                    'call' => $call['last_call_number'],
                                    'selection_criteria_id' => $call['selectionCriteria']->id
                                    ]) }}" class="bg-red-500 hover:bg-red-700 text-xs text-white font-bold p-2 m-2 rounded">
                                    Desfazer
                                </button>
                                </div>                
                                @endif
                                <x-modal title="Critérios de seleção" class="modal-open bg-gray-400 hover:bg-gray-500 text-sm text-white font-bold p-2 m-2 rounded" buttonText="Alterar Status">
                                    <form id="change-status" action="{{ route('admin.notices.calls.change-status-for-criteria',[
                                            'notice'    => $notice,
                                            'selectionCriteria' => $call['selectionCriteria'],
                                            'call'  => $call['last_call_number']
                                            ]) }}" method="POST">
                                        @csrf
                                        @method('put')
                                        <h2 class="text-2xl">Você quer alterar todos os pendentes para matriculados?
                                        </h2>
                                        <x-jet-danger-button type="submit">
                                            SIM
                                        </x-jet-danger-button>
                                    </form>
                                </x-modal>
                                <div>
                                <a href="{{ route('admin.notices.calls.enrollExport', [
                                    'notice'=>$notice,
                                    'call' => $call['last_call_number'],
                                    'selectionCriteria' => $call['selectionCriteria']
                                    ]) }}" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold p-2 m-2 rounded">
                                    Exportar Pendentes
                                </a>
                                </div>

                                @endcan
                                @can('isAcademicRegister')
                                @if ($call['last_call_number'] > 0)
                                <a type="submit" href="{{ route('cra.notices.calls.indexToRegister', [
                                    'notice'=>$notice,
                                    'call' => $call['last_call_number'],
                                    'selection_criteria_id' => $call['selectionCriteria']->id
                                    ]) }}" class="bg-blue-500 hover:bg-blue-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Matricular
                                </a>
                                @endif
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</x-manager.app-layout>