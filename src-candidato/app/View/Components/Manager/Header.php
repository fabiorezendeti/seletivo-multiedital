<?php

namespace App\View\Components\Manager;

use App\Models\Process\Notice;
use Illuminate\View\Component;

class Header extends Component
{
    public $tip;
    public $nomeEdital;
    public $home;
    public $notice;
    public $create;
    public $searchPlaceholder;

    public function __construct($tip,  $nomeEdital = null, $searchPlaceholder = null, $create = null, ?Notice $notice)
    {        
        $this->tip = $tip;
        $this->nomeEdital = $nomeEdital;        
        $this->create = $create;
        $this->notice = $notice;
        $this->searchPlaceholder = $searchPlaceholder;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.manager.header');
    }
}
