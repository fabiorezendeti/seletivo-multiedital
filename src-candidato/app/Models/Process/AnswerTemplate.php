<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerTemplate extends Model
{
    use HasFactory;

    public $fillable = [
        'exam_id',
        'question_number',
        'right_answer',
        'weight',
        'is_canceled',
        'area_id'
    ];

    public function exam()
    {
        return $this->belongsTo('App\Models\Process\Exam');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Process\KnowledgeArea');
    }
}
