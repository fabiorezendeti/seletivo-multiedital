<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Jobs\ScheduleEmailSend;
use App\Mail\ServiceRatingMail;
use App\Notifications\ServiceRating;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\GraphViz\Exception;

/*
 *  Manuais de uso da API de Avaliação e Acompanhamento:
 *  https://manual-avaliacao.servicos.gov.br/pt_BR/latest/
 *
 *  Teste unitário:
 *  php artisan test --filter testExisteAcompanhamento tests/Feature/ServiceRatingTest.php
 */

class ServiceRatingController extends Controller
{
    private $etapa = "[etapa]";
    private $situacaoEtapa = "[situacaoEtapa]";
    private $protocolo, $cpf, $cod_servico, $subject, $content;
    private $login, $password;
    private $orgao, $canal;

    private $linkForm;

    /**
     * @param string $protocolo id da inscrição
     * @param string $cpf CPF do candidato
     * @param string $subject Assunto do email
     * @param string $content Informação no corpo do email
     * @param string $cod_servico
     */
    public function __Construct($protocolo, $cpf, $cod_servico, $subject, $content)
    {
        $this->protocolo = $protocolo;
        $this->cpf = $cpf;
        $this->cod_servico = $cod_servico;
        $this->subject = $subject;
        $this->content = $content;

        switch ($cod_servico) {
            case 6544 :
                $this->etapa = "Inscrição.";
                $this->situacaoEtapa = "Inscrição nível médio/técnico."; // <- atenção! a variavel situação etapa tem um tamanho limite não especificado na API
                break;
            case 6693 :
                $this->etapa = "Inscrição.";
                $this->situacaoEtapa = "Inscrição nível superior."; // <- atenção! a variavel situação etapa tem um tamanho limite não especificado na API
                break;
            case 6598 :
                $this->etapa = "Matrícula.";
                $this->situacaoEtapa = "Matrícula em curso superior."; // <- atenção! a variavel situação etapa tem um tamanho limite não especificado na API
                break;
        }

        $this->login = env('AVALIACAO_SERVICO_USERLOGIN');
        $this->password = env('AVALIACAO_SERVICO_USERPASSWORD');
        $this->orgao = env('AVALIACAO_SERVICO_ORGAO');
        $this->canal = env('AVALIACAO_SERVICO_CANAL');

    }

    public function exec()
    {
        /*
         * O algoritmo leva em consideração que será registrado apenas um acompanhamento por serviço
         * antes de gerar o formulário de avaliação (processo em sequência).
         * Para mais de um acompanhamento a lógica precisará ser alterada para múltiplos cadastros e geração de formulário de avaliação
         * com disparo por método individual (não sequêncial).
         */
        if ($this->existeAcompanhamento())
            return;

        $response = $this->registrarAcompanhamento();
        if ($response->status() != 201)
            throw new \Exception("Não foi possível registrar o acompanhamento \n" . $response->json . "\n" . $response->body());

        $response = $this->concluirServico();
        if ($response->status() != 200)
            throw new \Exception("Não foi possível concluir o acompanhamento \n" . $response->json . "\n" . $response->body());

        $response = $this->gerarLinkDeAvaliacao();
        if ($response->status() != 200)
            throw new \Exception("Não foi possível gerar o link de avaliação \n" . $response->json . "\n" . $response->body());

        $this->agendarNotificacao();
    }

    public function registrarAcompanhamento(): Response
    {
        $json = '{"cpfCidadao": "' . $this->cpf . '",
        "dataEtapa": "' . Carbon::now()->format("d/m/Y") . '",
        "dataSituacaoEtapa": "' . Carbon::now()->format("d/m/Y") . '",
        "etapa": "' . $this->etapa . '",
        "orgao": "' . $this->orgao . '",
        "protocolo": "' . $this->protocolo . '",
        "servico": "' . $this->cod_servico . '",
        "situacaoEtapa": "' . $this->situacaoEtapa . '"
        }';
        $response = Http::withBasicAuth($this->login, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json;charset=UTF-8'
            ])
            ->withBody($json, 'application/json')
            ->post(env('AVALIACAO_SERVICO_URL_ACOMPANHAMENTO') . '/api/acompanhamento/');
        $response->json = $json;
        return $response;
    }

    public function concluirServico(): Response
    {
        $json = '{
        "cpfCidadao": "' . $this->cpf . '",
        "orgao": "' . $this->orgao . '",
        "protocolo": "' . $this->protocolo . '",
        "servico": "' . $this->cod_servico . '",
        "situacaoServico": "2"
        }';
        $response = Http::withBasicAuth($this->login, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json;charset=UTF-8',
                'Accept' => 'application/json'
            ])
            ->withBody($json, 'application/json')
            ->put(env('AVALIACAO_SERVICO_URL_ACOMPANHAMENTO') . '/api/acompanhamento/situacao');
        $response->json = $json;
        return $response;
    }

    public function gerarLinkDeAvaliacao(): Response
    {
        $json = '{
        "canalAvaliacao": "1",
        "canalPrestacao": "' . $this->canal . '",
        "cpfCidadao": "' . $this->cpf . '",
        "etapa": "' . $this->etapa . '",
        "orgao": "' . $this->orgao . '",
        "protocolo": "' . $this->protocolo . '",
        "servico": "' . $this->cod_servico . '"
        }';
        $response = Http::withBasicAuth($this->login, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json;charset=UTF-8',
                'Accept' => 'application/json'
            ])
            ->withBody($json, 'application/json')
            ->post(env('AVALIACAO_SERVICO_URL_AVALIACAO') . '/api/avaliacao/formulario');

        if ($response->status() == 200) {
            $this->linkForm = (json_decode($response->body()))->location;
        }
        $response->json = $json;
        return $response;
    }

    public function getLinkForm()
    {
        return $this->linkForm;
    }

    public function agendarNotificacao()
    {
        $user = Auth::user();

        $quando = env('APP_DEBUG') ? now() : now()->addDay(1);

        Mail::to($user->email)
            ->later($quando, new ServiceRatingMail($this->subject, $this->content, $user->name, $this->linkForm));
    }

    public function foiRespondida()
    {
        $ar = array(
            "cpfCidadao" => $this->cpf,
            "servico" => $this->cod_servico,
            "protocolo" => $this->protocolo,
            "orgao" => $this->orgao,
            "etapa" => $this->etapa
        );
        $response = Http::withBasicAuth($this->login, $this->password)
            ->withHeaders([
                'Content-Type' => 'application/json;charset=UTF-8',
                'Accept' => 'application/json'
            ])->get(env('AVALIACAO_SERVICO_URL_AVALIACAO') . '/api/avaliacao', $ar);

        return $response->status() == 200 ? true : false;
    }

    public function existeAcompanhamento()
    {
        $ar = array(
            "cpfCidadao" => $this->cpf,
            "servico" => $this->cod_servico,
            "protocolo" => $this->protocolo
        );

        $response = Http::withBasicAuth($this->login, $this->password)
            ->get(env('AVALIACAO_SERVICO_URL_ACOMPANHAMENTO') . '/api/acompanhamento/porProtocolo', $ar);

        if ($response->status() == 200)
            return true;

        $json = json_decode($response->body());
        if ($response->status() == 404 && $json->status == "NOT_FOUND")
            return false;

        throw new \Exception("Retorno diferente do esperado: " . $json);
    }
}
