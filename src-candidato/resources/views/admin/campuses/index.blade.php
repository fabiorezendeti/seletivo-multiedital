<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Campus" >
            <x-manager.internal-navbar-itens tip="Campus" create="admin.campuses.create" home="admin.campuses.index" search-placeholder="Buscar por nome"/>
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nome
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Site
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                E-mail
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($campi as $campus)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$campus->name}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$campus->site}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$campus->email}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                @can('updateCampus',$campus)
                                <a href="{{ route('admin.campuses.edit',['campus'=>$campus]) }}" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Editar
                                </a>
                                                                
                                <form action="{{ route('admin.campuses.destroy',['campus'=>$campus]) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-400 text-xs text-white font-bold py-2 px-3 mr-4 border-b-3 border-red-700 hover:border-red-500 rounded-full"
                                        type="button">
                                        Excluir
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $campi->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>
