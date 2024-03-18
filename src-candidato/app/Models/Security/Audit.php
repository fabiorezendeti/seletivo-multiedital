<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'uri',
        'method',
        'content',
        'ip',
        'user_agent',
        'referer'
    ];


    /**
     * Não tem uma chave estrangeira de verdade na migration,
     * isso porque um usuário pode excluir sua conta senão participou de nenhum
     * processo seletivo.
     * Porém o log ficará mantido! O id do usuário é guardado para fins de 
     * auditoria
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
