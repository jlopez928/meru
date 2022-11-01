<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\CuentasPorPagar\Proceso;

use Livewire\Component;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use Illuminate\Support\Facades\DB;


class DevolverRecepFactura extends Component
{
    public $accion='devolver';
    public $recepfactura;
    public $proveedores;
    public $cxptipodocumento;
    public $gerencias;
    public $resp_dev;
    public $faccausadevolucion;
    public $marcar;
    public $devolucion;

    public function cargar_emit()
    {
        $this->emit('alert', ['det' => $this->devolucion]);
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.cuentas-por-pagar.proceso.devolver-recep-factura',[
            'proveedores' => Proveedor::where('cod_edo','1')
                                    ->select('rif_prov','nom_prov')
                                    ->orderBy('nom_prov')
                                    ->get(),
            'cxptipodocumento' => CxPTipoDocumento::query()
                    ->where('status','1')
                    ->where('recp_factura','1')
                    ->get(),
            'ano_pro_act' => RegistroControl::periodoActual()
       ]);
    }

}
