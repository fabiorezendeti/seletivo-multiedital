@extends('layouts.candidate.app')
@section('content-app')
<span class="text-2xl font-open-sans uppercase font-bold">Inscrição</span>
@php
$defaultCriteria = $subscription->distributionOfVacancy->selection_criteria_id ?? $notice->selectionCriterias->first()->id ;
@endphp
<div x-data="distributionVacancies()"
    x-init="getByCriteria({{$defaultCriteria}});$dispatch('criteriaselection', { criteria: {{$defaultCriteria}} })"
    class="grid grid-cols-12 gap-4 md:py-5 text-gray-600">

    <div class="col-span-12 md:col-span-12 border border-t-4 border-green-700 rounded-md px-5 py-2 leading-loose">
        <h2 class="text-md font-bold uppercase mb-3 text-green-700"><span
                class="borde-0 border-l-2 border-green-400 pl-4"></span>
            Dados da inscrição
        </h2>

        <p>Você está se inscrevendo para {{ $notice->description }}:</p>
        <p><b>EDITAL:</b> <span>{{ $notice->number }}</span></p>
        <p><b>CURSO:</b> <span>{{ $offer->courseCampusOffer->course->name }}</span></p>
        <p><b>Campus: {{ $offer->courseCampusOffer->campus->name }}</b></p>

        <x-error-message />
        @include('candidate.subscription.subscription-exists-message')
        <form id="subscription-form"
            action="{{ route('candidate.subscription.store',['notice'=>$notice,'offer'=>$offer]) }}" method="POST"
            x-on:submit.prevent="subscribe($event)" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-12 md:py-5 text-gray-600">
                <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
                    <span class="text-6xl text-gray-200">1</span>
                </div>

                <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
                    <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                            class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                        Escolha o critério de seleção
                    </h2>
                    <div class="w-full flex justify-end">
                        <x-jet-input-error for="selection_criteria" class="mt-2" />
                        <x-modal title="Critérios de seleção"
                            class="modal-open float-right bg-gray-400 hover:bg-gray-500 text-sm text-white font-bold px-2 mr-3 rounded-md inline-flex items-center my-1"
                            buttonText="Saiba Mais">
                            <x-slot name="buttonIcon">
                                <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </x-slot>
                            <p>
                                Para verificar os procedimentos utilizados para seleção de estudantes nos processos
                                seletivos de ingresso, acesse
                                <a href="https://ingresso.ifc.edu.br/category/perguntas-frequentes/criterios-selecao/"
                                    class="underline" target="_blank">
                                    https://ingresso.ifc.edu.br/category/perguntas-frequentes/criterios-selecao/
                                </a>
                            </p>
                        </x-modal>
                    </div>
                    @if($selectionCriterias->count() > 1)
                    @foreach($notice->selectionCriterias as $selectionCriteria)
                    <label class="block items-center">
                        <input type="radio" class="form-radio h-8 w-8 text-green-500" name="selection_criteria"
                            x-model="criteria"
                            x-on:change="getByCriteria({{$selectionCriteria->id}});$dispatch('criteriaselection', { criteria: {{$selectionCriteria->id}} })"
                            value="{{ $selectionCriteria->id }}" @if(old('selection_criteria')==$selectionCriteria->id)
                        checked @endif>
                        <span class="ml-4 text-lg">{{ $selectionCriteria->details }}</span>
                    </label>
                    @endforeach
                    @else
                    @php
                    $selectionCriteria = $selectionCriterias->first()
                    @endphp
                    <label class="block items-center">
                        <input type="radio" class="form-radio h-8 w-8 text-green-500" name="selection_criteria"
                            value="{{ $selectionCriteria->id }}" checked readonly>
                        <span class="ml-4 text-lg">{{ $selectionCriteria->description }}</span>
                    </label>
                    @endif
                </div>

            </div>

            <div class="grid grid-cols-12 md:py-5 text-gray-600">
                <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
                    <span class="text-6xl text-gray-200">2</span>
                </div>

                <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
                    <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                            class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                        Escolha se deseja concorrer por Ação Afirmativa ou Ampla Concorrência
                    </h2>
                    <div class="w-full flex justify-end">
                        <x-jet-input-error for="distribution_of_vacancies" class="mt-2" />
                        <x-modal title="Ações Afirmativas"
                            class="modal-open float-right bg-gray-400 hover:bg-gray-500 text-sm text-white font-bold px-2 mr-3 rounded-md inline-flex items-center my-1"
                            buttonText="Saiba Mais">
                            <x-slot name="buttonIcon">
                                <svg class="fill-current w-4 h-4 mr-2 float-left" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </x-slot>
                            <p>Caso tenha dúvidas sobre qual ação afirmativa selecionar,
                                acesse o
                                <a href="https://ingresso.ifc.edu.br/category/acoes-afirmativas-cotas/" target="_blank"
                                    class="underline">
                                    Portal de Ingresso - Ações Afirmativas
                                    (https://ingresso.ifc.edu.br/category/acoes-afirmativas-cotas/)
                                </a>
                                e confira nossos vídeos explicativos
                            </p>
                        </x-modal>
                    </div>


                    <template x-for="item in affirmativeActions" :key="item">
                        <div class="py-5">
                            <label class="block items-center">
                                <input type="radio" :value="item.id" class="form-radio h-8 w-8 text-green-500"
                                    x-on:change="distributionAffirmativePreSelected = item.affirmative_action_id"
                                    name="distribution_of_vacancies" x-model="distributionVacancy" />
                                <span class="ml-4 text-md md:text-lg"
                                    x-text="item.affirmative_action.slug+' - '+item.affirmative_action.description">
                                </span>
                            </label>
                        </div>
                    </template>

                    <div class="bg-red-100 rounded border-red-700 border p-4 text-red-700">
                        <label class="block items-center">
                            <input type="checkbox" class="form-checkbox h-8 w-8 " required
                                name="check_affirmative_action" value="1" x-model="declaration">
                            <span class="ml-2 ">Declaro estar ciente e de acordo com os critérios exigidos para me
                                candidatar a concorrer uma vaga na respectiva ação afirmativa selecionada</span>
                            <x-jet-input-error for="check_affirmative_action" class="mt-2" />
                        </label>
                    </div>

                </div>
            </div>

            @if($notice->hasEnem())
            <div x-bind:class="{'hidden': criteria !== 3}" class="hidden">
                @include('candidate.subscription.criteria-templates.enem')
            </div>
            @endif
            @if($notice->hasCurriculum())
            <div x-bind:class="{'hidden': criteria !== 4}" class="hidden">
                @include('candidate.subscription.criteria-templates.curriculum')
            </div>
            @endif
            @if($notice->hasProva())
            <div x-bind:class="{'hidden': criteria !== 2}" class="hidden">
                @include('candidate.subscription.criteria-templates.prova')
            </div>
            @endif
            @include('candidate.subscription.send-document')

            <div>
                <a href="/dashboard"
                    class="bg-orange-500 hover:bg-orange-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="uppercase">Voltar</span>
                </a>
                <button type="submit" x-bind:disabled="submit"
                    class="float-right bg-green-500 hover:bg-green-700 text-sm text-white font-bold py-2 px-3 mr-3 rounded-md inline-flex items-center my-1">
                    <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="uppercase">Finalizar inscrição</span>
                </button>
            </div>

            <div x-show="confirmationShow" id="confirmation-modal"
                x-bind:class="{'opacity-0 pointer-events-none': confirmationShow === false}"
                class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
                <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

                <div
                    class="modal-container bg-white w-11/12 md:max-w-lg mx-auto rounded shadow-lg z-50 overflow-y-auto overscroll-auto">
                    <div x-on:click="confirmationShow = false"
                        class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
                        <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 18 18">
                            <path
                                d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                            </path>
                        </svg>
                    </div>

                    <div class="modal-content py-4 text-left px-6 overflow-y-auto h-auto">
                        <div class="flex justify-between items-center pb-3">
                            <p class="text-xl font-bold">Tem certeza que deseja continuar? </p>
                            <div class="modal-close cursor-pointer z-50" @click="confirmationShow = false">
                                <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18"
                                    height="18" viewBox="0 0 18 18">
                                    <path
                                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                                    </path>
                                </svg>
                            </div>
                        </div>


                        <div>
                            <p> Prezado candidato, você está se inscrevendo para o edital
                                <span x-text="notice.number"></span>
                                dos Cursos <span x-text="notice.description" /> </span>
                            </p>
                            <p x-text="notice.details" class="text-xs">
                            </p>                            
                        </div>

                        <!--Footer-->
                        <div class="flex justify-end pt-2">
                            <button type="button" @click="confirmationShow = false" x-bind:disabled="submit"
                                class="modal-close px-4 bg-red-500 p-3 rounded-lg text-white hover:bg-gray-400">NÃO</button>
                            <button type="button" x-on:click="confirm" x-bind:disabled="submit"
                                class="modal-close ml-5 px-4 bg-green-500 p-3 rounded-lg text-white hover:bg-gray-400">SIM</button>
                        </div>

                    </div>
                </div>
            </div>

        </form>

    </div>



</div>

@endsection

@push('js')
<script>
    function distributionVacancies() {                
        return {
            submit: false,
            confirmationShow: false,   
            declaration: 0,         
            notice: {
                number: "{{ $notice->number }}",
                description: "{{$notice->description}}",
                details: "{{$notice->details}}"
            },
            enem_ano: {{ $subscription->getScore()->ano_do_enem ?? 2017 }},
            criteria: {{  $subscription->distributionOfVacancy->selection_criteria_id ?? 0 }},
            criteriaOld: {{ $subscription->distributionOfVacancy->selection_criteria_id ?? 'null' }},
            hasDocs: {{ $subscription->hasSupportingDocuments() ? 'true' : 'false' }},
            distributionVacancy: {{  $subscription->distributionOfVacancy->id ?? 0 }},
            distributionAffirmativePreSelected: {{  $subscription->distributionOfVacancy->affirmative_action_id ?? 0 }},
            affirmativeActions: [],
            data: @json($offer->distributionVacancies),
            getByCriteria: function(criteria) {
                this.criteria = criteria
                affirmativeAction = []
                for ( i = 0; i < this.data.length; i++) {
                    if(this.data[i].selection_criteria_id == criteria) {
                        affirmativeAction.push(this.data[i])                                                         
                        if (this.distributionAffirmativePreSelected === this.data[i].affirmative_action_id) {                            
                            this.distributionVacancy = this.data[i].id
                        }
                    }
                }
                if (this.distributionAffirmativePreSelected == 0) {                    
                    this.distributionVacancy = affirmativeAction[0].id
                }
                this.affirmativeActions = affirmativeAction                 
            },
            subscribe: function(event) {
                if (this.declaration === 0) return;                                
                this.confirmationShow = true;                  
                if (!this.distributionVacancy) {
                    window.alert('Ação afirmativa é obrigatória');
                    this.submit = false;
                    return;
                }                
            },
            confirm: function() {                
                this.confirmationShow = false;
                this.submit = true;                                    
                let form = document.querySelector('#subscription-form')
                form.submit()
            }            
        }
    }        

</script>
@endpush