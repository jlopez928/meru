<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\CuentasPorPagar\Proceso;

use Livewire\Component;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacCausaDevolucion;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;

class EntregarFactura extends Component
{
    public $accion='entregar';
    public $recepfactura;
    public $proveedores;
    public $cxptipodocumento;
    public $ano_pro_act;
    public $causadev=[];
    public $onfocus;
    public $fec_entrega;
    protected $listeners =['habilita'=>'activa'];

    public $gerencias;
    public $resp_dev;
    public $marcar;
    public $devolucion;


    public function cargar_emit()
    {
        $this->emit('enableEntrega');
       // $this->emit('alert', ['tab' => '#devolucion-tab','onfocus' => $this->onfocus]);
    }

    public function mount()
    {

       foreach($this->recepfactura->faccausaxfactura as $index => $causa){
            $this->causadev[] = [
                'marcar'        => 'Si',
                'cod_dev'       => $causa['cod_dev'],
                'descrip_dev'   => FacCausaDevolucion::where('cod_dev',$causa['cod_dev'])->select('descrip_dev')->first()
            ];
        }
//dd($this->causadev);
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.cuentas-por-pagar.proceso.entregar-factura',[
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
