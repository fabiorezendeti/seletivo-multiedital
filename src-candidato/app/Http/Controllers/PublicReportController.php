<?php

namespace App\Http\Controllers;

use App\Models\Course\Modality;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use App\Models\Process\SelectionCriteria;
use App\Repository\EnrollmentCallRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PublicReportController extends Controller
{
    public function __construct()
    {
        //
    }

    public function selection_result(Request $request){
        if($request->method() == 'POST'){

            //Dados recebidos:
            //"_token" => "Fpb4Cj0bvmfn22fvj0jgWrUiiSh4Np17V9B6EpCF"
            //"modality_id" => "3"
            //"notice_id" => "9"
            //"campus_id" => "13"
            //"offer_id" => "104"
            //call_json" => "{"call_number":1,"selection_criteria_id":3,"selection_criteria_description":"ENEM", "offer_id":104}"

            /*
             * Adaptado da CallControler -> Show()
             */
            $notice = Notice::find($request->notice_id);
            $call = json_decode($request->call_json);
            $callNumber = $call->call_number;
            $selectionCriteria = SelectionCriteria::findOrFail($call->selection_criteria_id);
            $offer = Offer::find($request->offer_id);
            $enrollmentCallRepository = new EnrollmentCallRepository();
            $approvedList = $enrollmentCallRepository->getApprovedListByOfferAndCriteriaAndCallNumber($offer, $selectionCriteria, $call->call_number)->get();
            $view = ($selectionCriteria->id > 2) ? 'admin.notices.enrollment.calls.report-with-media' : 'admin.notices.enrollment.calls.report';
            return view($view, compact(
                'notice',
                'offer',
                'selectionCriteria',
                'callNumber',
                'approvedList'
            ));
        }
        return view('selection-result');
    }
}
