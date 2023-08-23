<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Configuracion;
use App\Models\Administrativo\Meru_Administrativo\Compras\CausaAnulacion;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;


class CausaAnulacionIndex extends Component
{use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';


    public function mount()
    {
        $this->sort = 'des_cau';
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
        return view('livewire.administrativo.meru-administrativo.compras.configuracion.causa-anulacion-index',[
            'headers' => [
                ['name' => 'ID',          'align' => 'center', 'sort' => 'id'],
                ['name' => 'Código.',     'align' => 'center', 'sort' => 'cod_cau'],
                ['name' => 'Descripción', 'align' => 'center', 'sort' => 'des_cau'],
                ['name' => 'Estado',      'align' => 'center', 'sort' => 'sta_reg'],
                'Acción'
            ],
            'causaanulacion' => CausaAnulacion::query()
                            ->orWhere('cod_cau', 'like', '%'.strtoupper($this->search).'%')
                            ->orWhere('des_cau', 'like', '%'.strtoupper($this->search).'%')
                            ->orwhere('sta_reg', 'like', '%'.ucfirst($this->search).'%')
                            ->orderBy($this->sort, $this->direction)
                            ->paginate($this->paginate)
                            ]);
    }
}
