<?php

namespace App\Providers;

use App\Models\Course\CampusOffer;
use App\Models\Course\Course;
use App\Models\Organization\Campus;
use App\Models\Process\DistributionOfVacancies;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use App\Policies\CampusPolicy;
use App\Models\Process\Subscription;
use App\Policies\CourseCampusOfferPolicy;
use App\Policies\CoursePolicy;
use App\Policies\DistributionOfVacancyPolicy;
use App\Policies\NoticeOfferPolicy;
use App\Policies\NoticePolicy;
use App\Policies\SubscriptionPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Campus::class => CampusPolicy::class,
        CampusOffer::class => CourseCampusOfferPolicy::class,
        Course::class => CoursePolicy::class,
        DistributionOfVacancies::class => DistributionOfVacancyPolicy::class,
        Offer::class => NoticeOfferPolicy::class,
        Notice::class => NoticePolicy::class,
        Subscription::class => SubscriptionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('managerArea', fn ($user) => $user->permissions->count()  > 0);

        Gate::define('isAdmin', fn ($user)   => $user
            ->permissions
            ->contains('role.slug', 'admin'));

        Gate::define('isAcademicRegister', fn ($user)   => $user
            ->permissions
            ->contains('role.slug', 'academic-register'));

        Gate::define('isPPICommitteMember', fn ($user)   => $user
            ->permissions
            ->contains('role.slug', 'committee'));

        Gate::define('isAcademicRegisterOrPPICommitte', fn ($user) => Gate::allows('isAcademicRegister') or Gate::allows('isPPICommitteMember'));

        Gate::define('isAcademicRegisterOrAdmin', fn ($user) => Gate::allows('isAcademicRegister') or Gate::allows('isAdmin'));

        Gate::define('isCentralCommitteeMember', fn ($user, Notice $selective)   => $user
            ->permissions
            ->where('selective_id', $selective->id)
            ->whereNull('campus_id')
            ->contains('role.slug', 'committee'));

        Gate::define('isLocalCommitteeMember', fn ($user, Notice $selective, Campus $campus)   => $user
            ->permissions
            ->where('selective_id', $selective->id)
            ->where('campus_id', $campus->id)
            ->contains('role.slug', 'committee'));
    }
}
