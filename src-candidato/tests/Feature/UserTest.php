<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
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
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexWithoutPermission()
    {
        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function testIndexWithPermission()
    {
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.users.index'));

        $response->assertOk()->assertSeeText('Usuários');
    }

    public function testEditWithoutPermission()
    {
        $user = User::first();

        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.users.edit', ['user' => $user]));

        $response->assertForbidden();
    }

    public function testEditWithPermission()
    {

        $user = User::first();
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.users.edit', ['user' => $user]));

        $response->assertOk()->assertSeeText('Dados básicos');
    }

    public function testUpdateWithoutPermission()
    {
        $user = User::latest()->first();

        $user->name = User::factory()->make()->name;

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($this->userWithoutPermission)
                         ->put(route('admin.users.update', ['user' => $user]),$user->toArray());

        $response->assertForbidden();
    }

    public function testUpdateWithPermission()
    {
        $user = user::latest()->first();

        $user->name = User::factory()->make()->name;
        $data = $user->toArray();
        $data['birth_date'] = '13/06/1991';
        $data['justify_text'] = "Teste de alteração!";

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($this->userWithPermission)
                         ->put(route('admin.users.update', ['user' => $user]), $data);

        $response->assertStatus(302)
                 ->assertSessionHas('success');
    }

    public function testDestroyWithoutPermission()
    {
        $user = User::latest()->first();
        $data = $user->toArray();
        $data['birth_date'] = '13/06/1991';
        $data['justify_text'] = "Teste de exclusão!";

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($this->userWithoutPermission)
                         ->delete(route('admin.users.destroy', ['user' => $user]),$data);

        $response->assertForbidden();
    }

    /**
     * @group loginantigo
     */     
    public function testDestroyWithPermission()
    {
        $user = User::latest()->first();
        $data = $user->toArray();
        $data['birth_date'] = '13/06/1991';
        $data['justify_text'] = "Teste de exclusão!";

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                         ->actingAs($this->userWithPermission)
                         ->delete(route('admin.users.destroy', ['user' => $user]),$data);

        $response->assertStatus(302)
                 ->assertSessionHas('success'); //Delete dos testes, não tem vinculos nenhum
    }

}
