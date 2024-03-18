<?php

namespace App\Policies;

use App\Models\Course\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
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

    public function updateOrDelete(User $user, Course $course)
    {
        $count = $course->campusesOffer()->whereHas('offers')->count();        
        return $count < 1;    
    }

}
