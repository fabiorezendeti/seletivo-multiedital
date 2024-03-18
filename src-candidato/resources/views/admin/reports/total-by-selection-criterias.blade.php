<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Relatório - Totais de Inscritos e Homologados por Critério de Seleção" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens home="admin.notices.show" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por campus" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5" x-data="{ newTab: false, campus: {{ request('campus') }} }" x-init="newTab = false">
                    <form action="#" method="GET" x-bind:target="(newTab) ? '_blank' : '_self'">
                        <label for="campus">Escolha um Campus</label>
                        <x-select-box name="campus" id="campus" x-model="campus">
                            <option value="">-- Todos --</option>
                            @foreach ($campuses as $campus)
                            <option value="{{ $campus->id }}"
                                >{{ $campus->name }}</option>
                            @endforeach
                        </x-select-box>
                        <input type="hidden" class="form-checkbox h-8 w-8" x-model="newTab" name="html" value="1">
                        <x-jet-secondary-button type="submit" x-on:click="console.log(newTab)">
                            Gerar
                        </x-jet-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-manager.app-layout>
