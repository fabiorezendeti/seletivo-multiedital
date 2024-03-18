<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Gerenciar oferta" :notice="$notice" :nomeEdital="$notice->number">
            <x-manager.internal-navbar-itens tip="Editais" home="admin.notices.show" :routeVars="['notice'=>$notice]" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="grid grid-cols">
            <div class="px-5 py-5 overflow-hidden md:col-span-3">
                <x-jet-validation-errors class="mb-4" />
                <form id="form-notice" method="POST"
                    action="{{ route('admin.notices.offers.store',['notice'=>$notice]) }}">
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Dados da Oferta</h2>
                    </div>
                    @csrf
                    @if ($offer->id)
                    @method('put')
                    @endif
                    <x-jet-label value="Escolha um Curso" />
                    <x-select-box name="course_campus_offer_id">
                        @foreach($campus as $campi)
                        <optgroup label="{{$campi->name}}">
                            @foreach($campi->courseOffers as $c)
                            <option value="{{ $c->id }}" @if($c->id == $offer->course_campus_offer_id)
                                selected="selected" @endif>{{ $c->course->name }} - {{$c->shift->description}} - {{ $c->course->modality->description }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </x-select-box>

                    <x-jet-label value="Total de Vagas" />
                    <x-jet-input type="number" name="total_vacancies"
                        :value="old('total_vacancies') ?? $offer->total_vacancies" required />

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" @if($offer->id)
                            formaction="{{ route('admin.notices.offers.update', ['notice' => $notice,'offer'=>$offer]) }}"
                            @endif
                            class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4
                            border-blue-700 hover:border-blue-500 rounded">
                            @if ( $offer->id )
                            Atualizar
                            @else
                            Adicionar
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($offer->id)
        <div class="p-5 border-t-2 border-gray-600">
            @livewire('notice.vacancies', ['offer' => $offer])
        </div>
        @endif
    </div>

</x-manager.app-layout>
