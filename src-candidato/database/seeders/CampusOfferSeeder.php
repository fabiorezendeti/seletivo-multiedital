<?php

namespace Database\Seeders;

use App\Models\Course\CampusOffer;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CampusOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 5; $i++) {
            try {
                CampusOffer::factory()->create();
            } catch (QueryException $exception) {
                Log::error($exception->getMessage(),['seed']);
            }
        }        
    }
}
