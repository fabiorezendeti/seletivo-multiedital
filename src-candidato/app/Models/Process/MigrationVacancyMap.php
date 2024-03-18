<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MigrationVacancyMap extends Model
{
    use HasFactory;


    public $fillable = [                
        'affirmative_action_to_id',
        'order'
    ];

    public function affirmativeActionFrom()
    {
        return $this->belongsTo('App\Models\Process\AffirmativeAction','affirmative_action_id');
    }

    public function affirmativeActionTo()
    {
        return $this->belongsTo('App\Models\Process\AffirmativeAction','affirmative_action_to_id');
    }

}
