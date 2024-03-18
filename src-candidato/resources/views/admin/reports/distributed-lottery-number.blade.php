<x-manager.app-layout>

    <x-slot name="header">
        <x-manager.header tip="Candidatos - Números de Sorteio Distribuídos" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens  :routeVars="['notice'=>$notice]"
                 />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="p-5" >
                    <form action="#" method="GET" target="_blank">
                        <label for="offer">Escolha uma oferta</label>
                        <x-select-box name="offer" id="offer" required>
                            @foreach ($offers as $offer)
                                <option value="{{ $offer->id }}"
                                    >
                                    {{ $offer->getString() }} -
                                     {{ $offer->courseCampusOffer->shift->description }}
                                     </option>
                            @endforeach
                        </x-select-box>
                        <label class="block items-center my-2" >
                            <input type="hidden" class="form-checkbox h-8 w-8" name="html" value="1">
                            <span class="ml-2 ">Gerar em formato HTML</span>
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
