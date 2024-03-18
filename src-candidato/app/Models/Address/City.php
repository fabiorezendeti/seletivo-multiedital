<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'state_id',
        'ibge_code'
    ];

    public function state()
    {
        return $this->belongsTo('App\Models\Address\State');
    }
    
}
