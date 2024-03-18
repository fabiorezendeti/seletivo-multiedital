<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Course\Course;
use App\Models\Process\Offer;
use App\Models\Process\Notice;
use App\Models\Course\CampusOffer;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use App\Models\Process\AffirmativeAction;
use App\Models\Process\SelectionCriteria;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;

class NoticeOfferPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function offersCreate(User $user)
    {
        if (CampusOffer::count() < 1) {
            return Response::deny('Você ainda não cadastrou Cursos e Campus de Oferta');
        }        
        if (AffirmativeAction::count() < 1){
            return Response::deny('Você ainda não cadastrou Ações Afirmativas');
        }
        return Response::allow();
    }

    public function hasLotteryDraw(User $user, Offer $offer)
    {
        try {
            $table = $offer->notice->getLotteryTable();
            return DB::table($table)
                ->where('offer_id',$offer->id)
                ->whereNotNull('global_position')
                ->count() > 0;
        } catch (QueryException $exception) {
            Log::warning($exception->getMessage(), ['NoticeOfferPolicy','hasLotteryDraw']);
            return false;
        }           
    }

    public function lotteryDrawAvailable(User $user, Offer $offer) 
    {        
        return ! Gate::allows('hasLotteryDraw',$offer);
    }

    public function hasLotteryCalls(User $user, Offer $offer)
    {
        try {
            $table = $offer->notice->getEnrollmentCallTableNameByCriteria(SelectionCriteria::find(1));
            return DB::table($table)
            ->where('offer_id',$offer->id)        
            ->count() > 0;
        } catch (QueryException $exception) {
            return false;
        }
        
    }

    public function doesntHaveLotteryCalls(User $user, Offer $offer)
    {        
       return ! Gate::allows('hasLotteryCalls',$offer);        
    }

}