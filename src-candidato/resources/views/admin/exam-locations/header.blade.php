<div class="col-span-12 md:col-span-6">
    <div class="p-4 rounded">
        <h2 class="font-bold text-xl mb-2">Informações</h2>
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
            <form action="{{ route('admin.process.exam-locations.report', ['exam_location'=> $examLocation]) }}" method="POST">
                @csrf
                <button class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Relatório
                </button>
            </form>
            <form action="{{ route('admin.process.exam-locations.report-by-room', ['exam_location'=> $examLocation]) }}" method="POST">
                @csrf
                <button class="bg-blue-500 mt-3 hover:bg-blue-700 text-xs
                        text-white font-bold py-2 px-3 mr-3 rounded-full">
                    Relatório por sala
                </button>
            </form>
        </div>
    </div>
@endcan
