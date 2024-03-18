<?php

namespace App\Repository;

use App\Models\Process\Offer;
use App\Models\Process\Notice;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Process\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Models\Process\SelectionCriteria;
use App\Models\Process\DistributionOfVacancies;

class EnrollmentCallRepository
{

    public function countCallsByNotice(Notice $notice): array
    {
        $countCallsByCriteria = [];
        foreach ($notice->selectionCriterias as $selectionCriteria) {
            $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
            $count = DB::table("$table as calls")
                ->select(DB::raw("max(call_number) as last_call_number"))
                ->first();
            $last = $count->last_call_number ?? 0;
            $countCallsByCriteria[$selectionCriteria->id] = [
                'selectionCriteria'     => $selectionCriteria,
                'last_call_number'      => $last,
                'enrollmentSchedule'    => $notice->enrollmentSchedule()
                                                ->where('call_number',$last)
                                                ->where('selection_criteria_id',$selectionCriteria->id)
                                                ->first()
            ];
        }
        return $countCallsByCriteria;
    }

    public function callsByNotice(Notice $notice): Collection
    {
        try {
            $calls = collect();
            foreach ($notice->selectionCriterias as $selectionCriteria) {
                $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
                $call = DB::table("$table as calls")
                    ->select("call_number",DB::raw('count(*) as total'))
                    ->groupBy("call_number")
                    ->orderBy("call_number")
                    ->get();
                $calls = $calls->union($call->pluck("call_number"));
            }
            return $calls;
        } catch (QueryException $exception) {
            return collect();
        }
    }

    public function callsByOffer(Offer $offer): Collection
    {
        try {
            $calls = collect();
            $notice = $offer->notice;
            foreach ($notice->selectionCriterias as $selectionCriteria) {
                $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
                $call = DB::table("$table as calls")
                    ->select("call_number",DB::raw('count(*) as total'))
                    ->where('offer_id', $offer->id)
                    ->groupBy("call_number")
                    ->orderBy("call_number", "desc")
                    ->get();
                $calls = $calls->union($call->pluck("call_number"));
            }
            return $calls;
        } catch (QueryException $exception) {
            return collect();
        }
    }

    public function totalCandidatesPerCall(Notice $notice): Collection
    {
        try {
            $calls = collect();
            foreach ($notice->selectionCriterias as $selectionCriteria) {
                $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
                $call = DB::table("$table as calls")
                    ->select("call_number",DB::raw('count(*) as total'),DB::raw(
                        "(select count(*) from $table as tb where tb.call_number = calls.call_number and status = 'matriculado') as total_matriculado"
                    ))
                    ->groupBy("call_number")
                    ->orderBy("call_number")
                    ->get();
                $calls->push([
                    'selectionCriteria' => $selectionCriteria,
                    'calls' => $call
                ]);
            }
            return $calls;
        } catch (QueryException $exception) {
            Log::warning($exception->getMessage(),['Enrollment Call Repository 75']); ;
            return collect();
        }
    }

    public function countVacancyUsedByDistributionOfVacancy(DistributionOfVacancies $distributionOfVacancy)
    {
        return DB::table("{$this->getTableByDistributionVacancy($distributionOfVacancy)} as calls")
            ->select('calls.*')
            ->join('core.subscriptions as s', 'calls.subscription_id', '=', 's.id')
            ->where('distribution_vacancy_used_id', '=', $distributionOfVacancy->id)
            ->whereNull('s.elimination')
            ->where('status', '!=', 'não matriculado')
            ->count();
    }

    public function countVacancyUsedByOfferAndSelectionCriteria(Offer $offer, SelectionCriteria $selectionCriteria)
    {
        return DB::table("{$offer->notice->getEnrollmentCallTableNameByCriteria($selectionCriteria)} as calls")
            ->select('calls.*')
            ->join('core.subscriptions as s', 'calls.subscription_id', '=', 's.id')
            ->where('calls.offer_id', '=', $offer->id)
            ->whereNull('s.elimination')
            ->where('calls.status', '!=', 'não matriculado')
            ->count();
    }


    private function getTableByDistributionVacancy(DistributionOfVacancies $distributionOfVacancy)
    {
        return $distributionOfVacancy->offer->notice->getEnrollmentCallTableNameByCriteria(
            $distributionOfVacancy->selectionCriteria
        );
    }

    public function getPendingsByCriteria(Notice $notice, SelectionCriteria $selectionCriteria)
    {
        return DB::table($notice->getEnrollmentCallTableNameByCriteria($selectionCriteria))
            ->where('status', 'pendente')
            ->count() > 0;
    }

    public function getSubscriptionsInCalls(Offer $offer, SelectionCriteria $selectionCriteria)
    {
        $subscriptions = DB::table($offer->notice->getEnrollmentCallTableNameByCriteria($selectionCriteria))
            ->get();
    }

    public function getApprovedById(Notice $notice, SelectionCriteria $selectionCriteria, int $id)
    {
        $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
        return Subscription::select(
            'subscriptions.*',
            "call.id as call_id",
            "call.distribution_vacancy_need_id",
            "call.call_position",
            "call.is_wide_concurrency",
            "call.status",
            'aa.slug as affirmative_action_slug'
        )
            ->with('user')
            ->with('distributionOfVacancy.offer.courseCampusOffer.course')
            ->with('distributionOfVacancy.offer.courseCampusOffer.campus')
            ->join("$table as call", 'subscriptions.id', '=', 'call.subscription_id')
            ->join("distribution_of_vacancies as dv", 'dv.id', '=', 'call.distribution_vacancy_need_id')
            ->join("affirmative_actions as aa", 'aa.id', '=', 'dv.affirmative_action_id')
            ->where("call.id", $id)
            ->orderBy("call.distribution_vacancy_need_id")
            ->orderBy("call.call_position")
            ->first();
    }

    public function getApprovedListByNoticeAndCriteriaAndCallNumber(Notice $notice, SelectionCriteria $selectionCriteria, int $callNumber)
    {
        return $this->getApprovedListByNoticeAndCriteriaAndCallNumberAndSearch($notice, $selectionCriteria, $callNumber);
    }

    public function getApprovedListByNoticeAndCriteriaAndCallNumberAndSearch(Notice $notice, SelectionCriteria $selectionCriteria, int $callNumber, $search = '', $status = null)
    {
        $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
        $subscriptionSelect = Subscription::select(
            'subscriptions.*',
            "call.id as call_id",
            "call.distribution_vacancy_need_id",
            "call.call_position",
            "call.is_wide_concurrency",
            "call.status",
            'aa.slug as affirmative_action_slug'
        )
            // ->with('user')
            ->with('distributionOfVacancy.offer.courseCampusOffer.course')
            ->with('distributionOfVacancy.offer.courseCampusOffer.campus')
            ->join("$table as call", 'subscriptions.id', '=', 'call.subscription_id')
            ->join("users", 'users.id', '=', 'subscriptions.user_id')
            ->join("distribution_of_vacancies as dv", 'dv.id', '=', 'call.distribution_vacancy_need_id')
            ->join("affirmative_actions as aa", 'aa.id', '=', 'dv.affirmative_action_id')
            ->where("call.call_number", $callNumber)
            ->orderBy("call.distribution_vacancy_need_id")
            ->orderBy("call.call_position");

        if (Gate::allows('isAcademicRegister')) {
            $campuses = Auth::user()->permissions()
                ->select('permissions.campus_id')
                ->where('role_id', 2)
                ->where('user_id', Auth::user()->id)->get();

            $subscriptionSelect = $subscriptionSelect
                ->whereHas('distributionOfVacancy.offer.courseCampusOffer', function ($q) use ($campuses) {
                    $q->whereIn('campus_id', $campuses->pluck('campus_id'));
                });
        }

        if (!empty($search)) {
            $subscriptionSelect->where(function ($q) use ($search) {
                $q->where('subscriptions.subscription_number', 'like', '%' . $search . '%')
                    ->orWhere('users.name', 'ilike', '%' . $search . '%')
                    ->orwhere('cpf', 'like', '%' . $search . '%');
            });
        }

        if ($status) {
            $subscriptionSelect->where('call.status',$status);
        }
        return $subscriptionSelect;
    }

    public function getApprovedListByOfferAndCriteriaAndCallNumber(Offer $offer, SelectionCriteria $selectionCriteria, int $callNumber = null)
    {
        return $this->getApprovedListByOfferAndCriteriaAndCallNumberAndSearch($offer, $selectionCriteria, $callNumber);
    }

    public function getApprovedListByOfferAndCriteriaAndCallNumberAndSearch(Offer $offer, SelectionCriteria $selectionCriteria, int $callNumber = null, $search = '', $status = null)
    {
        $table = $offer->notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
        $subscriptionSelect = Subscription::select(
            'subscriptions.*',
            "call.id as call_id",
            "call.distribution_vacancy_need_id",
            "call.call_position",
            "call.is_wide_concurrency",
            "call.status",
            'aa.slug as affirmative_action_slug'
        )
            // ->with('user')
            ->join("$table as call", 'subscriptions.id', '=', 'call.subscription_id')
            ->join("users", 'users.id', '=', 'subscriptions.user_id')
            ->join("distribution_of_vacancies as dv", 'dv.id', '=', 'call.distribution_vacancy_need_id')
            ->join("affirmative_actions as aa", 'aa.id', '=', 'dv.affirmative_action_id')
            ->where('call.offer_id', $offer->id)
            ->orderBy("call.distribution_vacancy_need_id")
            ->orderBy("call.call_position");

        if ($selectionCriteria->id > 2) {
            $scoreTable = $offer->notice->getScoreTableNameForCriteriaId($selectionCriteria->id);
            $subscriptionSelect->join("$scoreTable as scores",'call.subscription_id','=','scores.subscription_id');
            $subscriptionSelect->addSelect('scores.media');
        }

        if ($callNumber) {
            $subscriptionSelect->where("call.call_number", $callNumber);
        }

        if (!empty($search)) {
            $subscriptionSelect->where(function ($q) use ($search) {
                $q->orWhere('subscriptions.subscription_number', 'like', '%' . $search . '%')
                    ->orWhere('users.name', 'ilike', '%' . $search . '%')
                    ->orwhere('cpf', 'like', '%' . $search . '%');
            });
        }

        if ($status) {
            $subscriptionSelect->where('call.status',$status);
        }
        return $subscriptionSelect;
    }

    public function getCallsBySubscription(Subscription $subscription) : Collection
    {
        $table = $subscription->notice->getEnrollmentCallTableNameByCriteria($subscription->distributionOfVacancy->selectionCriteria);
        $subscriptionSelect = DB::table($table)
            ->select(
                "$table.call_position as call_position",
                "$table.is_wide_concurrency as is_wide_concurrency",
                "$table.distribution_vacancy_need_id",
                "aa.id as affirmative_action_id",
                "$table.status as status",
                "$table.call_number as call_number",
                'aa.slug as affirmative_action_slug'
        )
            ->join("distribution_of_vacancies as dv", 'dv.id', '=', "$table.distribution_vacancy_need_id")
            ->join("affirmative_actions as aa", 'aa.id', '=', 'dv.affirmative_action_id')
            ->where("$table.subscription_id",'=',$subscription->id)
            ->orderBy("$table.call_number");
        return $subscriptionSelect->get();
    }

    public function getPendingCallBySubscription(Subscription $subscription)
    {
        $table = $subscription->notice->getEnrollmentCallTableNameByCriteria($subscription->distributionOfVacancy->selectionCriteria);
        $subscriptionSelect = DB::table($table)
            ->select(
                "$table.id as call_id",
                "$table.call_position as call_position",
                "$table.is_wide_concurrency as is_wide_concurrency",
                "$table.distribution_vacancy_need_id",
                "aa.id as affirmative_action_id",
                "$table.status as status",
                "$table.call_number as call_number",
                'aa.slug as affirmative_action_slug'
        )
            ->join("distribution_of_vacancies as dv", 'dv.id', '=', "$table.distribution_vacancy_need_id")
            ->join("affirmative_actions as aa", 'aa.id', '=', 'dv.affirmative_action_id')
            ->where("$table.subscription_id",'=',$subscription->id)
            ->where("$table.status",'=','pendente')
            ->orderBy("$table.call_number");
        return $subscriptionSelect->first();
    }




    public function getRegisteredListByOfferAndCriteriaAndStatusOfAllCalls(Offer $offer, SelectionCriteria $selectionCriteria, $status)
    {
        $table = $offer->notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
        $subscriptionSelect =  Subscription::select(
            'subscriptions.*',
            "call.id as call_id",
            "call.distribution_vacancy_need_id",
            "call.call_number",
            "call.call_position",
            "call.is_wide_concurrency",
            "call.status",
            'aa.slug as affirmative_action_slug'
        )
            ->with('user')
            ->join("$table as call", 'subscriptions.id', '=', 'call.subscription_id')
            ->join("distribution_of_vacancies as dv", 'dv.id', '=', 'call.distribution_vacancy_need_id')
            ->join("affirmative_actions as aa", 'aa.id', '=', 'dv.affirmative_action_id')
            ->where('call.offer_id', $offer->id)
            //->where('call.status', 'matriculado')
            ->orderBy("call.distribution_vacancy_need_id")
            ->orderBy("call.call_position")
            ->orderBy("call.call_number");

        if (!empty($status)) {
            
            if($status == 'matriculado'){
                $subscriptionSelect->where('call.status', 'pré cadastro')->orWhere('call.status', 'matriculado');
            }else{
                $subscriptionSelect->where('call.status', $status);
            }
            
        }
        // else{
        //     $subscriptions->where('call.status', 'matriculado');
        // }

        return $subscriptionSelect->get();
    }

    public function getTotalByAffirmativeActionWhereStatusAndCallNumber(Notice $notice, string $status = 'matriculado', int $callNumber = null)
    {
        foreach ($notice->selectionCriterias as $selectionCriteria)
        {
            $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
            $subqueries[] = "select * from $table";
        }
        
        $unionQueries = implode(" union ",$subqueries);
        $callWhere  = ($callNumber) ? "and list.call_number = :call_number" : '';

        $statusWhere = $status == 'matriculado' ? " where list.status in ('pré cadastro', 'matriculado') " : " where list.status = :status" ;
        
        if($callNumber) $parameters['call_number'] = $callNumber;

        if($status != 'matriculado') 
            $parameters['status'] = $status;
        

        return DB::select("select list.offer_id, dov.affirmative_action_id, count(*) as total from ($unionQueries) as list
                    inner join core.distribution_of_vacancies dov on list.distribution_vacancy_need_id = dov.id
                    $statusWhere
                    $callWhere
                    group by (list.offer_id, dov.affirmative_action_id)", $parameters);
    }

    public function updateStatus(Notice $notice, SelectionCriteria $selectionCriteria, $id, $status)
    {
        $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
        return DB::table($table)
            ->where('id', $id)
            ->update(
                [
                    'status' => $status
                ]
            );
    }
}
