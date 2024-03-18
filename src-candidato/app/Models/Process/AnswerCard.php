<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerCard extends Model
{
    use HasFactory;

    public $fillable = [
        'notice_id',
        'subscription_id',
        'subscription_number',
        'exam_id',
        'is_absent',
        'answer'
    ];


}
