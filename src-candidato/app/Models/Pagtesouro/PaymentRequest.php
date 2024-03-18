<?php

namespace App\Models\Pagtesouro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    public $fillable = [        
        'subscription_id',
        'request',
        'idPagamento',
        'situacao_codigo',
        'proxima_url'
    ];
        

    public function subscription()
    {
        return $this->belongsTo('App\Models\Process\Subscription');
    }
}
