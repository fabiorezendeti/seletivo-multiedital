<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeArea extends Model
{
    use HasFactory;

    public $fillable = [
        'name'
    ];
}
