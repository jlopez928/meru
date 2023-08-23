<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Proveedores\Configuracion;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;

class RamoIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort = 'des_ram';
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
        return view('livewire.administrativo.meru-administrativo.proveedores.configuracion.ramo-index', [
            'headers' => [
                ['name' => 'Código', 'align' => 'center', 'sort' => 'cod_ram'],
                ['name' => 'Descripción', 'align' => 'left', 'sort' => 'des_ram'],
                ['name' => 'Estado', 'align' => 'center', 'sort' => 'sta_reg'],
                'Acción'
            ],
            'ramos' => Ramo::query()
                ->withCount('ramoproveedores')
                ->when($this->search != '', function ($query) {
                    $query->where('cod_ram', 'like', '%' . $this->search . '%')
                        ->orWhere('des_ram', 'like', '%' . strtoupper($this->search) . '%');
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate)
        ]);
    }
}