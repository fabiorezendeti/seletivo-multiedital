<?php

namespace App\Http\LiveComponents\Notice;

use App\Models\Process\CriteriaCustomization\Customization;
use App\Models\Process\CriteriaCustomization\Property;
use App\Models\Process\Notice;
use App\Models\Process\SelectionCriteria;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class CriteriaCustomization extends Component
{

    use AuthorizesRequests;

    public $criteria;
    public $noticeId;
    public $input = [
        'type'  => 'number',
        'name'  => 'Default Name',
        'help'  => 'Um texto de ajuda',
        'rules'  => [
            'required' => false,
            'is_score' => false,
        ],
        'number' => [
            'min' => 0,
            'max' => 10,
            'decimals' => 0,
            'tiebreaker' => 0,
            'weight'  => 0
        ],
        'select' => [
            'values' => ''
        ]
    ];
    public $customization = [
        'calc' => null,        
    ];

    public $properties = [];

    public function mount(Notice $notice, SelectionCriteria $criteria)
    {
        $this->noticeId = $notice->id;
        $this->criteria = $criteria->withoutRelations()->toArray();
        
    }

    public function render()
    {
        return view('live-components.notice.criteria-customization');
    }

    public function addProperty()
    {
        $this->authorize('isAdmin');
        $select = array_map(fn($item) => trim($item),explode(',',$this->input['select']['values']));        
        $prop = new Property($this->input['type'], $this->input['name'], $this->input['help'], $this->input['number'], $this->input['rules'], $select);
        $this->properties[$prop->friendlyName] = $prop->serialize();
        $this->reset(['input']);
    }

    public function saveCustomization()
    {
        $this->authorize('isAdmin');
        $customization = $this->customization;
        foreach ($this->properties as $prop) {
            $customization['properties'][] = unserialize($prop);
        }
        $notice = Notice::find($this->noticeId);
        $notice->selectionCriterias()
            ->updateExistingPivot($this->criteria['id'], [
                'customization' => json_encode($customization)
            ]);        
    }
}
