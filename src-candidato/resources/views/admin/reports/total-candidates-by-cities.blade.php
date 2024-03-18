<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Total de Candidatos por Cidade" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens home="admin.notices.show" :routeVars="['notice'=>$notice]"
                 />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5">
                    <form action="#" method="GET" target="_blank">
                        <label for="campus">Escolha um Campus</label>
                        <x-select-box name="campus" id="campus" >
                            <option value="">-- Todos --</option>
                            @foreach ($campuses as $campus)
                            <option value="{{ $campus->id }}"                                
                                >{{ $campus->name }}</option>
                            @endforeach
                        </x-select-box>
                        <label class="block items-center my-2" >
                            <input type="hidden" class="form-checkbox h-8 w-8" name="html" value="1">                            
                        </label>
                        <x-jet-secondary-button type="submit" x-on:click="console.log(newTab)">
                            Gerar
                        </x-jet-secondary-button>
                    </form>
                </div>                
            </div>
        </div>
    </div>
</x-manager.app-layout>