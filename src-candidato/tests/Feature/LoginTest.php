<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /**
    * @group loginantigo
    */
    public function testLoginCandidateUser()
    {
        $user = User::factory()->create();

        $response = $this->post('/login',[
            'cpf'   => $user->cpf,
            'password'  => 'password'
        ]);

        $response->assertRedirect('/dashboard');
    }
    /**
    * @group loginantigo
    */
    public function testLoginAdmin()
    {
        $response = $this->post('/login',[
            'cpf'   => '000.000.000-00',
            'password'  => 'password'
        ]);

        $response->assertSessionHasErrors();

        $user = User::where('cpf','000.000.000-00')
            ->first();
        $user->password = Hash::make('ifc789123');
        $user->save();
        
        $response = $this->post('/login',[
            'cpf'   => '000.000.000-00',
            'password'  => 'ifc789123'
        ]);

        $response->assertRedirect('/dashboard');
    }
    /**
     * @group loginantigo
     */
    public function testLoginInvalidCpf()
    {
        $data['cpf'] = '000.000.000-xx';
        $data['password'] = '12345678';
        $response = $this->post('/login',$data);
        $response->assertSessionHasErrors('cpf');
    }
    /**
     * @group loginantigo
     */
    public function testLoginInvalidPassword()
    {
        $data['cpf'] = '000.000.000-00';
        $data['password'] = 'invalid';
        $response = $this->post('/login',$data);
        $response->assertSessionHasErrors('cpf');
    }

    /**
     * @group loginantigo
     */    
    public function testLinkToForgotPassword()
    {
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->get('/login');
        $response->assertOk()->assertSee('/forgot-password');
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->get('/forgot-password');
        $response->assertOk()->assertSeeText('Email');
    }

    /**
     * @group loginantigo
     */    
    public function testLogout()
    {
        $response = $this
            ->actingAs(User::first())
            ->withoutMiddleware(VerifyCsrfToken::class)->post('/logout');    
        $response->assertRedirect('/');
    }

}
