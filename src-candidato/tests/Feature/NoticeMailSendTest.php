<?php

namespace Tests\Feature;

use App\Models\Process\Notice;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;

class NoticeMailSendTest extends TestCase
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

    public function testGetMailSendForm()
    {
        $response = $this->actingAs($this->userWithPermission)->get(route('admin.notices.mail-send.edit', [
                'notice' => Notice::first()
            ]));

        $response->assertOk()->assertSee('title');
    }

    public function testSendMailSendFormAccess()
    {
        $response = $this->actingAs($this->userWithPermission)->post(route('admin.notices.mail-send.sender', [
                'notice' => Notice::first()
        ]));        
        $response->assertSessionHasErrors(['subject','message']);
        
    }
}
