<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Configuracion;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\UnidadTributaria;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;


class UnidadTributariaIndexComponent extends Component
{use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort = 'vigente';
        $this->direction = 'desc';
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPaginate()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.configuracion.configuracion.unidad-tributaria-index-component', [
            'headers' => [
                ['name' => 'ID', 'align' => 'center', 'sort' => 'id'],
                ['name' => 'Fecha Vig.', 'align' => 'center', 'sort' => 'fec_ut'],
                ['name' => 'Monto UT', 'align' => 'center', 'sort' => 'bs_ut'],
                ['name' => 'Monto UCAU', 'align' => 'center', 'sort' => 'bs_ucau'],
                ['name' => 'Estado', 'align' => 'center', 'sort' => 'vigencia'],
                'Acción'
            ],
            'unidadtributaria' => UnidadTributaria::query()
                    ->where('id', 'like', '%'.$this->search.'%')
                    ->orWhere('fec_ut', 'like', '%'.ucfirst($this->search).'%')
                    ->orwhere('bs_ut', 'like', '%'.ucfirst($this->search).'%')
                    ->orderBy($this->sort, $this->direction)
                    ->paginate($this->paginate)
                    ]);
    }


}
