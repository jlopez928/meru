<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\CuentasPorPagar\Proceso;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacRecepFactura;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;


class RecepFacturaIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort =  'id' ;
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

        return view('livewire.administrativo.meru-administrativo.cuentas-por-pagar.proceso.recep-factura-index', [
            'headers' => [
                ['name' => 'Id',              'align' => 'center', 'sort' => 'id'],
                ['name' => 'Fecha Factura',   'align' => 'center', 'sort' => 'fec_fac'],
                ['name' => 'N° Factura',      'align' => 'center', 'sort' => 'num_fac'],
                ['name' => 'Ano',             'align' => 'center', 'sort' => 'ano_pro'],
                ['name' => 'Recibo',          'align' => 'center', 'sort' => 'recibo'],
                ['name' => 'Proveedor ',      'align' => 'center', 'sort' => 'rif_prov'],
                ['name' => 'Monto',           'align' => 'center', 'sort' => 'mto_fac'],
                ['name' => 'Estado',          'align' => 'center', 'sort' => 'sta_fac'],
                'Acción'
            ],
            'recepfactura' => FacRecepFactura::query()
                    ->where('num_fac', 'like', '%'.$this->search.'%')
                    ->orWhere('ano_pro', 'like', '%'.$this->search.'%')
                    ->orWhere('recibo', 'like', '%'.$this->search.'%')
                    ->orWhere('rif_prov', 'like', '%'.$this->search.'%')
                    ->orderBy($this->sort2, $this->direction)
                    ->orderBy($this->sort, $this->direction)
                    ->paginate($this->paginate)
                    ]);
    }
}
