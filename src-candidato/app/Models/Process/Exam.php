<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    public $fillable = [
        'notice_id',
        'title',   
    ];

    public function notice()
    {
        return $this->belongsTo('App\Models\Process\Notice');
    }

    public function answerTemplates()
    {
        return $this->hasMany('App\Models\Process\AnswerTemplate');
    }
}
