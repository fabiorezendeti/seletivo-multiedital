<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testForgotPassword()
    {
        $user = User::factory()->create();

        $data['email'] = $user->email;
        $response = $this->post('/forgot-password', $data);
        $response->assertStatus(302)->assertSessionHas('status', 'Enviamos um e-mail com um link para redefinir sua senha!');
    }

    public function testIfInvalidEmail()
    {
        $user = User::factory()->create();

        $data = $user->toArray();
        $data['email'] = 'myInvalidMail@teste.com';
        $response = $this->post('/forgot-password', $data);

        $response->assertSessionHasErrors('email');
    }
}
