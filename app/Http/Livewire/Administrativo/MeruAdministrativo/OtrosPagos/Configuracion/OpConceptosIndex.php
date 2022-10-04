<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\OtrosPagos\Configuracion;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpConceptos;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;


class OpConceptosIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';


    public function mount()
    {
        $this->sort =  'des_con' ;
        $this->sort2 =  'cod_con' ;
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
        return view('livewire.administrativo.meru-administrativo.otros-pagos.configuracion.op-conceptos-index', [
            'headers' => [
                ['name' => 'Id',              'align' => 'center', 'sort' => 'id'],
                ['name' => 'Codigo',              'align' => 'center', 'sort' => 'cod_con'],
                ['name' => 'Descripción', 'align' => 'center', 'sort' => 'des_con'],
                ['name' => 'Estado ',          'align' => 'center', 'sort' => 'sta_reg'],
                'Acción'
            ],
            'opconceptos' => OpConceptos::query()
                    ->where('cod_con', 'like', '%'.$this->search.'%')
                    ->orWhere('des_con', 'like', '%'.strtoupper($this->search).'%')
                    ->orderBy($this->sort2, $this->direction)
                    ->orderBy($this->sort, $this->direction)
                    ->paginate($this->paginate)
                    ]);



    }
}
