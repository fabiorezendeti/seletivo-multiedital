<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Modo de Manutenção">

        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto p-10">
            <div class="inline-block min-w-full  overflow-hidden">
                <form action="{{ route('dev.maintenance.store') }}" method="POST">
                    @csrf                    
                    @if($downFile)
                    <x-jet-button type="submit" name="maintenance_mode" value="0">
                        Remover o modo manutenção
                    </x-jet-button>
                    @else
                    <x-jet-label value="Secret" />
                    <x-jet-input name="secret" class="w-full" value="{{$secret}}" />
                    <x-jet-danger-button type="submit" name="maintenance_mode"  value="1">
                        Colocar em modo manutenção
                    </x-jet-danger-button>
                    <p></p>
                    @endif
                </form>

            </div>
        </div>
    </div>

</x-manager.app-layout>