<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Proceso;

use Livewire\Component;

class SolicitudUnidadProducto extends Component
{
    public $productos = [];
    public $prod_cod_prod;
    public $prod_des_prod;
    public $prod_cod_uni;
    public $prod_des_uni;

    protected $listeners = ['getDetalleProductos'];


    public function getDetalleProductos($detalle_productos)
    {
        $this->productos = $detalle_productos;
    }

    public function mostrarProducto($index)
    {
        $this->prod_cod_prod = $this->productos[$index]['cod_prod'];
        $this->prod_des_prod = $this->productos[$index]['des_prod'];
        $this->prod_cod_uni  = $this->productos[$index]['cod_uni'];
        $this->prod_des_uni  = $this->productos[$index]['des_uni'];
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.proceso.solicitud-unidad-producto');
    }
}
