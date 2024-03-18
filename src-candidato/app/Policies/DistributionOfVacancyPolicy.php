<?php

namespace App\Policies;

use App\Models\Process\DistributionOfVacancies;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DistributionOfVacancyPolicy
{
    use HandlesAuthorization;

    public function deleteOrUpdate(User $user, DistributionOfVacancies $distributionOfVacancies)
    {
        return $distributionOfVacancies->subscriptions()->count() < 1;
    }
}
