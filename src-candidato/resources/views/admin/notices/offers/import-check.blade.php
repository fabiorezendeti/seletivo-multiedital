<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Importar Ofertas - Feedback" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens tip="Editais" home="admin.notices.show" :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="grid grid-cols-12">
            <div class="px-5 py-5 overflow-hidden col-span-12">                
                <livewire:admin.notice.sisu-importer :offers="$offers" :modalityId="$notice->modality_id" :notice="$notice" :fileName="$fileName" />
                <x-jet-validation-errors class="mb-4" />
            </div>            
        </div>
    </div>

    </div>

</x-manager.app-layout>