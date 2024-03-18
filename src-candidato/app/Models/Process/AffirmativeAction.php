<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AffirmativeAction extends Model
{
    use HasFactory;

    public $fillable = [
        'description',
        'slug',
        'is_wide_competition',
        'is_ppi',
        'classification_priority'
    ];

    public $hidden = [
        'created_at',
        'deleted_at',
        'updated_at'
    ];
    
    public function migrationVacancyMap()
    {
        return $this->hasMany('App\Models\Process\MigrationVacancyMap')
            ->orderBy('order','asc');
    }

    public function distributionOfVacancies()
    {
        return $this->hasMany('App\Models\Process\DistributionOfVacancies');
    }
    
    public function modalities()
    {
        return $this->belongsToMany('App\Models\Course\Modality');
    }

    public function documentTypes()
    {
        return $this->belongsToMany(DocumentType::class);
    }

}
