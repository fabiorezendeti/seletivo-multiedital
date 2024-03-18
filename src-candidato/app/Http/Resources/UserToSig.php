<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserToSig extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'cpf' => $this->cpf,
            'nome_oficial' => $this->nome_oficial,
            'nome_social' => $this->nome_social,
            'email' => $this->email,
            'nome_mae' => $this->nome_mae,
            'nome_pai' => $this->nome_pai,
            'genero_oficial' => $this->genero_oficial,
            'genero_social' => $this->genero_social,
            'data_nascimento' => $this->data_nascimento,
            'estado_civil' => $this->estado_civil,
            'cor_raca' => $this->cor_raca,
            'tipo_escola_ensino_medio' => $this->tipo_escola_ensino_medio,
            'nome_escola_ensino_medio' => $this->nome_escola_ensino_medio,
            'ano_conclusao' => $this->ano_conclusao,
            'tipo_sanguineo' => $this->tipo_sanguineo,
            'rg_numero' => $this->rg_numero,
            'rg_orgao_expedidor' => $this->rg_orgao_expedidor,
            'rg_data_expedicao' => $this->rg_data_expedicao,
            'titulo_eleitor_numero' => $this->titulo_eleitor_numero,
            'titulo_eleitor_zona' => $this->titulo_eleitor_zona,
            'titulo_eleitor_sessao' => $this->titulo_eleitor_sessao,
            'titulo_eleitor_uf' => $this->titulo_eleitor_uf,
            'titulo_eleitor_data_expedicao' => $this->titulo_eleitor_data_expedicao,
            'endereco_cep' => $this->endereco_cep,
            'endereco_logradouro_tipo' => $this->endereco_logradouro_tipo,
            'endereco_logradouro_nome' => $this->endereco_logradouro_nome,
            'endereco_numero' => $this->endereco_numero,
            'endereco_complemento' => $this->endereco_complemento,
            'endereco_bairro' => $this->endereco_bairro,
            'endereco_cidade' => $this->endereco_cidade,
            'endereco_estado' => $this->endereco_estado,
            'telefone_fixo' => $this->telefone_fixo,
            'telefone_celular' => $this->telefone_celular
        ];
    }
}
