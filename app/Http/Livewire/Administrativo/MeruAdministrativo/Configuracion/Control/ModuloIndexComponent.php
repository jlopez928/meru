<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Control;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Modulo;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;



class ModuloIndexComponent extends Component
{ use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort = 'nombre';
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


        return view('livewire.administrativo.meru-administrativo.configuracion.control.modulo-index-component', [
        'headers' => [
            ['name' => 'ID', 'align' => 'center', 'sort' => 'id'],
            ['name' => 'Nombre', 'align' => 'center', 'sort' => 'name'],
            ['name' => 'Estado', 'align' => 'center', 'sort' => 'estado'],
            'Acción'
        ],
        'modulo' =>  Modulo::query()
                            ->whereNull('deleted_at')
                            ->where('id', 'LIKE', '%'.$this->search.'%')
                            ->orWhere('nombre','LIKE','%'.($this->search).'%')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->paginate)
   ]);
    }
}
