<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Customizando {{ $criteria->description }}" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens home="admin.notices.show" :routeVars="['notice'=>$notice]"
                search-placeholder="Buscar por curso ou campus" />
        </x-manager.header>
    </x-slot>

    <div
        class="grid grid-cols-12 rounded shadow-lg bg-white shadow-md border-b-4 border-blue-700 hover:border-blue-500 text-gray-600">
        <div class="col-span-12 p-5">
            @if($criteria->pivot->customization)
                <h2 class="text-lg text-red-700">Você já configurou esse processo</h2>
            @else
                @livewire('notice.criteria-customization', ['notice'=>$notice,'criteria'=>$criteria])
            @endif
        </div>

    </div>

</x-manager.app-layout>