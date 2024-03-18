<?php

namespace Tests\Feature\SorteioTest;


use Tests\TestCase;
use App\Models\User;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use App\Models\User\Contact;
use Illuminate\Support\Facades\DB;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateSubscriptionsTest extends TestCase
{

    use WithFaker;

    protected $userWithPermission;
    protected $userWithoutPermission;
    protected $notice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userWithoutPermission = User::whereDoesntHave('permissions')
            ->whereNotNull('is_foreign')
            ->first();
        $this->userWithPermission = User::whereHas('permissions', function ($query) {
            $query->where('role_id', 1);
        })->first();
        $this->notice =  Notice::subscriptionOpened()->whereHas('selectionCriterias',function($q){
            $q->where('id',1);
        })->orderBy('id', 'desc')->first();
    }

    public function testNoticeAccess()
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('notice.show', ['notice' => $this->notice]));

        $response->assertStatus(200);
    }

    public function testCreateNewSubscription()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('candidate.subscription.create', [
                'notice' => $this->notice,
                'offer' => $this->notice->offers()->orderBy('id','desc')->first()
            ]));
        
        $response->assertStatus(200);
    }


    public function testCreateNewSubscriptionStore()
    {
        $user = User::factory()->create();
        $contact = Contact::factory()->make();
        $user->contact()->create($contact->toArray());
        
        $offer = $this->notice->offers()->orderBy('id', 'desc')->first();

        $response = $this->actingAs($user)
            ->post(route('candidate.subscription.store', [
                'notice' => $this->notice,
                'offer' => $offer
            ]), [
                'selection_criteria' => 1,
                'distribution_of_vacancies' => $offer->distributionVacancies()->first()->id,
                'check_affirmative_action' => 1
            ]);
        $response->assertRedirect();
        $this->assertTrue($user->subscriptions()->where('notice_id', $this->notice->id)->exists());
    }    


    public function testLastSubscriptionTicket()
    {
        $subscription = Subscription::orderBy('id', 'desc')->first();
        $user = $subscription->user;

        $response = $this->actingAs($user)
            ->get(route('candidate.subscription.show', [
                'subscription' => $subscription
            ]));
        $response->assertSeeText($subscription->subscription_number);
        $response->assertSeeText($subscription->user->cpf);
    }

    public function testUpdateSubscriptionAccess()
    {
        $subscription = Subscription::orderBy('id', 'desc')->first();
        $user = $subscription->user;
        $this->notice = $subscription->notice;
        $subscription_final_date_temp = $this->notice->subscription_final_date;
        $this->notice->subscription_final_date = now()->addDay(-1);
        $this->notice->save();

        $response = $this->actingAs($user)
            ->get(route('candidate.subscription.create',  [
                'notice' => $this->notice,
                'offer' => $this->notice->offers()->first()
            ]));
        $response->assertStatus(403);

        $this->notice->subscription_final_date = $subscription_final_date_temp;
        $this->notice->save();
    }

    public function testUpdateSubscriptionStore()
    {
        $user = $this->notice->subscriptions()->first()->user;
        $offer = $this->notice->offers()->orderBy('id', 'desc')->first();
        $subscription_final_date_temp = $this->notice->subscription_final_date;
        $this->notice->subscription_final_date = now()->addDay(-1);
        $this->notice->save();

        $response = $this->actingAs($user)
            ->post(route('candidate.subscription.store', [
                'notice' => $this->notice,
                'offer' => $offer
            ]), [
                'selection_criteria' => 1,
                'distribution_of_vacancies' => $offer->distributionVacancies()->first()->id,
                'check_affirmative_action' => 1
            ]);
        $response->assertStatus(403);
        $this->notice->subscription_final_date = $subscription_final_date_temp;
        $this->notice->save();
    }

    public function testDistributedLotteryNumberWithSubscriptionPeriodOpen()
    {
        $user = $this->notice->subscriptions()->first()->user;

        $subscription_final_date_temp = $this->notice->subscription_final_date;
        $this->notice->subscription_final_date = now()->addYear(+1);
        $this->notice->save();

        $response = $this->actingAs($user)
            ->put(route('admin.notices.distribute-lottery-number', [
                'notice' => $this->notice
            ]));
        $response->assertStatus(403);
        $this->notice->subscription_final_date = $subscription_final_date_temp;
        $this->notice->save();
    }

    public function testDistributedLotteryNumber()
    {

        $notice = Notice::whereHas('selectionCriterias', function ($q) {
            $q->where('description', 'Sorteio');
        })
            ->whereHas('subscriptions')
            ->orderBy('id', 'desc')->first();
        $subscription_final_date_temp = $notice->subscription_final_date;
        $this->notice->subscription_final_date = now()->addDay(-1);
        $this->notice->save();

        Schema::connection('pgsql-chef')
            ->dropIfExists($notice->getLotteryTable());

        $response = $this->actingAs($this->userWithPermission)
            ->put(route('admin.notices.distribute-lottery-number', [
                'notice' => $notice
            ]));
        $response->assertSessionHas('success');
        $notice->subscription_final_date = $subscription_final_date_temp;
        $notice->save();
    }
}
