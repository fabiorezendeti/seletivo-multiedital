<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory;    

    protected $fillable = [
        'name',
        'modality_id',        
    ];
    

    public function modality()
    {
        return $this->belongsTo('App\Models\Course\Modality');
    }

    public function campusesOffer()
    {
        return $this->hasMany('App\Models\Course\CampusOffer');
    }
}
