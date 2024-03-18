<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Cadastro de Sala de Prova" :notice="$notice" :exam_location="$examLocation" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens backUrl="admin.notices.allocation-of-exam-room.exam_location" :route-vars="['notice'=>$notice,'exam_location'=>$examLocation]"/>
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div
            class="grid grid-cols-12 rounded shadow-lg bg-white  border-b-4 border-blue-700 hover:border-blue-500 text-gray-600">
            @include('admin.notices.allocation-of-exam-room.header')
        </div>

        <div class="grid grid-cols">
            <div class="px-5 py-5 overflow-hidden md:col-span-3">
                <x-jet-validation-errors class="mb-4" />
                <form id="form-campus"
                    action="{{route('admin.notices.allocation-of-exam-room.exam_location.exam_room_booking.store',['notice'=>$notice,'exam_location'=>$examLocation])}}"
                    method="POST">
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Dados do Local de Provas</h2>
                    </div>
                    @csrf
                    <div class="col-span-6  ">
                        <x-jet-label value="Nome" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="name"
                            :value="old('name') ?? $examRoomBooking->name" required autofocus />
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                            <x-jet-label value="Capacidade" />
                            <x-jet-input class="block mt-1 w-full" type="number" name="capacity" min="0"
                                :value="old('capacity') ?? $examRoomBooking->capacity" required />
                        </div>

                        <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                            <x-jet-label value="Prioridade" />
                            <x-jet-input class="block mt-1 w-full" type="number" name="priority" min="0"
                                :value="old('priority') ?? $examRoomBooking->priority" required />
                        </div>
                    </div>

                    <div class="col-span-2 p-5">
                        <x-jet-label value="Exclusivo para necessidade específicas" />
                        <input type="checkbox" value="1" name="for_special_needs" @if(old('for_special_needs') or $examRoomBooking->for_special_needs)
                        checked="checked" @endif />
                    </div>

                    <div class="col-span-2 p-5">
                        <x-jet-label value="Ativo" />
                        <input type="checkbox" value="1" name="active" @if(is_null($examRoomBooking->active) or old('active') or $examRoomBooking->active)
                        checked="checked" @endif />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if($examRoomBooking->id)
                        @method('put')
                        <button type="submit"
                            formaction="{{ route('admin.notices.allocation-of-exam-room.exam_location.exam_room_booking.update',['notice'=>$notice,'exam_location'=>$examLocation,'exam_room_booking'=>$examRoomBooking]) }}"
                            class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                            Atualizar
                        </button>
                        @else
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                            Cadastrar
                        </button>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>




</x-manager.app-layout>
