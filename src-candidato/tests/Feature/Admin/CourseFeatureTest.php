<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course\Course;
use App\Http\Middleware\Audit;
use App\Models\Course\Modality;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseFeatureTest extends TestCase
{
    use WithFaker;

    public $user;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::whereHas('permissions', function ($query) {
            $query->where('role_id', 1);
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

        $response = $this->actingAs($user)->withoutMiddleware(Audit::class)->get(route('admin.courses.index'));

        $response->assertStatus(403);
    }


    public function testAdminAccess()
    {
        $response = $this->actingAs($this->user)->get(route('admin.courses.index'));

        $response->assertStatus(200);
        $response->assertSee('Editar');

        $response = $this->actingAs($this->user)->get(route('admin.courses.create'));
        $response->assertSeeText('Cadastrar');

        $course = Course::first();

        $response = $this->actingAs($this->user)->get(route('admin.courses.edit', [
            'course'    => $course
        ]));
        $response->assertStatus(200);
        $response->assertSee($course->name);
    }

    public function testStoreCourse()
    {
        $response = $this->actingAs($this->user)->post(route('admin.courses.store'), [
            'name'         => $this->faker->text(20),
            'modality_id'          => Modality::first()->id
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
    }
    

    public function testUpdateCourse()
    {
        $response = $this->actingAs($this->user)->put(route('admin.courses.update', [
            'course'    => Course::whereDoesntHave('campusesOffer')->first()
        ]), [
            'name'   => $this->faker->text(200),
            'modality_id'          => Modality::first()->id
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(302);
    }

    public function testStoreCourseValidation()
    {
        $response = $this->actingAs($this->user)->post(route('admin.courses.store'), []);

        $response->assertSessionHasErrors(['name', 'modality_id']);
    }

    public function testDeleteCourseValidation()
    {        

        $response = $this->actingAs($this->user)->delete(route('admin.courses.destroy', [
            'course'    => Course::whereDoesntHave('campusesOffer')->first()
        ]), []);

        $response->assertSessionHas('success');
        $response->assertRedirect(route('admin.courses.index'));
    }
}
