<?php

namespace App\Models\Process\CriteriaCustomization\CurriculumAnalisys;

class Modality {

    public $title;
    public $description;

    public function __construct($title, $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

}