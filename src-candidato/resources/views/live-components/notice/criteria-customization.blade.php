<div x-data="formFields()">
    <div class="grid grid-cols-12">
        <div class="col-span-3">
            <div class="border-2 bg-gray-500 p-2" x-show="!checkCalc()">
                <h2 class="text-xl text-white">Customizar cálculo</h2>
                <x-select-box wire:model="customization.calc" x-on:change="showWeight($event)" required>
                    <option x-show="checkCalc() === null">-- selecione um método --</option>
                    <option value="arithmetic">Média Aritmética</option>
                    <option value="weighted">Média Ponderada</option>
                </x-select-box>                
            </div>                        
            <div class="border-2 border-gray-400 p-2" x-show="checkCalc()">
                Cálculo: {{ $customization['calc'] }}
                <form wire:submit.prevent="addProperty">
                    <h3 class="text-lg">Adicionar Propriedade</h3>
                    <x-jet-label value="Nome do Campo" />
                    <x-jet-input type="text" wire:model.defer="input.name" required />
                    <x-jet-label value="Tipo" />
                    <x-select-box wire:model.defer="input.type" x-on:change="checkFields($event)" required>
                        <option value="text">campo de texto</option>
                        <option value="textarea">campo de texto longo
                        </option>
                        <option value="select">campo de seleção</option>
                        <option value="number">campo numérico</option>
                    </x-select-box>
                    <div x-show="select">
                        <x-jet-label value="Coloque as opções separadas por vírgula (,)" />
                        <x-jet-input type="text" wire:model.defer="input.select.values" />
                    </div>
                    <div x-show="format">
                        <x-jet-label value="Casas Decimais" />
                        <x-jet-input type="text" min="0" max="2" wire:model.defer="input.number.decimals" />
                        <x-jet-label value="Valor Mínimo" />
                        <x-jet-input type="text" wire:model.defer="input.number.min" />
                        <x-jet-label value="Valor Máximo" />
                        <x-jet-input type="text" wire:model.defer="input.number.max" />
                        <x-jet-label value="Ordem de desempate (0 para não ser usado)" />
                        <x-jet-input type="text" min="0" max="3" wire:model.defer="input.number.tiebreaker" />
                        <div x-show="calc ==='weighted'">
                            <x-jet-label
                                value="Peso (usado para o cálculo da nota / média), deixe 0 para usar média aritmética" />
                            <x-jet-input type="text" min="0" max="3" wire:model.defer="input.number.weight" />
                        </div>
                    </div>

                    <x-jet-label value="Texto de Ajuda" />
                    <textarea wire:model.defer="input.help" required
                        class="form-input rounded-md shadow-sm w-full"></textarea>

                    <x-jet-label value="Restrições" />
                    <input type="checkbox" value="1" wire:model.defer="input.rules.required"> Obrigatório

                    <x-jet-label value="É usado como pontuação?" />
                    <input type="checkbox" value="1" wire:model.defer="input.rules.is_score"> Sim
                    <x-jet-button type="submit" class="w-full">
                        Adicionar Propriedade
                    </x-jet-button>
                </form>
            </div>
        </div>
        <div class="col-span-3 ml-5 p-2 border-2 border-gray-500 border-dashed">
            <h2 class="text-xl">Preview</h2>
            <p class="text-xs">Infelizmente as máscaras e o cálculo ainda não funcionam no preview</p>
            @foreach ($properties as $item)
            {!! unserialize($item)->render() !!}<br>
            {!! unserialize($item)->renderTieBreakerMessage() !!}<br>
            @endforeach
        </div>
        <div class="col-span-6 ml-5 p-2 border-2 border-gray-500 border-dashed text-white bg-black text-xs">
            <h2 class="text-xl">HTML</h2>
            @foreach ($properties as $item)
            {{ unserialize($item)->render() }} <br>
            @endforeach
        </div>
    </div>
    <button type="button" wire:click="saveCustomization"
        class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 m-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
        Finalizar Customização
    </button>
    <script>
        function formFields()
        {
            return { 
                format: true, 
                calc: null, 
                select: false,
                showWeight(evt) { 
                    this.calc = evt.target.value
                },
                checkCalc() {
                    return this.calc !== null
                },
                checkFields(evt) {
                    switch (evt.target.value) {
                        case 'select':
                            this.format = false
                            this.select = true
                            break
                        case 'number':
                            this.format = true
                            this.select = false
                            break
                        default:
                            this.format = false
                            this.select = false                                            
                    }
                }
            }
        }
             
    
    </script>
</div>
