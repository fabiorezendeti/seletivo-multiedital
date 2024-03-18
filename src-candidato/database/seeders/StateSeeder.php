<?php

namespace Database\Seeders;

use App\Models\Address\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::get('https://servicodados.ibge.gov.br/api/v1/localidades/estados');
        $states = json_decode($response->getBody());
        foreach ($states as $state) {
            State::updateOrCreate(
                ['slug'=>$state->sigla],
                ['name'=>$state->nome]
            );
        }
    }
}
