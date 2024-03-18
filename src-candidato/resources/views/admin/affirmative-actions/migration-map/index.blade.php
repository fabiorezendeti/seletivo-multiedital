<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Migração de Vagas {{ $affirmativeAction->slug }}">
            <x-manager.internal-navbar-itens tip="BUtton Tip" home="admin.affirmative-actions.index" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">
        <div class=" overflow-x-auto">
            <div class="inline-block min-w-full  overflow-hidden">
                <div class="border border-green-700 p-5 bg-green-500 text-white">
                    <h3 class="text-2xl">{{$affirmativeAction->slug}}</h3>
                    <p class="text-xs">{{$affirmativeAction->description}}</p>
                </div>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 17l-4 4m0 0l-4-4m4 4V3"></path>
                </svg>

                @foreach ($affirmativeAction->migrationVacancyMap as $map)
                <div class="border border-green-700 p-5 bg-green-300">
                    <h3 class="text-2xl">
                        <span class="bg-green-500 p-1 rounded text-xs"> {{ $map->order }} </span>
                        {{$map->affirmativeActionTo->slug}}                        
                    </h3>
                    <p class="text-xs">{{$map->affirmativeActionTo->description}}</p>
                </div>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 17l-4 4m0 0l-4-4m4 4V3"></path>
                </svg>
                @endforeach

                <div class="border border-red-700 p-5 bg-red-300">
                    @if($availableAffirmativeActions)
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <form action="{{ route('admin.affirmative-actions.migration-vacancy-map.store',[
                        'affirmative_action'=>$affirmativeAction
                    ])  }}" method="POST">
                        @csrf
                        <x-jet-label value="Ação Afirmativa" />
                        <x-select-box name="affirmative_action_to_id">
                            @foreach($availableAffirmativeActions as $available)
                            <option value="{{$available->id}}" title="{{$available->description}}">{{$available->slug}}
                            </option>
                            @endforeach
                        </x-select-box>
                        <x-jet-label value="Ordem De Migração (ascendente)" />
                        <x-jet-input name="order" type="number" min="0" max="1000" step="1" required />
                        <x-jet-button type="submit">
                            Adicionar
                        </x-jet-button>
                    </form>
                    @else
                        Não existem mais ações para incluir
                    @endif
                </div>


            </div>
        </div>

</x-manager.app-layout>