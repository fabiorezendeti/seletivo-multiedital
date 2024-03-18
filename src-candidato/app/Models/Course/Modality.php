<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modality extends Model
{
    use HasFactory;

    public $fillable = [
        'description',
        'slug'
    ];

    public function affirmativeActions()
    {
        return $this->belongsToMany('App\Models\Process\AffirmativeAction');
    }

}
