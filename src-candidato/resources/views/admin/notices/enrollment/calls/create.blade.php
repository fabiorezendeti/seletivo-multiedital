<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Lista de Chamadas" :nomeEdital="$notice->number" :notice="$notice">
            <x-manager.internal-navbar-itens tip="BUtton Tip" home="admin.notices.calls.index"
            :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div id="my-table">                        
        <div class=" overflow-x-auto p-5">
            <div class="inline-block min-w-full  overflow-hidden text-center">                                
                <form action="{{ route('admin.notices.calls.store',['notice'=>$notice]) }}" 
                    method="POST">                    
                    @csrf                    
                    <p class="bg-red-300 border-red-700 border-2 text-red-700 text-2xl p-5 rounded">
                    Você tem certeza que deseja gerar as seguintes chamadas?                                     
                    </p>
                    <x-jet-validation-errors class="mb-4" />
                    <input type="hidden" name="calls" value="{{json_encode($callCounting)}}">                    
                    <ul class="p-10 text-2xl">                        
                    @foreach ($callCounting as $call)
                        <li>                            
                            {{$call['selectionCriteria']->description}}: {{$call['last_call_number'] +1}}ª chamada </li>
                    @endforeach   
                    </ul>
                    <div class="col-span-2 sm:col-span-2 md:col-span-2 ">
                        <x-jet-label value="Primeiro dia de matrícula" />
                        <x-jet-input class="block mt-1 w-full" type="date"
                            name="enrollment_start_date"
                            :value="old('enrollment_start_date')" />
                        <x-jet-label value="Último dia de matrícula" />
                        <x-jet-input class="block mt-1 w-full" type="date"
                            name="enrollment_end_date"
                            :value="old('enrollment_end_date')" />
                            @if($call['last_call_number'] + 1 == 1 )
                        <x-jet-label value="O que fazer se tiver MAIS vagas do que INSCRITOS" />
                        <x-select-box id="protected_affirmative_actions" name="protected_affirmative_actions">
                            <option value="N" selected="selected">Classificar TODOS como Ampla Concorrência</option>
                            <option value="Y">Manter as ações afirmativas originais</option>
                        </x-select-box>
                        @endif
                    </div>                    
                    <button type="submit" class="bg-red-700 text-white p-2 rounded mt-4 font-bold w-full">
                        GERAR CHAMADA
                    </button>
                </form>
                
                
            </div>
        </div>
    </div>        
</x-manager.app-layout>