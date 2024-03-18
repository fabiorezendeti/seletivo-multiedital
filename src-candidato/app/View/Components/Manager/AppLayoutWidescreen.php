<?php

namespace App\View\Components\Manager;

use Illuminate\View\Component;

class AppLayoutWidescreen extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('layouts.manager.widescreen');
    }
}
