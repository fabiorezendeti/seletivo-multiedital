<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Relatório Inscritos e Homologados" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5">
                    <form action="#" method="GET" target="_blank">
                        <label for="campus">Escolha um Campus</label>
                        <x-select-box name="campus" id="campus">
                            <option value="">-- Todos --</option>
                            @foreach ($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                            @endforeach
                        </x-select-box>
                        <label for="criteria">Escolha um Critério de Seleção</label>
                        <x-select-box name="criteria" id="criteria" class="mb-4">
                            <option value="">-- Todos --</option>
                            @foreach ($selectionCriterias as $selectionCriteria)
                            <option value="{{ $selectionCriteria->id }}">{{ $selectionCriteria->description }}</option>
                            @endforeach
                        </x-select-box>
                        <input type="hidden" class="form-checkbox h-8 w-8" name="html" value="1">
                        <x-jet-secondary-button type="submit">
                            Gerar
                        </x-jet-secondary-button>
                        <x-jet-secondary-button type="submit"
                            formaction="{{ route('admin.notices.totalByAffirmativeActions.report',['notice'=>$notice]) }}">
                            Gerar separando Ações Afirmativas
                        </x-jet-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-manager.app-layout>
