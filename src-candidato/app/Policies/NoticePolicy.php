<?php

namespace App\Policies;

use App\Models\Process\ExamLocation;
use App\Models\Process\ExamRoomBooking;
use App\Models\Process\Notice;
use App\Models\Process\Subscription;
use App\Models\User;
use App\Models\Process\Offer;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;

class NoticePolicy
{
    use HandlesAuthorization;


    public function delete(User $user, Notice $notice)
    {
        if (Gate::denies('isAdmin')) return false;
        return $notice->offers()->count() < 1;
    }

    public function subscriptionsIsOpen(User $user, Notice $notice)
    {
        return $notice->inSubscriptionsPeriod();
    }

    public function updateCriteria(User $user, ?Notice $notice)
    {
        if ($notice->selectionCriterias->where('id',5)->first()) return false;
        return $notice->offers()->count() < 1;
    }

    public function userIsNotSubscribed(User $user, Notice $notice)
    {
        return $user->subscriptions()->where('notice_id', $notice->id)->count() < 1;
    }

    public function isClosed(User $user, Notice $notice)
    {
        return $notice->isClosed();
    }

    public function distributeLotteryNumber(User $user, Notice $notice)
    {
        if ($notice->selectionCriterias->where('description', 'Sorteio')->count() < 1) {
            return false;
        }

        if ($notice->inSubscriptionsPeriod()) return false;

        try {
            $table = $notice->getLotteryTable();
            $count = DB::table($table)->count() < 1;
        } catch (QueryException $exception) {
            $count = true;
        }

        return Gate::allows('isAdmin') && $count;
    }

    public function paymentAvailable(User $user, Notice $notice)
    {
        return $notice->payment_date->gte(Carbon::now()->format('Y-m-d'));
    }

    public function hasSISU(User $user, Notice $notice)
    {
        return $notice->hasSISU();
    }


    public function lotteryDrawAvailable(User $user, Notice $notice)
    {
        if ($notice->inSubscriptionsPeriod()) return false;

        try {
            $table = $notice->getLotteryTable();
            return DB::table($table)->count() > 0;
        } catch (QueryException $exception) {
            return false;
        }
    }

    public function classificationAvailable(User $user, Notice $notice)
    {
        if (Gate::denies('isAdmin')) return false;
        if ($notice->inSubscriptionsPeriod()) return false;
        return $notice->selectionCriterias->where('id', '!=', 1)->count() > 0 && $notice->classification_fields_created == false;
    }

    public function classificationReportAvailables(User $user, Notice $notice)
    {
        return $notice->classification_fields_created;
    }

    /**
     * Verifica a possibilidade da edição da nota com os seguintes critérios:
     * - Edital com critério de seleção do tipo ENEM(3) ou Análise de Currículo(4);
     * - Que a classificação ainda não tenha sido executada
     * @param User $user
     * @param Notice $notice
     * @return bool
     */
    public function allowUpdateScores(User $user, Notice $notice)
    {
        return ($notice->hasCurriculum() or $notice->hasEnem()) and $notice->classification_fields_created == false;
    }

    public function undoClassificationAvailable(User $user, Notice $notice)
    {
        if (Gate::denies('isAdmin')) return false;
        if ($notice->inSubscriptionsPeriod()) return false;

        foreach ($notice->selectionCriterias as $criteria) {
            $calls = false;
            try {
                $table = $notice->getEnrollmentCallTableNameByCriteria($criteria);
                $calls = DB::table($table)->count() > 0;
            } catch (QueryException $exception) {
                $calls = false;
            }
        }

        return $notice->selectionCriterias->where('id', '!=', 1)->count() > 0 && $notice->classification_fields_created == true
         && $calls === false;
    }

    public function callAvailable(User $user, Notice $notice)
    {
        if ($notice->inSubscriptionsPeriod()) return false;

        try {
            foreach ($notice->selectionCriterias as $criteria) {
                $table = $notice->getScoreTableNameForCriteriaId($criteria->id);
                $checks[] = !(DB::table($table)->whereNotNull('global_position')->count() === 0);
            }
            return in_array(true,$checks);
        } catch (QueryException $exception) {
            return false;
        }
    }

    public function hasOfferInMyCampuses(User $user, Notice $notice)
    {
        $permissions = $user->permissions()->whereNotNull('campus_id')->get();
        return Offer::where('notice_id', $notice->id)
            ->whereHas('courseCampusOffer', function ($q) use ($permissions) {
                $q->whereIn('campus_id', $permissions->pluck('campus_id'));
            })->count() > 0;
    }

    public function allowAllocateExamRoom(User $user, Notice $notice)
    {
        return $notice->hasProva()
                && $notice->afterReviewRequestPeriod();
    }

    public function allowAutoAllocateExamRoom(User $user, Notice $notice)
    {
        if(array_key_exists('exam_location', Request::all())){
            $examLocation = ExamLocation::find(Request::all('exam_location'))->first();
            $notice->appendExamLocation($examLocation);
        }
        if($notice->examLocation) {
            $examRooms = $notice->examLocation->examRooms->pluck('id');
            $hasAutoAllocate = $notice->subscriptions()->whereIn('exam_room_booking_id',$examRooms)->whereNotNull('exam_room_booking_id')->whereNull('special_need_id')->count() === 0;
        } else {
            //$hasAutoAllocate = $notice->subscriptions()->whereNotNull('exam_room_booking_id')->whereNull('special_need_id')->count() === 0;
            $hasAutoAllocate = $notice->subscriptions()->whereNotNull('exam_room_booking_id')
            ->where(function ($query) {
                $query->WhereNull('additional_test_time_analysis')
                    ->orWhereRaw("cast(additional_test_time_analysis->>'approved' as boolean) is false");
            })
            ->where(function ($query) {
                $query->WhereNull('exam_resource_analysis')
                    ->orWhereRaw("cast(exam_resource_analysis->>'approved' as boolean) is false");
            })->count() === 0;
            //conta inscrições desse edital que ja está ensalado E não tem necessidades especiais
        }
        $allowAllocate = Gate::check('allowAllocateExamRoom',$notice);
        return $allowAllocate &&  $hasAutoAllocate;
    }

    public function examRoomAvailable(User $user, Notice $notice)
    {
        $now = Carbon::now();
        return Gate::allows('allowAllocateExamRoom',$notice)
            && $now->greaterThan($notice->display_exam_room_date);
    }

    public function examIsNotPast(User $user, Notice $notice){
        return $notice->examIsNotPast();
    }
}
