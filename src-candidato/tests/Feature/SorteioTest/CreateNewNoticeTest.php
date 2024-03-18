<?php

namespace Tests\Feature\SorteioTest;

use App\Http\LiveComponents\Notice\Vacancies;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Process\Notice;
use App\Models\Course\CampusOffer;
use App\Models\Organization\Campus;
use App\Models\Process\AffirmativeAction;
use App\Models\Process\DistributionOfVacancies;
use App\Models\Process\Offer;
use App\Models\Process\SelectionCriteria;
use Dotenv\Util\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class CreateNewNoticeTest extends TestCase
{

    use WithFaker;

    protected $userWithPermission;
    protected $userWithoutPermission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userWithoutPermission = User::whereDoesntHave('permissions')->first();
        $this->userWithPermission = User::whereHas('permissions', function ($query) {
            $query->where('role_id', 1);
        })->first();
    }

    public function testCreateANewNoticeToCriteriaSorteio()
    {
        $notice = Notice::factory([
            'subscription_final_date' => Carbon::now()->addMonth(1)->format('Y-m-d'),
            'registration_fee'  => '0.00',
        ])->make();
        $selectionCriteria = SelectionCriteria::where('id', 1)->first();
        $data = $notice->toArray();
        $data['selection_criteria']['0'] = $selectionCriteria->id;

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithPermission)
            ->post(route('admin.notices.store'), $data);
        $response->assertStatus(302)
            ->assertSessionHas('success');
    }

    public function testAccessLastNoticeCreated()
    {
        $notice = Notice::latest()->first();

        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.show', ['notice' => $notice]));

        $response->assertSeeText('Informações');
        $response->assertStatus(200);
    }

    public function testAddOffers()
    {
        $notice = Notice::latest()->first();


        $campusOffer = CampusOffer::factory([
            'campus_id'  => Campus::factory([
                'name' => $this->faker->unique()->company . random_int(0, 1000) . uniqid()
            ])
        ])->create();

        $response = $this->actingAs($this->userWithPermission)
            ->post(route('admin.notices.offers.store', ['notice' => $notice]), [
                'course_campus_offer_id' => $campusOffer->id,
                'total_vacancies'        => 900
            ]);

        $response->assertSessionHas('success');
    }

    public function testUpdateOffers()
    {
        $notice = Notice::latest()->first();
        $offer = $notice->offers()->orderBy('created_at','desc')->first();

        $campusOffer = CampusOffer::factory([
            'campus_id'  => Campus::factory([
                'name' => $this->faker->unique()->company . random_int(0, 1000) . uniqid()
            ])
        ])->create();

        $response = $this->actingAs($this->userWithPermission)
            ->put(route('admin.notices.offers.update', ['notice' => $notice,'offer'=>$offer]), [
                'course_campus_offer_id' => $campusOffer->id,
                'total_vacancies'        => 400
            ]);

        $response->assertSessionHas('success');
    }

    public function testOfferCreate()
    {
        $notice = Notice::latest()->first();
        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.offers.create',['notice'=>$notice]));
        $response->assertOk()->assertSeeText('Total de Vagas');
    }

    public function testOfferEdit()
    {        
        $notice = Notice::latest()->first();
        $offer = $notice->offers()->orderBy('created_at','desc')->first();
        $response = $this->actingAs($this->userWithPermission)
            ->get(route('admin.notices.offers.edit',['notice'=>$notice,'offer'=>$offer]));
        $response->assertOk()
            ->assertSeeText($offer->total_vacancies)
            ->assertSeeText('Total de Vagas');
    }

    public function testAddVacancies()
    {
        $notice = Notice::latest()->first();
        $aff = AffirmativeAction::factory(2)->create()
            ->each(function($a) {
                $a->is_ppi = true;
                $a->save();
            });
        for ($i = 0; $i < 5; $i++) {

            $campusOffer = CampusOffer::factory([
                'campus_id'  => Campus::factory([
                    'name' => $this->faker->unique()->company . random_int(0, 1000) . uniqid()
                ])
            ])->create();

            $this->actingAs($this->userWithPermission);

            $offer = Offer::factory([
                'course_campus_offer_id' => $campusOffer->id,
                'total_vacancies'   => 900,
                'notice_id' => $notice->id
            ])->create();

            Livewire::test(Vacancies::class, ['offer' => $offer])
                ->set('selected', [
                    'affirmative_action_id' => $aff[0]->id,
                    'selection_criteria_id' => 1,
                    'total_vacancies' => 3,
                    'has_subscriptions' => null
                ])
                ->call('save')
                ->assertHasNoErrors();

            $this->assertTrue(
                $offer->distributionVacancies()->where('affirmative_action_id', $aff[0]->id)
                    ->where('selection_criteria_id', 1)
                    ->exists()
            );

            Livewire::test(Vacancies::class, ['offer' => $offer])
                ->set('selected', [
                    'affirmative_action_id' => $aff[1]->id,
                    'selection_criteria_id' => 1,
                    'total_vacancies' => 3,
                    'has_subscriptions' => null
                ])
                ->call('save')
                ->assertHasNoErrors();

            $this->assertTrue(
                $offer->distributionVacancies()->where('affirmative_action_id', $aff[1]->id)
                    ->where('selection_criteria_id', 1)
                    ->exists()
            );
        }
    }
}
