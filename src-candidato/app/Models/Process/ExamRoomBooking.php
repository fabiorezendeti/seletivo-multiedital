<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRoomBooking extends Model
{
    use HasFactory;

    public $fillable = [
        'notice_id',
        'location_id',
        'name',
        'capacity',
        'for_special_needs',
        'priority',
        'active'
    ];

    public function notice()
    {
        return $this->belongsTo('App\Models\Process\Notice');
    }
    public function examLocation()
    {
        return $this->belongsTo('App\Models\Process\ExamLocation');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Process\Subscription');
    }

    public function scopeIsForSpecialNeeds($query)
    {
        return $query->where('for_special_needs', 'true');
    }

    public function scopeIsActivated($query)
    {
        return $query->where('active',true);
    }
}
