<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Proveedores\Reportes;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\UbicacionGeografica;

use Livewire\Component;

class ProveedorMunicipioIndexComponet extends Component
{


    public $estados = [], $municipios = [];
	public $estado, $municipio;

	public function mount()
	{
		$this->estados = UbicacionGeografica::getEstados();
		$this->municipios = collect();
	}

	public function updatedEstado($value)
	{
		$this->municipios = UbicacionGeografica::getMunicipios($value);
		$this->municipio  = null;
	}

	public function render()
	{
        return view('livewire.administrativo.meru-administrativo.proveedores.reportes.proveedor-municipio-index-componet');
	}

}
