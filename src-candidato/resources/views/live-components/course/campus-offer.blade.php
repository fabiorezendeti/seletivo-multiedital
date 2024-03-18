<div>
    <div class="grid grid-cols-6">
        <div class="col-span-6">
            <h2 class="text-xl">Campus de Oferta</h2>
            <x-jet-button wire:click=edit()>
                Adicionar
            </x-jet-button>
        </div>
        <x-jet-dialog-modal wire:model="modalOpen">
            <x-slot name="title">
                Vincular Campus de Oferta
            </x-slot>

            <x-slot name="content">
                <x-error-message />

                <x-jet-label value="Campus" />
                <x-select-box wire:model.defer="offer.campus_id" required="required" :disabled="$onlySISUCode" >
                    <option value="">Selecione um campus</option>
                    @foreach ($campuses as $campus)
                    <option value="{{$campus->id}}">{{$campus->name}}</option>
                    @endforeach
                </x-select-box>
                <x-jet-input-error for="offer.campus_id" />

                <x-jet-label value="Turno" />
                <x-select-box wire:model.defer="offer.course_shift_id" required="required" :disabled="$onlySISUCode" >
                    <option value="">Selecione um turno</option>
                    @foreach ($shifts as $shift)
                    <option value="{{$shift->id}}">{{$shift->description}}</option>
                    @endforeach
                </x-select-box>
                <x-jet-input-error for="offer.course_shift_id" />

                <x-jet-label value="Site do Curso Ex: http://endereco.com.br"  />
                <x-jet-input class="w-full" type="url" wire:model.defer="offer.website" required=true />
                <x-jet-input-error for="offer.website" />

                <x-jet-label value="Código do Curso no SISU" />                
                <x-jet-input class="w-full"  type="text" pattern="[0-9]+" maxlength="10" wire:model.defer="offer.sisu_course_code" required=true />
                <x-jet-input-error for="offer.sisu_course_code" />
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
                Você tem certeza que deseja excluir este campus de oferta?
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('selectedToDelete')" wire:loading.attr="disabled">
                    Cancelar
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="destroy" wire:loading.attr="disabled">
                    Excluir Campus de Oferta
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>

        <div class="col-span-6 p-5">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Campus</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Turno</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Website</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Código SISU</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bindedCampuses as $item)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$item['campus']['name']}}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$item['shift']['description']}}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{$item['website']}}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{$item['sisu_course_code']}}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            @can('onlySISUCodeUpdate',$item)
                            <button type="button" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full" wire:click="edit({{$item['id']}})">Editar</button>
                            @endcan
                            @can('updateOrDelete',$item)
                            <button type="button" class="bg-orange-500 hover:bg-orange-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full" wire:click="edit({{$item['id']}})">Editar</button>
                            <button type="button" class="bg-red-500 hover:bg-red-700 text-xs text-white font-bold py-2 px-3 mr-3 rounded-full" wire:click="setToDelete({{$item['id']}})">Excluir</button>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>