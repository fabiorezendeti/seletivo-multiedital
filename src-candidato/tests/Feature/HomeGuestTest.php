<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeGuestTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /**
     * @group loginantigo
     */
    public function testHomePageGuestUser()
    {
        $response = $this->get('/');

        $response->assertSeeText('ENTRAR');

        $response->assertStatus(200);
    }

    /**
     * @group loginunico
     */    
    public function testHomePageGuestUserLoginUnico()
    {
        $response = $this->get('/');

        $response->assertSeeText('Entrar com GOV.BR');

        $response->assertStatus(200);
    }

    /**
     * @group loginantigo
     */
    public function testRegisterTest()
    {
        $response = $this->get('/register');

        $response->assertSeeText('Cadastrar');
    }

}
