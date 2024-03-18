<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organization\Campus;
use App\Models\Address\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CampusTest extends TestCase
{

    protected $userWithoutPermission;
    protected $userWithPermission;

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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexWithoutPermission()
    {

        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.campuses.index'));

        // $response->assertStatus(200);
        $response->assertForbidden();
    }

    public function testIndexWithPermission()
    {

        $response = $this->actingAs($this->userWithPermission)->get(route('admin.campuses.index'));

        $response->assertOk()->assertSeeText('Campus');;
    }

    public function testCreateWithoutPermission()
    {
        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.campuses.create'));

        $response->assertForbidden();
    }

    public function testCreateWithPermission()
    {
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.campuses.create'));

        $response->assertOk()->assertSeeText('Dados');
    }

    public function testEditWithoutPermission()
    {
        $campus = Campus::first();

        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.campuses.edit', ['campus' => $campus]));

        $response->assertForbidden();
    }

    public function testEditWithPermission()
    {

        $campus = Campus::first();
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.campuses.edit', ['campus' => $campus]));

        $response->assertOk()->assertSeeText('Dados');
    }

    public function testStoreWithoutPermission()
    {
        $city = City::first();

        $campus = Campus::factory()->make();
        $campus->city_id = $city->id;

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithoutPermission)
            ->post(route('admin.campuses.store'), $campus->toArray());

        $response->assertForbidden();
    }

    public function testStoreWithPermission()
    {
        $city = City::first();

        $campus = Campus::factory()->make();
        $campus->city_id = $city->id;

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithPermission)
            ->post(route('admin.campuses.store'), $campus->toArray());

        $response->assertStatus(302)
            ->assertSessionHas('success');
    }

    public function testUpdateWithoutPermission()
    {

        $campus = Campus::latest()->first();

        $campus->name = Campus::factory()->make()->name;

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithoutPermission)
            ->put(route('admin.campuses.update', ['campus' => $campus]), $campus->toArray());

        $response->assertForbidden();
    }

    public function testUpdateWithPermission()
    {

        $campus = Campus::latest()->first();

        $campus->name = Campus::factory()->make()->name;

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithPermission)
            ->put(route('admin.campuses.update', ['campus' => $campus]), $campus->toArray());

        $response->assertStatus(302)
            ->assertSessionHas('success');
    }

    public function testDestroyWithoutPermission()
    {

        $campus = Campus::latest()->first();

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithoutPermission)
            ->put(route('admin.campuses.destroy', ['campus' => $campus]), $campus->toArray());

        $response->assertForbidden();
    }

    public function testDestroyWithPermission()
    {
        $campus = Campus::latest()->first();

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithPermission)
            ->put(route('admin.campuses.destroy', ['campus' => $campus]), $campus->toArray());

        $response->assertStatus(302)
            ->assertSessionHas('success');
    }
}
