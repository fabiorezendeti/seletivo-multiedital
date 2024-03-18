<?php

namespace App\Http\LiveComponents;

use App\Models\Address\City;
use App\Models\Address\State;
use Livewire\Component;

class CityOptions extends Component
{

    public $cityId;

    public $stateSlug;

    public $states;

    public $cities = [];

    public function mount($cityId = null)
    {                        
        $city = ($cityId) ? City::find($cityId) : new City();
        $this->cityId =  $city->id;
        $this->states = State::orderBy('name')->get();
        $this->stateSlug =  old('state') ??  (($city->id) ? $city->state->slug  : 'SC');                
        $this->cityId = old('city_id') ?? $this->cityId;
        $this->updatedStateSlug();
    }


    public function render()
    {
        return view('live-components.admin.city-options');
    }

    public function updatedStateSlug()
    {
        $this->cities = State::where('slug',$this->stateSlug)
        ->firstOrFail()
        ->cities()
        ->orderBy('name')
        ->get()->toArray();
    }


}
