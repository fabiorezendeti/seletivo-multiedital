<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Justify extends Model
{
    use HasFactory;    

    public $fillable = [
        'justify',
        'data',
        'author_id',
        'uri'
    ];
    
}
