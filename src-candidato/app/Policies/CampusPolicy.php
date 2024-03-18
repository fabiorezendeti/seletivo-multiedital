<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organization\Campus;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Builder;

class CampusPolicy
{
    use HandlesAuthorization;

    
    public function updateCampus(User $user, Campus $campus)
    {        
        return $this->checkIfNotBindInEditalOffer($campus);
    }

    
    public function deleteCampus(User $user, Campus $campus)
    {
        return $this->checkIfNotBindInEditalOffer($campus);
    }

    private function checkIfNotBindInEditalOffer(Campus $campus)
    {
        $count = $campus->courseOffers()->whereHas('offers')->count();        
        return $count < 1;
    }
}
