<?php

namespace Tests\E2e;

use Tests\TestCase;
use Tests\E2eUtils;
use Facebook\WebDriver\WebDriverBy;
use Exception;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverCheckboxes;
use Facebook\WebDriver\WebDriverSelect;
use Illuminate\Foundation\Testing\WithFaker;
use Facebook\WebDriver\WebDriverExpectedCondition;

class RegisterTest extends TestCase
{
    use WithFaker;
    private static WebDriver $driver;

    public static function setUpBeforeClass(): void
    {
        $e2eUtils = new E2eUtils();
        self::$driver = $e2eUtils->createDriver();
    }

    //executa antes de cada teste da classe
    protected function setUp(): void
    {
        parent::setup();
        
        self::$driver->get('http://candidato:8000');
        self::$driver->findElement(WebDriverBy::linkText('CADASTRAR-SE'))
            ->click();
        self::$driver->wait(10, 500)->until(
            function ($driver) {
                return $driver->getCurrentURL() === 'http://candidato:8000/register';
            });
        self::$driver->findElement(WebDriverBy::id('name'))
        ->sendKeys('Sou Um Robô Selenium');
        self::$driver->findElement(WebDriverBy::id('birth_date'))
        ->sendKeys('01/02/1986');
        self::$driver->findElement(WebDriverBy::id('rg'))
        ->sendKeys('123456');
        self::$driver->findElement(WebDriverBy::id('rg_emmitter'))
        ->sendKeys('SSP-SC');
        self::$driver->findElement(WebDriverBy::id('mother_name'))
        ->sendKeys('Selena Gomez');
        self::$driver->findElement(WebDriverBy::id('password'))
        ->sendKeys('12345678');
        self::$driver->findElement(WebDriverBy::id('password_confirmation'))
        ->sendKeys('12345678');
        $this->assertSame(self::$driver->findElement(WebDriverBy::id('password'))->getText(), self::$driver->findElement(WebDriverBy::id('password_confirmation'))->getText(), 'senha não confirmada');

        self::$driver->findElement(WebDriverBy::id('phone_number'))
        ->sendKeys('(47)9746-4128');

        $checkboxes = new WebDriverCheckboxes(self::$driver->findElement(WebDriverBy::xpath('//input[@type="checkbox"]')));
        $checkboxes->selectByVisibleText('WhatsApp');

        self::$driver->findElement(WebDriverBy::id('street'))
        ->sendKeys('Rua da Ficção');
        self::$driver->findElement(WebDriverBy::id('number'))
        ->sendKeys('10010');
        self::$driver->findElement(WebDriverBy::id('district'))
        ->sendKeys('Bairro das Luzes');
        self::$driver->findElement(WebDriverBy::id('zip_code'))
        ->sendKeys('89000-000');
        
        self::$driver->wait(10, 1000)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('check-lgpd'))
        );
        self::$driver->findElement(WebDriverBy::name('check-lgpd'))->click();
}

    //ao final de todas os testes, fecha o navegador.
    public static function tearDownAfterClass(): void{
        self::$driver->quit();
    }

    /**
     * Arrange:
     * Dado que estou na página inicial, sem estar logado,
     * 
     * Act:
     * quero clicar no botão "cadastrar-se" e 
     * ser direcionado para o formulário de cadastro e
     * quando preencho e envio o formulário com informações válidas
     * 
     * Assert:
     * Então devo ser direcionado para o dashboard.
     * 
     *
     * @return void
     */
    public function testIfRegisterOk()
    {
        try {          
            $cpf = $this->faker->unique()->cpf;
            $email = $this->faker->unique()->email;
            $this->assertEquals(
                'http://candidato:8000/register', 
                self::$driver->getCurrentURL()
            );
            
            self::$driver->findElement(WebDriverBy::id('username'))
            ->sendKeys($cpf);
            
            self::$driver->findElement(WebDriverBy::id('email'))
            ->sendKeys($email);
            self::$driver->findElement(WebDriverBy::id('email_confirmation'))
            ->sendKeys($email);
            $this->assertSame(self::$driver->findElement(WebDriverBy::id('email'))->getText(), self::$driver->findElement(WebDriverBy::id('email_confirmation'))->getText(), 'e-mail não confirmado');
            $selectCity = new WebDriverSelect(self::$driver->findElement(WebDriverBy::name('city')));
            $selectCity->selectByVisibleText('Blumenau');
            self::$driver->findElement(WebDriverBy::id('register_button'))
            ->click();

            self::$driver->wait(10, 500)->until(
                function ($driver) {
                    return $driver->getCurrentURL() === 'http://candidato:8000/dashboard';
                });
            $this->assertSame(self::$driver->getCurrentURL(), 'http://candidato:8000/dashboard', 'Não chegou no dashboard.');
            self::$driver->findElement(WebDriverBy::cssSelector("[title=\"Meu Perfil\"]"))->click();
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::linkText("Sair"))
            );
            self::$driver->findElement(WebDriverBy::linkText("Sair"))->click();
            
        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            self::$driver->quit();
        }
    }

    /**
     * Arrange:
     * Dado que estou na página inicial, sem estar logado,
     * 
     * Act:
     * quero clicar no botão "cadastrar-se" e 
     * ser direcionado para o formulário de cadastro e
     * quando preencho e envio o formulário com e-mail inválido
     * 
     * Assert:
     * Então devo permanecer na página de cadastro (validação barra prosseguir registro)
     * 
     *
     * @return void
     */
    public function testIfRegisterWithInvalidEmail()
    {
        try {          
            $cpf = $this->faker->unique()->cpf;
            $email = 'emailinvalido.com.br';
            $this->assertEquals(
                'http://candidato:8000/register', 
                self::$driver->getCurrentURL()
            );
            self::$driver->findElement(WebDriverBy::id('username'))
            ->sendKeys($cpf);
            self::$driver->findElement(WebDriverBy::id('email'))
            ->sendKeys($email);
            self::$driver->findElement(WebDriverBy::id('email_confirmation'))
            ->sendKeys($email);
            $this->assertSame(self::$driver->findElement(WebDriverBy::id('email'))->getText(), self::$driver->findElement(WebDriverBy::id('email_confirmation'))->getText(), 'e-mail não confirmado');
            $selectCity = new WebDriverSelect(self::$driver->findElement(WebDriverBy::name('city')));
            $selectCity->selectByVisibleText('Blumenau');
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('register_button'))
            );
            self::$driver->findElement(WebDriverBy::id('register_button'))
            ->click();
            $this->assertSame(
                'http://candidato:8000/register', 
                self::$driver->getCurrentURL(),
                'Não deveria aceitar e-mail inválido'
            );
            
        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            self::$driver->quit();
        }    
    }

    /**
     * Arrange:
     * Dado que estou na página inicial, sem estar logado,
     * 
     * Act:
     * quero clicar no botão "cadastrar-se" e 
     * ser direcionado para o formulário de cadastro e
     * quando preencho e envio o formulário com CPF inválido
     * 
     * Assert:
     * Então devo permanecer na página de cadastro (validação barra prosseguir registro)
     * 
     *
     * @return void
     */
    public function testIfRegisterWithInvalidCpf()
    {
        try {          
            $cpf = '012.000.000-00';
            $email = $this->faker->unique()->email;
            $this->assertEquals(
                'http://candidato:8000/register', 
                self::$driver->getCurrentURL()
            );
           
            self::$driver->findElement(WebDriverBy::id('username'))
            ->sendKeys($cpf);
            self::$driver->findElement(WebDriverBy::id('email'))
            ->sendKeys($email);
            self::$driver->findElement(WebDriverBy::id('email_confirmation'))
            ->sendKeys($email);
            $this->assertSame(self::$driver->findElement(WebDriverBy::id('email'))->getText(), self::$driver->findElement(WebDriverBy::id('email_confirmation'))->getText(), 'e-mail não confirmado');
            $selectCity = new WebDriverSelect(self::$driver->findElement(WebDriverBy::name('city')));
            $selectCity->selectByVisibleText('Blumenau');
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('register_button'))
            );
            self::$driver->findElement(WebDriverBy::id('register_button'))
            ->click();
            
            $errorsElements = self::$driver->findElements(WebDriverBy::className('one-error'));
            foreach ($errorsElements as $error) {
                $this->assertStringEndsWith('não é um CPF válido', $error->getText(), 'Esperava um erro de CPF inválido');   
            }
        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            self::$driver->quit();
        }    
    }
}
