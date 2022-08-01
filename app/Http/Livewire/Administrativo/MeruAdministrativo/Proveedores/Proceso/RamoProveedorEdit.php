<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Proveedores\Proceso;

use Livewire\Component;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\RamoProveedor;

class RamoProveedorEdit extends Component
{
    public $rif_prov;
    public $nom_prov;
    public $proveedor;
    public $selectedRamoId;
    public $ramos = [];

    protected $rules =  [
                            'selectedRamoId'    => 'required'
                        ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        $this->rif_prov = $this->proveedor->rif_prov;
        $this->nom_prov = $this->proveedor->nom_prov;
        $this->ramos    = $this->proveedor->ramos;
    }

    public function deleteRamo($cod_ram)
    {
        try {
            $this->proveedor->ramos()->detach($cod_ram);

        }catch (\Exception $ex) {
            flash()->addError('Transacción Fallida: '. str($ex)->limit(250));

            return redirect()->back()->withInput();
        }

        flash()->addSuccess('Ramo desvinculado con  éxito');

        $this->ramos = Ramo::whereIn('cod_ram', RamoProveedor::where('rif_prov', $this->proveedor->rif_prov)->pluck('cod_ram'))->get(['cod_ram', 'des_ram']);
    }

    public function addRamo()
    {
        $this->validate();

        try {
            $this->proveedor->ramos()->attach($this->selectedRamoId, ['usuario' => auth()->id() ]);

        }catch (\Exception $ex) {
            flash()->addError('Transacción Fallida: '. str($ex)->limit(250));

            return redirect()->back()->withInput();
        }

        flash()->addSuccess('Ramo agregado con  éxito');

        $this->ramos = Ramo::whereIn('cod_ram', RamoProveedor::where('rif_prov', $this->proveedor->rif_prov)->pluck('cod_ram'))->get(['cod_ram', 'des_ram']);

        $this->resetRamos();
    }

    public function getRamoProperty(){
        return Ramo::query()
                        ->where('sta_reg', 1)
                        ->whereNotIn('cod_ram', $this->ramos->pluck('cod_ram'))
                        ->orderBy('des_ram')
                        ->pluck('des_ram','cod_ram');
    }

    public function resetRamos()
    {
        $this->reset(['selectedRamoId']);
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.proveedores.proceso.ramo-proveedor-edit');
    }
}