<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Contratos\Proceso;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Facades\Route;

class CertificacionContratoIndex extends Component
{   use WithPagination, WithSorting;

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
       if ( Route::currentRouteName()=='contratos.proceso.certificacioncontrato.index'){
                $query= OpSolservicio::query()
                ->where('grupo', '=', 'CO')
                ->where('ult_sol', '>=', 0)
                ->where('ano_pro', 'like', '%'.$this->search.'%')
                ->Where('xnro_sol', 'like', '%'.$this->search.'%')
                ->where('rif_prov', 'like', '%'.$this->search.'%')
                ->orderBy($this->sort2, $this->direction)
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate);
       }else{
                $query= OpSolservicio::query()
                ->where('grupo', '=', 'CO')
                ->where('ult_sol', '=', -1)
                ->where('ano_pro', 'like', '%'.$this->search.'%')
                ->Where('xnro_sol', 'like', '%'.$this->search.'%')
                ->where('rif_prov', 'like', '%'.$this->search.'%')
                ->orderBy($this->sort2, $this->direction)
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->paginate);
       }
        return view('livewire.administrativo.meru-administrativo.contratos.proceso.certificacion-contrato-index', [
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
            'certificacion' =>$query
                    ]);



    }

    }
