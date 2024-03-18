<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Cadastro de Sala de Prova">
            <x-manager.internal-navbar-itens tip="Locais de Prova" home="admin.process.exam-locations.show"
                :routeVars="['exam_location'=>$examLocation]" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div
            class="grid grid-cols-12 rounded shadow-lg bg-white  border-b-4 border-blue-700 hover:border-blue-500 text-gray-600">
            @include('admin.exam-locations.header')
        </div>

        <div class="grid grid-cols">
            <div class="px-5 py-5 overflow-hidden md:col-span-3">
                <x-jet-validation-errors class="mb-4" />
                <form id="form-campus"
                    action="{{route('admin.process.exam-locations.exam-rooms.store',['exam_location'=>$examLocation])}}"
                    method="POST">
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Dados do Local de Prova</h2>
                    </div>
                    @csrf
                    <div class="col-span-6  ">
                        <x-jet-label value="Nome" />
                        <x-jet-input class="block mt-1 w-full" type="text" name="name"
                            :value="old('name') ?? $examRoom->name" required autofocus />
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                            <x-jet-label value="Capacidade" />
                            <x-jet-input class="block mt-1 w-full" type="number" name="capacity"
                                :value="old('capacity') ?? $examRoom->capacity" required />
                        </div>

                        <div class="col-span-6 sm:col-span-6 md:col-span-3 ">
                            <x-jet-label value="Prioridade" />
                            <x-jet-input class="block mt-1 w-full" type="number" name="priority"
                                :value="old('priority') ?? $examRoom->priority" required />
                        </div>
                    </div>

                    <div class="col-span-2 p-5">
                        <x-jet-label value="Exclusivo para necessidade específicas" />
                        <input type="checkbox" value="1" name="for_special_needs" @if(old('for_special_needs') or $examRoom->for_special_needs)
                        checked="checked" @endif />
                    </div>

                    <div class="col-span-2 p-5">
                        <x-jet-label value="Ativo" />
                        <input type="checkbox" value="1" name="active" @if(old('active') or $examRoom->active)
                        checked="checked" @endif />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if($examRoom->id)
                        @method('put')
                        <button type="submit"
                            formaction="{{ route('admin.process.exam-locations.exam-rooms.update',['exam_location'=>$examLocation,'exam_room'=>$examRoom]) }}"
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