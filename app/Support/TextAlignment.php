<?php

namespace App\Support;

class TextAlignment
{
    private $align;

    public function __construct($align='left')
    {
        $this->align = $align;
    }

    public function className()
    {
        return [
            'left'      =>  'text-left',
            'right'     =>  'text-right',
            'center'    =>  'text-center'
        ][$this->align] ?? 'text-left';
    }
}
