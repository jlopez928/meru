<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Proceso;

use App\Models\Administrativo\Meru_Administrativo\Compras\SolicitudUnidad;
use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;

class SolicitudUnidadIndex extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';

    protected $listeners = ['activarSolicitud','aprobarSolicitud'];

    public function confirmActivarSolicitud($ano_pro, $grupo, $nro_req)
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de ACTIVAR la Solicitud?',
            'funcion'   => 'activarSolicitud',
            'ano_pro'   => $ano_pro,
            'grupo'     => $grupo,
            'nro_req'   => $nro_req
        ]);
    }

    public function activarSolicitud($ano_pro, $grupo, $nro_req)
    {
        return redirect()->route('compras.proceso.solicitud_unidad.activar', [$ano_pro, $grupo, $nro_req]);
    }

    public function confirmAprobarSolicitud($ano_pro, $grupo, $nro_req)
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de CONFORMAR EN PRESUPUESTO la Solicitud?',
            'funcion'   => 'aprobarSolicitud',
            'ano_pro'   => $ano_pro,
            'grupo'     => $grupo,
            'nro_req'   => $nro_req
        ]);
    }

    public function aprobarSolicitud($ano_pro, $grupo, $nro_req)
    {
        return redirect()->route('compras.proceso.solicitud_unidad.aprobar', [$ano_pro, $grupo, $nro_req]);
    }

    public function mount()
    {
        $this->sort = 'ano_pro';
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
        return view('livewire.administrativo.meru-administrativo.compras.proceso.solicitud-unidad-index', [
            'headers' => [
                            ['name' => 'Año', 'align' => 'center', 'sort' => 'ano_pro'],
                            ['name' => 'Número', 'align' => 'center', 'sort' => 'nro_req'],
                            ['name' => 'Grupo', 'align' => 'center', 'sort' => 'grupo'],
                            ['name' => 'Fecha Emisión', 'align' => 'center', 'sort' => 'fec_emi'],
                            ['name' => 'Gerencia', 'align' => 'left', 'sort' => 'fk_cod_ger'],
                            ['name' => 'Monto', 'align' => 'left', 'sort' => 'monto_tot'],
                            ['name' => 'Estado', 'align' => 'center', 'sort' => 'sta_sol'],
                            'Acción'
                        ],

            'solicitudesUnidad' => SolicitudUnidad::query()
                                                    ->with('gerencia:cod_ger,des_ger', 'estado:siglas,descripcion')
                                                    ->select('ano_pro','nro_req','grupo','fec_emi','fk_cod_ger','monto_tot','sta_sol','fk_cod_cau')
                                                    ->when($this->search != '', function($query) {
                                                        $query->where('ano_pro', 'like', '%'.$this->search.'%')
                                                            ->orWhere('nro_req', 'like', '%'.$this->search.'%')
                                                            ->orWhere('grupo', 'like', '%'.strtoupper($this->search).'%')
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
