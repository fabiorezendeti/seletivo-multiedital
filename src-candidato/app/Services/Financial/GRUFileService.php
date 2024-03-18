<?php

namespace App\Services\Financial;

use App\Models\Process\Notice;
use App\Models\Process\Subscription;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Object_;

class GRUFileService
{

    private array $paymentList = [];
    private array $errorList  = [];
    private array $notFoundList  = [];
    private array $previousHomologate = [];
    private Notice $notice;


    public function process(string $file, Notice $notice): void
    {
        $this->notice = $notice;
        foreach (file($file) as $line) {
            $this->lineConverter($line);
        }
    }

    public function processXML(string $file, Notice $notice) : void
    {
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $this->notice = $notice;
        $content = file_get_contents($file);
        $xmlObject = simplexml_load_string($content);
        try {
            foreach ($xmlObject->gru as $gru) {                                
                
                $this->processXMLEntry($gru);
            }
        } catch (Exception $exception) {
            Log::error('Erro ao ler arquivo xml do sisgru',['GRUFileService','ProcessXML']);
        }
        
    }

    public function processXMLEntry($gruEntry)
    {        
        try {
            $value = $gruEntry->vlTotal;
            $gruEntry->numReferencia;
            $tamanho = strlen($gruEntry->codigoRecolhedor);
            $cpf = substr($gruEntry->codigoRecolhedor,$tamanho-11,$tamanho);
            $cpf = $this->applyMask($cpf, '###.###.###-##');
            $date = $gruEntry->dtTransferencia;            
            $subscription_number = (int) $gruEntry->numReferencia;
            $this->homologate(new GRUPayment($gruEntry, $cpf, $subscription_number, $date, $value));
        } catch (LineReaderException $exception) {
            $this->errorList[] = 'Inscrição: ' . $subscription_number . ' Erro: ' . $exception->getMessage() . ' -> ' . json_encode($gruEntry);
        } catch (Exception $exception) {
            $this->errorList[] = 'Erro Genérico na linha: ' . json_encode($gruEntry) . $exception->getMessage();
        }
    }

    public function getPaymentList(): array
    {
        return $this->paymentList;
    }

    public function getErrorList(): array
    {
        return $this->errorList;
    }

    public function getPreviousHomologate(): array
    {
        return $this->previousHomologate;
    }

    public function getNotFoundList(): array
    {
        return $this->notFoundList;
    }

    private function lineConverter($line): void
    {
        $line = strip_tags($line);
        $line = filter_var($line, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
        try {
            if (substr($line, 0, 1) == '*') {
                throw new Exception('Cabeçalho');
            }
            $cpf = substr($line, 59, 11);
            $cpf = $this->applyMask($cpf, '###.###.###-##');
            $date = substr($line, 32, 8);
            $date = date('d/m/Y', strtotime($this->applyMask($date, '####-##-##')));
            $subscription = substr($line, 83, 10);
            $value = substr($line, 106, 4);
            $value = number_format(($value / 100), 2, '.', '');
            if ($value < $this->notice->registration_fee) throw new LineReaderException('Valor incompatível');
            $payment = new GRUPayment($line, $cpf, $subscription, $date, $value);
            $this->homologate($payment);
        } catch (LineReaderException $exception) {
            $this->errorList[] = 'Inscrição: ' . $subscription . ' Erro: ' . $exception->getMessage() . ' -> ' . $line;
        } catch (Exception $exception) {
            $this->errorList[] = 'Erro Genérico na linha: ' . $line . $exception->getMessage();
        }
    }

    private function homologate(GRUPayment $payment)
    {
        $subscription = $this->notice->subscriptions()->where(
            'subscription_number',
            $payment->subscription
        )->first();
        if (!$subscription) {
            $subscription = $this->notice
                ->subscriptions()
                ->whereHas('user', function ($q) use ($payment) {
                    $q->where('cpf', $payment->cpf);
                })
                ->first();
            $payment->possibleSubscription = $subscription;
            return $this->notFoundList[] = $payment;
        };
        if (!$subscription) {
            return $this->notFoundList[] = $payment;
        }        
        if (!$subscription->is_homologated && $subscription->cpf === $payment->cpf) {
            $subscription->is_homologated = true;
            $subscription->save();
            $payment->possibleSubscription = $subscription;
            return $this->paymentList[] = $payment;
        }
        if ($subscription->cpf !== $payment->cpf) {
            $payment->possibleSubscription = $subscription;
            return $this->notFoundList[] = $payment;
        }
        $payment->possibleSubscription = $subscription;
        $this->previousHomologate[] = $payment;
    }

    private function applyMask($val, $mask)
    {
        $masked = '';
        $j = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$j]))
                    $masked .= $val[$j++];
            } else {
                if (isset($mask[$i]))
                    $masked .= $mask[$i];
            }
        }
        return $masked;
    }
}
