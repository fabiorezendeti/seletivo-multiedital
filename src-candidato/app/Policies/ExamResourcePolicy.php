<?php

namespace App\Policies;

use App\Models\Process\ExamResource;
use App\Models\Process\Notice;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamResourcePolicy
{
    use HandlesAuthorization;

    public function updateDescription(User $user, ExamResource $examResource)
    {
        if (Gate::denies('isAdmin')) return false;
        return $examResource->subscriptions()->count() < 1;
    }
    

    public function update(User $user, ExamResource $examResource)
    {
        return Notice::subscriptionOpened()->whereHas('selectionCriterias',function($q) {
            $q->where('id',2);
        })->count() < 1;
    }

}
