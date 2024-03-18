<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectionCriteria extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'description',
        'details',
        'is_customizable'
    ];

    public function distributionOfVacancies()
    {
        return $this->hasMany('App\Models\Process\DistributionOfVacancies');
    }
}
