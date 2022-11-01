<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Proceso;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use App\Models\Administrativo\Meru_Administrativo\Compras\Bien;
class BienVehiculo extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';
    public $vehiculos = [];
    public $solicitudUnidad;
    public $accion;

    public function mount()
    {
        $this->sort = 'cod_corr';
        $this->direction = 'desc';

        if($this->solicitudUnidad->ano_pro){

            if($this->solicitudUnidad->grupo == 'SV'){
                $servicios = $this->solicitudUnidad->vehiculos;

                $servicios->map( function($item, $key){
                    $this->vehiculos[] =    [
                                                'cod_corr' => $item->cod_corr,
                                                'placa'    => $item->bien->placa,
                                                'modelo'   => $item->bien->modelo,
                                                'marca'    => $item->bien->marca
                                            ];
                });
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPaginate()
    {
        $this->resetPage();
    }

    public function updatedVehiculos($value)
    {
        if (count($value) > 16) {
            $this->emit('swal:alert', [
                'tipo'      => 'warning',
                'mensaje'   => 'No puede seleccionar mas de 16 Vehiculos'
            ]);

            array_pop($this->vehiculos);
        }
    }

    public function agregarVehiculo(Bien $bien)
    {
        $this->vehiculos[] =    [
                                    'cod_corr'  => $bien->cod_corr,
                                    'placa'     => $bien->placa,
                                    'modelo'    => $bien->modelo,
                                    'marca'     => $bien->marca
                                ];

        $this->emit('cargarVehiculo', ['vehiculos' => $this->vehiculos]);
    }

    public function eliminarVehiculo($index)
    {
        unset($this->vehiculos[$index]);

        $this->vehiculos = array_values($this->vehiculos);

        $this->emit('cargarVehiculo', ['vehiculos' => $this->vehiculos]);
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.proceso.bien-vehiculo', [
            'headers' => [
                ['name' => 'Cód. Correlativo', 'align' => 'left', 'sort' => 'cod_corr'],
                ['name' => 'Placa', 'align' => 'left', 'sort' => 'placa'],
                ['name' => 'modelo', 'align' => 'left', 'sort' => 'modelo'],
                ['name' => 'marca', 'align' => 'left', 'sort' => 'marca'],
                'Acción'
            ],
            'vehiculosList' => Bien::query()
                                ->select('cod_corr', 'placa', 'modelo', 'marca')
                                ->whereNotIn('cod_corr', array_column($this->vehiculos, 'cod_corr'))
                                ->when($this->search != '', function($query) {
                                    $query->where('cod_corr', 'like', '%'.$this->search.'%')
                                        ->orWhere('placa', 'like', '%'.strtoupper($this->search).'%')
                                        ->orWhere('modelo', 'like', '%'.strtoupper($this->search).'%')
                                        ->orWhere('marca', 'like', '%'.strtoupper($this->search).'%');
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->paginate),
        ]);
    }
}
