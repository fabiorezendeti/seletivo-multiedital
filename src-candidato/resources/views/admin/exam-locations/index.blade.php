<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Locais de Prova" >
            @can('isAdmin')
            <x-manager.internal-navbar-itens tip="Locais de Prova"  create="admin.process.exam-locations.create"  home="admin.process.exam-locations.index" search-placeholder="Buscar por nome"/>
            @else
            <x-manager.internal-navbar-itens tip="Locais de Prova"  home="admin.process.exam-locations.index" search-placeholder="Buscar por nome"/>
            @endcan
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Campus
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nome
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ativo
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Opções
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($examLocations as $examLocation)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$examLocation->campus->name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$examLocation->local_name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{($examLocation->active) ? 'Sim' : 'Não'}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ route('admin.process.exam-locations.show',['exam_location'=>$examLocation]) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-xs text-white
                                    font-bold py-2 px-3 mr-3 rounded text-center ">
                                    Visualizar
                                </a>
                                <a href="{{ route('admin.process.exam-locations.edit',['exam_location'=>$examLocation]) }}"
                                    class="bg-orange-500 hover:bg-orange-700 text-xs text-white
                                    font-bold py-2 px-3 mr-3 rounded text-center ">
                                    Editar
                                </a>
                                <form action="{{ route('admin.process.exam-locations.destroy',['exam_location'=>$examLocation]) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('delete')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 mt-2 mb-2 text-xs text-white font-bold py-2 px-3 mr-3
                                        rounded text-center"
                                        type="button">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $examLocations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>
