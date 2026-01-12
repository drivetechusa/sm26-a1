<?php

namespace App\Traits;

trait SearchableTable
{
    public $search = '';

    public $searchFields = [];

    public $perPage = 10;

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query->whereAny($this->searchFields, 'like', '%'.$this->search.'%');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
