<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Gerenciar Inscrições" :notice="$notice" :nomeEdital="$notice->number">
            @can('isAdmin')
            <x-manager.internal-navbar-itens home="admin.notices.subscriptions.index" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por CPF ou Número de Inscrição" />
            @else
            <x-manager.internal-navbar-itens home="cra.notices.subscriptions.index" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por CPF ou Número de Inscrição" />
            @endcan
        </x-manager.header>
    </x-slot>

    <div id="subscription-table" x-data="subscription()">
        <div class=" overflow-x-auto">
            <div id="filters" class="inline-block min-w-full  overflow-hidden">
                <div class="p-5" x-data="{ newTab: false, offer: {{ request('offer') ?? 0 }} }" x-init="newTab = false">
                    <form action="#" method="GET" target="_blank">
                            <label for="oferta">Filtrar por Oferta</label>
                            <x-select-box name="offer" id="offer" x-model="offer">
                                <option value="">-- Todas --</option>
                                @foreach ($results as $result)
                                <option value="{{ $result->id }}">{{ $result->campus }} - {{ $result->curso }}</option>
                                @endforeach
                            </x-select-box>
                        <input type="hidden" name="html" value="1">
                        <x-jet-secondary-button type="submit">
                            Gerar
                        </x-jet-secondary-button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-manager.app-layout>
