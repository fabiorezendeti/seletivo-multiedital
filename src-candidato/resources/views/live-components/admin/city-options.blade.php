<div class="grid grid-cols-6 gap-4 py-5">
    <div class="col-span-6 sm:col-span-6 md:col-span-3">
        <x-jet-label value="Estado" />
        <select name="state" class="form-input rounded-md shadow-sm block mt-1 w-full" wire:model="stateSlug"
            id="state">
            @foreach ($states as $state)
            <option value="{{$state->slug}}">{{$state->getString()}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-span-6 sm:col-span-6 md:col-span-3">
        <x-jet-label value="Cidade" />
        <div wire:loading.remove>
            <select name="city_id" id="city_id" wire:model.defer="cityId"  class="form-input rounded-md shadow-sm block mt-1 w-full">
                @foreach ($cities as $city)
                <option value="{{$city['id']}}" @if(old('city') == $city['id']) @endif>{{$city['name']}}</option>
                @endforeach
            </select>
        </div>
        <div wire:loading>
            <p> carregando ... </p>
        </div>
    </div>

</div>
