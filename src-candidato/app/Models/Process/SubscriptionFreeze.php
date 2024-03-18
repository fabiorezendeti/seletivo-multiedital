<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFreeze extends Model
{
    use HasFactory;


    protected $fillable = [
        'content'
    ];
    
}
