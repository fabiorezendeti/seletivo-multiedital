<?php

namespace App\Models\Address;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'slug',
        'name'
    ];

    public function getString()
    {
        return $this->slug . ' - ' . $this->name;
    }

    public function cities()
    {
        return $this->hasMany('App\Models\Address\City');
    }
}
