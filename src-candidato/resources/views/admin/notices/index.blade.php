<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Editais" >
            @can('isAdmin')
            <x-manager.internal-navbar-itens tip="Editais" home="admin.notices.index"
            create="admin.notices.create" search-placeholder="Buscar edital"/>
            @else
            <x-manager.internal-navbar-itens tip="Editais" home="cra.notices.index"
             search-placeholder="Buscar edital"/>
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
                                Número
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Modalidade
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Descrição
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Link
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notices as $notice)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$notice->number}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$notice->modality->description}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$notice->description}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{$notice->link}}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex">
                                @can('isAdmin',$notice)
                                <a href="{{ route('admin.notices.show', ['notice'=>$notice] )}}" class="bg-blue-500 hover:bg-blue-700 text-xs text-white font-bold py-2 mr-2 rounded-full text-center h-9" style="width: 6rem">
                                    Gerenciar
                                </a>
                                <a href="{{ route('admin.notices.edit', ['notice'=>$notice] )}}" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold pt-2 mr-2 rounded-full text-center h-9" style="width: 6rem">
                                    Editar
                                </a>
                                @elsecan('hasOfferInMyCampuses', $notice)
                                <a href="{{ route('cra.notices.show', ['notice'=>$notice] )}}" class="bg-blue-500 hover:bg-blue-700 text-xs text-white font-bold pt-2 mr-2 rounded-full text-center h-9" style="width: 6rem">
                                    Gerenciar
                                </a>
                                @elsecannot('hasOfferInMyCampuses', $notice)
                                <p class="text-gray-500 italic">Não existe oferta para seu campus</p>
                                @endcannot
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $notices->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-manager.app-layout>
