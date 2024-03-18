<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialNeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'activated',
        'require_details'
    ];
    

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Process\Subscription');
    }

    public function scopeIsActivated($query)
    {
        return $query->where('activated',true);
    }

}
