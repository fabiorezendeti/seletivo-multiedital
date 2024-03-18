<?php

namespace App\Policies;

use App\Models\Course\CampusOffer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseCampusOfferPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    } 

    public function onlySISUCodeUpdate(User $user, CampusOffer $campusOffer)
    {
        return $campusOffer->offers->count() > 0;
    }

    public function updateOrDelete(User $user, CampusOffer $campusOffer)
    {                                  
        $count = $campusOffer->offers->count();    
        return $count < 1;
    }
}
