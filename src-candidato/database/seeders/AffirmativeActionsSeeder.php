<?php

namespace Database\Seeders;

use App\Models\Process\AffirmativeAction;
use Illuminate\Database\Seeder;

class AffirmativeActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AffirmativeAction::factory(10)
            ->create();
    }
}
