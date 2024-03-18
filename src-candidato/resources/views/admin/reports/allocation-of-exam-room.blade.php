<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Relatórios de Ensalamento" :notice="$notice" :nomeEdital="$notice->number">
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
                            @foreach ($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                            @endforeach
                        </x-select-box>
                        
                        <label class="block items-center my-2" >
                            <input type="radio" class="form-checkbox h-8 w-8" x-model="newTab" name="type" value="html"
                            {{ (old('type', 'unknown') == 'unknown') ? 'checked' : ''}}>
                            <span class="ml-2 ">formato HTML</span>                                                        
                        </label>
                        <label class="block items-center my-2" >
                            <input type="radio" class="form-checkbox h-8 w-8" x-model="newTab" name="type" value="csv">
                            <span class="ml-2 ">formato CSV</span>                                                        
                        </label>
                        <label class="block items-center my-2" >
                            <input type="radio" class="form-checkbox h-8 w-8" x-model="newTab" name="type" value="lista">
                            <span class="ml-2 ">formato Lista de Presença</span>                                                        
                        </label>    

                        <x-jet-secondary-button type="submit">
                            Gerar
                        </x-jet-secondary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-manager.app-layout>
