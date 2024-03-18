<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Course\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexWithoutPermission()
    {
        $user = User::whereDoesntHave('permissions')
            ->whereNotNull('is_foreign')
            ->first();

        $response = $this->actingAs($user)
            ->get(route('admin.courses.index'));

        // $response->assertStatus(200);
        $response->assertForbidden();
    }

    public function testIndexWithPermission()
    {
        $user = User::whereHas('permissions',function($query) {
            $query->where('role_id',1);
         })->first();

        $response = $this->actingAs($user)->get(route('admin.courses.index'));

        $response->assertOk()->assertSeeText('Cursos');;
    }

    public function testCreateWithoutPermission()
    {
        $user = User::whereDoesntHave('permissions')
            ->whereNotNull('is_foreign')
            ->first();

        $response = $this->actingAs($user)->get(route('admin.courses.create'));

        $response->assertForbidden();
    }

    public function testCreateWithPermission()
    {
        $user = User::whereHas('permissions',function($query) {
            $query->where('role_id',1);
         })->first();

        $response = $this->actingAs($user)->get(route('admin.courses.create'));

        $response->assertOk()->assertSeeText('Cadastro de Curso');;
    }

    public function testEditWithoutPermission()
    {
        $user = User::whereDoesntHave('permissions')
            ->whereNotNull('is_foreign')
            ->first();
        $course = Course::first();

        $response = $this->actingAs($user)->get(route('admin.courses.edit', ['course' => $course]));

        $response->assertForbidden();
    }

    public function testEditWithPermission()
    {
        $user = User::whereHas('permissions',function($query) {
            $query->where('role_id',1);
         })->first();
        $course = Course::first();

        $response = $this->actingAs($user)->get(route('admin.courses.edit', ['course' => $course]));

        $response->assertOk()->assertSeeText('Cadastro de Curso');;
    }

    public function testStoreWithoutPermission()
    {
        $user = User::whereDoesntHave('permissions')->first();

        $course = Course::factory()->make();
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($user)
                         ->post(route('admin.courses.store'),$course->toArray());

        $response->assertForbidden();

    }

    public function testStoreWithPermission()
    {
        $user = User::whereHas('permissions',function($query) {
            $query->where('role_id',1);
         })->first();
        $course = Course::factory()->make();
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($user)
                         ->post(route('admin.courses.store'),$course->toArray());

        $response->assertStatus(302)
                 ->assertSessionHas('success');

    }

    public function testUpdateWithoutPermission()
    {
        $user = User::whereDoesntHave('permissions')->first();

        $course = Course::latest()->first();

        $course->name = Course::factory()->make()->name;

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($user)
                         ->put(route('admin.courses.update', ['course' => $course]),$course->toArray());

        $response->assertForbidden();
    }

    public function testUpdateWithPermission()
    {
        $user = User::whereHas('permissions',function($query) {
            $query->where('role_id',1);
         })->first();

        $course = Course::latest()->first();

        $course->name = Course::factory()->make()->name;

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($user)
                         ->put(route('admin.courses.update', ['course' => $course]),$course->toArray());

        $response->assertStatus(302)
                 ->assertSessionHas('success');
    }

    public function testDestroyWithoutPermission()
    {
        $user = User::whereDoesntHave('permissions')->first();

        $course = Course::latest()->first();

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($user)
                         ->put(route('admin.courses.destroy', ['course' => $course]),$course->toArray());

        $response->assertForbidden();
    }

    public function testDestroyWithPermission()
    {
        $user = User::whereHas('permissions',function($query) {
            $query->where('role_id',1);
         })->first();

        $course = Course::latest()->first();

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($user)
                         ->put(route('admin.courses.destroy', ['course' => $course]),$course->toArray());

        $response->assertStatus(302)
                 ->assertSessionHas('success');
    }

    public function testDestroyRelatedWithPermission()
    {
        $user = User::whereHas('permissions',function($query) {
            $query->where('role_id',1);
         })->first();

        $course = Course::factory()->create();

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($user)
                         ->delete(route('admin.courses.destroy', ['course' => $course]),$course->toArray());

        $response->assertStatus(302)
                 ->assertSessionHas('success');
    }
}
