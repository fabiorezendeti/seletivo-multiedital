<?php

namespace App\Policies;

use App\Models\Process\AffirmativeAction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AffirmativeActionPolicy
{
    use HandlesAuthorization;
     
    public function editOrDelete(User $user ,AffirmativeAction $affirmativeAction)
    {                  
        return $affirmativeAction->distributionOfVacancies()->count() < 1;
    }
}
