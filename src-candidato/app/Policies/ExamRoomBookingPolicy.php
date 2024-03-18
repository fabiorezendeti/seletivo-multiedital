<?php

namespace App\Policies;

use App\Models\Process\ExamLocation;
use App\Models\Process\ExamRoomBooking;
use App\Models\Process\Notice;
use App\Models\User;
use App\Models\Process\ExamRoom;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExamRoomBookingPolicy
{
    use HandlesAuthorization;

    public function updateExamRoomBooking(User $user, ExamRoomBooking $examRoomBooking)
    {
        return $this->checkIfNotLinkWithSubscription($examRoomBooking);
    }

    public function deleteExamRoomBooking  (User $user, ExamRoomBooking $examRoomBooking)
    {
        return $this->checkIfNotLinkWithSubscription($examRoomBooking);
    }

    private function checkIfNotLinkWithSubscription(ExamRoomBooking $examRoomBooking)
    {
        if (Gate::denies('isAdmin')) return false;
        $count = ExamRoomBooking::whereHas('subscriptions')->count();
        return $count < 1;
    }
}
