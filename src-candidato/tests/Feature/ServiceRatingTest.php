<?php

namespace Tests\Feature;


use App\Http\Controllers\Candidate\ServiceRatingController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ServiceRatingTest extends TestCase
{
    public $protocolo = 'KLJHGKH8.abcd123X1236';

    public function getServiceRating(){
        return new ServiceRatingController($this->protocolo,'00333472047', 6544, 'inscrição em processo seletivo');
    }


    /**
     * @group desativado
     */
    public function testRegistraAcompanhamento()
    {
        $ServiceAssessment = $this->getServiceRating();
        $response = $ServiceAssessment->registrarAcompanhamento();
        if($response->status() != 201){
            var_dump($response->body());
        }
        $this->assertEquals(201, $response->status());
    }

    /**
     * @group desativado
     */
    public function testConcluiServico()
    {
        $ServiceAssessment = $this->getServiceRating();
        $response = $ServiceAssessment->concluirServico();
        if($response->status() != 200){
            var_dump($response->body());
        }
        $this->assertEquals(200, $response->status());
    }

    /**
     * @group desativado
     */
    public function testGerarLinkAvaliacao(){
        $ServiceAssessment = $this->getServiceRating();
        $response = $ServiceAssessment->gerarLinkDeAvaliacao();
        if($response->status() != 200){
            var_dump($response->body());
        }
        $this->assertEquals(200, $response->status());
    }

    /*
    Devido à falta de API de homologação estes testes foram desativados para não gerar avaliações falsas em produção
    */
    /**
     * @group desativado
     */
    public function testLinkForm(){
        $ServiceAssessment = $this->getServiceRating();
        $ServiceAssessment->gerarLinkDeAvaliacao();
        $response = $ServiceAssessment->getLinkForm();
        //log($response);
        $this->assertStringContainsString("https", $response);
    }

    /**
     * retorna TRUE somente depois de respondido o formulario de avaliação
     * para pegar o link é necessário descomentar a linha log() no testLinkForm() e executar esse teste
     */
    /**
     * @group desativado
     */
    public function testExisteAcompanhamento(){
        $ServiceAssessment = $this->getServiceRating();
        $response = $ServiceAssessment->existeAcompanhamento();
        $this->assertEquals(true, $response);
    }

}
