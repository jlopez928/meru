<?php

namespace App\Traits;

trait WithSorting
{
    public $sort = '';
    public $direction = '';

    public function sortBy($field)
        {
            $this->direction = $this->sort === $field
                ? $this->reverseSort()
                : 'asc';

            $this->sort = $field;
        }

    public function reverseSort()
    {
        return $this->direction === 'asc'
            ? 'desc'
            : 'asc';
    }

}
