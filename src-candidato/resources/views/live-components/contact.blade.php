<div>
    <div class="grid grid-cols-6 gap-4 py-5">
        <div class="col-span-6">
            <h2 class="border-gray-300 border-b-2">Seus dados de contato</h2>
        </div>
        <div class="col-span-6 md:col-span-1">
            <x-jet-label for="phone_number" value="Telefone (principal)" />
            <x-jet-input id="phone_number" type="text" class="mask-phone block mt-1 w-full" name="phone_number"
            required wire:model.defer="contact.phone_number"
                placeholder="(00) 00000-0000" />
        </div>
        <div class="col-span-6 md:col-span-2">
            <x-jet-label value="Neste número tenho:" />
            <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" name="has_whatsapp" value="1" wire:model.defer="contact.has_whatsapp" @if($contact['has_whatsapp'] ?? null) checked @endif>
                <span class="ml-2 text-sm text-gray-600">WhatsApp</span>
            </label>
            <label class="flex items-center">
                <input type="checkbox" class="form-checkbox" name="has_telegram" value="1" @if($contact['has_telegram'] ?? null) checked @endif>
                <span class="ml-2 text-sm text-gray-600">Telegram</span>
            </label>
        </div>
    </div>
    <div class="grid grid-cols-6 gap-4">
        <div class="col-span-6 md:col-span-1">
            <x-jet-label for="alternative_phone_number" value="Telefone (alternativo)" />
            <x-jet-input id="alternative_phone_number" type="text" class="mask-phone block mt-1 w-full" name="alternative_phone_number"
                wire:model.defer="contact.alternative_phone_number"
                placeholder="(00) 00000-0000" />
        </div>
    </div>
    <div class="grid grid-cols-6 gap-4 pt-5">
        <div class="col-span-6 md:col-span-4">
            <x-jet-label for="street" value="Rua" />
            <x-jet-input id="street" type="text" class="block mt-1 w-full" required name="street" maxlength="100"
            wire:model.defer="contact.street" />
        </div>
        <div class="col-span-6 md:col-span-2">
            <x-jet-label for="number" value="Número" />
            <x-jet-input id="number" type="text" class="block mt-1 w-full" name="number" maxlength="10" wire:model.defer="contact.number" />
        </div>
        <div class="col-span-6 md:col-span-4">
            <x-jet-label for="complement" value="Complemento" />
            <x-jet-input id="complement" type="text" class="block mt-1 w-full" name="complement" maxlength="100"
                wire:model.defer="contact.complement" />
        </div>
        <div class="col-span-6 md:col-span-2">
            <x-jet-label for="zip_code" value="CEP" />
            <x-jet-input id="zip_code" type="text" class="mask-cep block mt-1 w-full" required wire:model.defer="contact.zip_code"
                maxlength="10" name="zip_code" placeholder="00000-000" />
        </div>
        <div class="col-span-6 md:col-span-2">
            <x-jet-label for="district" value="Bairro" />
            <x-jet-input id="district" type="text" class="block mt-1 w-full" required name="district" maxlength="100"
                wire:model.defer="contact.district" />
        </div>
        <div class="col-span-6 md:col-span-2">
            <x-jet-label for="state" value="Estado" />
            <select name="state" class="form-input rounded-md shadow-sm block mt-1 w-full" required
                wire:model="stateSlug" id="state">
                @foreach ($states as $state)
                <option value="{{$state['slug']}}">{{$state['name']}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-span-6 md:col-span-2">
            <x-jet-label for="city" value="Cidade" />
            <div wire:loading.remove>
                <select name="city" id="city" required class="form-input rounded-md shadow-sm block mt-1 w-full"
                wire:model.defer="contact.city">
                    @foreach ($cities as $city)
                    <option value="{{$city['id']}}">{{$city['name']}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div wire:loading>
                <p> carregando ... </p>
            </div>
        </div>
    </div>
</div>
