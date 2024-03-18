<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\Audit;
use App\Models\Process\AffirmativeAction;
use App\Models\Process\AffirmativeActions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class AffirmativeActionTest extends TestCase
{

    use WithFaker;

    public $user;


    protected function setUp(): void
    {
        parent::setUp();
    
        $this->user = User::whereHas('permissions',function($query) { 
            $query->where('role_id',1);
         })->first();
    }
    

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNotAdminAccess()
    {
        $user = User::factory()->make();

        $response = $this->actingAs($user)->withoutMiddleware(Audit::class)->get(route('admin.affirmative-actions.index'));

        $response->assertStatus(403);
    }


    public function testAdminAccess()
    {

        AffirmativeAction::factory([
            'slug'  => uniqid()
        ])->create();

        $response = $this->actingAs($this->user)->get(route('admin.affirmative-actions.index'));

        $response->assertStatus(200);
        

        $response = $this->actingAs($this->user)->get(route('admin.affirmative-actions.create'));
        $response->assertSeeText('Cadastrar');

        $response = $this->actingAs($this->user)->get(route('admin.affirmative-actions.edit',[
            'affirmative_action'    => AffirmativeAction::whereDoesntHave('distributionOfVacancies')->first()
        ]));
        $response->assertStatus(200);
        $response->assertSee('slug');
    }

    public function testStoreAffirmativeAction()
    {
        $response = $this->actingAs($this->user)->post(route('admin.affirmative-actions.store'),[
            'description'   => $this->faker->text(200),
            'slug'          => $this->faker->text(40),
            'classification_priority'   => random_int(10,1000)
        ]);

        $response->assertSessionHasNoErrors();  
        $response->assertStatus(302);
    }

    public function testUpdateAffirmativeAction()
    {
        $response = $this->actingAs($this->user)->put(route('admin.affirmative-actions.update',[
            'affirmative_action'    => AffirmativeAction::whereDoesntHave('distributionOfVacancies')->first()
        ]),[
            'description'   => $this->faker->text(200),
            'slug'          => $this->faker->text(40),
            'classification_priority'   => random_int(10,1000)
        ]);

        $response->assertSessionHasNoErrors();  
        $response->assertStatus(302);
    }
    
    public function testStoreAffirmativeActionValidation()
    {
        $response = $this->actingAs($this->user)->post(route('admin.affirmative-actions.store'),[
            
        ]);

        $response->assertSessionHasErrors(['description','slug']);          
    }

    public function testDeleteAffirmativeActionValidation()
    {
        $response = $this->actingAs($this->user)->delete(route('admin.affirmative-actions.destroy',[
            'affirmative_action'    => AffirmativeAction::whereHas('distributionOfVacancies')->first()
        ]),[
            
        ]);
        $response->assertStatus(403);        
    }

    public function testDeleteAffirmativeActionSuccessValidation()
    {
        $response = $this->actingAs($this->user)->delete(route('admin.affirmative-actions.destroy',[
            'affirmative_action'    => AffirmativeAction::whereDoesntHave('distributionOfVacancies')->first()
        ]),[
            
        ]);

        $response->assertSessionHas('success');        
    }

}
