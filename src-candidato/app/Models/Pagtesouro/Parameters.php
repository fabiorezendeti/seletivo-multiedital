<?php

namespace App\Models\Pagtesouro;

use Illuminate\Support\Collection;


class Parameters
{

    public string $pagtesouro_url_solicitacao_pagamento;
    public string $pagtesouro_url_consulta_pagamento;
    public string $pagtesouro_cod_servico;
    public string $pagtesouro_token;

    public function setFromCollection(Collection $parameterList)
    {
        foreach ($parameterList as $item) {            
            $name = $item->name;
            $this->$name = $item->value;
        }        
    }

}