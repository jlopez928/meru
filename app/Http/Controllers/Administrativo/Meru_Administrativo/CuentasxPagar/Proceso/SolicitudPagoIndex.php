<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\CuentasxPagar\Proceso;

use Livewire\Component;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Solpago;
use App\Traits\WithSorting;
use Livewire\WithPagination;

class SolicitudPagoIndex extends Component
{use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort =  'ord_pag' ;
        $this->sort2 =  'ano_pro' ;
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
        return view('livewire.administrativo.meru-administrativo.cuentasx-pagar.proceso.solicitud-pago-index',[
            'headers' => [
                ['name' => 'Id',              'align' => 'center', 'sort' => 'id'],
                ['name' => 'Ano',              'align' => 'center', 'sort' => 'ano_pro'],
                ['name' => 'Certificación', 'align' => 'center', 'sort' => 'xnro_sol'],
                ['name' => 'Nro Factura', 'align' => 'center', 'sort' => 'xnro_sol'],
                ['name' => 'Fecha ',          'align' => 'center', 'sort' => 'fec_emi'],
                ['name' => 'Proveedor',          'align' => 'center', 'sort' => 'rif_prov'],
                ['name' => 'Monto',           'align' => 'center', 'sort' => 'monto_total'],
                ['name' => 'Estado',          'align' => 'center', 'sort' => 'sta_sol'],
                'Acción'
            ],
            'solpago' =>Solpago::query()
                                ->where('tipo_doc',  'like', '%'.$this->search.'%')
                                ->where('ano_pro', 'like', '%'.$this->search.'%')
                                ->Where('ord_pag', 'like', '%'.$this->search.'%')
                                ->where('benefi', 'like', '%'.$this->search.'%')
                                ->orderBy($this->sort2, $this->direction)
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->paginate)
                                        ]);

    }
}

