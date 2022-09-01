<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Configuracion;

use App\Models\Administrativo\Meru_Administrativo\Compras\GrupoProducto;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;

class GrupoProductoIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort = 'grupo';
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
        return view('livewire.administrativo.meru-administrativo.compras.configuracion.grupo-producto-index', [
            'headers' => [
                            ['name' => 'Grupo', 'width' => '10%', 'align' => 'left', 'sort' => 'grupo'],
                            ['name' => 'Descripción','width' => '70%', 'align' => 'left', 'sort' => 'des_grupo'],
                            ['name' => 'Estado', 'width' => '10%', 'align' => 'center', 'sort' => 'sta_reg'],
                            'Acción'
                        ],
            'grupos' => GrupoProducto::query()
                                ->withCount('subgrupoproductos')
                                ->when($this->search != '', function($query) {
                                    $query->where('grupo', 'like', '%'.strtoupper($this->search).'%')
                                    ->orWhere('des_grupo', 'like', '%'.strtoupper($this->search).'%');
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->paginate)
        ]);
    }
}
