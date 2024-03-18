<?php

namespace App\Policies;

use App\Models\Process\Notice;
use App\Models\User;
use App\Models\Process\SpecialNeed;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecialNeedPolicy
{
    use HandlesAuthorization;

    public function updateDescription(User $user, SpecialNeed $specialNeed)
    {
        if (Gate::denies('isAdmin')) return false;
        return $specialNeed->subscriptions()->count() < 1;
    }
    

    public function update(User $user, SpecialNeed $specialNeed)
    {
        return Notice::subscriptionOpened()->whereHas('selectionCriterias',function($q) {
            $q->where('id',2);
        })->count() < 1;
    }

}
