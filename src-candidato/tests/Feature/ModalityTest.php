<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course\Course;
use App\Models\Course\Modality;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModalityTest extends TestCase
{

    use WithFaker;

    protected $userWithoutPermission;
    protected $userWithPermission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userWithoutPermission = User::whereDoesntHave('permissions')
            ->whereNotNull('is_foreign')
            ->first();
        $this->userWithPermission = User::whereHas('permissions',function($query) {
            $query->where('role_id',1);
         })->first();
    }

    public function testIndexWithoutPermission()
    {
        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.modalities.index'));

        $response->assertForbidden();
    }

    public function testModalityIndexWithPermission()
    {
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.modalities.index'));

        $response->assertStatus(200);
        $response->assertSeeText('Descrição');
    }

    public function testCreateWithoutPermission()
    {
        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.modalities.create'));
        $response->assertForbidden();
    }

    public function testCreateWithPermission()
    {
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.modalities.create'));
        $response->assertStatus(200);
        $response->assertSeeText('Cadastrar');
    }

    public function testEditWithoutPermission()
    {
        $modality = Modality::first();
        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.modalities.edit', [
            'modality'    => $modality
        ]));
        $response->assertForbidden();
    }

    public function testEditWithPermission()
    {
        $modality = Modality::first();
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.modalities.edit', [
            'modality'    => $modality
        ]));
        $response->assertStatus(200);
        $response->assertSeeText('Atualizar');
        $response->assertSee($modality->description);
    }

    public function testStoreWithoutPermission()
    {
        $response = $this->actingAs($this->userWithoutPermission)->post(route('admin.modalities.store'), [
            'description'   => $this->faker->name
        ]);
        $response->assertForbidden();
    }

    public function testStoreWithPermission()
    {
        $response = $this->actingAs($this->userWithPermission)->post(route('admin.modalities.store'), [
            'description'   => $this->faker->name
        ]);
        $response->assertRedirect(route('admin.modalities.index'));
        $response->assertSessionHas('success');
    }

    public function testUpdateWithoutPermission()
    {
        $modality = Modality::first();
        $response = $this->actingAs($this->userWithoutPermission)->put(route('admin.modalities.update', ['modality' => $modality]), [
            'description'   => $this->faker->name
        ]);
        $response->assertForbidden();
    }

    public function testUpdateWithPermission()
    {
        $modality = Modality::first();
        $response = $this->actingAs($this->userWithPermission)->put(route('admin.modalities.update', ['modality' => $modality]), [
            'description'   => $this->faker->name
        ]);
        $response->assertRedirect(route('admin.modalities.index'));
        $response->assertSessionHas('success');
    }


    public function testValidation()
    {
        $response = $this->actingAs($this->userWithPermission)->post(route('admin.modalities.store'), [
            'description'   => Modality::first()->description
        ]);
        $response->assertSessionHasErrors('description');
    }

    public function testModalityDelete()
    {
        $modality = Modality::factory([
            'description'   => uniqid()
        ])->create();
        

        $response = $this->actingAs($this->userWithPermission)->delete(route('admin.modalities.destroy', ['modality' => $modality]));
        $response->assertRedirect(route('admin.modalities.index'));
        $response->assertSessionHas('success');
    }

}
