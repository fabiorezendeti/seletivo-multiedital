<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Ações Afirmativas">
            <x-manager.internal-navbar-itens tip="Usuários" home="admin.affirmative-actions.index"
                create="admin.affirmative-actions.create" search-placeholder="Buscar por descrição ou sigla" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <form action="#" method="POST" id="delete-action">
                    @csrf
                    @method('delete')
                </form>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="py-3 px-5 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Sigla
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Descrição
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ampla Concorrência?
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                PPI
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Modalidades
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Prioridade
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($affirmativeActions as $action)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$action->slug}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ Str::words($action->description,10, ' ... ') }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$action->is_wide_competition ? 'Sim' : 'Não'}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$action->is_ppi ? 'Sim' : 'Não'}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {!! $action->modalities->implode('description','<br>')!!}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                @livewire('affirmative-action-priority', ['affirmativeAction' => $action])
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm  text-center">
                                <a href="{{route('admin.affirmative-actions.migration-vacancy-map.index',['affirmative_action'=>$action])}}"
                                    class="bg-blue-500 hover:bg-blue-700 text-xs text-white 
                                        font-bold py-2 px-3 mr-3 rounded inline-block text-center w-full">
                                    Mapa de Migração
                                </a>
                                @can('editOrDelete',$action)
                                <a href="{{route('admin.affirmative-actions.edit',['affirmative_action'=>$action])}}"
                                    class="bg-orange-500 hover:bg-orange-700 mt-2 mb-2 text-xs text-white font-bold py-2 px-3 mr-3 
                                        rounded inline-block  w-full text-center">
                                    Editar
                                </a>
                                <button type="submit"
                                    formaction="{{route('admin.affirmative-actions.destroy',['affirmative_action'=>$action])}}"
                                    form="delete-action"
                                    class="bg-red-500 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 
                                    rounded w-full inline-block  text-center">
                                    Excluir
                                </button>
                                @endcan
                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $affirmativeActions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>