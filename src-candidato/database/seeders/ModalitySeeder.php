<?php

namespace Database\Seeders;

use App\Models\Course\Modality;
use Illuminate\Database\Seeder;

class ModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Modality::factory(5)
            ->create();
    }
}
