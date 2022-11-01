<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\CuentasPorPagar\Proceso;

use Livewire\Component;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacCausaDevolucion;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacCausaxFactura;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;

class ModificarFactura extends Component
{


    public $accion='entregar';
    public $recepfactura;
    public $proveedores;
    public $cxptipodocumento;
    public $faccausadevolucion;
    public $ano_pro;
    public $causadev=[];
    public $onfocus;
    public $fec_entrega;
    public $sta_fac;
    public $mto_fac;
    public $ano_sol;
    public $nro_doc;
    public $tipo_doc;
    public $recibo;
    public $marca;
    public $fec_rec;
    public $fec_fac;
    public $fec_dev;
    public $observaciones;
    protected $listeners =['habilita'=>'activa'];
    public $faccausaxfactura;
    public $gerencias;
    public $resp_dev;
    public $marcar=[];
    public $devolucion;


    public function cargar_emit()
    {
        $this->marcar =  $this->causadev;


         $this->sta_fac     = $this->recepfactura->sta_fac;
         $this->mto_fac     = $this->recepfactura->mto_fac;
         $this->ano_sol     = $this->recepfactura->ano_sol;
         $this->nro_doc     = $this->recepfactura->nro_doc;
         $this->tipo_doc    = $this->recepfactura->tipo_doc;
         $this->recibo      = $this->recepfactura->recibo;
         if ($this->recepfactura->fec_rec)
         $this->fec_rec     = $this->recepfactura->fec_rec->format('Y-m-d');
         if ($this->recepfactura->fec_fac)
         $this->fec_fac     = $this->recepfactura->fec_fac->format('Y-m-d');
         if ($this->recepfactura->fec_dev)
         $this->fec_dev     = $this->recepfactura->fec_dev->format('Y-m-d');
         if ($this->recepfactura->fec_entrega)
            $this->fec_entrega     = $this->recepfactura->fec_entrega->format('Y-m-d');
         $this->observaciones    = $this->recepfactura->observaciones;
         $this->resp_dev    = $this->recepfactura->resp_dev;


        if ($this->sta_fac == 2){
            $this->emit('enableFacDev');
        }
        else{
            $this->emit('enableFacMod');
        }
        // $this->emit('alert', ['tab' => '#devolucion-tab','onfocus' => $this->onfocus]);
    }

    public function mount()
    {

       $this->sta_fac     = $this->recepfactura->sta_fac;
        if ($this->sta_fac == 2){
            foreach($this->faccausadevolucion as $index => $causa){

            $this->marca = FacCausaxFactura::where('ano_pro',   $this->recepfactura->ano_pro)
                                ->where('nro_reng',  $this->recepfactura->nro_reng)
                                ->where('rif_prov',  $this->recepfactura->rif_prov)
                                ->where('num_fac',   $this->recepfactura->num_fac)
                                ->whereIn('cod_dev',[ $causa['cod_dev']])
                                ->selectRaw("'Si' as marca")
                                ->first();



                $this->causadev[] = [
                    'marcar'       => ($this->marca) ? $this->marca->marca : 'No',
                'cod_dev'       => $causa['cod_dev'],
                'descrip_dev'   => $causa['descrip_dev'],
                ];

            }
        }

        // foreach($this->recepfactura->faccausaxfactura as $index => $causa){
        //     $this->causadev[] = [
        //         'marcar'        => 'Si',
        //         'cod_dev'       => $causa['cod_dev'],
        //         'descrip_dev'   => FacCausaDevolucion::where('cod_dev',$causa['cod_dev'])->select('descrip_dev')->first()
        //     ];
        // }

    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.cuentas-por-pagar.proceso.modificar-factura',[
            'cxptipodocumento' => CxPTipoDocumento::query()
                    ->where('status','1')
                    ->where('recp_factura','1')
                    ->get(),
            'ano_pro' => RegistroControl::periodoActual(),
            'gerencias' => Gerencia::where('cod_ger','<>','17')
                                   ->select( 'cod_ger', 'des_ger')
                                   ->orderBy('des_ger')
                                   ->get(),
            'faccausadevolucion' => FacCausaDevolucion::query()->get()
        ]);
    }

}
