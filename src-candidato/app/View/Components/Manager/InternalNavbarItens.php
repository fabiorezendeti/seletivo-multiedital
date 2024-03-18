<?php

namespace App\View\Components\Manager;

use Illuminate\View\Component;

class InternalNavbarItens extends Component
{

    public $tip;
    public $home;
    public $create;
    public $routeVars;
    public $searchPlaceholder;
    public $buttonName;
    public $backUrl;

    public function __construct($home = null, $searchPlaceholder = null,$create = null,$routeVars = [], $buttonName = null, $backUrl = null)
    {
        $this->home = $home;
        $this->create = $create;
        $this->routeVars = $routeVars;
        $this->searchPlaceholder = $searchPlaceholder;
        $this->buttonName = $buttonName;
        $this->backUrl = $backUrl;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.manager.internal-navbar-itens');
    }
}
