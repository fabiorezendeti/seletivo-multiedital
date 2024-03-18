<?php

namespace Tests\Feature\SorteioTest;

use Tests\TestCase;
use App\Models\User;
use App\Models\Process\Offer;
use App\Models\Process\Notice;
use Illuminate\Support\Facades\DB;
use App\Models\Process\Subscription;
use App\Repository\CampusRepository;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Process\DistributionOfVacancies;

class NoticeReportTest extends TestCase
{

    use WithFaker;

    protected $userWithPermission;
    protected $userWithoutPermission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userWithoutPermission = User::whereDoesntHave('permissions')
            ->whereNotNull('is_foreign')
            ->first();
        $this->userWithPermission = User::whereHas('permissions', function ($query) {
            $query->where('role_id', 1);
        })->first();
    }

    public function testCandidateVacanciesReportNoAdmin()
    {
        $notice = Notice::whereHas('subscriptions')->first();

        $response = $this->actingAs($this->userWithoutPermission)
            ->get(route('admin.notices.candidatesForVacancies.report', [
                'notice'    => $notice
            ]));

        $response->assertStatus(403);
    }

    public function testCandidateVacanciesReport()
    {
        $notice = Notice::whereHas('subscriptions')->first();

        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.candidatesForVacancies.report', [
                'notice'    => $notice
            ]));

        $response->assertStatus(200);

        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.candidatesForVacancies.report', [
                'notice'    => $notice,
                'html'  => 1
            ]));

        $campus = (new CampusRepository())->getCampusesByNotice($notice);
        $response->assertOk()->assertSeeText($campus[0]->name);

        $vacancies =  
        
        DistributionOfVacancies::select(DB::raw('sum (total_vacancies) as total'))
        ->whereHas('offer', function ($query) use ($notice) {
            $query->where('notice_id', $notice->id);
        })
            ->whereHas('offer.courseCampusOffer', function ($q) use ($campus) {
                $q->where('campus_id', $campus[0]->id);
            })->first()->total;

        $response->assertSeeText($vacancies);
    }

    public function testContactsReport()
    {
        $notice = Notice::whereHas('subscriptions')->first();
        $campus = $notice->subscriptions()
            ->first()->distributionOfVacancy
            ->offer
            ->courseCampusOffer
            ->campus;
        

        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.contact.report', [
                'notice'    => $notice
            ]));

        $response->assertStatus(200);

        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.contact.report', [
                'notice'    => $notice,
                'campus'    => $campus->id,
                'html'  => 1
            ]));

        $dV = DistributionOfVacancies::whereHas('offer', function ($query) use ($notice) {
            $query->where('notice_id', $notice->id);
        })
            ->whereHas('subscriptions')
            ->whereHas('offer.courseCampusOffer', function ($q) use ($campus) {
                $q->where('campus_id', $campus->id);
            })->first();        

        $s = $dV->subscriptions()->first();        
        
        $response->assertOk()->assertSeeText($s->user->name);
    }

    public function testCandidatesByCityReport()
    {
        $notice = Notice::whereHas('subscriptions',function($q) {
            $q->whereHas('user.contact');
        })->first();        

        
        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.totalCandidatesByCities.report', [
                'notice'    => $notice,                
            ]));

        $response->assertStatus(200);

        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.totalCandidatesByCities.report', [
                'notice'    => $notice,
                'html'  => 1
            ]));

        $s = $notice->subscriptions()
            ->whereHas('user.contact')        
            ->first();
                
        $response->assertOk()->assertSeeText($s->user->contact->city->name);
        
    }

    public function testPPICandidatesReport()
    {
        $notice = Notice::whereHas('subscriptions',function($q) {
            $q->whereHas('user.contact');
        })->first();        

        
        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.affirmative-actions-ppi.report', [
                'notice'    => $notice,                
            ]));

        $response->assertStatus(200);                

        $user = User::factory()->create();        
        $user->subscriptions()->create(
                [
                    'notice_id' => $notice->id,
                    'distribution_of_vacancies_id' => DistributionOfVacancies::
                        whereHas('affirmativeAction',function($q) {
                            $q->where('is_ppi',true);
                        })
                        ->whereHas('offer',function($q) use ($notice){
                            $q->where('notice_id',$notice->id);
                        })->first()->id,
                    'is_homologated' => 1,
                ]
            );        

        $subscription = $notice->subscriptions()->whereHas(
            'distributionOfVacancy.affirmativeAction',function($q) {
                $q->where('is_ppi',true);
            }
        )->first();

        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.affirmative-actions-ppi.report', [
                'notice'    => $notice,
                'campus' => $subscription->distributionOfVacancy->offer->courseCampusOffer->campus_id,
                'html'  => 1
            ]));
        
                
        $response->assertOk()->assertSeeText($subscription->user->name);
        
    }

}
