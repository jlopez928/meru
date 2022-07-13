<?php

namespace App\View\Components;

use App\Support\TextAlignment;
use Illuminate\View\Component;

class TableHeaders extends Component
{
    public array $headers;
    public $id;
    public $sortby;
    public $order;
    public $iconClass;
    public $mainHeader;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(array $headers, $id=null, $sortby=null, $order=null, $mainHeader=null)
    {
        $this->headers = $this->formatHeaders($headers);
        $this->id = $id;
        $this->sortby = $sortby;
        $this->order = $order;
        $this->iconClass = $this->iconClasses();
        $this->mainHeader = $mainHeader;
    }

    private function formatHeaders(array $headers)
    {
        return array_map(function ($header) {
            $name = is_array($header) ? $header['name']  : $header;
            $sort = is_array($header) ? $header['sort']  : null;
            $width= is_array($header) ? (isset($header['width']) ? $header['width'] : 'auto' ) : null;
            return [
                'name'      => $name,
                'sort'      => $sort,
                'classes'   => $this->textAlignClasses($header['align'] ?? 'center'),
                'width'     => $width,
            ];
        }, $headers);
    }

    private function textAlignClasses($align)
    {
        return (new TextAlignment($align))->className();
    }

    private function iconClasses()
    {
        return [
            'asc'      =>  'fa-sort-alpha-up',
            'desc'     =>  'fa-sort-alpha-down-alt'
        ][$this->order] ?? null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.table-headers');
    }
}
