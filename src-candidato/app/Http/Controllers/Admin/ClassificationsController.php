<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use App\Models\Process\Offer;
use App\Models\Process\Notice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Lottery\DrawService;
use Illuminate\Database\QueryException;
use App\Models\Process\AffirmativeAction;
use App\Models\Process\SelectionCriteria;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Notice\ClassificationService;
use App\Models\Process\CriteriaCustomization\Customization;
use App\Repository\EnrollmentCallRepository;

class ClassificationsController extends Controller
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
        return view('admin.notices.classifications.index', compact([
            'notice',
            'offers'
        ]));
    }

    public function store(Request $request, Notice $notice, ClassificationService $classificationService)
    {
        try {
            foreach ($notice->selectionCriterias as $criteria) {
                $customization = new Customization($notice, $criteria);
                $customization->alterDatabaseForClassification();
                $classificationService->boot($notice,$criteria);
                $classificationService->run();
            }
            $notice->classification_fields_created = true;
            $notice->save();
            return redirect()->route('admin.notices.show',['notice'=>$notice])
                ->with('success','A classificação foi concluída com sucesso, confira os relatórios');
        } catch (Exception $exception) {
            if(env('APP_DEBUG'))
                throw $exception;
            Log::error("Um erro ocorreu na classificação {$exception->getMessage()}",['ClassificationStore',"Line {$exception->getLine()}"]);
            return redirect()->route('admin.notices.show',['notice'=>$notice])
                ->with('error','A classificação sofreu um erro');
        }
    }

    public function destroy(Notice $notice)
    {
        foreach ($notice->selectionCriterias as $criteria) {
            DB::table($notice->getScoreTableNameForCriteriaId($criteria->id))
                ->update([
                    'offer_id'  => null,
                    'global_position'   => null,
                    'distribution_of_vacancy_position' => null,
                    'distribution_of_vacancies_id'  => null,
                    'is_tied'   => false,
                    'is_eliminated' => false
                ]);
        }
        $notice->subscriptions()->update(['elimination' => null]);
        $notice->classification_fields_created = false;
        $notice->save();
        return redirect()->route('admin.notices.show', ['notice' => $notice])
            ->with('success', 'A classificação foi desfeita com sucesso');
    }

    public function classificationReportByCriteria(EnrollmentCallRepository $enrollmentCallRepository, Notice $notice, Offer $offer, SelectionCriteria $selectionCriteria)
    {
        $classificationList  = $notice->getScoreClassificationByCriteria($selectionCriteria,$offer,'global_position');

        $wideCompetition = $offer->distributionVacancies()->whereHas('affirmativeAction',function($q){
            $q->where('is_wide_competition',true);
        })->get();

        $classificationListWithoutWideCompetition = $classificationList->whereNotIn(
            'distribution_of_vacancies_id',$wideCompetition->pluck('id')
        );

        try {
            $firstCallApprovedList = $enrollmentCallRepository->getApprovedListByOfferAndCriteriaAndCallNumber($offer,$selectionCriteria, 1)->get();
        } catch (QueryException $exception) {
            $firstCallApprovedList = collect();
        }
        if($notice->hasProva()){
            return view('admin.notices.classifications.report.classification-report',compact(
                'offer','notice', 'classificationList','classificationListWithoutWideCompetition','firstCallApprovedList'
            ));
        }
        return view('admin.notices.classifications.report.classification-report',compact(
            'offer','notice', 'classificationList','classificationListWithoutWideCompetition','firstCallApprovedList'
        ));
    }
}
