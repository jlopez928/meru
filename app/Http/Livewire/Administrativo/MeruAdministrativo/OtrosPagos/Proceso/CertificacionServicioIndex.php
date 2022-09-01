<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\OtrosPagos\Proceso;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;

class CertificacionServicioIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';


    public function mount()
    {
        $this->sort =  'nro_sol' ;
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

        return view('livewire.administrativo.meru-administrativo.otros-pagos.proceso.certificacion-servicio-index', [
        'headers' => [
            ['name' => 'Id',              'align' => 'center', 'sort' => 'id'],
            ['name' => 'Ano',              'align' => 'center', 'sort' => 'ano_pro'],
            ['name' => 'Certificación', 'align' => 'center', 'sort' => 'xnro_sol'],
            ['name' => 'Fecha ',          'align' => 'center', 'sort' => 'fec_emi'],
            ['name' => 'Gerencia ',          'align' => 'center', 'sort' => 'cod_ger'],
            ['name' => 'Proveedor',          'align' => 'center', 'sort' => 'rif_prov'],
            ['name' => 'Monto',           'align' => 'center', 'sort' => 'monto_total'],
            ['name' => 'Estado',          'align' => 'center', 'sort' => 'sta_sol'],
            'Acción'
        ],
        'certificacion' => OpSolservicio::query()
                ->where('grupo', '=', 'PD')
                ->where('ano_pro', 'like', '%'.$this->search.'%')
                ->orWhere('xnro_sol', 'like', '%'.$this->search.'%')
                ->orwhere('rif_prov', 'like', '%'.$this->search.'%')
                ->orderBy($this->sort2, $this->direction)
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate)
                ]);



}

}
