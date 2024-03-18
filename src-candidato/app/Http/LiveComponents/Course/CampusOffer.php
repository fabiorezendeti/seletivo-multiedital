<?php

namespace App\Http\LiveComponents\Course;

use App\Models\Course\CampusOffer as CourseCampusOffer;
use App\Models\Course\Course;
use App\Models\Course\Shift;
use App\Models\Organization\Campus;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class CampusOffer extends Component
{

    use AuthorizesRequests;

    public $courseId;
    public $offer = null;
    public $modalOpen = false;
    public $onlySISUCode = false;
    public $bindedCampuses = [];
    public $selectedToDelete = null;

    protected $rules = [
        'offer.campus_id' => 'required',
        'offer.course_shift_id'  => 'required',
        'offer.website'  => 'required|url',
        'offer.sisu_course_code'  => 'nullable|integer'
    ];

    protected $messages = [
        'offer.campus_id.*' => 'Você deve escolher um campus',
        'offer.course_shift_id.*'  => 'Você deve escolher um turno',
        'offer.website.*'  => 'O site do curso é obrigatório e deve ser no formato de Endereço Web',
        'offer.sisu_course_code.*'  => 'Deve ser um número'
    ];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->offer = (new CourseCampusOffer())->toArray();
    }

    public function render()
    {
        $campuses = Campus::all();
        $shifts = Shift::all();
        $this->refreshBinds();
        return view('live-components.course.campus-offer', compact(
            'shifts',
            'campuses'
        ));
    }

    public function edit($id = null)
    {
        $this->authorize('isAdmin');
        $offer = ($id) ? CourseCampusOffer::findOrFail($id) : new CourseCampusOffer();
        $this->offer = $offer->toArray();        
        $this->onlySISUCode = Gate::check('onlySISUCodeUpdate',$offer);
        $this->modalOpen = true;
    }

    public function setToDelete($id)
    {
        $this->selectedToDelete = $id;
    }

    private function refreshBinds()
    {
        $this->bindedCampuses = CourseCampusOffer::where('course_id', $this->courseId)
            ->with('shift', 'campus')            
            ->get();            
    }


    public function save()
    {        
        $this->authorize('isAdmin');        
        try {
            $this->validate($this->rules, $this->messages);
            $course = Course::findOrFail($this->courseId);
            $this->offer['sisu_course_code'] = $this->offer['sisu_course_code'] ?? null;            
            $campusesOffer = $course->campusesOffer()->updateOrCreate(
                [
                    'id'    => $this->offer['id'] ?? null
                ],
                $this->offer
            );
            $this->reset(['offer']);
            $this->modalOpen = false;
            $this->refreshBinds();
        } catch (QueryException $queryException) {          
            if ($queryException->getCode() == 23505) {
                session()->flash('error','Este código já está sendo utilizado por outra oferta');
                return;
            }            
            session()->flash('error','Parece que você já tem esse campus vinculado ao curso');
        }        
    }
    

    public function destroy()
    {
        $this->authorize('isAdmin');
        try {
            $campusOffer = CourseCampusOffer::findOrFail($this->selectedToDelete);        
            $campusOffer->delete();
            $this->selectedToDelete = null;
        } catch (QueryException $queryException) {
            session()->flash('error','Parece que você já tem esse campus vinculado ao curso');
        }
    }

}
