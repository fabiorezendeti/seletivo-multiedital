<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organization\Campus;
use App\Models\Process\ExamLocation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamLocationPolicy
{
    use HandlesAuthorization;


    public function updateExamLocation(User $user, ExamLocation $examLocation)
    {
        return $this->checkIfNotLinkWithSubscription($examLocation);
    }


    public function deleteExamLocation  (User $user, ExamLocation $examLocation)
    {
        if ($examLocation->examRooms->count() > 0) return false  ;
        return $this->checkIfNotLinkWithSubscription($examLocation);
    }

    private function checkIfNotLinkWithSubscription(ExamLocation $examLocation)
    {
        if (Gate::denies('isAdmin')) return false;
        $count = $examLocation->examRoomBookings()->whereHas('subscriptions')->count();
        return $count < 1;
    }
}
