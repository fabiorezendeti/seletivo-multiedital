<?php

namespace Database\Seeders;

use App\Models\Process\AffirmativeAction;
use App\Models\Process\DistributionOfVacancies;
use App\Models\Process\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Offer::factory(40)->create();

        foreach (Offer::all() as $offer) {
            foreach (AffirmativeAction::all() as $affirmative) {
                $offer->distributionVacancies()->insert([
                    [
                        'offer_id'  => $offer->id,
                        'affirmative_action_id' => $affirmative->id,
                        'selection_criteria_id' => 1,
                        'total_vacancies'   => 5
                    ],
                    [
                        'offer_id'  => $offer->id,
                        'affirmative_action_id' => $affirmative->id,
                        'selection_criteria_id' => 2,
                        'total_vacancies'   => 6
                    ],
                    [
                        'offer_id'  => $offer->id,
                        'affirmative_action_id' => $affirmative->id,
                        'selection_criteria_id' => 3,
                        'total_vacancies'   => 7
                    ],
                    [
                        'offer_id'  => $offer->id,
                        'affirmative_action_id' => $affirmative->id,
                        'selection_criteria_id' => 4,
                        'total_vacancies'   => 8
                    ]
                ]
                );
            }
        }
    }
}
