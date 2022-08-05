<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Configuracion\Configuracion;

use App\Models\Administrativo\Meru_Administrativo\Configuracion\TipoMonto;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Residencia;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Retencion;
use Livewire\Component;
use Carbon\Carbon;

class SelectInput extends Component
{
    public $descuento;
    public $selectedResidencia;
    public $selectedTipoMonto;
    public $selectedRetencion;
    public $residente;
    public $tip_mto;
    public $cla_desc;



    public function mount(){

        if ($this->descuento){
            $this->selectedResidencia = $this->descuento->adm_residencia_id;
            $this->selectedTipoMonto = $this->descuento->tipo_montos_id;
            $this->selectedRetencion = $this->descuento->adm_retencion_id;
            $this->residente = $this->descuento->residente;
            $this->tip_mto = $this->descuento->tip_mto;
            $this->cla_desc = $this->descuento->cla_desc;
        }
    }

    public function updatedSelectedResidencia($id){
        $this->residente = Residencia::where('id',$id)->first()->codigo;
    }

    public function updatedSelectedTipoMonto($id){
        $this->tip_mto = TipoMonto::where('id',$id)->first()->codigo;
    }

    public function updatedSelectedRetencion($id){
        $this->cla_desc = Retencion::where('id',$id)->first()->cod_ret;
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.configuracion.configuracion.select-input',[
            'residencia' =>  Residencia::query()->get(),
            'tipomontos' =>  TipoMonto::query()->get(),
            'retencion' =>  Retencion::query()->get()
         ]);
    }

}
