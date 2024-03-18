<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Address\City;
use Tests\TestCase;
use App\Models\User;
use App\Models\User\Contact;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class RegisterTest extends TestCase
{

    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /**
     * @group loginantigo
     */
    public function testIfRegisterOk()
    {

        $user = User::factory()->make();
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $password = Str::random(8);
        $data['password'] = $password;
        $data['password_confirmation'] = $password;
        $data['birth_date'] = '23/01/1984';
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertRedirect('/dashboard');
    }

    /**
     * @group loginantigo
     */
    public function testLinkToNotRegistered()
    {
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->get('/register');
        $response->assertOk()->assertSee('/login');
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->get('/login');
        $response->assertOk()->assertSeeText('CPF')->assertSeeText('Senha');
    }

    /**
     * @group loginantigo
     */
    public function testIfRegisterWithValidPhone()
    {
        $user = User::factory()->make();
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $data['phone_number']    = '(49)3566-0148';
        $data['alternative_phone_number']    = '(49)93566-0148';
        $data['birth_date'] = '23/01/1984';
        $password = Str::random(8);
        $data['password'] = $password;
        $data['password_confirmation'] = $password;
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertRedirect('/dashboard');
    }

    public function testIfRegisterWithInvalidEmail()
    {
        $user = User::factory()->make();
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $data['birth_date'] = '23/01/1984';
        $data['email'] = "invalid.com.br";
        $password = Str::random(8);
        $data['password'] = $password;
        $data['password_confirmation'] = $password;
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertSessionHasErrors('email');
    }

    public function testIfRegisterWithInvalidBirthDate()
    {
        $user = User::factory()->make();
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $data['birth_date'] = '23/14/1984';
        $password = Str::random(8);
        $data['password'] = $password;
        $data['password_confirmation'] = $password;
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertSessionHasErrors('birth_date');
    }

    public function testIfRegisterWithInvalidCPF()
    {
        $user = User::factory()->make();
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $data['birth_date'] = '23/01/1984';
        $data['cpf']    = '001.002.003-55';
        $password = Str::random(8);
        $data['password'] = $password;
        $data['password_confirmation'] = $password;
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertSessionHasErrors('cpf');
    }

    /**
     * @group loginantigo
     */
    public function testIfRegisterWithInvalidPassword()
    {
        $user = User::factory()->make();
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $data['password'] = null;
        $data['birth_date'] = '23/01/1984';
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertSessionHasErrors('password');
    }

    /**
     * @group loginantigo
     */    
    public function testIfRegisterWithInvalidPasswordConfirmation()
    {
        $user = User::factory()->make();
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $data['birth_date'] = '23/01/1984';
        $password = Str::random(8);
        $data['password'] = $password;
        $data['password_confirmation'] = null;
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertSessionHasErrors('password');
    }

    public function testIfRegisterWithInvalidPhone()
    {
        $user = User::factory()->make();
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $data['phone_number']    = '(49)93566-01481';
        $data['alternative_phone_number']    = '(49)3566-0148';
        $data['birth_date'] = '23/01/1984';
        $password = Str::random(8);
        $data['password'] = $password;
        $data['password_confirmation'] = $password;
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertSessionHasErrors('phone_number');
    }

    /**
     * @group loginantigo
     */    
    public function testIfRegisterWithNullAlternativePhone()
    {
        $user = User::factory()->make();        
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $data['phone_number']    = '(49)93566-0148';
        $data['alternative_phone_number']    = null;
        $data['birth_date'] = '23/01/1984';
        $password = Str::random(8);
        $data['password'] = $password;
        $data['password_confirmation'] = $password;
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertRedirect('/dashboard');
    }

    /**
     * @group loginantigo
     */    
    public function testIfRegisterWithInvalid()
    {
        $user = User::factory()->make();
        $contact = Contact::factory()->make();
        $data = array_merge($user->toArray(), $contact->toArray());

        $data['cpf']    = '001.002.003-55';
        $data['phone_number'] = 'fafasffas';
        $data['birth_date'] = '1984-01-23';
        $data['email'] = null;
        $data['rg'] = null;
        $data['rg_emmitter']  = null;
        $data['mother_name']   = null;
        $data['district']   = null;
        $data['street'] = null;
        $password = Str::random(8);
        $data['password'] = $password;
        $data['password_confirmation'] = 'afadfasa';
        $data['city']   = City::first()->id;
        $response = $this->withoutMiddleware(VerifyCsrfToken::class)->post('/register',$data);
        $response->assertSessionHasErrors(['cpf','password','phone_number','email','rg','rg_emmitter','mother_name','district','street','birth_date']);
    }
}
