<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Proceso;

use App\Models\Administrativo\Meru_Administrativo\Compras\Producto;
use Livewire\Component;

class SolicitudUnidadDetalle extends Component
{
    public $productos = [];
    public $detalle_productos = [];
    public $grupo;
    public $grupo_ram;

    public $cod_prod;
    public $des_prod;
    public $cod_uni;
    public $des_uni;
    public $cantidad = 0;
    public $ult_pre;
    public $mon_sub_tot;
    public $cod_par;
    public $cod_gen;
    public $cod_esp;
    public $cod_sub;
    public $cod_status = 0;
    public $renglon;

    protected $listeners = ['getProductos'];

    public function getProductos($grupo_ram, $grupo)
    {
        $this->grupo     = $grupo;
        $this->grupo_ram = $grupo_ram;

        $this->productos = Producto::query()
                                    ->where('gru_ram', $grupo_ram)
                                    ->when($grupo == 'BM', function($query){
                                        $query->where(function($q) {
                                            $q->where('tip_prod', 'B')->orWhere('tip_prod', 'P');
                                        });
                                    })
                                    ->when($grupo == 'SG', function($query){
                                        $query->where(function($q) {
                                            $q->where('tip_prod', 'G')->orWhere('tip_prod', 'O');
                                        });
                                    })
                                    ->when($grupo == 'SV', function($query){
                                        $query->where(function($q) {
                                            $q->where('tip_prod', 'G')->orWhere('tip_prod', 'V');
                                        });
                                    })
                                    ->orderBy('des_prod')
                                    ->pluck('des_prod','cod_prod');
    }

    public function updatedCodProd($cod_prod)
    {
        ds($cod_prod);
        if($cod_prod)
        {
            $producto = Producto::query()
                ->with('unidadmedida:cod_uni,des_uni')
                ->where('cod_prod', $cod_prod)
                ->where('gru_ram', $this->grupo_ram)
                ->when($this->grupo == 'BM', function($query){
                    $query->where(function($q) {
                        $q->where('tip_prod', 'B')->orWhere('tip_prod', 'P');
                    });
                })
                ->when($this->grupo == 'SG', function($query){
                    $query->where(function($q) {
                        $q->where('tip_prod', 'G')->orWhere('tip_prod', 'O');
                    });
                })
                ->when($this->grupo == 'SV', function($query){
                    $query->where(function($q) {
                        $q->where('tip_prod', 'G')->orWhere('tip_prod', 'V');
                    });
                })
                ->first(['des_prod','cod_uni','ult_pre','por_iva','por_islr','stock','cod_par','cod_gen','cod_esp','cod_sub']);

            $this->des_prod     = $producto->des_prod;
            $this->cod_uni      = $producto->cod_uni;
            $this->des_uni      = $producto->unidadmedida->des_uni;
            $this->ult_pre      = $producto->ult_pre;
            $this->mon_sub_tot  = (($this->ult_pre * $this->cantidad)*100)/100;
            $this->cod_par      = $producto->cod_par;
            $this->cod_gen      = $producto->cod_gen;
            $this->cod_esp      = $producto->cod_esp;
            $this->cod_sub      = $producto->cod_sub;

        }
    }

    public function agregarRenglon()
    {
        $this->detalle_productos[] =    [
                                            'cod_prod'              => $this->cod_prod,
                                            'des_prod'              => strtoupper($this->des_prod),
                                            'cod_uni'               => $this->cod_uni,
                                            'des_uni'               => $this->des_uni
                                        ];

        $this->emit('getDetalleProductos', $this->detalle_productos);
    }

    public function updatedCantidad($cantidad)
    {
        if($cantidad)
        $this->mon_sub_tot  = (($this->ult_pre * $cantidad)*100)/100;
    }

    public function updatedUltPre($ult_pre)
    {
        if($ult_pre)
            $this->mon_sub_tot  = (($ult_pre * $this->cantidad)*100)/100;
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle');
    }
}
