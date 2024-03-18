<x-manager.app-layout>
    <x-slot name="header">
        <x-manager.header tip="Editais">
            <x-manager.internal-navbar-itens tip="Editais" home="admin.notices.index" />
        </x-manager.header>
    </x-slot>

    <div class="overflow-x-auto">

        <div class="grid grid-cols">
            <div class="px-5 py-5 overflow-hidden md:col-span-3">
                <x-jet-validation-errors class="mb-4" />
                <form action="#" method="POST" id="delete-notice">
                    @csrf
                    @method('delete')
                </form>
                <form id="form-notice" method="POST" action="{{ route('admin.notices.store') }}">
                    @csrf
                    @if ($notice->id)
                    @method('put')
                    @endif
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Dados do Edital</h2>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-6 sm:col-span-6 md:col-span-6">
                            <x-jet-label value="Modalidade" />
                            <x-select-box name="modality_id">
                                @foreach ($modalities as $modality)
                                <option value="{{$modality->id}}" @if($modality->id === $notice->modality_id)
                                    selected="selected" @endif
                                    >{{$modality->description}}</option>
                                @endforeach
                            </x-select-box>
                        </div>
                        <div class="col-span-1 sm:col-span-1 md:col-span-1">
                            <x-jet-label value="Número:  Ex: 01/2020" />
                            <x-jet-input class="block mt-1 w-full" type="text" name="number" :value="old('number') ?? $notice->number" required autofocus />
                        </div>
                        <div class="col-span-5 sm:col-span-5 md:col-span-5">
                            <x-jet-label value="Descrição" />
                            <x-jet-input class="block mt-1 w-full" type="text" name="description" :value="old('description') ?? $notice->description" required />
                        </div>
                        <div class="col-span-6 sm:col-span-6 md:col-span-6">
                            <x-jet-label value="Detalhes" />
                            <textarea class="form-input rounded-md shadow-sm block mt-1 w-full" name="details" rows="2" required>{{ old('details') ?? $notice->details }}</textarea>
                        </div>
                        <div class="col-span-6 sm:col-span-6 md:col-span-6">
                            <x-jet-label value="Mensagem de Instrução (vai para o e-mail do candidato)" />
                            <textarea class="form-input rounded-md shadow-sm block mt-1 w-full" name="candidate_additional_instructions" rows="2">{{ old('candidate_additional_instructions') ?? $notice->candidate_additional_instructions }}</textarea>
                        </div>
                        <div class="col-span-6 sm:col-span-6 md:col-span-6 py-2">
                            <x-jet-label value="{{ __('Link') }}" />
                            <x-jet-input class="block mt-1 w-full" type="url" name="link" :value="old('link') ?? $notice->link" required />
                        </div>
                    </div>
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Período de Inscrição ou Período de Manifestação de Interesse em Vaga (SISU)</h2>
                        <p class="text-sm text-red-500 py-2">Quando o Edital é para o critério de seleção SISU o período de inscrição considera o de Manifestação de Interesse na Vaga,
                            neste caso o candidato pode via software indicar que tem interesse em concorrer e então ter sua inscrição homologada
                        </p>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-3 sm:col-span-3 md:col-span-3">
                            <x-jet-label value="{{ __('Data Inicial') }}" />
                            <x-jet-input class="block mt-1 w-full" type="date" name="subscription_initial_date" :value="old('subscription_initial_date') ?? $notice->subscription_initial_date->format('Y-m-d')" required />
                        </div>
                        <div class="col-span-3 sm:col-span-3 md:col-span-3">
                            <x-jet-label value="{{ __('Data Final') }}" />
                            <x-jet-input class="block mt-1 w-full" type="date" name="subscription_final_date" :value="old('subscription_final_date') ?? $notice->subscription_final_date->format('Y-m-d')" required />
                        </div>
                    </div>
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Período de Recursos de Classificação</h2>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-6 sm:col-span-6 md:col-span-3">
                            <x-jet-label value="{{ __('Data Inicial') }}" />
                            <x-jet-input class="block mt-1 w-full " type="date" name="classification_review_initial_date" :value="old('classification_initial_date') ?? $notice->classification_review_initial_date->format('Y-m-d')" required />
                        </div>
                        <div class="col-span-3 sm:col-span-3 md:col-span-3">
                            <x-jet-label value="{{ __('Data Final') }}" />
                            <x-jet-input class="block mt-1 w-full" type="date" name="classification_review_final_date" :value="old('classification_final_date') ?? $notice->classification_review_final_date->format('Y-m-d')" required />
                        </div>
                    </div>
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Data de Encerramento do Edital</h2>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-6 sm:col-span-6 md:col-span-6">
                            <p>A partir desta data o edital não aparece mais para os candidatos, deve ser uma data maior
                                que o período de inscrição</p>
                            <x-jet-label value="Encerrar em:" />
                            <x-jet-input class="block mt-1 w-full" type="date" name="closed_at" required :value="old('closed_at') ?? ($notice->closed_at ? $notice->closed_at->format('Y-m-d') : null)" />
                        </div>
                    </div>
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2 mt-4">Critérios de Seleção</h2>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5" x-data="selectionCriteriaManager()">
                        <div class="col-span-3 sm:col-span-3 md:col-span-3 ">
                            @can('updateCriteria', $notice)
                            @foreach($selectionCriterias as $selectionCriteria)
                            <input class="checkbox @if($selectionCriteria->id == 2) exemption_request @endif" x-on:change="check()" type="checkbox" name="selection_criteria[]" value="{{ $selectionCriteria->id }}" @if($notice->selectionCriterias->contains($selectionCriteria)) checked @endif
                            /> {{ $selectionCriteria->details }}
                            @if($selectionCriteria->is_customizable)
                            <span class="text-xs text-red-500">* </span>
                            @endif
                            <br>
                            @endforeach
                            <span class="text-xs text-red-500">* Exige customização</span>
                            @else
                                {!! $notice->selectionCriterias->implode('details','<br>')!!}
                            @endcan
                        </div>
                    </div>
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Pagamento e Cobrança</h2>
                    </div>
                    <div class="col-span-6 py-5">
                        <div class="grid grid-cols-4 gap-4">
                            <div class="col-span-1 sm:col-span-1 md:col-span-1 ">
                                <x-jet-label value="Taxa de Inscrição:" />
                                <x-jet-input class="block mt-1 w-full mask-money" type="text" name="registration_fee" :value="old('registration_fee') ?? number_format($notice->registration_fee, 2, ',','.')" />
                                <p class="text-xs">deixe 0,00 para gratuito</p>
                            </div>
                            <div class="col-span-2">
                                <x-jet-label value="Habilita PagTesouro" class="content-center" />
                                <input type="radio" value="1" name="pagtesouro_activated" {{ old('pagtesouro_activated',$notice->pagtesouro_activated) == '1' ? 'checked' : '' }} />Sim
                                <input type="radio" value="0" name="pagtesouro_activated" {{ old('pagtesouro_activated',$notice->pagtesouro_activated) == '0' ? 'checked' : '' }} />Não
                            </div>
                            <div class="col-span-2 exemption_request @if(empty($notice->selectionCriterias[0]->id) or (!empty($notice->selectionCriterias[0]->id) && $notice->selectionCriterias[0]->id != 2)) hidden @endif">
                                <div class="col-span-3 sm:col-span-3 md:col-span-3">
                                    <x-jet-label value="{{ __('Data Final Para Solicitação de Isenção') }}" />
                                    <x-jet-input class="block mt-1 w-full" type="date" name="exemption_request_final_date" :value="old('exemption_request_final_date') ?? ($notice->exemption_request_final_date ? $notice->exemption_request_final_date->format('Y-m-d') : null)" />
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-6 gap-4 py-3">
                            <div class="col-span-2 sm:col-span-2 md:col-span-2 ">
                                <x-jet-label value="Código do Favorecido" />
                                <x-jet-input class="block mt-1 w-full" type="text" required name="gru_config[codigo_favorecido]" :value="old('gru_config.codigo_favorecido') ?? $notice->gru_config['codigo_favorecido']" />
                            </div>
                            <div class="col-span-2 sm:col-span-2 md:col-span-2">
                                <x-jet-label value="Número da Gestão (UG)" />
                                <x-jet-input class="block mt-1 w-full" type="text" required name="gru_config[gestao]" :value="old('gru_config.gestao') ?? $notice->gru_config['gestao']" />
                            </div>
                            <div class="col-span-2 sm:col-span-2 md:col-span-2">
                                <x-jet-label value="Código de Correlação" />
                                <x-jet-input class="block mt-1 w-full" type="text" required name="gru_config[codigo_correlacao]" :value="old('gru_config.codigo_correlacao') ?? $notice->gru_config['codigo_correlacao']" />
                            </div>
                        </div>
                        <div class="grid grid-cols-6 gap-4 py-3">
                            <div class="col-span-4 sm:col-span-4 md:col-span-4 ">
                                <x-jet-label value="Nome do Favorecido" />
                                <x-jet-input class="block mt-1 w-full" type="text" required name="gru_config[nome_favorecido]" :value="old('gru_config.nome_favorecido') ?? $notice->gru_config['nome_favorecido']" />
                            </div>
                            <div class="col-span-2 sm:col-span-2 md:col-span-2 ">
                                <x-jet-label value="Código de Recolhimento" />
                                <x-jet-input class="block mt-1 w-full" type="text" required name="gru_config[codigo_recolhimento]" :value="old('gru_config.codigo_recolhimento') ?? $notice->gru_config['codigo_recolhimento']" />
                            </div>
                            <div class="col-span-4 sm:col-span-4 md:col-span-4 ">
                                <x-jet-label value="Nome do Serviço" />
                                <x-jet-input class="block mt-1 w-full" type="text" required name="gru_config[nome_recolhimento]" :value="old('gru_config.nome_recolhimento') ?? $notice->gru_config['nome_recolhimento']" />
                            </div>
                            <div class="col-span-2 sm:col-span-2 md:col-span-2 ">
                                <x-jet-label value="Mês de Competência (MM/YYYY)" />
                                <x-jet-input class="block mt-1 w-full" type="text" required name="gru_config[competencia]" :value="old('gru_config.competencia') ?? $notice->gru_config['competencia']" />
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-2 md:col-span-2 ">
                            <x-jet-label value="{{ __('Data Limite de Pagamento') }}" />
                            <x-jet-input class="block mt-1 w-full" type="date" name="payment_date" :value="old('payment_date') ?? (($notice->payment_date) ? $notice->payment_date->format('Y-m-d') : null)" />
                        </div>
                    </div>
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Local de Prova</h2>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-2 sm:col-span-2 md:col-span-2 ">
                            @can('updateCriteria', $notice)
                                <x-jet-label value="{{ __('Data para Disponibilização os Locais de Prova') }}" />
                                <x-jet-input class="block mt-1 w-full" type="date" name="display_exam_room_date"
                                    :value="old('display_exam_room_date') ?? (($notice->display_exam_room_date) ? $notice->display_exam_room_date->format('Y-m-d') : null)" />
                            @else
                                {!! $notice->display_exam_room_date ?? "Nenhuma data para disponibilização de local de prova definida" !!}
                            @endcan
                        </div>

                    </div>

                    <div class="exemption_request @if(empty($notice->selectionCriterias[0]->id) or (!empty($notice->selectionCriterias[0]->id) && $notice->selectionCriterias[0]->id != 2)) hidden @endif">
                        <div class="col-span-6">
                            <h2 class="border-gray-700 border-b-2">Prova</h2>
                        </div>
                    <div class="grid grid-cols-6 gap-4 py-5">

                        <div class="col-span-2 sm:col-span-2 md:col-span-2 ">
                            <x-jet-label value="{{ __('Data da Prova') }}" />
                            <x-jet-input class="block mt-1 w-full" type="date" name="exam_date" :value="old('exam_date') ?? (($notice->exam_date) ? $notice->exam_date->format('Y-m-d') : null)" />
                        </div>
                        <div class="col-span-1 sm:col-span-1 md:col-span-1 ">
                            <x-jet-label value="{{ __('Horário de início da prova') }}" />
                            <x-jet-input class="block mt-1 w-full" type="time" name="exam_time" :value="old('exam_time') ?? (($notice->exam_time) ? $notice->exam_time : null)" />
                        </div>
                        </div>
                    </div>
                    <div class="col-span-6">
                        <h2 class="border-gray-700 border-b-2">Processo de Matrícula</h2>
                    </div>
                    <div class="grid grid-cols-6 gap-4 py-5">
                        <div class="col-span-2">
                            <x-jet-label value="Habilita Matrícula pelo software de ingresso para este edital" />
                            <input type="radio" value="1" name="enrollment_process_enable" {{
                                old('enrollment_process_enable', $notice->enrollment_process_enable) == 1 ? 'checked' : '' }} />Sim
                            <input type="radio" value="0" name="enrollment_process_enable" {{
                                old('enrollment_process_enable', $notice->enrollment_process_enable) == 0 ? 'checked' : '' }} />Não
                        </div>
                    </div>
                    <div class="block items-center justify-end mt-4">
                        @if($notice->id)
                        <button type="submit" onclick="return deleteNotice()" formaction="{{route('admin.notices.destroy',['notice'=>$notice])}}" form="delete-notice" class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-red-700 hover:border-red-500 rounded float-left">
                            Excluir
                        </button>
                        @endif
                        <button type="submit" @if($notice->id)
                            formaction="{{ route('admin.notices.update', ['notice' => $notice]) }}" @endif
                            class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4
                            border-blue-700 hover:border-blue-500 rounded float-right">
                            @if ( $notice->id )
                            Atualizar
                            @else
                            Adicionar
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')

    <script>

        function deleteNotice(){
            if(confirm("ATENÇÃO! Após essa ação o edital será marcado como excluído no Banco de Dados e só poderá ser recuperado pela equipe de TI, deseja continuar?")){
                return true;
            }
            return false;
        }

        $('.checkbox.exemption_request').on("change", function (e){
            if($(this).is(':checked')){
                $('div.exemption_request').removeClass('hidden');
                //$('input[name="exemption_request_final_date"]').prop('required',true);
            }else{
                $('div.exemption_request').addClass('hidden');
                //$('input[name="exemption_request_final_date"]').prop('required',false);
            }
        });


        function selectionCriteriaManager() {
            return {
                check: function() {
                    let selectionCriterias = document.querySelectorAll("input[name='selection_criteria[]']")
                    if (selectionCriterias[5 - 1].checked) {
                        for (let i = 0; i < selectionCriterias.length; i++) {
                            if (selectionCriterias[i].value != 5) {
                                selectionCriterias[i].checked = false
                            }
                        }
                        $('div.exemption_request').addClass('hidden');
                        let classificationReviewStart = document.querySelector('input[name="classification_review_initial_date"]')
                        let classificationReviewEnd = document.querySelector('input[name="classification_review_final_date"]')
                        classificationReviewStart.value = '2020-01-01'
                        classificationReviewEnd.value = '2020-01-01'
                    }
                }
            }
        }
    </script>


    @endpush

</x-manager.app-layout>
