<?php

namespace App\Models\Process;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Offer extends Model
{
    use HasFactory;
    

    public $fillable = [
        'notice_id',
        'course_campus_offer_id',
        'total_vacancies',        
    ];

    public function getString()
    {
        return $this->courseCampusOffer->campus->name . ' - ' . $this->courseCampusOffer->course->name ;
    }

    public function notice()
    {
        return $this->belongsTo('App\Models\Process\Notice');
    }

    public function courseCampusOffer()
    {
        return $this->belongsTo('App\Models\Course\CampusOffer','course_campus_offer_id');
    }

    public function distributionVacancies()
    {
        return $this->hasMany('App\Models\Process\DistributionOfVacancies');
    }

    public function getOfferSubscriptions(?SelectionCriteria $selectionCriteria)
    {
        return Subscription::where('notice_id',$this->notice_id)
            ->whereHas('distributionOfVacancy',function($q) use ($selectionCriteria){
                $q->where('offer_id',$this->id);                
                if ($selectionCriteria) $q->where('selection_criteria_id',$selectionCriteria->id);
            });
    }  
    

    public function scopeBySelectionCriteria($query,SelectionCriteria $selectionCriteria)
    {
        return $query->whereHas('distributionVacancies.selectionCriteria',function($q) use ($selectionCriteria) {
            $q->where('selection_criteria_id',$selectionCriteria->id);
        });
    }    

    public function getTotalHomologatedSubscriptions(?SelectionCriteria $selectionCriteria, $excludeElimination = false)
    {
         $query = $this->getOfferSubscriptions($selectionCriteria)->isHomologated();
         if ($excludeElimination) $query->IsNotEliminated();
         return $query->count();
    }

    public function getHomologatedSubscriptionsByCriteria(SelectionCriteria $selectionCriteria)
    {
        return $this->getOfferSubscriptions($selectionCriteria)->isHomologated()->get();
    }

    public function getTotalVacancies(?SelectionCriteria $selectionCriteria)
    {        
        $count = $this->distributionVacancies()
            ->select(DB::raw('sum (total_vacancies) as total'));
        if ($selectionCriteria) $count->groupBy('selection_criteria_id')->where('selection_criteria_id',$selectionCriteria->id);
        return $count->first()->total ?? 0;
    }

    public function getTotalSubscriptions(?SelectionCriteria $selectionCriteria)
    {
        return $this->getOfferSubscriptions($selectionCriteria)->count();
    }

    private function getOfferSubscriptionsByAffirmativeAction(AffirmativeAction $affirmativeAction,?SelectionCriteria $selectionCriteria)
    {
        return Subscription::where('notice_id',$this->notice_id)
            ->whereHas('distributionOfVacancy',function($q) use ($selectionCriteria,$affirmativeAction){
                $q->where('offer_id',$this->id);
                $q->where('affirmative_action_id',$affirmativeAction->id);
                if ($selectionCriteria) $q->where('selection_criteria_id',$selectionCriteria->id);                
            });
    }

    public function getTotalHomologatedSubscriptionsByAffirmativeAction(AffirmativeAction $affirmativeAction,?SelectionCriteria $selectionCriteria)
    {
        return $this->getOfferSubscriptionsByAffirmativeAction($affirmativeAction,$selectionCriteria)->isHomologated()->count();
    }

    public function getTotalSubscriptionsByAffirmativeAction(AffirmativeAction $affirmativeAction, ?SelectionCriteria $selectionCriteria)
    {
        return $this->getOfferSubscriptionsByAffirmativeAction($affirmativeAction,$selectionCriteria)->count();
    }

}
