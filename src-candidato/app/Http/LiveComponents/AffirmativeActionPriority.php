<?php

namespace App\Http\LiveComponents;

use App\Models\Process\AffirmativeAction;
use Doctrine\DBAL\Query\QueryException;
use Livewire\Component;

class AffirmativeActionPriority extends Component
{

    public $affirmativeAction;
    public $priority;
    public $message = null;

    public function mount(AffirmativeAction $affirmativeAction)
    {
        $this->affirmativeAction = $affirmativeAction->withoutRelations()->toArray();
        $this->priority = $affirmativeAction->classification_priority;
    }    

    public function render()
    {
        return view('live-components.affirmative-action-priority');
    }

    public function save()
    {
        try {
            $affirmativeAction = AffirmativeAction::findOrFail($this->affirmativeAction['id']);
            $affirmativeAction->classification_priority = $this->priority;
            $affirmativeAction->save();    
            $this->emit('saved');
            $this->message = 'Atualizado!';
        } catch (QueryException $exception) {
            $this->saved = 'Erro ao atualizar';
        }                    
    }
}