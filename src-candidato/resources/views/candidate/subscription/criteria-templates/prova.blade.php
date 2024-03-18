<div x-data="prova()" x-init="checkNeedDetails();checkExamResourceNeedDetails();" x-on:keydown.escape="isModalOpen=false" >
    <div class="grid grid-cols-12 md:py-5 text-gray-600">
        <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
            <span class="text-6xl text-gray-200">3</span>
        </div>
        <template x-on:criteriaselection.window="criteria = $event.detail.criteria"></template>    
        <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
            <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                    class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                Necessidade Específica
            </h2>        
            <select name="special_need_id" x-on:change="checkNeedDetails()" id="special-need" x-model="specialNeed" class="form-input rounded-md shadow-sm block mt-1 w-full">
                <option value="0">-- Nenhuma --</option>        
                @foreach($specialNeeds as $specialNeed)            
                    <option value="{{$specialNeed->id}}" @if($subscription->special_need_id == $specialNeed->id) selected @endif>{{$specialNeed->description}}</option>            
                @endforeach
            </select>
            <div x-show="requireDetails">
            <x-jet-label value="Especifique sua deficiência:" />
            <textarea name="special_need_description" x-bind:required="requireDetails" id="special-need-description" rows="5" x-model="specialNeedDescription" class="w-full form-input rounded-md shadow-sm"></textarea>
            </div>
        </div>    
    </div>    

    <div class="grid grid-cols-12 md:py-5 text-gray-600">
        <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
            <span class="text-6xl text-gray-200">4</span>
        </div>
        <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
            <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                    class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                Tempo Adicional de Prova
            </h2>        
            <x-jet-label value="Tempo Adicional de Prova"/>
            <label class="block items-center">
                            <input type="radio" class="form-radio h-8 w-8 text-green-500" name="additional_test_time"                            
                                value="0"  @if(!$subscription->additional_test_time or !old('additional_test_time')))
                            checked @endif>
                            <span class="ml-4 text-lg">Não</span>
                            <input type="radio" x-on:change="isModalOpen = true" class="form-radio h-8 w-8 text-green-500" name="additional_test_time"                            
                                value="1" @if($subscription->additional_test_time or old('additional_test_time')))
                            checked @endif>
                            <span class="ml-4 text-lg">Sim</span>
            </label>                                
        </div>
    </div>

    <div class="grid grid-cols-12 md:py-5 text-gray-600">
        <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
            <span class="text-6xl text-gray-200">5</span>
        </div>
        <template x-on:criteriaselection.window="criteria = $event.detail.criteria"></template>    
        <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
            <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                    class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                Recursos adicionais para realização de prova
            </h2>        
            <select name="exam_resource_id" x-on:change="checkExamResourceNeedDetails(); isModalOpen = true" id="exam-resource" x-model="examResource" class="form-input rounded-md shadow-sm block mt-1 w-full">
                <option value="0">-- Nenhuma --</option>        
                @foreach($examResources as $examResource)            
                    <option value="{{$examResource->id}}" @if($subscription->exam_resource_id == $examResource->id) selected @endif>{{$examResource->description}}</option>            
                @endforeach
            </select>
            <div x-show="examResourceRequireDetails">
            <x-jet-label value="Especifique:" />
            <textarea name="exam_resource_description" x-bind:required="examResourceRequireDetails" id="exam-resource-description" rows="5" x-model="examResourceDescription" class="w-full form-input rounded-md shadow-sm"></textarea>
            </div>
        </div>    
    </div>
    <!--modal tempo de prova adicional-->
    <div role="dialog" tabindex="-1" x-show="isModalOpen" x-on:click.away="isModalOpen = false" x-cloak x-transition id="confirmation-modal"
    x-bind:class="{'opacity-0 pointer-events-none': isModalOpen === false}"
    class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
    <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>

    <div class="modal-container bg-white w-11/12 md:max-w-lg mx-auto rounded shadow-lg z-50 overflow-y-auto overscroll-auto">
        <div x-on:click="isModalOpen = false"
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
                <p class="text-xl font-bold">Aviso</p>
                <div class="modal-close cursor-pointer z-50" @click="isModalOpen = false">
                    <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18"
                        height="18" viewBox="0 0 18 18">
                        <path
                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                        </path>
                    </svg>
                </div>
            </div>
            <div>
                <p> Verifique os procedimentos descritos em edital, para enviar o laudo técnico que comprove sua necessidade específica, 
                    para que sua solicitação seja aprovada.                
                </p>                    
            </div>

            <!--Footer modal-->
            <div class="flex justify-end pt-2">   
                <button type="button" x-on:click="isModalOpen = false"
                    class="modal-close ml-5 px-4 bg-green-500 p-3 rounded-lg text-white hover:bg-gray-400">OK</button>
            </div>

        </div>
    </div>
</div>
</div>

@push('js')
<script>
    function prova()
    {                
        
        return {
            requireDetails: false,     
            examResourceRequireDetails: false,    
            isModalOpen: false,       
            specialNeed: {{ $subscription->special_need_id ??  0 }},            
            specialNeedDescription: "{{ $subscription->special_need_description }}",
            examResource: {{ $subscription->exam_resource_id ??  0 }},  
            examResourceDescription: "{{ $subscription->exam_resource_description }}", 
            criteria: {{ $subscription->distributionOfVacancy->selection_criteria_id ?? $defaultCriteria }},       
            specialNeeds: @json($specialNeeds),
            examResources: @json($examResources),
            checkNeedDetails: function() {                
                if (this.specialNeed == 0) return this.requireDetails = false;
                this.requireDetails = this.specialNeeds.find(el => el.id == this.specialNeed).require_details
            },
            checkExamResourceNeedDetails: function() {                
                if (this.examResource == 0) return this.examResourceRequireDetails = false;
                this.examResourceRequireDetails = this.examResources.find(el => el.id == this.examResource).require_details;
            }
        }
    }    
</script>
@endpush

@push('css')
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
@endpush