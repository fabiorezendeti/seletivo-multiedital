<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Local de Prova">
                <x-manager.internal-navbar-itens tip="Criar sala de prova"  create="admin.process.exam-locations.exam-rooms.create"  home="admin.process.exam-locations.index" :route-vars="['exam_location'=>$examLocation]" search-placeholder="Buscar por nome"/>
        </x-manager.header>
    </x-slot>

    <div
        class="grid grid-cols-12 rounded shadow-lg bg-white  border-b-4 border-blue-700 hover:border-blue-500 text-gray-600">
       @include('admin.exam-locations.header')
    </div>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <form action="#" method="POST" id="delete-notice">
                    @csrf
                    @method('delete')
                </form>
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nome
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Capacidade
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                N.E
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Prioridade
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ativo
                            </th>
                            @can('isAdmin')
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Ações
                            </th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                       @foreach($examLocation->examRooms as $room)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->name }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->capacity }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->for_special_needs ? 'Sim' : 'Não' }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->priority }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->active ? 'Sim' : 'Não' }}</td>
                            @can('isAdmin')
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <a href="{{ route('admin.process.exam-locations.exam-rooms.edit',['exam_location'=>$examLocation,'exam_room'=>$room]) }}"
                                class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">Editar</a>
                                @can('deleteExamLocation',$room)
                                <button type="submit" form="delete-notice" formaction="{{ route('admin.process.exam-locations.exam-rooms.destroy',['exam_location'=>$examLocation,'exam_room'=>$room]) }}"
                                     class="bg-red-500 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">Excluir</button>
                                @endcan
                            </td>
                            @endcan
                        </tr>
                       @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</x-manager.app-layout>
