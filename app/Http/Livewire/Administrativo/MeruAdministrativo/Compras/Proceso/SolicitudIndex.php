<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Proceso;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Administrativo\Meru_Administrativo\Compras\EncSolicitud;

class SolicitudIndex extends Component
{
    use WithPagination, WithSorting;

    public $modulo;
    public $descripcionModulo;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    public function mount()
    {
        $this->sort = 'fec_emi';
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
        return view('livewire.administrativo.meru-administrativo.compras.proceso.solicitud-index', [
            'headers' => [
                            ['name' => 'Año', 'align' => 'center', 'sort' => 'ano_pro'],
                            ['name' => 'Solicitud', 'align' => 'center', 'sort' => 'grupo_numero'],
                            ['name' => 'Fecha Emisión', 'align' => 'center', 'sort' => 'fec_emi'],
                            ['name' => 'Gerencia', 'align' => 'left', 'sort' => 'fk_cod_ger'],
                            ['name' => 'Monto', 'align' => 'left', 'sort' => 'monto_tot'],
                            ['name' => 'Estado', 'align' => 'center', 'sort' => 'sta_sol'],
                            'Acción'
                        ],

            'solicitudes' =>    EncSolicitud::query()
                                                    ->with('gerencia:cod_ger,des_ger', 'estado:siglas,descripcion')
                                                    ->select('ano_pro','nro_req','grupo','fec_emi','fec_dev_com','fec_dev_cont','fec_dev_pre','fk_cod_ger','monto_tot','sta_sol','fk_cod_cau', DB::raw("grupo||'-'||nro_req as grupo_numero"))
                                                    ->when($this->search != '', function($query) {
                                                        $query->where('ano_pro', 'like', '%'.$this->search.'%')
                                                            ->orWhere(DB::raw("grupo||'-'||nro_req"), 'like', '%'.strtoupper($this->search).'%')
                                                            ->orWhere('fec_emi', 'like', '%'.$this->search.'%')
                                                            ->orWhereHas('gerencia', function($query){
                                                                $query->where('des_ger', 'like', '%'.strtoupper($this->search).'%');
                                                            })
                                                            ->orWhere('monto_tot', 'like', '%'.$this->search.'%')
                                                            ->orWhereHas('estado', function($query){
                                                                $query->where('descripcion', 'like', '%'.strtoupper($this->search).'%');
                                                            });
                                                    })
                                                    ->orderBy($this->sort, $this->direction)
                                                    ->paginate($this->paginate)

        ]);
    }
}
