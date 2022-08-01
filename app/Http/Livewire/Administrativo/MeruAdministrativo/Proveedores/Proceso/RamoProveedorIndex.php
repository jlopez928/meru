<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Proveedores\Proceso;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;

class RamoProveedorIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort = 'nom_prov';
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
        return view('livewire.administrativo.meru-administrativo.proveedores.proceso.ramo-proveedor-index', [
            'headers' => [
                            ['name' => 'Rif', 'align' => 'center', 'sort' => 'rif_prov'],
                            ['name' => 'Nombre', 'align' => 'left', 'sort' => 'nom_prov'],
                            'Acción'
                        ],
            'proveedores' => Proveedor::query()
                                        ->select('rif_prov','nom_prov')
                                        ->where('rif_prov', 'like', '%'.strtoupper($this->search).'%')
                                        ->orWhere('nom_prov', 'like', '%'.strtoupper($this->search).'%')
                                        ->orderBy($this->sort, $this->direction)
                                        ->paginate($this->paginate)

        ]);
    }
}