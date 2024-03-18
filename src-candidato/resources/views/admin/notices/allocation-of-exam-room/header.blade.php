<div class="col-span-12 md:col-span-6">
    <div class="p-4 rounded">
        <h2 class="font-bold text-xl mb-2">Local de Prova</h2>
        <p class="text-justify">Nome: {{$examLocation->local_name }}</p>
        <p class="text-justify">Campus: {{$examLocation->campus->name }}</p>
        <p class="text-justify">Ativo: {{$examLocation->active ? 'Sim' : 'Não' }}</p>
        <p class="text-justify">Endereço: {{ $examLocation->getAddressString()}}</p>
        <p class="text-justify">Fone: {{ $examLocation->getPhoneString()}}</p>
    </div>
</div>
@can('isAdmin')
    <div class="col-span-12 md:col-span-6">
        <div class="p-4">
            <h2 class="font-bold text-xl mb-2">Opções</h2>
            <form action="{{ route('admin.notices.allocation-of-exam-room.exam-location.report-by-room-booking-short', ['notice' => $notice, 'exam_location' => $examLocation]) }}" method="POST" target="_blank">
                @csrf
                <button class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Relatório
                </button>
            </form>
            <form action="{{ route('admin.notices.allocation-of-exam-room.exam-location.report-by-room-booking', ['notice'=>$notice,'exam_location'=>$examLocation]) }}" method="POST" target="_blank">
                @csrf
                <button class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Relatório por sala
                </button>
            </form>
        </div>
    </div>
@endcan
@if($notice->checkAllocationNeed($examLocation->campus()->first()) > 0)
    <div class="col-span-12 md:col-span-12 p-4">
        <div class="bg-orange-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <b>Atenção!</b> <br>
            Faltam {{$notice->checkAllocationNeed($examLocation->campus()->first())}} lugares "ativos" para alocar o total de candidatos nesse local de prova.
        </div>
    </div>
@endif
