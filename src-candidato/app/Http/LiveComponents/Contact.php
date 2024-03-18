<?php

namespace App\Http\LiveComponents;

use App\Models\Address\State;
use App\Models\User\Contact as UserContact;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Contact extends Component
{
    public $stateSlug;

    public $states;

    public $cities = [];

    public $contact = [];

    public function mount()
    {
        $user = Auth::user();
        $this->contact = (old() or !isset($user->contact)) ? old() : $user->contact->toArray();
        $this->contact['phone_number'] = session()->exists('telefone') ? session()->get('telefone') : null;
        $this->contact['city'] = $this->contact['city_id'] ?? old('city');
        $this->states = State::orderBy('name')->get()->toArray();
        $this->stateSlug =  old('state') ??  (isset($user->contact) ? $user->contact->city->state->slug  : 'SC') ;
        $this->updatedStateSlug();
    }


    public function render()
    {
        return view('live-components.contact',
            ['contact'=>$this->contact]
        );
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
