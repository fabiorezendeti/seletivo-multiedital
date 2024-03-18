<div>
    <h2 class="text-lg">Distribuição de vagas</h2>
    <x-jet-button wire:click=edit()>
        Adicionar
    </x-jet-button>

    <x-jet-dialog-modal wire:model="modalOpen">
        <x-slot name="title">
            Adicionar Oferta
        </x-slot>

        <x-slot name="content">
            <x-error-message />
            <div class="bg-red-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Fique ligado!</strong>
                <span class="block sm:inline">Se você selecionar uma ação afirmativa e critério de seleção já cadastrados o número de vagas é atualizado</span>                
              </div>
            <x-jet-label value="Escolha uma ação afirmativa" />
            
            <x-select-box wire:model.defer="selected.affirmative_action_id" :disabled="$selected['has_subscriptions']">
                <option value="">Selecione uma ação afirmativa</option>
                @foreach($affirmativeActions as $affirmativeAction)
                <option value="{{ $affirmativeAction->id }}" title="{{ $affirmativeAction->description }}">
                    {{ $affirmativeAction->slug}}</option>
                @endforeach
            </x-select-box>
            <x-jet-input-error for="selected.affirmative_action_id" />
            

            <x-jet-label value="Escolha um critério de seleção" />
            <x-select-box wire:model.defer="selected.selection_criteria_id" 
                :disabled="$selected['has_subscriptions']">
                <option value="">Selecione um critério de seleção</option>
                @foreach($selectionCriterias as $selectionCriteria)
                <option value="{{ $selectionCriteria->id }}">{{ $selectionCriteria->details}}</option>
                @endforeach
            </x-select-box>
            <x-jet-input-error for="selected.selection_criteria_id" />

            <x-jet-label value="Total de Vagas" />
                    <x-jet-input type="number" name="total_vacancies"
                       wire:model.defer="selected.total_vacancies" required />
            <x-jet-input-error for="selected.total_vacancies" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalOpen')" wire:loading.attr="disabled">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
                Salvar
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>    


    <x-jet-confirmation-modal wire:model="selectedToDelete">
        <x-slot name="title">
            Confirmar exclusão
        </x-slot>
    
        <x-slot name="content">
            Você tem certeza que deseja excluir esta Distribuição?                                        
        </x-slot>
    
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('selectedToDelete')" wire:loading.attr="disabled">
                Cancelar
            </x-jet-secondary-button>
    
            <x-jet-danger-button class="ml-2" wire:click="destroy" wire:loading.attr="disabled">
                Excluir
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

    <div class="col-span-6 p-5">
        Total Distribuído: {{ $distributedVacanciesCount }} de {{ $offerTotalVacancies  }} - Restam {{ $availableVacancies }}
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ação afirmativa</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Critério de Seleção</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Vagas</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($distribution as $item)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$item->affirmativeAction->slug}}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$item->selectionCriteria->details}}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{$item->total_vacancies}}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <button type="button" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full" wire:click="edit({{$item->id}})">Editar</button>
                        @can('deleteOrUpdate',$item)
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full" wire:click="setToDelete({{$item->id}})">Excluir</button>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>