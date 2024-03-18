<?php

namespace Database\Seeders;

use App\Models\Address\City;
use App\Models\Address\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $states = (config('app.env') == 'production') ? State::all() : State::whereIn('slug',['SC','PR'])->get(); 
        
        foreach ($states as $state) {
            $response = Http::get("https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$state->slug}/municipios");
            $cities = json_decode($response->getBody());
            foreach ($cities as $city) {
                $state->cities()->updateOrCreate(
                    ['ibge_code'=>$city->id],
                    ['name'=>$city->nome]
                );
            }
        }        
    }
}
