<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Relatório de Matrículados e não matriculados" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5">
                    <form action="#" method="GET" target="_blank">                                                
                        <label for="call_number">Escolha um Chamada</label>
                        <x-select-box name="call_number" id="call_number" class="mb-4">
                            <option value="">-- Todas --</option>
                            @foreach ($calls as $call)
                                <option value="{{ $call }}">{{ $call }}</option>
                            @endforeach
                        </x-select-box>
                        <label for="type">Tipo</label>
                        <x-select-box name="type" id="type" class="mb-4">
                            <option value="matriculados">Matrículados</option>                            
                            <option value="nao-matriculados">Não Matrículados</option>                            
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
