<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process\AffirmativeAction;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use App\Models\Process\SelectionCriteria;
use App\Repository\EnrollmentCallRepository;
use App\Services\Lottery\DrawService;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException as DatabaseQueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LotteryDrawController extends Controller
{

    
    public function index(Notice $notice)
    {
        $offers = $notice->offers()
            ->orderBy('id', 'asc')
            ->with(['courseCampusOffer' => function ($query) {
                $query->with(['campus', 'course', 'shift']);
            }])
            ->whereHas('distributionVacancies')
            ->get();
        return view('admin.notices.lottery-draw.index', compact([
            'notice',
            'offers'
        ]));
    }

    public function draw(Notice $notice, Offer $offer)
    {
        $selectCriteria = SelectionCriteria::find(1);
        $subscriptions = $notice->getLotteryDraw($offer);
        $finalList = $offer->lottery_seed ? $notice->getLotteryDraw($offer,$orderBy = 'global_position') : [];
        return view('admin.notices.lottery-draw.draw', compact([
            'notice',
            'offer',
            'subscriptions',
            'finalList'
        ]));
    }

    public function classificationReport(EnrollmentCallRepository $enrollmentCallRepository, Notice $notice, Offer $offer)
    {                        
        $classificationList  = $notice->getLotteryDraw($offer,'global_position');
        $wideCompetition = $offer->distributionVacancies()->whereHas('affirmativeAction',function($q){
            $q->where('is_wide_competition',true);
        })->get();
        $classificationListWithoutWideCompetition = $classificationList->whereNotIn(
            'distribution_of_vacancies_id',$wideCompetition->pluck('id')
        );
        try {
            $firstCallApprovedList = $enrollmentCallRepository->getApprovedListByOfferAndCriteriaAndCallNumber($offer,SelectionCriteria::find(1), 1)->get();
        } catch (DatabaseQueryException $exception) {
            $firstCallApprovedList = collect();
        }        
        return view('admin.notices.lottery-draw.report.classification-report',compact(
            'offer','notice', 'classificationList','classificationListWithoutWideCompetition', 'firstCallApprovedList'
        ));
    }

    public function store(Request $request, DrawService $drawService, Notice $notice, Offer $offer)
    {        
        DB::beginTransaction();
        try {
            $offer->lottery_seed = $request->seed;
            $offer->system_info = $request->systemInfo;
            $offer->save();
            $i = 1;
            $drawService->save($offer, $request->finalList);           
            DB::commit();
            $results = $notice->getLotteryDraw($offer,$orderBy = 'global_position');
            $stringResult = $results->implode('lottery_number',', ');
            if ($stringResult !== $request->lotteryResult) {
                throw new Exception('As strings não está equivalentes');
            }
            return response()->json([
                'savedList' => $results,
                'listString' => $stringResult,                
            ]);
        } catch (QueryException $exception) {
            DB::rollBack();
            Log::error($exception->getMessage(), ['Lottery Draw Controller']);
            return response()->json(['error' => $exception->getMessage()], 500);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }   
    
    public function destroy(Request $request, DrawService $drawService, Notice $notice, Offer $offer)
    {        
        DB::beginTransaction();
        try {
            $offer->lottery_seed = null;
            $offer->system_info = null;
            $offer->save();            
            $drawService->delete($offer);            
            DB::commit();
            return redirect()->route('admin.notices.lottery-draw.index',['notice'=>$notice])
                ->with('success','O sorteio foi desfeito');
        }
        catch (QueryException $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    

}
