<?php

namespace App\Http\LiveComponents\Notice;

use App\Models\Process\AffirmativeAction;
use App\Models\Process\DistributionOfVacancies;
use App\Models\Process\Notice;
use App\Models\Process\Offer;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Vacancies extends Component
{

    use AuthorizesRequests;

    public $modalOpen = false;
    public $state = [];
    public $offerTotalVacancies = 0;
    public $distributedVacanciesCount = 0;
    public $selected = [];
    public $availableVacancies = 0;
    public $selectedToDelete = null;

    protected $rules = [
        'selected.affirmative_action_id' => 'required',
        'selected.selection_criteria_id'  => 'required',
        'selected.total_vacancies'  => ['required','integer','min:0']
    ];

    protected $messages = [
        'selected.affirmative_action_id.*' => 'Você deve escolher uma ação afirmativa',
        'selected.selection_criteria_id.*'  => 'Você deve escolher um critério de seleção',
        'selected.total_vacancies.*'  => "Você deve informar o número de vagas maior que zero e menor ou igual a que o total restantes"
    ];
    

    public function mount(Offer $offer)
    {
        $this->updateVacanciesInfo($offer);
        $this->state['notice_id'] = $offer->notice_id;        
        $this->state['modality_id'] = $offer->notice->modality->id;
        $this->state['offer_id'] = $offer->id;
        $this->selected['has_subscriptions'] = null;
    }

    public function render()
    {         
        $selectionCriterias = Notice::find($this->state['notice_id'])->selectionCriterias;
        $affirmativeActions = AffirmativeAction::whereHas(
            'modalities',function($q){
                $q->where('id',$this->state['modality_id']);
            }
        )->get();  
        $distribution = Offer::find($this->state['offer_id'])->distributionVacancies()->with(['affirmativeAction','selectionCriteria'])->get();        
        return view('live-components.notice.vacancies',compact([
            'affirmativeActions',
            'selectionCriterias',
            'distribution'
        ]));
    }

    public function edit($id = null)
    {
        $this->state['id'] = $id;        
        $this->authorize('isAdmin');
        $selected = ($id) ? DistributionOfVacancies::findOrFail($id) : new DistributionOfVacancies();                
        $this->selected = $selected->toArray();
        $this->selected['total_vacancies_old'] = $selected['total_vacancies'];
        $this->selected['has_subscriptions'] = ($id) ? Gate::denies('deleteOrUpdate',$selected) : false;
        $this->modalOpen = true;
    }

    public function save()
    {
        $this->authorize('isAdmin');
        $availableVacancies = isset($this->selected['id']) ? $this->availableVacancies + $this->selected['total_vacancies_old'] : $this->availableVacancies;
        $this->rules['selected.total_vacancies'][] = "between:0,$availableVacancies";
        $this->validate($this->rules, $this->messages);
        $offer = Offer::findOrFail($this->state['offer_id']);
        $offer->distributionVacancies()->updateOrCreate(
            [
                'affirmative_action_id' => $this->selected['affirmative_action_id'],
                'selection_criteria_id'  => $this->selected['selection_criteria_id'],                
            ],
            [                
                'total_vacancies'  => $this->selected['total_vacancies'],
            ]
        );
        $this->reset(['rules']);
        $this->updateVacanciesInfo();
        $this->modalOpen = false;
    }

    private function updateVacanciesInfo(Offer $offer = null)
    {
        $offer = $offer ?? Offer::find($this->state['offer_id']);
        $this->offerTotalVacancies = $offer->total_vacancies;
        $this->distributedVacanciesCount = $offer->distributionVacancies()->groupBy('offer_id')->sum('total_vacancies');
        $this->availableVacancies = $this->offerTotalVacancies - $this->distributedVacanciesCount;
    }
    
    
    public function setToDelete($id) {
        $this->selectedToDelete = $id;
    }

    public function destroy()
    {
            $this->authorize('isAdmin');
            $offer = Offer::findOrFail($this->state['offer_id']);
            $offer->distributionVacancies()->where('id',$this->selectedToDelete)->delete();        
            $this->selectedToDelete = null;       
            $this->updateVacanciesInfo();        
    }
}
