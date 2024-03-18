<?php

namespace App\Models\Organization;

use App\Models\Process\Notice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campus extends Model
{
    use HasFactory;  

    public $fillable = [
        'name',
        'email',
        'site',
        'street',
        'number',
        'district',
        'zip_code',
        'phone_number',
        'city_id'
    ];

    public function city()
    {
        return $this->belongsTo('App\Models\Address\City');
    }

    public function examLocations()
    {
        return $this->hasMany('App\Models\Process\ExamLocation');
    }

    public function courseOffers()
    {
        return $this->hasMany('App\Models\Course\CampusOffer');
    }

    public function scopeWithVacanciesByNotice($query, Notice $notice)
    {
        return $query->whereHas('courseOffers.offers.distributionVacancies',function($q) use($notice){
            $q->where('notice_id',$notice->id);
        })->orderBy('name');
    }

}
