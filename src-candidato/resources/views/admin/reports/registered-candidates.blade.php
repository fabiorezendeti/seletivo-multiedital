<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Relatório de Candidatos Matrículados" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5">
                    <form action="#" method="GET" target="_blank">
                        <label for="criteria">Escolha um Critério de Seleção</label>
                        <x-select-box name="criteria" id="criteria">
                            {{-- <option value="">-- Todos --</option> --}}
                            @foreach ($selectionCriterias as $selectionCriteria)
                            <option value="{{ $selectionCriteria->id }}">{{ $selectionCriteria->description }}</option>
                            @endforeach
                        </x-select-box>
                        {{-- @cannot('isAdmin') --}}
                            <label for="oferta">Escolha uma Oferta</label>
                            <x-select-box name="oferta" id="oferta">
                                <option value="">-- Todas --</option>
                                @foreach ($offers as $offer)
                                <option value="{{ $offer->id }}">{{ $offer->getString() }}</option>
                                @endforeach
                            </x-select-box>
                            <label for="status">Escolha um Status</label>
                            <x-select-box name="status" id="status">
                                <option value="">-- Todos --</option>
                                <option value="pendente">Pendente</option>
                                <option value="matriculado">Matriculado</option>
                                <option value="não matriculado">Não Matriculado</option>
                            </x-select-box>
                        {{-- @endcan --}}
                        <input type="hidden" class="form-checkbox h-8 w-8" name="html" value="1">
                        <x-jet-secondary-button type="submit">
                            Gerar
                        </x-jet-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-manager.app-layout>
