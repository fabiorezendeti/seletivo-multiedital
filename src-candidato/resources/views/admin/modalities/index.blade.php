<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Modalidade">
            <x-manager.internal-navbar-itens tip="Usuários" home="admin.modalities.index"
                create="admin.modalities.create" search-placeholder="Buscar por descrição ou sigla" />
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
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modalities as $modality)
                        <tr>                            
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $modality->description }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">                                
                                <a href="{{route('admin.modalities.edit',['modality'=>$modality])}}"
                                    class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Editar
                                </a>                            
                                <button type="submit" formaction="{{route('admin.modalities.destroy',['modality'=>$modality])}}"
                                    form="delete-action"
                                    class="bg-red-500 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Excluir
                                </a>                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $modalities->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>