<div>
    <div class="flex mt-6 div-modality">
        <div class="m-auto text-1xl">
            <label for="modality_id">Modalidade:</label>
            <x-select-box name="modality_id" id="modality_id" wire:model="modality_id">
                <option hidden selected>Selecione uma modalidade...</option>
                @foreach($modalities as $modality)
                    <option value="{{ $modality->id }}">{{ $modality->description }}</option>
                @endforeach
            </x-select-box>
        </div>
    </div>
    @if(count($notices) > 0)
        <div class="flex mt-6 div-notice">
            <div class="m-auto text-1xl">
                <label for="notice_id">Edital:</label>
                <div wire:loading.remove wire:target="modality_id">
                    <x-select-box name="notice_id" id="notice_id" wire:model="notice_id">
                        <option hidden selected>Selecione um edital...</option>
                        @foreach($notices as $notice)
                            <option value="{{ $notice->id }}">{{ $notice->number }}</option>
                        @endforeach
                    </x-select-box>
                </div>
                    <div wire:loading.block wire:target="modality_id">
                        <x-select-box disabled>
                            <option hidden selected>Carregando...</option>
                        </x-select-box>
                    </div>
            </div>
        </div>
    @endif
    @if(count($campuses) > 0)
        <div class="flex mt-6 div-campus">
            <div class="m-auto text-1xl">
                <label for="campus_id">Campus:</label>
                <div wire:loading.remove wire:target="modality_id, notice_id">
                    <x-select-box name="campus_id" id="campus_id" wire:model="campus_id">
                        <option hidden selected>Selecione um campus...</option>
                        @foreach($campuses as $campus)
                            <option value="{{ $campus->id }}">{{ $campus->name }}</option>
                        @endforeach
                    </x-select-box>
                </div>
                <div wire:loading.block wire:target="modality_id, notice_id">
                    <x-select-box disabled>
                        <option hidden selected>Carregando...</option>
                    </x-select-box>
                </div>
            </div>
        </div>
    @endif
    @if(count($offers) > 0)
        <div class="flex mt-6 div-offer">
            <div class="m-auto text-1xl">
                <label for="offer_id">Curso:</label>
                <div wire:loading.remove wire:target="modality_id, notice_id, campus_id">
                    <x-select-box name="offer_id" id="offer_id" wire:model="offer_id">
                        <option hidden selected value="-1">Selecione o curso...</option>
                        @foreach($offers as $offer)
                            <option value="{{ $offer->id }}">{{ $offer->courseCampusOffer->course->name }}</option>
                        @endforeach
                    </x-select-box>
                </div>
                <div wire:loading.block wire:target="modality_id, notice_id, campus_id">
                    <x-select-box disabled>
                        <option hidden selected>Carregando...</option>
                    </x-select-box>
                </div>
            </div>
        </div>
    @endif
    @if(count($calls) > 0)
        <div class="flex mt-6 div-call">
            <div class="m-auto text-1xl">
                <label for="call_id">Chamada:</label>
                <div wire:loading.remove wire:target="modality_id, notice_id, campus_id, offer_id">
                    <x-select-box name="call_json" id="call_json" wire:model="call_json">
                        <option hidden selected>Selecione a chamada...</option>
                        @foreach($calls as $call)
                            <option value="{{ json_encode($call) }}">Chamada {{ $call['call_number']}} - {{$call['selection_criteria_description']}}</option>
                        @endforeach
                    </x-select-box>
                </div>
                <div wire:loading.block wire:target="modality_id, notice_id, campus_id, offer_id">
                    <x-select-box disabled>
                        <option hidden selected>Carregando...</option>
                    </x-select-box>
                </div>
            </div>
        </div>
    @endif
    @if($error)
        <div class="flex mt-6 div-call">
            <div class="m-auto text-1xl">
                <div class="block bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                     role="alert">
                    <span class="block sm:inline">{{$error}}</span>
                </div>
            </div>
        </div>
    @endif
    @if($btn_submit)
        <div class="flex mt-6 div-btn">
            <div class="m-auto text-1xl" >
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50"
                        wire:loading.class.remove="hover:bg-blue-400"
                        wire:target="modality_id, notice_id, campus_id, offer_id, call_id"
                        class="mx-3 inline bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4
                        border-blue-700 rounded disabled:opacity-50">
                    Gerar Relatório
                </button>
            </div>
        </div>
    @endif
</div>
