<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    public $fillable = [
        'street',
        'number',
        'district',
        'zip_code',
        'city_id',
        'phone_number',
        'alternative_phone_number',
        'has_whatsapp',
        'has_telegram',
        'complement'
    ];

    public function city()
    {
        return $this->belongsTo('App\Models\Address\City');
    }
    
    public function setHasWhatsAppAttribute($value)
    {
        $this->attributes['has_whatsapp'] = ($value) ?? false;
    }

    public function setHasTelegramAttribute($value)
    {
        $this->attributes['has_telegram'] = ($value) ?? false;
    }

}
