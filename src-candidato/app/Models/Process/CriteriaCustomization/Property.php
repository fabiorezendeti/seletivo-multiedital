<?php

namespace App\Models\Process\CriteriaCustomization;

use Exception;
use Illuminate\Support\Str;

class Property
{

    private $availableTypes = [
        'text', 'textarea', 'select', 'checkbox', 'number', 'radio'
    ];

    public string $type;
    public string $name;
    public string $friendlyName;
    public string $helpText;
    public array $rules = [];
    public array $number = [];
    public array $selectValues = [];
    public array $values = [];    

    public function __construct(string $type, string $friendlyName, $helpText, $number = [] ,$rules = [], array $selectValues = [])
    {
        if(!in_array($type,$this->availableTypes)) {
            throw new Exception("Type is not available, accept only " . implode(",",$this->availableTypes));
        }
        $this->type = $type;
        $this->helpText = $helpText;
        $friendlyName = strip_tags($friendlyName);
        $this->name = Str::slug($friendlyName,'_') ; 
        $this->friendlyName = $friendlyName;
        $this->rules = $rules;
        $this->number =  ($type == 'number') ? $number : [];
        $this->selectValues = ($type == 'select') ? $selectValues : [];
    }

    public function toJson()
    {
        return json_encode($this);
    }

    public function serialize()
    {
        return serialize($this);
    }

    public function render()
    {
        $required = ($this->rules['required']) ? 'required="required"' : null;        
        switch($this->type) {
            case 'text' :
                return <<<TEXT
                    <label class="block font-medium text-sm text-gray-700">$this->friendlyName</label>
                    <p class="text-xs">$this->helpText</p>
                    <input type="text" id="$this->name" class="form-input rounded-md shadow-sm" name="$this->name" $required />
                TEXT;
            case 'number' :
                $decimals = ($this->number['decimals'] > 0) ? ',' . str_repeat('0',$this->number['decimals']) : null;
                $dataMask = str_repeat('0',strlen($this->number['max']))  . $decimals;              
                return <<<NUMERIC
                    <label class="block font-medium text-sm text-gray-700">$this->friendlyName</label>
                    <p class="text-xs">$this->helpText</p>
                    <input type="text" id="$this->name"  class="form-input rounded-md shadow-sm" min={$this->number['min']} max={$this->number['max']} data-mask="$dataMask" name="$this->name" $required/>                    
                NUMERIC;
            case 'select' :
                $options = implode('',array_map(fn($item) => "<option value=\"$item\">$item</option>" ,$this->selectValues));
                return <<<SELECT
                    <label class="block font-medium text-sm text-gray-700">$this->friendlyName</label>
                    <p class="text-xs">$this->helpText</p>
                    <select class="form-input rounded-md shadow-sm w-full">
                        $options
                    </select>
                SELECT;
        }
    }

    public function renderTieBreakerMessage()
    {       
        if (!$this->number) return ;
        if (!$this->number['tiebreaker']) {
            return;
        } 
        return <<<TIE
            <p class="text-xs"> Ordem de desempate {$this->number['tiebreaker']} </p>
        TIE;
    }
    
    
}
