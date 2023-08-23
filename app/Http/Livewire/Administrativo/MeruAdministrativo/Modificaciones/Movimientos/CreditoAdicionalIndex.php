<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Modificaciones\Movimientos;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\TipoModificacion;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\Modificacion;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class CreditoAdicionalIndex extends Component
{

    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search   = '';
    public $paginate = '10';
    
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
        return view('livewire.administrativo.meru-administrativo.modificaciones.movimientos.credito-adicional-index',[
            'headers' => [
                ['name' => 'Año', 'align' => 'center', 'sort' => 'ano_pro', 'width' => '8%'],
                ['name' => 'Código', 'align' => 'center', 'sort' => 'xnro_mod', 'width' => '12%'],
                ['name' => 'Concepto', 'align' => 'center', 'sort' => 'concepto', 'width' => '25%'],
                ['name' => 'Justificación', 'align' => 'center', 'sort' => 'juastificacion', 'width' => '25%'],
                ['name' => 'Fecha', 'align' => 'center', 'sort' => 'fec_sol', 'width' => '10%'],
                ['name' => 'Estado', 'align' => 'center', 'sort' => 'sta_reg', 'width' => '10%'],
                'Acción'
            ],
            'creditosAdicionales' => Modificacion::query()
                ->where('tip_ope', TipoModificacion::Credito_Adicional)
                ->where(function ($query){
                    $query->where('xnro_mod', 'ilike', '%'.$this->search.'%')
                    ->orWhere('concepto', 'ilike', '%'.$this->search.'%')
                    ->orWhere('justificacion', 'ilike', '%'.$this->search.'%');
                })
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate),
            'periodos' => RegistroControl::periodosAbiertos()->toArray()
        ]);
    }
}
