<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusOffer extends Model
{
    use HasFactory;

    protected $table = 'course_campus_offers';

    protected $fillable = [
        'website',
        'course_shift_id',
        'campus_id',
        'course_id',
        'sisu_course_code'
    ];    

    public function campus()
    {
        return $this->belongsTo('App\Models\Organization\Campus');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course\Course');
    }

    public function shift()
    { 
        return $this->belongsTo('App\Models\Course\Shift','course_shift_id');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Process\Offer','course_campus_offer_id');
    }

    public function scopeByModality($query,Modality $modality)
    {
        return $query->where('course.modality_id',$modality->id);
    }

}
