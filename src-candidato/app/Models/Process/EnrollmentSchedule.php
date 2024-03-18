<?php

namespace App\Models\Process;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentSchedule extends Model
{
    use HasFactory;

    protected $dates = [
        'start_date',
        'end_date'
    ];

    protected $fillable = [
        'start_date',
        'end_date',
        'selection_criteria_id',
        'call_number'
    ];

    public function notice()
    {
        return $this->belongsTo('App\Models\Process\Notice');
    }

    public function scopeEnrollmentOpened($query)
    {
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;
        return $query
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);
    }
}
