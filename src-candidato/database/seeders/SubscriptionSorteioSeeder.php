<?php

namespace Database\Seeders;


use App\Models\Process\DistributionOfVacancies;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use App\Models\Process\Subscription;
use App\Models\User;
use App\Models\User\Contact;
use Illuminate\Database\Seeder;

class SubscriptionSorteioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notice = Notice::whereHas('selectionCriterias', function ($q) {
            $q->whereIn('id', [1, 2]);
        })->first();

        foreach ($notice->offers as $offer) {
            $distributionsVacancies = $offer->distributionVacancies()->whereHas('selectionCriteria', function ($q) {
                $q->whereIn('id', [1, 2]);
            })->get();
            foreach ($distributionsVacancies as $distributionVacancie) {
                $user = User::factory(25)->create()->each(function ($u) use ($notice, $distributionVacancie) {
                    $contact = Contact::factory()->make();
                    $u->contact()->create($contact->toArray());
                    $u->subscriptions()->create(
                        [
                            'notice_id' => $notice->id,
                            'distribution_of_vacancies_id' => $distributionVacancie->id,
                            'is_homologated' => 1,
                            'subscription_number'   => $u->id . $notice->id
                        ]
                    );
                });
            }
        }
    }
}