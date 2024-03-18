<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Process\Notice;
use App\Models\Process\SelectionCriteria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoticeTest extends TestCase
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
        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.notices.index'));

        $response->assertForbidden();
    }

    public function testIndexWithPermission()
    {
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.notices.index'));

        $response->assertOk()->assertSeeText('Editais');
    }

    public function testCreateWithoutPermission()
    {
        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.notices.create'));

        $response->assertForbidden();
    }

    public function testCreateWithPermission()
    {
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.notices.create'));

        $response->assertOk()->assertSeeText('Dados do Edital');
    }

    public function testEditWithoutPermission()
    {
        $notice = Notice::first();

        $response = $this->actingAs($this->userWithoutPermission)->get(route('admin.notices.edit', ['notice' => $notice]));

        $response->assertForbidden();
    }

    public function testEditWithPermission()
    {

        $notice = Notice::first();
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.notices.edit', ['notice' => $notice]));

        $response->assertOk()->assertSeeText('Dados do Edital');
    }

    public function testStoreWithoutPermission()
    {
        $notice = notice::factory()->make();

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithoutPermission)
            ->post(route('admin.notices.store'), $notice->toArray());

        $response->assertForbidden();
    }

    public function testStoreWithPermission()
    {
        $notice = Notice::factory()->make();
        $selectionCriteria = SelectionCriteria::first();
        // $notice->selectionCriterias()->attach($selectionCriteria->pluck('id'));
        $data = $notice->toArray();
        $data['selection_criteria']['0'] = $selectionCriteria->id;

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithPermission)
            ->post(route('admin.notices.store'), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success');
    }

    public function testUpdateWithoutPermission()
    {
        $notice = Notice::latest()->first();

        $notice->description = "Edição de qualquer coisa?! Nr - " . random_int(100, 200);

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithoutPermission)
            ->put(route('admin.notices.update', ['notice' => $notice]), $notice->toArray());

        $response->assertForbidden();
    }

    public function testUpdateWithPermission()
    {
        $notice = notice::latest()->first();

        $notice->description = "Edição de qualquer coisa?! Nr - " . random_int(100, 200);

        $data = $notice->toArray();
        $data['has_fee'] = $notice->has_fee ? "1" : "0";
        $data['registration_fee'] = "1";
        $data['selection_criteria']["0"] = "4";

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithPermission)
            ->put(route('admin.notices.update', ['notice' => $notice]), $data);

        $response->assertStatus(302)
            ->assertSessionHas('success');
    }

    public function testDestroyWithoutPermission()
    {
        $notice = Notice::latest()->first();

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithoutPermission)
            ->delete(route('admin.notices.destroy', ['notice' => $notice]), $notice->toArray());

        $response->assertForbidden();
    }

    public function testDestroyWithPermission()
    {
        $notice = Notice::first();

        $response = $this->withoutMiddleware(VerifyCsrfToken::class)
            ->actingAs($this->userWithPermission)
            ->delete(route('admin.notices.destroy', ['notice' => $notice]), $notice->toArray());
        $response->assertStatus(302);
    }

}
