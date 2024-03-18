<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Relatório de endereço dos candidatos" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5">
                    <form action="#" method="GET" target="_blank">
                        <label for="call_number">Escolha um Chamada</label>
                        <x-select-box name="call_number" id="call_number" class="mb-4" required>
                            <option value="">-- Todas --</option>
                            @foreach ($calls as $call)
                                <option value="{{ $call }}">{{ $call }}</option>
                            @endforeach
                        </x-select-box>
                        <label for="campus">Escolha um Campus</label>
                        <x-select-box name="campus" id="campus" required x-model="campus">
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->id }}"
                                >{{ $campus->name }}</option>
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
