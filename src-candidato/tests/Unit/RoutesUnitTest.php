<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class RoutesUnitTest extends TestCase
{

    protected $routes = [
        'admin.affirmative-actions.store',
        'admin.affirmative-actions.index',
        'admin.viewBoletim',
        'admin.affirmative-actions.create',
        'admin.affirmative-actions.update',
        'admin.affirmative-actions.destroy',
        'admin.affirmative-actions.edit',
        'admin.affirmative-actions.migration-vacancy-map.store',
        'admin.affirmative-actions.migration-vacancy-map.index',
        'admin.campuses.index',
        'admin.campuses.store',
        'admin.campuses.create',
        'admin.campuses.update',
        'admin.campuses.destroy',
        'admin.campuses.edit',
        'admin.courses.store',
        'admin.courses.index',
        'admin.courses.create',
        'admin.courses.show',
        'admin.courses.update',
        'admin.courses.destroy',
        'admin.courses.edit',
        'admin.modalities.store',
        'admin.modalities.index',
        'admin.modalities.create',
        'admin.modalities.destroy',
        'admin.modalities.update',
        'admin.modalities.edit',
        'admin.notices.store',
        'admin.notices.index',
        'admin.notices.create',
        'admin.notices.show',
        'admin.notices.update',
        'admin.notices.destroy',
        'admin.notices.edit',
        'admin.notices.offers.store',
        'admin.notices.offers.create',
        'admin.notices.offers.update',
        'admin.notices.offers.edit',
        'admin.users.index',
        'admin.users.store',
        'admin.users.update',
        'admin.users.destroy',
        'admin.users.edit',
        'admin.notices.mail-send.edit',
        'admin.notices.mail-send.sender',
        'admin.notices.candidatesForVacancies.report',
        'admin.notices.contact.report',
        'admin.notices.distribute-lottery-number',
        'admin.notices.recourses.index',
        'admin.notices.recourses.store',
        'admin.notices.recourses.create',
        'admin.notices.recourses.show',
        'admin.notices.recourses.update',
        'admin.notices.recourses.destroy',
        'admin.notices.recourses.edit',
        'admin.notices.subscriptions.index',
        'admin.notices.subscriptions.show',
        'admin.notices.subscriptions.cancel',        
        'admin.notices.subscriptions.homologate',
        'admin.notices.totalByAffirmativeActions.report',
        'admin.notices.totalCandidatesByCities.report',
        'admin.notices.totalSubscriptions.report',
        'admin.notices.affirmative-actions-ppi.report',
        'candidate.subscription.store',
        'candidate.subscription.create',
        'candidate.subscription.show',
        'candidate.subscription.request-recourse',
        'candidate.viewBoletim',
        'cra.viewBoletim',
        'dashboard',
        'dev.single',
        'dev.single',
        'dev.single',
        'dev.single',
        'livewire.preview-file',
        'livewire.upload-file',
        'login',
        'logout',
        'manager.index',
        'notice.show',
        'register',
        'password.update',
        'password.reset',
        'password.request',
        'password.email',
        'profile.show',
        'password.confirm',
        'password.confirmation',
        'two-factor.login',
        'user.contact.update',
        'user.contact.edit',
        'user-password.update',
        'user-profile-information.update',
        #'verification.send',
        #'verification.notice',
        #'verification.verify',        
    ];


    public function testIndexRouteExists()
    {
        foreach ($this->routes as $route) {
            $exists = Route::has($route);              
            if (!$exists) dd($route)  ;      
            $this->assertTrue($exists);
        }        
    }
}
