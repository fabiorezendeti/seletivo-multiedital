<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Lista de Chamadas" :nomeEdital="$notice->number" :notice="$notice">
            @can('isAdmin')
            <x-manager.internal-navbar-itens tip="BUtton Tip" home="admin.notices.calls.index"
                :routeVars="['notice'=>$notice]" />
            @else
            <x-manager.internal-navbar-itens tip="BUtton Tip" home="cra.notices.calls.index"
                :routeVars="['notice'=>$notice]" />
            @endcan
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto p-5">
            <div class="inline-block min-w-full  overflow-hidden ">
                <h2 class="2xl">{{$callNumber}}º Chamada para {{$selectionCriteria->description}}</h2>
                <form action="{{ route('admin.notices.calls.show',[
                        'notice'=>$notice,
                        'call'=>$callNumber,                        
                    ]) }}" method="GET" target="_blank">
                    <label for="offer">Escolha uma oferta</label>
                    <x-select-box name="offer" id="offer">
                        @foreach ($notice->offers->sortBy('courseCampusOffer.campus.name') as $offer)
                        <option value="{{ $offer->id }}">{{ $offer->getString() }}</option>
                        @endforeach
                    </x-select-box>
                    <input type="hidden" class="form-checkbox h-8 w-8" name="selection_criteria_id"
                        value="{{ $selectionCriteria->id }}">
                    <input type="hidden" class="form-checkbox h-8 w-8" name="html" value="1">
                    <x-jet-secondary-button type="submit">
                        Relatório de Aprovados
                    </x-jet-secondary-button>
                </form>
            </div>
        </div>
    </div>
</x-manager.app-layout>