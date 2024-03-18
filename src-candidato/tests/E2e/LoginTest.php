<?php

namespace Tests\E2e;

use Tests\TestCase;
use Tests\E2eUtils;
use Facebook\WebDriver\WebDriverBy;
use Exception;
use Facebook\WebDriver\WebDriver;
use Illuminate\Foundation\Testing\WithFaker;
use Facebook\WebDriver\WebDriverExpectedCondition;
use App\Models\User;

class LoginTest extends TestCase
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
        self::$driver->findElement(WebDriverBy::linkText('ENTRAR'))
            ->click();
        self::$driver->wait(10, 500)->until(
            function ($driver) {
                return $driver->getCurrentURL() === 'http://candidato:8000/login';
            });
    }

    //ao final de todas os testes, fecha o navegador.
    public static function tearDownAfterClass(): void
    {
        self::$driver->quit();
    }

    /**
     * Arrange:
     * Dado que estou na página inicial, sem estar logado,
     * 
     * Act:
     * quero clicar no botão "entrar" e 
     * informar login e senha inválida
     * 
     * Assert:
     * Então devo ser avisado de login/senha inválido
     * 
     * @return void
     */
    public function testInvalidLogin()
    {
        try {   
            $user = User::factory()->create();
            
            self::$driver->findElement(WebDriverBy::id('cpf'))
            ->sendKeys($user->cpf);
            self::$driver->findElement(WebDriverBy::id('password'))
            ->sendKeys("passworddddd");
            
            self::$driver->findElement(WebDriverBy::tagName('form'))->submit();
                
            $this->assertSame(self::$driver->getCurrentURL(), 'http://candidato:8000/login', 'Deveria estar na página de login.');
            $errorsElements = self::$driver->findElements(WebDriverBy::className('one-error'));
            foreach ($errorsElements as $error) {
                $this->assertStringEndsWith('Não conseguimos encontrar usuário/senha em nossos registros.', $error->getText(), 'Esperava um erro de usuário/senha inválidos');   
            }
            
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
     * quero clicar no botão "entrar" e 
     * informar login e senha válidos
     * 
     * Assert:
     * Então devo ser direcionado para o dashboard
     * 
     * @return void
     */
    public function testLoginOk()
    {
        try {   
            $user = User::factory()->create();

            self::$driver->findElement(WebDriverBy::id('cpf'))
            ->sendKeys($user->cpf);
            self::$driver->findElement(WebDriverBy::id('password'))
            ->sendKeys("password");
            
            self::$driver->findElement(WebDriverBy::tagName('form'))->submit();
            
            $this->assertSame(self::$driver->getCurrentURL(), 'http://candidato:8000/dashboard', 'Não chegou no dashboard.');
            self::$driver->findElement(WebDriverBy::cssSelector("[title=\"Meu Perfil\"]"))->click();
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::linkText("Sair"))
            );
            self::$driver->findElement(WebDriverBy::linkText("Sair"))->click();
            $this->assertSame(self::$driver->getCurrentURL(), 'http://candidato:8000', 'Não fez logout.');
        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            self::$driver->quit();
        }
    }
}
