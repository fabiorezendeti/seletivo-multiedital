<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToSig extends Model
{
    use HasFactory;

    public $fillable = [        
        'cpf',
        'nome_oficial',
        'nome_social',
        'email',
        'nome_mae',
        'nome_pai',
        'genero_oficial',
        'genero_social',
        'data_nascimento',
        'estado_civil',
        'cor_raca',
        'tipo_escola_ensino_medio',
        'nome_escola_ensino_medio',
        'ano_conclusao',
        'tipo_sanguineo',
        'rg_numero',
        'rg_orgao_expedidor',
        'rg_uf',
        'rg_data_expedicao',
        'titulo_eleitor_numero',
        'titulo_eleitor_zona',
        'titulo_eleitor_sessao',
        'titulo_eleitor_uf',
        'titulo_eleitor_ddata_expedicao',
        'endereco_cep',
        'endereco_logradouro_tipo',
        'endereco_logradouro_nome',
        'endereco_numero',
        'endereco_complemento',
        'endereco_bairro',
        'endereco_cidade',
        'endereco_estado',
        'telefone_fixo',
        'telefone_celular'
    ];
}
