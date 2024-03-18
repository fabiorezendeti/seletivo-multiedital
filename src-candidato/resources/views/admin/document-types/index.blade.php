<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Tipos de Documentos">
            <x-manager.internal-navbar-itens tip="BUtton Tip" home="admin.document-types.index" create="admin.document-types.create"
                search-placeholder="Buscar por Tipo de Documento " />
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
                                Título
                            </th>                            
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Descrição
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Contexto
                            </th>                            
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Order
                            </th>        
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documentTypes as $documentType)
                        <tr>                            
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $documentType->title }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $documentType->description }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $documentType->context }}
                            </td> 
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $documentType->order }}
                            </td> 
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">                                
                                <a href="{{ route('admin.document-types.show',['document_type'=>$documentType]) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Ver
                                </a>         
                                <a href="{{ route('admin.document-types.edit',['document_type'=>$documentType]) }}"
                                    class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Editar
                                </a>                            
                                <button type="submit" formaction="{{ route('admin.document-types.destroy',['document_type'=>$documentType]) }}"
                                    form="delete-action"
                                    class="bg-red-500 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Excluir
                                </button>                                
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $documentTypes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
        
</x-manager.app-layout>