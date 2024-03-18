<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Relatório de Candidatos Matrículados" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5" x-data="{criteria: 3}" x-init="{criteria: 3}">
                    <form action="#" method="GET" target="_blank">
                        <label for="criteria">Escolha um Critério de Seleção</label>
                        <x-select-box name="criteria" id="criteria" x-model="criteria">
                            @foreach ($selectionCriterias as $selectionCriteria)
                            <option value="{{ $selectionCriteria->id }}">{{ $selectionCriteria->description }}</option>
                            @endforeach
                        </x-select-box>                        
                            <label for="modality">Modalidade</label>
                            <x-select-box name="modality" id="modality">
                                <option value="">-- Todas --</option>
                                @foreach ($notice->getModalitiesForCurriculumAnalisys() as $modality)
                                <option x-show="criteria == 4" value="{{ $modality->title }}">{{ $modality->description }}</option>
                                @endforeach
                            </x-select-box>
                        <label for="offer">Escolha uma Oferta</label>
                        <x-select-box name="offer" id="offer">                            
                            @foreach ($offers as $offer)
                            <option value="{{ $offer->id }}">{{ $offer->getString() }}</option>
                            @endforeach
                        </x-select-box>                        
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