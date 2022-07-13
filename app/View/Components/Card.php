<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Card extends Component
{
    public $header;

    public $body;

    public $footer;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($header=null, $body=null, $footer=null)
    {
        //
        $this->header = $header;
        $this->body = $body;
        $this->footer = $footer;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card');
    }
}
