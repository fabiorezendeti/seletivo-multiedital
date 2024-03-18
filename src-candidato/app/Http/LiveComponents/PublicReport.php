<?php

namespace App\Http\LiveComponents;

use App\Models\Course\Modality;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use App\Repository\EnrollmentCallRepository;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PublicReport extends Component
{
    //WIRE MODELS
    public $modality_id = NULL;
    public $notice_id = NULL;
    public $campus_id = NULL;
    public $offer_id = -1;
    public $call_json = NULL;
    public $btn_submit = false;
    public $error = null;

    //
    public $modalities = [];
    public $notices = [];
    public $campuses = [];
    public $offers = [];
    public $calls = [];

    public function mount(){
        $this->modalities = Modality::select('id', 'description')->orderBy('description','asc')->get();
        $this->notices = collect();
        $this->campuses = collect();
        $this->offers = collect();
        $this->calls = collect();
        $this->btn_submit = false;
    }

    public function updatedModalityId($modality_id){
        $this->notices = Notice::select('id', 'number')->where('modality_id', $modality_id)->orderBy('subscription_initial_date','desc')->get();
        $this->reset([
            'notice_id',
            'campus_id', 'campuses',
            'offer_id', 'offers',
            'call_json', 'calls',
            'btn_submit', 'error'
        ]);
    }

    public function updatedNoticeId($notice_id){
        $this->campuses = Notice::where('id',$notice_id)
                                ->first()
                                ->getCampusesWithOffers();
        $this->reset([
            'campus_id',
            'offer_id', 'offers',
            'call_json', 'calls',
            'btn_submit', 'error'
        ]);
    }

    public function updatedCampusId($campus_id){
        $this->reset([
            'offer_id', 'offers',
            'call_json', 'calls',
            'btn_submit', 'error'
        ]);
        $this->offers =  Offer::select('offers.id', 'offers.course_campus_offer_id', 'offers.notice_id')
                            ->join('notices', 'offers.notice_id', '=', 'notices.id')
                            ->join('course_campus_offers', 'offers.course_campus_offer_id', '=', 'course_campus_offers.id')
                            ->where('notices.id', $this->notice_id)
                            ->where('course_campus_offers.campus_id', $campus_id)
                            ->get()
                            ->sortBy(function($of){
                                return $of->courseCampusOffer->course->name ;
                            });

    }

    public function updatedOfferId($offer_id){
        $notice = Notice::find($this->notice_id);
        $selectionCriterias = $notice->selectionCriterias;
        $calls = [];
        try{
            foreach ($selectionCriterias as $selectionCriteria) {
                $table = $notice->getEnrollmentCallTableNameByCriteria($selectionCriteria);
                $result = DB::table("$table as calls")
                    ->select(
                        "calls.call_number",
                        "sc.id as selection_criteria_id",
                        "sc.description as selection_criteria_description",
                        "of.id as offer_id"
                    )
                    ->distinct("calls.call_number")
                    ->join("offers as of", "calls.offer_id", "=", "of.id")
                    ->join("notice_selection_criteria as nsc", "of.notice_id", "=", "nsc.notice_id")
                    ->join("selection_criterias as sc", "nsc.selection_criteria_id", "=", "sc.id")
                    ->where('of.id', $this->offer_id)
                    ->where("nsc.notice_id", $this->notice_id)
                    ->where("nsc.selection_criteria_id", $selectionCriteria->id)
                    ->orderBy("calls.call_number", "desc")
                    ->get()
                    ->toArray();
                $calls = array_merge($calls, $result);
            }
            foreach ($calls as $k => $v){
                $calls[$k] = (array) $v;
            }
            usort($calls, function ($a, $b){
                return $b['call_number'] - $a['call_number'];
            });
        }catch (\Illuminate\Database\QueryException $e){
            //
        }
        if(count($calls) == 0)
            $this->error = "Não há resultados para exibir!";
        $this->calls = $calls;
        $this->reset([
            'call_json',
            'btn_submit'
        ]);
    }

    public function updatedCallJson($call_json){
        $this->btn_submit = true;
    }

    public function render()
    {
        return view('live-components.public-report');
    }
}
