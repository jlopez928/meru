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
                            ['name' => 'Año', 'align' => 'left', 'sort' => 'ano_pro'],
                            ['name' => 'Número', 'align' => 'left', 'sort' => 'nro_req'],
                            ['name' => 'Grupo', 'align' => 'left', 'sort' => 'grupo'],
                            ['name' => 'Fecha Emisión', 'align' => 'left', 'sort' => 'fec_emi'],
                            ['name' => 'Monto Total', 'align' => 'left', 'sort' => 'monto_tot'],
                            'Acción'
                        ],
            'solicitudesUnidad' => SolicitudUnidad::query()
                                        ->select('ano_pro','nro_req','grupo','fec_emi','monto_tot')
                                        ->when($this->search != '', function($query) {
                                            $query->where('ano_pro', 'like', '%'.$this->search.'%')
                                                ->orWhere('nro_req', 'like', '%'.$this->search.'%')
                                                ->orWhere('grupo', 'like', '%'.strtoupper($this->search).'%')
                                                ->orWhere('fec_emi', 'like', '%'.$this->search.'%')
                                                ->orWhere('monto_tot', 'like', '%'.$this->search.'%');
                                        })
                                        ->orderBy($this->sort, $this->direction)
                                        ->paginate($this->paginate)

        ]);
    }
}
