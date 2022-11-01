<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Proceso;

use App\Models\Administrativo\Meru_Administrativo\Compras\Producto;
use Livewire\Component;

class SolicitudUnidadProducto extends Component
{
    public $productos = [];
    public $solicitudUnidad;
    public $accion;

    public $prod_cod_prod;
    public $prod_des_prod;
    public $prod_cod_uni;
    public $prod_des_uni;
    public $prod_cantidad;
    public $prod_cant_ord;
    public $prod_cant_sal;
    public $prod_precio;
    public $prod_total;
    public $prod_cod_par;
    public $prod_cod_gen;
    public $prod_cod_esp;
    public $prod_cod_sub;
    public $prod_cod_status;

    protected $listeners = ['getDetalleProductos','resetDetalleProductos'];

    public function mount(){

        if($this->solicitudUnidad->ano_pro){
            $this->productos = $this->solicitudUnidad->productos->toArray();
        }
    }

    public function getDetalleProductos($detalle_productos)
    {
        $this->productos = array_values($this->productosAgrupadosPorCodigo($detalle_productos));

        $this->reasignarRenglon($this->productos);

        $this->emit('cargarProducto', ['productos' => $this->productos]);
    }

    private function productosAgrupadosPorCodigo($productos)
    {
        return array_reduce($productos, function($accumulator, $item){
            $index = $item['fk_cod_mat'];

            if (!isset($accumulator[$index])) {
                $accumulator[$index] = [
                    'fk_cod_mat'        => $item['fk_cod_mat'],
                    'des_bien'          => Producto::getProducto($item['fk_cod_mat'])->des_prod,
                    'fk_cod_uni'        => $item['fk_cod_uni'],
                    'des_uni_med'       => $item['des_uni_med'],
                    'cantidad'          => 0,
                    'cant_ord'          => 0,
                    'cant_sal'          => 0,
                    'pre_ref'           => $item['precio'],
                    'tot_ref'           => 0,
                    'cod_par'           => $item['cod_par'],
                    'cod_gen'           => $item['cod_gen'],
                    'cod_esp'           => $item['cod_esp'],
                    'cod_sub'           => $item['cod_sub'],
                    'sta_reg'           => $item['sta_reg'],
                    'nro_ren'           => 0,
                ];
            }

            $accumulator[$index]['cantidad']    += $item['cantidad'];
            $accumulator[$index]['tot_ref'] += $item['total'];

            return $accumulator;
        }, []);
    }

    private function reasignarRenglon($productos)
    {
        foreach($productos as $index => $producto)
        {
            $this->productos[$index]['nro_ren'] = $index + 1;
        }
    }

    public function resetDetalleProductos()
    {
        $this->reset(['productos']);
        $this->setDefault();
    }

    public function setDefault()
    {
        $this->reset([
                        'productos',
                        'prod_cod_prod',
                        'prod_des_prod',
                        'prod_cod_uni',
                        'prod_des_uni',
                        'prod_cantidad',
                        'prod_cant_ord',
                        'prod_cant_sal',
                        'prod_precio',
                        'prod_total',
                        'prod_cod_par',
                        'prod_cod_gen',
                        'prod_cod_esp',
                        'prod_cod_sub',
                        'prod_cod_status'
                    ]);
    }

    public function mostrarProducto($index)
    {
        $this->prod_cod_prod    = $this->productos[$index]['fk_cod_mat'];
        $this->prod_des_prod    = $this->productos[$index]['des_bien'];
        $this->prod_cod_uni     = $this->productos[$index]['fk_cod_uni'];
        $this->prod_des_uni     = $this->productos[$index]['des_uni_med'];
        $this->prod_cantidad    = $this->productos[$index]['cantidad'];
        $this->prod_cant_ord    = $this->productos[$index]['cant_ord'];
        $this->prod_cant_sal    = $this->productos[$index]['cant_sal'];
        $this->prod_precio      = $this->productos[$index]['pre_ref'];
        $this->prod_total       = $this->productos[$index]['tot_ref'];
        $this->prod_cod_par     = $this->productos[$index]['cod_par'];
        $this->prod_cod_gen     = $this->productos[$index]['cod_gen'];
        $this->prod_cod_esp     = $this->productos[$index]['cod_esp'];
        $this->prod_cod_sub     = $this->productos[$index]['cod_sub'];
        $this->prod_cod_status  = $this->productos[$index]['sta_reg'];
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.proceso.solicitud-unidad-producto');
    }
}
