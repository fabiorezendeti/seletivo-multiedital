<?php

namespace App\Models\Process;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentExemption extends Model
{
    // use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_id_front',
        'document_id_back',
        'document_form'
    ];

    protected  $dates = [
    ];

    protected $appends = ['status'];

    protected $casts = [
    ];

    protected $hidden = [];

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Process\Subscription');
    }

    public function getStatusAttribute() : string
    {
        $status = [
            null => 'Não avaliado',
            1   => 'Deferido',
            0   => 'Indeferido',
        ];
        return $status[$this->is_accepted];
    }


}
