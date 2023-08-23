<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Modificaciones\Configuracion;

use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class PermisoTraspasoIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search   = '';
    public $paginate = '10';
    
    public function mount()
    {
        $this->sort      = 'usuario';
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

    public function order()
    {

    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.modificaciones.configuracion.permiso-traspaso-index', [
            'headers' => [
                ['name' => 'Usuario', 'align' => 'center', 'sort' => 'usuario', 'width' => '40%'],
                ['name' => 'Unidades Tributarias', 'align' => 'center', 'sort' => 'maxut', 'width' => '20%'],
                ['name' => 'Multicentro', 'align' => 'center', 'sort' => 'multicentro', 'width' => '20%'],
                'Acción'
            ],
            'permisos' => PermisoTraspaso::query()
                ->where('usuario', 'ilike', '%'.$this->search.'%')
                ->whereNotNull('usuario_id')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate),
        ]);
    }
}
