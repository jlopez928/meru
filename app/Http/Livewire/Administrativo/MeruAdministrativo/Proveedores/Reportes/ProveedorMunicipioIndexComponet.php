<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Proveedores\Reportes;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\UbicacionGeografica;

use Livewire\Component;

class ProveedorMunicipioIndexComponet extends Component
{


    public $estados = [], $municipios = [], $parroquias = [];
	public $estado, $municipio, $parroquia;

	public function mount()
	{
		$this->estados = UbicacionGeografica::getEstados();
		$this->municipios = collect();
		$this->parroquias = collect();
	}

	public function updatedEstado($value)
	{
		$this->municipios = UbicacionGeografica::getMunicipios($value);
		$this->municipio  = null;
		$this->parroquias = collect();
		$this->parroquia  = null;
	}

	public function updatedMunicipio($value)
	{
		$this->parroquias = UbicacionGeografica::getParroquias($this->estado, $value);
		$this->parroquia  = null;
	}

	public function render()
	{
        return view('livewire.administrativo.meru-administrativo.proveedores.reportes.proveedor-municipio-index-componet');
	}

}
