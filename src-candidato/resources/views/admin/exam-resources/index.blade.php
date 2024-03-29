<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Recursos para realizar prova">
            <x-manager.internal-navbar-itens tip="Usuários" home="admin.modalities.index"
                create="admin.exam-resources.create" search-placeholder="Buscar por descrição" />
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
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Descrição
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ativo?
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider"
                                title="O candidato deve informar mais informações sobre a necessidade específica">
                                Requer Detalhes
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($examResources as $er)
                        <tr>                            
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $er->description }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $er->activated ? 'Sim' : 'Não' }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $er->require_details ? 'Sim' : 'Não' }}
                            </td>                            
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">                                     
                            @can('update',$er)
                                <a href="{{route('admin.exam-resources.edit',['exam_resource'=>$er])}}"
                                    class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Editar
                                </a>                            
                                <button type="submit" formaction="{{route('admin.exam-resources.destroy',['exam_resource'=>$er])}}"
                                    form="delete-action"
                                    class="bg-red-500 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Excluir
                                </a>            
                            @endcan                    
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $examResources->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>