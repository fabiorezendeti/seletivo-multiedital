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

class PaymentTest extends TestCase
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
        
        if(count(self::$driver->findElements(WebDriverBy::linkText('ENTRAR'))) > 0){
            self::$driver->findElement(WebDriverBy::linkText('ENTRAR'))
            ->click();
            self::$driver->wait(10, 500)->until(
                function ($driver) {
                    return $driver->getCurrentURL() === 'http://candidato:8000/login';
                });
            $this->user = User::factory()->create();
    
            $this->makeSubscriptionSorteio(true);
        }else{
            self::$driver->findElement(WebDriverBy::partialLinkText('Meu'))
            ->click();
            $this->makeSubscriptionSorteio(false);
        }
        
        
    }

    public function tearDown(): void
    {
        for ($i=0; $i < 6; $i++) { 
            self::$driver->findElement(WebDriverBy::tagName('body'))->sendKeys(array(WebDriverKeys::PAGE_UP));
        }
        self::$driver->switchTo()->defaultContent();
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

    public function makeSubscriptionSorteio(bool $newUser)
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
            $this->notice->registration_fee = 1.10;
            $this->notice->payment_date = now()->addDays(2);
            $this->notice->pagtesouro_activated = true;
            $this->notice->subscription_final_date = now()->addDays(2);
            $this->notice->save();
            $this->setSelectionCriteriaInAllOffers($this->notice, $selectionCriteria);

            self::$driver->manage()->window()->maximize();
            if($newUser){
                self::$driver->findElement(WebDriverBy::id('cpf'))
                ->sendKeys($this->user->cpf);
                self::$driver->findElement(WebDriverBy::id('password'))
                ->sendKeys("password");
                
                self::$driver->findElement(WebDriverBy::tagName('form'))->submit();
            }
            
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
        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            $this->assertTrue(false);
            self::$driver->quit();
        }
    }

    /**
     * Arrange:
     * Dado que estou na página de comprovante de inscrição (ticket)
     * 
     * Act:
     * quero fechar o popup de lembrete de pagamento e (button class="modal-close")
     * clicar em Pagamento
     * 
     * Assert:
     * Então devo conseguir chegar na tela do QR Code PIX
     * 
     * @return void
     */
    public function testPaymentPix()
    {
        try {   
            self::$driver->findElement(WebDriverBy::className('modal-close'))->click();
            self::$driver->findElement(WebDriverBy::linkText('Pagamento'))->click();      
            self::$driver->findElement(WebDriverBy::id('payment-pagtesouro-form'))->submit();
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('iframe-epag'))
            );
            sleep(10);
            $iframe = self::$driver->findElement(WebDriverBy::className('iframe-epag'));
            self::$driver->switchTo()->frame($iframe);
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOf(self::$driver->findElement(WebDriverBy::linkText('Pix')))
            );
            sleep(10);
            self::$driver->findElement(WebDriverBy::linkText('Pix'))->click();
            self::$driver->switchTo()->defaultContent();
            for ($i=0; $i < 2; $i++) { 
                self::$driver->findElement(WebDriverBy::tagName('body'))->sendKeys(array(WebDriverKeys::PAGE_DOWN));
            }
            self::$driver->switchTo()->frame($iframe);
            self::$driver->findElement(WebDriverBy::id('btnPgto'))->click();
            sleep(10);
            $qrcode = self::$driver->findElement(WebDriverBy::className('qr-code-img'));
            $btnCopyQr = self::$driver->findElement(WebDriverBy::id('btn-copy-qr'));
            
            $this->assertSame($qrcode->getTagName(), "img", "QR Code do PIX não apareceu");
            $this->assertNotNull($btnCopyQr->getAttribute('data-clipboard-text'), "Botão de copiar QR Code não apareceu");

        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            $this->assertTrue(false);
            self::$driver->quit();
        }
    }


    /**
     * Arrange:
     * Dado que estou na página de comprovante de inscrição (ticket)
     * 
     * Act:
     * quero fechar o popup de lembrete de pagamento e (button class="modal-close")
     * clicar em Pagamento
     * 
     * Assert:
     * Então devo conseguir chegar na tela de Pagamento com Cartão
     * 
     * @return void
     */
    public function testPaymentCartao()
    {
        try {   
            self::$driver->findElement(WebDriverBy::className('modal-close'))->click();
            self::$driver->findElement(WebDriverBy::linkText('Pagamento'))->click();      
            self::$driver->findElement(WebDriverBy::id('payment-pagtesouro-form'))->submit();
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('iframe-epag'))
            );
            sleep(6);
            $iframe = self::$driver->findElement(WebDriverBy::className('iframe-epag'));
            self::$driver->switchTo()->frame($iframe);
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOf(self::$driver->findElement(WebDriverBy::partialLinkText('Cartão')))
            );
            sleep(6);
            self::$driver->findElement(WebDriverBy::partialLinkText('Cartão'))->click();
            sleep(6);
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('custom-control-input'))
            );
            self::$driver->findElement(WebDriverBy::className('custom-radio'))->click();
            
            self::$driver->switchTo()->defaultContent();
            for ($i=0; $i < 2; $i++) { 
                self::$driver->findElement(WebDriverBy::tagName('body'))->sendKeys(array(WebDriverKeys::PAGE_DOWN));
            }
            self::$driver->switchTo()->frame($iframe);
            self::$driver->findElement(WebDriverBy::id('btnPgto'))->click();
            sleep(6);
            $handleCount = self::$driver->getWindowHandles();
            self::$driver->switchTo()->window($handleCount[0]);
            sleep(6);
            self::$driver->switchTo()->frame($iframe);
            $this->assertTrue(self::$driver->findElement(WebDriverBy::className('aviso-enc-psp'))->isDisplayed(), "Usuário não foi encaminhado para janela do prestador do cartão");
            
        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            $this->assertTrue(false);
            self::$driver->quit();
        }
    }

    /**
     * Arrange:
     * Dado que estou na página de comprovante de inscrição (ticket)
     * 
     * Act:
     * quero fechar o popup de lembrete de pagamento e (button class="modal-close")
     * clicar em Pagamento
     * 
     * Assert:
     * Então devo conseguir gerar uma GRU
     * 
     * @return void
     */
    public function testPaymentGruPagTesouro()
    {
        try {   
            self::$driver->findElement(WebDriverBy::className('modal-close'))->click();
            self::$driver->findElement(WebDriverBy::linkText('Pagamento'))->click();      
            self::$driver->findElement(WebDriverBy::id('payment-pagtesouro-form'))->submit();
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::className('iframe-epag'))
            );
            sleep(10);
            $iframe = self::$driver->findElement(WebDriverBy::className('iframe-epag'));
            self::$driver->switchTo()->frame($iframe);
            self::$driver->wait(10, 1000)->until(
                WebDriverExpectedCondition::visibilityOf(self::$driver->findElement(WebDriverBy::partialLinkText('Cartão')))
            );
            sleep(10);
            self::$driver->findElement(WebDriverBy::partialLinkText('Boleto'))->click();
            sleep(10);
                        
            self::$driver->switchTo()->defaultContent();
            for ($i=0; $i < 2; $i++) { 
                self::$driver->findElement(WebDriverBy::tagName('body'))->sendKeys(array(WebDriverKeys::PAGE_DOWN));
            }
            self::$driver->switchTo()->frame($iframe);
            self::$driver->findElement(WebDriverBy::id('btnPgto'))->click();
            sleep(10);
            $handleCount = self::$driver->getWindowHandles();
            self::$driver->switchTo()->window($handleCount[0]);
            sleep(10);
            self::$driver->switchTo()->frame($iframe);
            $this->assertStringContainsStringIgnoringCase(
                "Boleto gerado com sucesso", 
                self::$driver->findElement(WebDriverBy::className('resumo-pgto-msg'))->getText(),
                "Usuário não foi encaminhado para GRU");
            
        } catch (Exception $e) {
            echo "EXCEPTION: " .$e->getMessage();
            $this->assertTrue(false);
            self::$driver->quit();
        }
    }
     
}
