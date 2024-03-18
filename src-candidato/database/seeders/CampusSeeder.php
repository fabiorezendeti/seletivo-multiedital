<?php

namespace Database\Seeders;

use App\Models\Organization\Campus;
use App\Models\Address\City;
use Illuminate\Database\Seeder;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campus::factory(2)
             ->create();

        
    }
}
