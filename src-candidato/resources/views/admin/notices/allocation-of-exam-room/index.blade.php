<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Gerenciar Ensalamento" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens backUrl="admin.notices.show" :routeVars="['notice'=>$notice]"/>
        </x-manager.header>
    </x-slot>

    @if($notice->hasExamRoomBookings())
        @php($allocationNeed = $notice->checkAllocationNeed())
        @if($notice->examIsNotPast())
        <div
            class="grid grid-cols-12 rounded shadow-lg bg-white  border-b-4 border-blue-700 hover:border-blue-500 text-gray-600">
            <div class="col-span-12 md:col-span-6">
                <div class="p-4 rounded">
                    <h2 class="font-bold text-xl mb-2">Informações</h2>
                    <p class="text-gray-700 text-base">
                    <p class="text-justify">A opção de 'fazer todos' irá executar o ensalamento automático para todos
                        os candidatos inscritos que não irão dispor de necessidades específicas </p>
                    <p>Sendo possível reverter o processo usando a opção 'desfazer todos' </p>
                    </p>
                </div>
            </div>
            <div class="col-span-12 md:col-span-6 @if(is_null($allocationNeed)) hidden @endif">
                <div class="p-4">
                    <h2 class="font-bold text-xl mb-2">Opções</h2>
                    @if($allocationNeed > 0)
                        <div class="bg-orange-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <b>Atenção!</b> <br>
                            Não há capacidade de ensalamento suficiente para o total de inscrições homologadas. Verifique abaixo os campus que precisam de mais capacidade.
                        </div>
                    @else
                        @can('allowAutoAllocateExamRoom',$notice)
                            <form
                                action="{{ route('admin.notices.allocation-of-exam-room.auto-allocate',['notice'=>$notice]) }}"
                                method="POST">
                                @csrf
                                <button class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs
                                    text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Fazer para todos
                                </button>
                            </form>
                        @endcan
                        @can('allowAllocateExamRoom',$notice)
                            <form
                                action="{{ route('admin.notices.allocation-of-exam-room.undo-auto-allocate',['notice'=>$notice]) }}"
                                method="POST">
                                @csrf
                                @method('delete')
                                <button class="bg-red-500 mt-3 hover:bg-red-700 text-xs
                                    text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Desfazer de todos
                                </button>
                            </form>
                            {{-- <button class="bg-orange-500 mt-3 hover:bg-orange-700 text-xs
                                    text-white font-bold py-2 px-3 mr-3 rounded-full">
                                Manual
                            </button> --}}
                            <div class="mt-3 mb-1">
                                <a href="{{ route('admin.notices.allocation-of-exam-room.manual', ['notice'=>$notice]) }}"
                                   class="bg-orange-500 mt-3 hover:bg-orange-700 text-xs
                                            text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Manual
                                </a>
                            </div>
                            {{-- <form action="{{ route('admin.notices.allocation-of-exam-room.report', ['notice'=>$notice]) }}" method="POST">
                                @csrf
                                <button class="bg-orange-500 mt-3 hover:bg-orange-700 text-xs
                                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                                    Relatório de Ensalamento
                                </button>
                            </form> --}}
                        @endcan
                    @endif
                </div>
            </div>
        </div>
        @endif
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
                                Salas para Necessidades Específicas
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
                                    {{ $examLocation->hasRoomForSpecialNeeds() ? 'Sim' : 'Não'}}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{($examLocation->active) ? 'Sim' : 'Não'}}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @php($color = $notice->checkAllocationNeed($examLocation->campus()->first()) > 0 ? 'red' : 'blue')
                                    <a href="{{ route('admin.notices.allocation-of-exam-room.exam_location',['notice'=>$notice, 'exam_location' => $examLocation]) }}"
                                       class="bg-{{$color}}-500 mt-3 hover:bg-{{$color}}-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full">
                                        Gerenciar Salas
                                    </a>
                                    @if($allocationNeed == 0 && $notice->examIsNotPast())
                                            @if(!$examLocation->hasAllocation($notice->id))
                                            <form
                                                action="{{ route('admin.notices.allocation-of-exam-room.auto-allocate',['notice'=>$notice, 'exam_location' => $examLocation]) }}"
                                                method="POST">
                                                @csrf
                                                <button class="bg-blue-500 mt-4 hover:bg-blue-700 text-xs
                                                text-white font-bold py-2 px-3 mr-3 rounded-full">
                                                    Automático
                                                </button>
                                            </form>
                                            @else
                                            <form
                                                action="{{ route('admin.notices.allocation-of-exam-room.undo-auto-allocate',['notice'=>$notice, 'exam_location' => $examLocation]) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="bg-red-500 mt-3 hover:bg-red-700 text-xs
                                                text-white font-bold py-2 px-3 mr-3 rounded-full">
                                                    Desfazer
                                                </button>
                                            </form>
                                            @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    {{ $examLocations->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="text-center p-10">
            Não há salas importadas para esse edital! <br>
            @if($notice->examIsNotPast())
            <form
                action="{{ route('admin.notices.allocation-of-exam-room.exam_location.import', ['notice' => $notice]) }}"
                method="POST">
                @csrf
                <button class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs text-white font-bold py-1 px-3 mr-3">
                    Importar Salas
                </button>
            </form>
            @else
                <div class="font-medium text-red-600">(Impossível importar salas após a data da prova)</div>
            @endif
        </div>
    @endif
</x-manager.app-layout>
