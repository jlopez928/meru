<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DatatableFooter extends Component
{
    // public $datafooter;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $datafooter = null
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.datatable-footer');
    }
}
