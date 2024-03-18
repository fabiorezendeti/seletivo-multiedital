<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionOfVacancies extends Model
{
    use HasFactory;

    public $fillable = [
        'offer_id',
        'affirmative_action_id',
        'selection_criteria_id',
        'total_vacancies'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function offer()
    {
        return $this->belongsTo('App\Models\Process\Offer');
    }

    public function selectionCriteria()
    {
        return $this->belongsTo('App\Models\Process\SelectionCriteria');
    }

    public function affirmativeAction()
    {
        return $this->belongsTo('App\Models\Process\AffirmativeAction');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Models\Process\Subscription','distribution_of_vacancies_id');
    }

    public function scopeByCriteria($query,SelectionCriteria $selectionCriteria)
    {
        return $query->where('selection_criteria_id',$selectionCriteria->id);
    }
        
    public function scopeOrderByAffirmativeActionPriority($query)
    {
        return $query->join(
            'affirmative_actions','affirmative_actions.id','=','distribution_of_vacancies.affirmative_action_id'
        )
        ->orderBy('affirmative_actions.classification_priority','desc');
    }

    public function scopeWhereLottery($query)
    {
        return $query->where('selection_criteria_id',1);
    }

    public function isLottery()
    {
        return $this->selection_criteria_id === 1;
    }

    public function isProva()
    {
        return $this->selection_criteria_id === 2;
    }

    public function isEnem()
    {
        return $this->selection_criteria_id === 3;
    }

    public function isCurriculum()
    {
        return $this->selection_criteria_id === 4;
    }    

    public function isSISU()
    {
        return $this->selection_criteria_id === 5;
    }

}
