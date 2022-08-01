<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Control;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Permiso;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class PermisoIndexComponent extends Component
{   use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';
    public function mount()
    {
        $this->sort = 'name';
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
        return view('livewire.administrativo.meru-administrativo.configuracion.control.permiso-index-component', [
            'headers' => [
                ['name' => 'ID', 'align' => 'center', 'sort' => 'id'],
                ['name' => 'Nombre', 'align' => 'center', 'sort' => 'name'],
                ['name' => 'Nombre de la Ruta', 'align' => 'center', 'sort' => 'name'],
                ['name' => 'Modulo', 'align' => 'center', 'sort' => 'name'],
                ['name' => 'Estado', 'align' => 'center', 'sort' => 'estado'],
                'Acción'
            ],
            'permiso' => Permiso::with('modulo')
                          ->whereNull('deleted_at')
                          ->where('id', 'LIKE', '%'.$this->search.'%')
                          ->orWhere('name','LIKE','%'.($this->search).'%')
                          ->orderBy($this->sort, $this->direction)
                          ->paginate($this->paginate)
      ]);
    }
}
