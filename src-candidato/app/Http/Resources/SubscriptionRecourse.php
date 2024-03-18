<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionRecourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'subscription_number'  => $this->subscription_number,
            'name'            => $this->user->name,
            'email'           => $this->user->email,
            'phone_number'    => $this->user->contact->phone_number ?? null,
            'alternative_phone_number' => $this->user->contact->alternative_phone_number ?? null,
            'city'              => $this->user->contact->city->name ?? null,
            'state'             => $this->user->contact->city->state->slug ?? null,
            'course'                  => $this->distributionOfVacancy->offer->courseCampusOffer->course->name,
            'affirmative_action'    => $this->distributionOfVacancy->affirmativeAction->slug
        ];
    }
}
