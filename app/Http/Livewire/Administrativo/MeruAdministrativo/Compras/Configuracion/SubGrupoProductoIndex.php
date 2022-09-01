<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Configuracion;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use App\Models\Administrativo\Meru_Administrativo\Compras\SubGrupoProducto;

class SubGrupoProductoIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort = 'subgrupo';
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
        return view('livewire.administrativo.meru-administrativo.compras.configuracion.sub-grupo-producto-index', [
            'headers' => [
                ['name' => 'Grupo', 'align' => 'center', 'sort' => 'grupo'],
                ['name' => 'SubGrupo', 'align' => 'center', 'sort' => 'subgrupo'],
                ['name' => 'Descripción', 'align' => 'left', 'sort' => 'des_subgrupo'],
                ['name' => 'Estado', 'align' => 'center', 'sort' => 'sta_reg'],
                'Acción'
            ],
            'subgrupos' => SubGrupoProducto::query()
                ->withCount('productos')
                ->when($this->search != '', function ($query) {
                    $query->where('grupo', 'like', '%' . strtoupper($this->search) . '%')
                        ->orWhere('subgrupo', 'like', '%' . strtoupper($this->search) . '%')
                        ->orWhere('des_subgrupo', 'like', '%' . strtoupper($this->search) . '%');
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate)
        ]);
    }
}