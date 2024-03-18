<div class="grid grid-cols-12 md:py-5 text-gray-600">
    <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
        <span class="text-6xl text-gray-200">3</span>
    </div>

    <div
        class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
        <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                class="borde-0 border-l-2 border-blue-400 pl-4"></span>
            Ano do ENEM que deseja utilizar:
        </h2>
        <x-jet-input-error for="criteria_3_ano_do_enem" class="mt-2" />

        <label class="block items-center">
            <input type="radio" x-bind:disabled="criteria !== 3" class="form-radio h-8 w-8 text-green-500" name="criteria_3_ano_do_enem" value="2017"
            @if(old('ano_do_enem') == 2017) checked @endif
            x-model="enem_ano">
            <span class="ml-4 text-lg">2017</span>
        </label>
        <label class="block items-center">
            <input type="radio" x-bind:disabled="criteria !== 3" class="form-radio h-8 w-8 text-green-500" name="criteria_3_ano_do_enem" value="2018"
            @if(old('ano_do_enem') == 2018) checked @endif
            x-model="enem_ano">
            <span class="ml-4 text-lg">2018</span>
        </label>
        <label class="block items-center">
            <input type="radio" x-bind:disabled="criteria !== 3" class="form-radio h-8 w-8 text-green-500" name="criteria_3_ano_do_enem" value="2019"
            @if(old('ano_do_enem') == 2019) checked @endif
            x-model="enem_ano">
            <span class="ml-4 text-lg">2019</span>
        </label>
        <label class="block items-center">
            <input type="radio" x-bind:disabled="criteria !== 3" class="form-radio h-8 w-8 text-green-500" name="criteria_3_ano_do_enem" value="2020"
            @if(old('ano_do_enem') == 2020) checked @endif
            x-model="enem_ano">
            <span class="ml-4 text-lg">2020</span>
        </label>
    </div>

</div>


<div x-data="enemScore()"  class="grid grid-cols-12 md:py-5 text-gray-600">
    <div class="col-span-2 px-5 py-5 text-sm border border-r-0 border-l-8 border-gray-300 rounded-md">
        <span class="text-6xl text-gray-200">4</span>
    </div>
    <template x-on:criteriaselection.window="criteria = $event.detail.criteria"></template>    
    <div class="col-span-9 px-5 py-5 text-sm border border-l-0 border-gray-300 rounded-md">
        <h2 class="text-md font-bold uppercase mb-3 text-blue-700"><span
                class="borde-0 border-l-2 border-blue-400 pl-4"></span>
            Informe suas notas nas seguintes áreas:
        </h2>

        <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
            <x-jet-label value="Linguagens, Códigos e suas Tecnologias:" />
            <x-jet-input x-bind:disabled="criteria !== 3" min="0" step=".01" max="1000.00" 
                pattern="\d{1-4}+(,\d{2})?" class="block mt-1 w-40" 
                type="number"  x-on:input="calculaMedia()"  
                x-model="linguagens" name="criteria_3_linguagens_codigos_e_tecnologias" required  />
            <x-jet-input-error for="criteria_3_linguagens_codigos_e_tecnologias" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
            <x-jet-label value="Matemática e suas Tecnologias:" />
            <x-jet-input x-bind:disabled="criteria !== 3" class="block mt-1 w-40" 
                type="number" min="0" step=".01" max="1000.00"  x-on:input="calculaMedia()" 
                pattern="\d{1-4}+(,\d{2})?" x-model="matematica" 
                name="criteria_3_matematica_e_suas_tecnologias"  required />
            <x-jet-input-error for="criteria_3_matematica_e_suas_tecnologias" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
            <x-jet-label value="Ciências Humanas e suas Tecnologias:" />
            <x-jet-input x-bind:disabled="criteria !== 3"  class="block mt-1 w-40" type="number"  
                min="0" step=".01" max="1000.00"
                x-on:input="calculaMedia()" pattern="\d{1-4}+(,\d{2})?"
                x-model="humanas" name="criteria_3_ciencias_humanas_e_suas_tecnologias" required  />
            <x-jet-input-error for="criteria_3_ciencias_humanas_e_suas_tecnologias" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
            <x-jet-label value="Ciências da Natureza e suas Tecnologias:" />
            <x-jet-input x-bind:disabled="criteria !== 3" class="block mt-1 w-40" type="number"  
                x-on:input="calculaMedia()" pattern="\d{1-4}+(,\d{2})?"
                min="0" step=".01" max="1000.00"
                x-model="natureza" name="criteria_3_ciencias_da_natureza_e_suas_tecnologias" required  />
            <x-jet-input-error for="criteria_3_ciencias_da_natureza_e_suas_tecnologias" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-6 md:col-span-3 pb-5">
            <x-jet-label value="Redação:" />
            <x-jet-input x-bind:disabled="criteria !== 3" class="block mt-1 w-40" type="number"  
                x-on:input="calculaMedia()" pattern="\d{1-4}+(,\d{2})?"
                min="0" step=".01" max="1000.00"
                x-model="redacao" name="criteria_3_redacao" required  />
            <x-jet-input-error for="criteria_3_redacao" class="mt-2" />
        </div>
    
        <label for="" class="text-5xl">Média: </label>        
        <input type="number" x-bind:disabled="criteria !== 3"  class="text-5xl text-green-900"  
            min="0" step=".01" max="1000.00"
            name="criteria_3_media" pattern="\d{1-4}+(,\d{2})?" x-model="media" readonly>
        <x-jet-input-error for="criteria_3_media" class="mt-2" />
    </div>
</div>


@push('js')
<script>
    function enemScore()
    {        
        return {                
            criteria: {{ $subscription->distributionOfVacancy->selection_criteria_id ?? $defaultCriteria }},     
            matematica: {{ $subscription->getScore()->matematica_e_suas_tecnologias ?? 'null' }},
            linguagens: {{ $subscription->getScore()->linguagens_codigos_e_tecnologias ?? 'null' }},
            humanas: {{ $subscription->getScore()->ciencias_humanas_e_suas_tecnologias ?? 'null' }},
            natureza: {{ $subscription->getScore()->ciencias_da_natureza_e_suas_tecnologias ?? 'null' }},
            redacao: {{ $subscription->getScore()->redacao ?? 'null' }},
            media: {{ $subscription->getScore()->media ?? 0}},
            calculaMedia(){                                
                linguagens = this.linguagens ? parseFloat(this.linguagens) : 0
                humanas = this.humanas ? parseFloat(this.humanas) : 0
                natureza = this.natureza ? parseFloat(this.natureza) : 0
                matematica =  this.matematica ? parseFloat(this.matematica) : 0
                redacao =  this.redacao ? parseFloat(this.redacao) : 0
                media = (linguagens + humanas + natureza + matematica + redacao) /5
                this.media = media.toFixed(2);
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