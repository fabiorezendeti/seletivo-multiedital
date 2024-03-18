<?php

namespace App\Services\Financial;

class GRUPayment {

    public $cpf;
    public $subscription;
    public $date;
    public $value;
    public $possibleSubscription = null;    
    public string $line;
    
    public function __construct($line, string $cpf,string $subscription, string $date,string $value)
    {
        $this->cpf = $cpf;
        $this->subscription = $subscription;
        $this->date = $date;
        $this->value = $value;                
        $this->line = $line;
    }

    public function toArray() {
        return (array) $this;
    }

}