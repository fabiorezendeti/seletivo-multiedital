<?php

namespace Tests\E2e;

use Tests\TestCase;
use Tests\E2eUtils;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Exception;
use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverRadios;
use Illuminate\Foundation\Testing\WithFaker;
use Facebook\WebDriver\WebDriverExpectedCondition;
use App\Models\User;
use App\Models\Process\Notice;
use App\Models\Process\SelectionCriteria;
use App\Models\Process\Offer;
use App\Models\Process\AffirmativeAction;

class SubscriptionTest extends TestCase
{
    use WithFaker;
    private static WebDriver $driver;
    protected $notice;
    protected $offer;
    protected $user;
    public $selectionCriteria;
    protected $userWithPermission;


    public static function setUpBeforeClass(): void
    {
        $e2eUtils = new E2eUtils();
        self::$driver = $e2eUtils->createDriver();
    }

    //executa antes de cada teste da classe
    protected function setUp(): void
    {
        parent::setup();
        foreach (Notice::all() as $notice) {
            //limpa editais abertos para o ponteiro do mouse selenium não se perder em rolagem de tela
            $notice->subscription_final_date = now()->subDays(5);
            $notice->save();
        }
        $this->userWithPermission = User::whereHas('permissions', function ($query) {
            $query->where('role_id', 1);
        })->first();

        self::$driver->get('http://candidato:8000');
        self::$driver->findElement(WebDriverBy::linkText('ENTRAR'))
            ->click();
        self::$driver->wait(10, 500)->until(
            function ($driver) {
                return $driver->getCurrentURL() === 'http://candidato:8000/login';
            });
        $this->user = User::factory()->create();
    }

    public function tearDown(): void
    {
        for ($i=0; $i < 6; $i++) { 
            self::$driver->findElement(WebDriverBy::tagName('body'))->sendKeys(array(WebDriverKeys::PAGE_DOWN));
        }
        self::$driver->wait(10, 1000)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::name('check_affirmative_action'))
        );
        self::$driver->findElement(WebDriverBy::name('check_affirmative_action'))->click();
        sleep(3);
        for ($i=0; $i < 4; $i++) { 
            self::$driver->findElement(WebDriverBy::tagName('body'))->sendKeys(array(WebDriverKeys::PAGE_DOWN));
        }
        self::$driver->findElement(WebDriverBy::xpath("//button[contains(.,'Finalizar')]"))->click();
        self::$driver->wait(10, 1000)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath("//button[contains(.,'SIM')]"))
        );

        self::$driver->findElement(WebDriverBy::xpath("//button[contains(.,'SIM')]"))->click();
        self::$driver->wait(10, 1000)->until(
            WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('subscription-number'))
        );
        $subscriptionNumber = self::$driver->findElement(WebDriverBy::className('subscription-number'))->getText();
        $subscriptionNumber = substr($subscriptionNumber,4);
        
        $this->assertStringContainsString($subscriptionNumber, self::$driver->getCurrentURL());

        echo "teste com o CPF: ".$this->user->cpf;

        self::$driver->findElement(WebDriverBy::cssSelector("[title=\"Meu Perfil\"]"))->click();
        self::$driver->wait(10, 1000)->until(
            WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::linkText("Sair"))
        );
        self::$driver->findElement(WebDriverBy::linkText("Sair"))->click();
    }

    //ao final de todas os testes, fecha o navegador.
    public static function tearDownAfterClass(): void
    {
       self::$driver->quit();
    }

    public function setSelectionCriteriaInAllOffers(Notice $notice, SelectionCriteria $selectionCriteria): void
    {
        $this->selectionCriteria = $selectionCriteria;
        foreach (Offer::all() as $offer) {
            foreach (AffirmativeAction::all() as $affirmative) {
                $offer->distributionVacancies()->insert([
                    [
                        'offer_id'  => $offer->id,
                        'affirmative_action_id' => $affirmative->id,
                        'selection_criteria_id' => $selectionCriteria->id,
                        'total_vacancies'   => 5
                    ]
                ]
                );
            }
        }
        
    }

    /**
     * Arrange:
     * Dado que estou na página inicial, sem estar logado,
     * 
     * Act:
     * quero clicar no botão "entrar" e 
     * informar login e senha válidos, escolher um edital de Sorteio
     * 
     * Assert:
     * Então devo conseguir me inscrever em alguma oferta
     * 
     * @return void
     */
    public function testSubscriptionSorteio()
    {
        try { 
            $notice = Notice::factory()->make();
            $selectionCriteria = SelectionCriteria::find(1);

            $data = $notice->toArray();
            $data['selection_criteria'] = [$selectionCriteria->id];
            $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                ->actingAs($this->userWithPermission)
                ->post(route('admin.notices.store'), $data);
            $this->notice = Notice::orderBy('id','desc')->first();
            Offer::factory()->create([
                'notice_id' => $this->notice->id,
            ]);
            $this->notice->subscription_final_date = now()->addDays(2);
            $this->notice->save();
            $this->setSelectionCriteriaInAllOffers($this->notice, $selectionCriteria);

            self::$driver->manage()->window()->maximize();

            self::$driver->findElement(WebDriverBy::id('cpf'))
            ->sendKeys($this->user->cpf);
            self::$driver->findElement(WebDriverBy::id('password'))
            ->sendKeys("password");
            
            self::$driver->findElement(WebDriverBy::tagName('form'))->submit();
            
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('open-registrations'))
            );
            
            self::$driver->findElement(WebDriverBy::xpath('//a[@href="http://candidato:8000/notice/'.$this->notice->id.'"]'))->click();

            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('notice-offer-table'))
            );
            self::$driver->findElement(WebDriverBy::linkText('Selecionar'))->click();

            $radiosElement = self::$driver->findElement(WebDriverBy::xpath('//input[@type="radio"]'));
            $radios = new WebDriverRadios($radiosElement);
            $radios->selectByVisibleText('Sorteio');
        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            $this->assertTrue(false);
            self::$driver->quit();
        }
    }

    /**
     * Arrange:
     * Dado que estou na página inicial, sem estar logado,
     * 
     * Act:
     * quero clicar no botão "entrar" e 
     * informar login e senha válidos, escolher um edital de Prova
     * 
     * Assert:
     * Então devo conseguir me inscrever em alguma oferta
     * 
     * @return void
     */
    public function testSubscriptionProva()
    {
        try {   
            $notice = Notice::factory()->make();
            $selectionCriteria = SelectionCriteria::find(2);

            $data = $notice->toArray();
            $data['selection_criteria'] = [$selectionCriteria->id];
            $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                ->actingAs($this->userWithPermission)
                ->post(route('admin.notices.store'), $data);
            $this->notice = Notice::orderBy('id','desc')->first();
            Offer::factory()->create([
                'notice_id' => $this->notice->id,
            ]);
            $this->notice->subscription_final_date = now()->addDays(2);
            $this->notice->save();
            $this->setSelectionCriteriaInAllOffers($this->notice, $selectionCriteria);

            self::$driver->manage()->window()->maximize();

            self::$driver->findElement(WebDriverBy::id('cpf'))
            ->sendKeys($this->user->cpf);
            self::$driver->findElement(WebDriverBy::id('password'))
            ->sendKeys("password");
            
            self::$driver->findElement(WebDriverBy::tagName('form'))->submit();
            
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('open-registrations'))
            );
            
            self::$driver->findElement(WebDriverBy::xpath('//a[@href="http://candidato:8000/notice/'.$this->notice->id.'"]'))->click();

            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('notice-offer-table'))
            );
            self::$driver->findElement(WebDriverBy::linkText('Selecionar'))->click();

            $radiosElement = self::$driver->findElement(WebDriverBy::xpath('//input[@type="radio"]'));
            $radios = new WebDriverRadios($radiosElement);
            $radios->selectByVisibleText('Prova');
            
        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            $this->assertTrue(false);
            self::$driver->quit();
        }
    }

    /**
     * Arrange:
     * Dado que estou na página inicial, sem estar logado,
     * 
     * Act:
     * quero clicar no botão "entrar" e 
     * informar login e senha válidos, escolher um edital de Enem
     * 
     * Assert:
     * Então devo conseguir me inscrever em alguma oferta
     * 
     * @return void
     */
    public function testSubscriptionEnem()
    {
        try {   
            $notice = Notice::factory()->make();
            $selectionCriteria = SelectionCriteria::find(3);

            $data = $notice->toArray();
            $data['selection_criteria'] = [$selectionCriteria->id];
            $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                ->actingAs($this->userWithPermission)
                ->post(route('admin.notices.store'), $data);
            $this->notice = Notice::orderBy('id','desc')->first();
            Offer::factory()->create([
                'notice_id' => $this->notice->id,
            ]);
            $this->notice->subscription_final_date = now()->addDays(2);
            $this->notice->save();
            $this->setSelectionCriteriaInAllOffers($this->notice, $selectionCriteria);

            self::$driver->manage()->window()->maximize();

            self::$driver->findElement(WebDriverBy::id('cpf'))
            ->sendKeys($this->user->cpf);
            self::$driver->findElement(WebDriverBy::id('password'))
            ->sendKeys("password");
            
            self::$driver->findElement(WebDriverBy::tagName('form'))->submit();
            
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('open-registrations'))
            );
            
            self::$driver->findElement(WebDriverBy::xpath('//a[@href="http://candidato:8000/notice/'.$this->notice->id.'"]'))->click();

            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('notice-offer-table'))
            );
            self::$driver->findElement(WebDriverBy::linkText('Selecionar'))->click();

            $radiosElement = self::$driver->findElement(WebDriverBy::xpath('//input[@type="radio"]'));
            $radios = new WebDriverRadios($radiosElement);
            $radios->selectByVisibleText('Enem');

            self::$driver->findElement(WebDriverBy::name('criteria_3_linguagens_codigos_e_tecnologias'))
            ->sendKeys('900');
            self::$driver->findElement(WebDriverBy::name('criteria_3_matematica_e_suas_tecnologias'))
            ->sendKeys('900');
            self::$driver->findElement(WebDriverBy::name('criteria_3_ciencias_humanas_e_suas_tecnologias'))
            ->sendKeys('900');
            self::$driver->findElement(WebDriverBy::name('criteria_3_ciencias_da_natureza_e_suas_tecnologias'))
            ->sendKeys('900');
            self::$driver->findElement(WebDriverBy::name('criteria_3_redacao'))
            ->sendKeys('900');

            $inputDocumento = self::$driver->findElement((WebDriverBy::name('documento_comprovacao')));
            $inputDocumento->sendKeys(array('/opt/google/chrome/product_logo_64.png'));

        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            $this->assertTrue(false);
            self::$driver->quit();
        }
    }

    /**
     * Arrange:
     * Dado que estou na página inicial, sem estar logado,
     * 
     * Act:
     * quero clicar no botão "entrar" e 
     * informar login e senha válidos, escolher um edital de Análise de Currículo
     * 
     * Assert:
     * Então devo conseguir me inscrever em alguma oferta
     * 
     * @return void
     * @to-do
     */
    public function testSubscriptionAnaliseCurriculo()
    {
        try {   
            $notice = Notice::factory()->make();
            $selectionCriteria = SelectionCriteria::find(4);

            $data = $notice->toArray();
            $data['selection_criteria'] = [$selectionCriteria->id];
            $response = $this->withoutMiddleware(VerifyCsrfToken::class)
                ->actingAs($this->userWithPermission)
                ->post(route('admin.notices.store'), $data);
            $this->notice = Notice::orderBy('id','desc')->first();
            Offer::factory()->create([
                'notice_id' => $this->notice->id,
            ]);
            $this->notice->subscription_final_date = now()->addDays(2);
            $this->notice->save();
            $this->setSelectionCriteriaInAllOffers($this->notice, $selectionCriteria);

            self::$driver->manage()->window()->maximize();

            self::$driver->findElement(WebDriverBy::id('cpf'))
            ->sendKeys($this->user->cpf);
            self::$driver->findElement(WebDriverBy::id('password'))
            ->sendKeys("password");
            
            self::$driver->findElement(WebDriverBy::tagName('form'))->submit();
            
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('open-registrations'))
            );
            
            self::$driver->findElement(WebDriverBy::xpath('//a[@href="http://candidato:8000/notice/'.$this->notice->id.'"]'))->click();

            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('notice-offer-table'))
            );
            self::$driver->findElement(WebDriverBy::linkText('Selecionar'))->click();

            $radiosElement = self::$driver->findElement(WebDriverBy::xpath('//input[@type="radio"]'));
            $radios = new WebDriverRadios($radiosElement);
            $radios->selectByVisibleText('Análise de Currículo');

            self::$driver->findElement(WebDriverBy::name('criteria_4_media_regular'))
            ->sendKeys('10');

            $inputDocumento = self::$driver->findElement((WebDriverBy::name('documento_comprovacao')));
            $inputDocumento->sendKeys(array('/opt/google/chrome/product_logo_64.png'));

        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            $this->assertTrue(false);
            self::$driver->quit();
        }
    }
     
}
