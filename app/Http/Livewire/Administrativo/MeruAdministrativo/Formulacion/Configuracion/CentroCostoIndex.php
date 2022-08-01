<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Formulacion\Configuracion;

use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class CentroCostoIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public $paginate           = '10';
    
    public function mount()
    {
        $this->sort      = 'id';
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

    public function order()
    {

    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.formulacion.configuracion.centro-costo-index', [
            'headers' => [
                ['name' => 'ID', 'align' => 'center', 'sort' => 'id', 'width' => '7%'],
                ['name' => 'Año', 'align' => 'center', 'sort' => 'ano_pro', 'width' => '8%'],
                ['name' => 'Código', 'align' => 'center', 'sort' => 'cod_cencosto', 'width' => '12%'],
                ['name' => 'Descripción', 'align' => 'center', 'sort' => 'des_con', 'width' => '47%'],
                ['name' => 'Crédito Adicional', 'align' => 'center', 'sort' => 'cre_adi', 'width' => '8%'],
                ['name' => 'Estado', 'align' => 'center', 'sort' => 'sta_reg', 'width' => '7%'],
                'Acción'
            ],
            'centros' => CentroCosto::query()
                ->where('des_con', 'ilike', '%'.$this->search.'%')
                ->orWhere('cod_cencosto', 'ilike', $this->search.'%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate)
        ]);
    }
}