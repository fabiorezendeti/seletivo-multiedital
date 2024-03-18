<div x-data="curriculumScore()">    
    <template x-on:criteriaselection.window="criteria = $event.detail.criteria"></template>    
    <div class="grid grid-cols-12 md:py-5 text-gray-600">
        <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
            <span class="text-6xl text-gray-200">3</span>
        </div>

        <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
            <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                    class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                Informe a modalidade pela qual se deu a conclusão do ensino médio:
            </h2>

            @foreach($notice->getModalitiesForCurriculumAnalisys() as $key=>$modality)
            <label class="block items-center">
                <input  type="radio" x-on:change="changeModality()" class="form-radio h-8 w-8 text-green-500" name="criteria_4_modalidade"
                    x-model="modality"
                    value="{{$key}}">
                <span class="ml-4 text-lg">{{$modality->description}}</span>
            </label>
            @endforeach            
        </div>

    </div>

    <div id="modality-grades" x-show="criteria === 4 && modality > 1" class="grid grid-cols-12 md:py-5 text-gray-600">
        <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
            <span class="text-6xl text-gray-200">4</span>
        </div>

        <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
            <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                    class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                Informe suas notas nas seguintes áreas:
            </h2>

            <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                <x-jet-label value="Linguagens, Códigos e suas Tecnologias:" />
                <x-jet-input x-bind:disabled="criteria !== 4 || modality < 2" class="block mt-1 w-40" 
                    type="number" x-on:input="calculaMedia()" pattern="\d{1-4}+(,\d{2})?"
                    min="0" step=".01" x-bind:max="max" 
                    x-model="linguagens" name="criteria_4_linguagens_codigos_e_tecnologias" required />
                <x-jet-input-error for="criteria_4_linguagens_codigos_e_tecnologias" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                <x-jet-label value="Matemática e suas Tecnologias:" />
                <x-jet-input x-bind:disabled="criteria !== 4 || modality < 2" class="block mt-1 w-40" 
                    type="number" x-on:input="calculaMedia()" pattern="\d{1-4}+(,\d{2})?" 
                    min="0" step=".01" x-bind:max="max"
                    x-model="matematica" name="criteria_4_matematica_e_suas_tecnologias" required />
                <x-jet-input-error for="criteria_4_matematica_e_suas_tecnologias" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                <x-jet-label value="Ciências Humanas e suas Tecnologias:" />
                <x-jet-input x-bind:disabled="criteria !== 4 || modality < 2" class="block mt-1 w-40" 
                    type="number" x-on:input="calculaMedia()" pattern="\d{1-4}+(,\d{2})?"
                    min="0" step=".01" x-bind:max="max"
                    x-model="humanas" name="criteria_4_ciencias_humanas_e_suas_tecnologias" required />
                <x-jet-input-error for="criteria_4_ciencias_humanas_e_suas_tecnologias" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                <x-jet-label value="Ciências da Natureza e suas Tecnologias:" />
                <x-jet-input x-bind:disabled="criteria !== 4 || modality < 2" class="block mt-1 w-40" 
                    type="number" x-on:input="calculaMedia()" pattern="\d{1-4}+(,\d{2})?"
                    min="0" step=".01" x-bind:max="max"
                    x-model="natureza" name="criteria_4_ciencias_da_natureza_e_suas_tecnologias" required />
                <x-jet-input-error for="criteria_4_ciencias_da_natureza_e_suas_tecnologias" class="mt-2" />
            </div>

            <label for="" class="text-5xl">Média: </label>
            <input type="number" x-bind:disabled="criteria !== 4 || modality < 2" 
                class="text-5xl text-green-900" name="criteria_4_media_certificacao"
                pattern="\d{1-4}+(,\d{2})?" x-model="media" readonly>
            <x-jet-input-error for="criteria_4_media_certificacao" class="mt-2" />
            <p class="text-sm">A média deve ser um número de 0 a 10, a conversão é feita automaticamente</p>
        </div>
    </div>

    <div id="modality-technician" x-show="criteria === 4 && modality < 2" 
        class="grid grid-cols-12 md:py-5 text-gray-600">
        <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
            <span class="text-6xl text-gray-200">4</span>
        </div>

        <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
            <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                    class="borde-0 border-l-2 border-blue-400 pl-4"></span>
                Informe sua média geral obtida no ensino médio:
            </h2>

            <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
                <x-jet-label value="Média Geral:" />
                <x-jet-input pattern="\d{1-4}+(,\d{2})?" x-model="media" 
                    min="0" max="10" step="0.01"
                    x-bind:disabled="criteria !== 4 || modality > 1" 
                    class="block mt-1 w-40" type="number" name="criteria_4_media_regular" required />
                <x-jet-input-error for="criteria_4_media_regular" class="mt-2" />    
                <p class="text-sm">A média deve ser um número de 0 a 10</p>                
            </div>
        </div>
    </div>
</div>



@push('js')
<script>       
function curriculumScore()    {                  
        return {                 
            criteria: {{ $subscription->distributionOfVacancy->selection_criteria_id ?? 4 }},       
            modality: {{ $notice->getModalitiesForCurriculumAnalisysKeyByValue($subscription->getScore()->modalidade ?? 0)}},
            matematica: {{ $subscription->getScore()->matematica_e_suas_tecnologias ?? 'null' }},
            linguagens: {{ $subscription->getScore()->linguagens_codigos_e_tecnologias ?? 'null' }},
            humanas: {{ $subscription->getScore()->ciencias_humanas_e_suas_tecnologias ?? 'null' }},
            natureza: {{ $subscription->getScore()->ciencias_da_natureza_e_suas_tecnologias ?? 'null' }},
            media: {{ $subscription->getScore()->media ?? 0 }},
            max: (this.modality == 2) ? 1000 : 180,
            calculaMedia(){                                
                linguagens = this.linguagens ? parseFloat(this.linguagens) : 0
                humanas = this.humanas ? parseFloat(this.humanas) : 0
                natureza = this.natureza ? parseFloat(this.natureza) : 0
                matematica =  this.matematica ? parseFloat(this.matematica) : 0
                if (this.modality == 2) {
                    media = ((linguagens + humanas + natureza + matematica) /4 ) /100
                } else {
                    media = ((linguagens + humanas + natureza + matematica) /4 )
                    media = (media * 10) / 180
                }
                this.media = media.toFixed(2);
            },
            changeModality(){   
                console.log(this.modality)
                this.matematica = 0
                this.linguagens = 0
                this.humanas = 0
                this.natureza = 0
                this.media = 0
                this.max = (this.modality == 2) ? 1000 : 180
            }
        };
    }
</script>
@endpush

            