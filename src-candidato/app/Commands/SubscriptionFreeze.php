<?php

namespace App\Commands;

use Carbon\Carbon;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Storage;

class SubscriptionFreeze
{


    public function freeze(Subscription $subscription)
    {
        $this->save($subscription);
    }

    private function save(Subscription $subscription)
    {                        
        $subscription->distributionOfVacancy->offer->courseCampusOffer->course->modality;
        $subscription->distributionOfVacancy->offer->courseCampusOffer->campus;        
        $subscription->distributionOfVacancy->offer->courseCampusOffer->shift;
        $subscription->distributionOfVacancy->affirmativeAction;
        $subscription->user->contact;
        $subscription->freezes()
            ->create(
                ['content'=>$subscription->toJson()]
            );        
        $this->makeFile($subscription);         
    }

    private function makeFile(Subscription $subscription)
    {
        $dateTime = Carbon::now()->timestamp;
        Storage::put(
            "subscriptions/notice_{$subscription->notice->id}/{$subscription->id}/{$dateTime}.json",
            $subscription->toJson(JSON_PRETTY_PRINT));
    }


}