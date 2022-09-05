<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Configuracion;
use App\Models\Administrativo\Meru_Administrativo\Compras\UnidadMedida;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;


class UnidadMedidaIndex extends Component
{ use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';


    public function mount()
    {
        $this->sort = 'des_uni';
        $this->direction = 'asc';
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
        return view('livewire.administrativo.meru-administrativo.compras.configuracion.unidad-medida-index', [
            'headers' => [
                ['name' => 'ID',          'align' => 'center', 'sort' => 'id'],
                ['name' => 'Código.',     'align' => 'center', 'sort' => 'cod_uni'],
                ['name' => 'Descripción', 'align' => 'center', 'sort' => 'des_uni'],
                ['name' => 'Estado',      'align' => 'center', 'sort' => 'sta_reg'],
                'Acción'
            ],
            'unidadmedida' => UnidadMedida::query()
                            ->orWhere('cod_uni', 'like', '%'.strtoupper($this->search).'%')
                            ->orWhere('des_uni', 'like', '%'.strtoupper($this->search).'%')
                            ->orwhere('sta_reg', 'like', '%'.ucfirst($this->search).'%')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->paginate)
                            ]);
    }

}
