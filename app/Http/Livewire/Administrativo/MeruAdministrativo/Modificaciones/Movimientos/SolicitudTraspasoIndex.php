<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Modificaciones\Movimientos;

use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class SolicitudTraspasoIndex extends Component
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
        return view('livewire.administrativo.meru-administrativo.modificaciones.movimientos.solicitud-traspaso-index',[
            'headers' => [
                ['name' => 'Año', 'align' => 'center', 'sort' => 'ano_pro', 'width' => '8%'],
                ['name' => 'Solicitud', 'align' => 'nro_sol', 'sort' => 'nro_sol', 'width' => '8%'],
                ['name' => 'Gerencia', 'align' => 'center', 'sort' => null, 'width' => '14%'],
                ['name' => 'Justificación', 'align' => 'center', 'sort' => 'justificacion', 'width' => '30%'],
                ['name' => 'Documento', 'align' => 'center', 'sort' => 'num_sop', 'width' => '10%'],
                ['name' => 'Fecha', 'align' => 'center', 'sort' => 'fec_sol', 'width' => '10%'],
                ['name' => 'Estado', 'align' => 'center', 'sort' => 'sta_reg', 'width' => '10%'],
                'Acción'
            ],
            'solicitudes' => SolicitudTraspaso::query()
                ->with('gerencia', 'centroCosto')
                ->orWhere('nro_sol', 'ilike', '%'.$this->search.'%')
                ->orWhere('justificacion', 'ilike', '%'.$this->search.'%')
                ->orWhere('num_sop', 'ilike', '%'.$this->search.'%')
                ->orWhere('num_sop', 'ilike', '%'.$this->search.'%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate),
            'periodos' => RegistroControl::periodosAbiertos()->toArray()
        ]);
    }
}
