<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Tipo de Documento">
            <x-manager.internal-navbar-itens tip="Tipo de documento" create="admin.document-types.create"
                home="admin.document-types.index" search-placeholder="Buscar por título ou descrição" />
        </x-manager.header>
    </x-slot>

    <div
        class="grid grid-cols-12 rounded shadow-lg bg-white  border-b-4 border-blue-700 hover:border-blue-500 text-gray-600">
        <div class="col-span-12 md:col-span-12">
            <div class="p-4 rounded">
                <h2 class="text-xl font-bold uppercase">Nome: {{ $documentType->title }}</h2>
                <p class="text-gray-700 text-base">Contexto: {{ $documentType->context}}</p>
                <p class="text-gray-700 text-base">Descrição: {{ $documentType->description}}</p>
                <p class="text-gray-700 text-base">Ordem: {{ $documentType->order}}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 rounded shadow-lg bg-white ">
        <div class="col-span-12 md:col-span-12">
            <div class="p-4 rounded">
                <h2 class="text-xl">Ações afirmativas associadas</h2>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ação afirmativa
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Descrição
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Modalidades
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documentType->affirmativeActions as $affirmativeAction)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $affirmativeAction->slug }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $affirmativeAction->description }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $affirmativeAction->modalities->implode('description', ',')}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</x-manager.app-layout>
