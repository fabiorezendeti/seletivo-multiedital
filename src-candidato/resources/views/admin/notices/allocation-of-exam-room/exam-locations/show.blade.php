<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Gerenciar Salas" :notice="$notice" :nomeEdital="$notice->number" >
            @if($notice->examIsNotPast())
                <x-manager.internal-navbar-itens tip="Criar sala de prova" buttonName="Nova sala" create="admin.notices.allocation-of-exam-room.exam_location.exam_room_booking.create" :route-vars="['notice'=>$notice,'exam_location'=>$examLocation]"
                                                 backUrl="admin.notices.allocation-of-exam-room.index" />
            @else
                <x-manager.internal-navbar-itens tip="Criar sala de prova" :route-vars="['notice'=>$notice,'exam_location'=>$examLocation]"
                                                 backUrl="admin.notices.allocation-of-exam-room.index" />
            @endif
        </x-manager.header>
    </x-slot>
    <div
        class="grid grid-cols-12 rounded shadow-lg bg-white  border-b-4 border-blue-700 hover:border-blue-500 text-gray-600">
       @include('admin.notices.allocation-of-exam-room.header')
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
                                @if($notice->examIsNotPast())
                                    <th
                                        class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Ações
                                    </th>
                                @endif
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                       @foreach($examLocation->examRoomBookings()->where('notice_id','=', $notice->id)->orderBy('name')->orderBy('active')->get() as $room)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->name }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->capacity }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->for_special_needs ? 'Sim' : 'Não' }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->priority }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $room->active ? 'Sim' : 'Não' }}</td>
                            @can('isAdmin')
                                @if($notice->examIsNotPast())
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                        <a href="{{ route('admin.notices.allocation-of-exam-room.exam_location.exam_room_booking.edit',['notice'=>$notice,'exam_location'=>$examLocation,'exam_room_booking'=>$room]) }}"
                                        class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">Editar</a>
                                        <button type="submit" form="delete-notice" formaction="{{ route('admin.notices.allocation-of-exam-room.exam_location.exam_room_booking.destroy',['notice'=>$notice,'exam_location'=>$examLocation,'exam_room_booking'=>$room]) }}"
                                             class="bg-red-500 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">Excluir</button>
                                    </td>
                                @endif
                            @endcan
                        </tr>
                       @endforeach
                        @if($examLocation->examRoomBookings->isEmpty())
                            <tr>
                                <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                    Sem registros para exibir!
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</x-manager.app-layout>
