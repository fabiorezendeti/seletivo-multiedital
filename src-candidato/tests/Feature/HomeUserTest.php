<?php

namespace Tests\Feature;

use App\Http\Middleware\Audit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeUserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAuthenticateInHome()
    {

        $user = User::factory()->make();

        $response = $this->actingAs($user)->get('/');

        $response->assertSeeText('Redirecting to');
    }

    public function testRedirectRegisterAndLoginIfAuthenticated()
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/dashboard');

        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect('/dashboard');
    }

    /**
     * @group loginantigo
     */    
    public function testLogout()
    {
        $user = User::factory()->make();
        $response = $this
            ->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
    }

    /**
     * @group loginunico
     */
    public function testLogoutLoginUnico()
    {
        $user = User::factory()->make();
        $response = $this
            ->actingAs($user)->post('/logout');
        $response->assertRedirect(env('LOGIN_UNICO_PROVIDER') . "/logout?post_logout_redirect_uri=" . env('APP_URL') . "/logout");
    }

}
