<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Proceso;

use App\Models\Administrativo\Meru_Administrativo\Compras\Bien;
use Livewire\Component;

class BienVehiculo extends Component
{
    public $selectedVehiculos = [];

    public function updatedSelectedVehiculos($value)
    {
        if (count($value) > 16) {
            $this->emit('swal:alert', [
                'tipo'      => 'warning',
                'titulo'    => 'Servicio Vehiculos',
                'mensaje'   => 'No puede seleccionar mas de 16 Vehiculos'
            ]);

            array_pop($this->selectedVehiculos);
        }
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.proceso.bien-vehiculo', [
            'vehiculos' => Bien::get(['cod_corr', 'placa', 'modelo', 'marca'])
        ]);
    }
}
