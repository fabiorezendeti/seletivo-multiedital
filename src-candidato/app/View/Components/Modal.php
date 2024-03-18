<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{    
    public $title;    
    public $buttonText;
    public $buttonIcon;
    public $height;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($buttonText,$title, $buttonIcon = null, $height = null)
    {        
        $this->buttonText = $buttonText;
        $this->title = $title;     
        $this->buttonIcon = $buttonIcon;   
        $this->height = $height;
    }

    /**
     * Get the view / contents that represent the component. 
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
