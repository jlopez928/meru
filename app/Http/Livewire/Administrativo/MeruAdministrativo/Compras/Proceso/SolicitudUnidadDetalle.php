<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Proceso;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Administrativo\Meru_Administrativo\Compras\Producto;
use App\Models\Administrativo\Meru_Administrativo\Compras\SolicitudUnidad;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;

class SolicitudUnidadDetalle extends Component
{
    public $solicitudUnidad;
    public $productos = [];
    // public $detalle_productos = [];
    public $detalle_productos;
    public $grupo;
    public $grupo_ram;
    public $accion;

    public $cod_prod;
    public $des_prod;
    public $cod_uni;
    public $des_uni;
    public $cantidad = 0;
    public $ult_pre = 0;
    public $mon_sub_tot;
    public $cod_par = 0;
    public $cod_gen = 0;
    public $cod_esp = 0;
    public $cod_sub = 0;
    public $cod_status = 0;
    public $des_status = '';
    public $renglon;
    public $monto_tot = 0;
    public $producto_key;
    public $mostrar = false;

    public $ano_pro;
    public $aplica_pre;
    public $tip_cod = 0;
    public $cod_pryacc = 0;
    public $cod_obj = 0;
    public $gerencia = 0;
    public $unidad = 0;

    protected $listeners = ['getListaProductos', 'resetProductos', 'getCentroCosto'];

    public function rules(){
        return [
                    'cod_prod'      => 'required',
                    'des_prod'      => 'required',
                    'cantidad'      =>  [   'required',
                                            function($attribute,$value,$fail){
                                                if($value == 0){
                                                    $fail('Debe ingresar cantidad');
                                                }
                                            }
                                        ],
                    'ult_pre'       => [   'required',
                                            function($attribute,$value,$fail){
                                                if($value == 0){
                                                    $fail('Debe ingresar precio');
                                                }
                                            }
                                        ],
                    'mon_sub_tot'   => 'required',
                    'cod_status'    => [
                                            'required',
                                            function($attribute,$value,$fail){
                                                if($value != 0){
                                                    $fail('Código de estatus incorrecto');
                                                }
                                            }

                                        ],
                ];
    }

    public $validationAttributes = [
        'cod_prod'       => 'producto',
        'des_prod'       => 'descripción',
        'ult_pre'        => 'precio',
        'mon_sub_tot'    => 'total',
    ];

    public function cargar_emit(){

        $this->emit('cargarDetalle', ['detalle_productos' => $this->detalle_productos]);

        $this->emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-producto','getDetalleProductos', $this->detalle_productos);
    }

    public function mount(){

        if($this->solicitudUnidad->ano_pro){
            $this->ano_pro = $this->solicitudUnidad->ano_pro;
            $this->grupo_ram = $this->solicitudUnidad->gru_ram;
            $this->grupo = $this->solicitudUnidad->grupo;
            $this->aplica_pre = $this->solicitudUnidad->aplica_pre;

            $this->detalle_productos = $this->solicitudUnidad->detalles->toArray();

            $this->calcularTotal($this->detalle_productos);

            $this->productos = Producto::getProductos($this->grupo_ram, $this->grupo);
        }else{
            $this->detalle_productos = [];
        }

            $valor = json_encode(session()->getOldInput());

            $valor = json_decode($valor);

            if ($valor) {
                $this->detalle_productos = json_decode($valor->detalle_productos,true);

                $this->calcularTotal($this->detalle_productos);

                $this->emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-producto','getDetalleProductos', $this->detalle_productos);

                $this->emit('cargarDetalle', ['detalle_productos' => $this->detalle_productos]);
            }
    }

    public function resetProductos()
    {
        $this->reset(['productos','monto_tot']);

        $this->detalle_productos = [];

        $this->setDefault();

        $this->emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-producto','resetDetalleProductos');
    }

    public function getCentroCosto($tip_cod, $cod_pryacc, $cod_obj, $gerencia, $unidad)
    {
        $this->tip_cod = $tip_cod;
        $this->cod_pryacc = $cod_pryacc;
        $this->cod_obj = $cod_obj;
        $this->gerencia = $gerencia;
        $this->unidad = $unidad;
    }

    public function setDefault()
    {
        $this->reset([
                        'des_prod',
                        'cod_uni',
                        'des_uni',
                        'cantidad',
                        'ult_pre',
                        'mon_sub_tot',
                        'cod_par',
                        'cod_gen',
                        'cod_esp',
                        'cod_sub',
                        'cod_status',
                        'des_status',
                        'renglon'
                    ]);
    }

    private function verificarDisponibilidad($valor)
    {
        if ($valor != '')
        {
            $consulta = DB::select("SELECT a.mto_dis, coalesce(x.sum_tot_ref, 0) sum_tot_ref,
                        (a.mto_dis - coalesce(x.sum_tot_ref, 0)) as mto_dis_real,
                        a.tip_cod, a.cod_pryacc, a.cod_obj, a.gerencia,
                        a.unidad, a.cod_par, a.cod_gen, a.cod_esp,
                        a.cod_sub, a.cod_com
                        FROM pre_maestroley a
                        LEFT JOIN
                        (   SELECT b.ano_pro, b.tip_cod, b.cod_pryacc, b.cod_obj,
                            b.gerencia, b.unidad, b.cod_par, b.cod_gen,
                            b.cod_esp, b.cod_sub, b.cod_com, sum(b.tot_ref) as sum_tot_ref
                            FROM com_encsolicitud a
                            INNER JOIN com_detsolicitud b
                            on a.nro_req=b.nro_req AND a.grupo=b.grupo AND a.ano_pro=b.ano_pro
                            WHERE   a.ano_pro= $this->ano_pro AND a.grupo='$this->grupo' AND
                                    a.sta_sol in ('2','4') AND a.aplica_pre='1' AND
                                    b.tip_cod = $this->tip_cod AND b.cod_pryacc = $this->cod_pryacc AND b.cod_obj = $this->cod_obj AND
                                    b.gerencia = $this->gerencia AND b.unidad = $this->unidad AND b.cod_par = $this->cod_par AND
                                    b.cod_gen= $this->cod_gen AND b.cod_esp = $this->cod_esp AND b.cod_sub = $this->cod_sub
                            GROUP BY 1,2,3,4,5,6,7,8,9,10,11) as x
                            ON x.ano_pro=a.ano_pro AND x.cod_com = a.cod_com
                        WHERE   a.ano_pro = $this->ano_pro AND
                                a.tip_cod = $this->tip_cod AND a.cod_pryacc = $this->cod_pryacc AND a.cod_obj = $this->cod_obj AND
                                a.gerencia = $this->gerencia AND a.unidad = $this->unidad AND a.cod_par = $this->cod_par AND
                                a.cod_gen = $this->cod_gen AND a.cod_esp = $this->cod_esp AND a.cod_sub = $this->cod_sub");

            if (count($consulta) == 0)
            {
                $this->cod_status = 'E';
                $this->des_status = SolicitudUnidad::obtenerStatusRenglon($this->cod_status);

                $this->emit('swal:alert', [
                    'tipo'      => 'warning',
                    'mensaje'   => "La Estructura de Gastos [".CentroCosto::generarCodCentroCosto($this->tip_cod,$this->cod_pryacc,$this->cod_obj,$this->gerencia,$this->unidad).".".Producto::generarCodPartida($this->cod_par,$this->cod_gen,$this->cod_esp,$this->cod_sub)."] No Existe.\n"."Por favor verifique."
                ]);

            }else{
                $cod_com  = $consulta[0]->cod_com;
                $mto_dis  = $consulta[0]->mto_dis_real;
                $mon_tot1 = $this->mon_sub_tot;

                $mon_tot = $this->detalleProductosTotalPorCodigo($this->detalle_productos, $this->cod_par, $this->cod_gen, $this->cod_esp, $this->cod_sub, $this->producto_key) + $mon_tot1;

                if ($this->aplica_pre == '0')
                {
                    $this->cod_status = '0';
                }else{
                    if($mto_dis < $mon_tot)
                    {
                        // alert("La Estructura de Gastos [" + cod_com + "] No tiene Disponibilidad: \n" +
                        //         "\t* Disponibilidad: " + Utils.__formatNumber(mto_dis.toString().replace(/\ |\./g, Utils.PUNTODECIMAL), 2, true, Utils.PUNTODECIMAL) + "\n" +
                        //         "\t* Monto que esta Solicitando: " + Utils.__formatNumber(mon_tot1.toString().replace(/\ |\./g, Utils.PUNTODECIMAL), 2, true, Utils.PUNTODECIMAL) + "\n" +
                        //         "\t* Monto Total por Pre-Comprometer en la Solicitud Actual.: " + Utils.__formatNumber(mon_tot.toString().replace(/\ |\./g, Utils.PUNTODECIMAL), 2, true, Utils.PUNTODECIMAL) + "\n" +
                        //         "Puede Solicitar traspasos para Procesar la Solicitud.");

                        $this->emit('swal:alert', [
                            'tipo'      => 'warning',
                            'mensaje'   => "La Estructura de Gastos [".$cod_com."] No tiene Disponibilidad"."Por favor verifique."
                        ]);

                        $this->cod_status = 'D';
                    }else{
                        $this->cod_status = '0';
                    }
                }

                $this->des_status = SolicitudUnidad::obtenerStatusRenglon($this->cod_status);
            }
        }
    }

    private function detalleProductosTotalPorCodigo($productos,$cod_par,$cod_gen,$cod_esp,$cod_sub,$posicion)
    {
        $total = 0;

        if(count($productos) > 0)
        {
            foreach($productos as $index => $producto)
            {
                if (($producto['cod_par'] == $cod_par) && ($producto['cod_gen'] == $cod_gen) && ($producto['cod_esp'] == $cod_esp) && ($producto['cod_sub'] == $cod_sub) && ($index != $posicion))
                {
                    $total += $producto['total'];
                }

            }
        }

        return $total;
    }

    public function getListaProductos($grupo_ram, $grupo, $ano_pro, $aplica_pre)
    {
        $this->setDefault();

        $this->grupo = $grupo;

        $this->grupo_ram = $grupo_ram;

        $this->ano_pro = $ano_pro;

        $this->aplica_pre = $aplica_pre;

        $this->productos = Producto::getProductos($grupo_ram, $grupo);
    }

    public function updatedCodProd($cod_prod)
    {
        $this->setDefault();
        $this->resetValidation();

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
                                ->first(['grupo','des_prod','cod_uni','ult_pre','por_iva','por_islr','stock','cod_par','cod_gen','cod_esp','cod_sub']);

            $this->des_prod         = $producto->des_prod;
            $this->cod_uni          = $producto->cod_uni;
            $this->des_uni          = $producto->unidadmedida->des_uni;
            $this->ult_pre          = $producto->ult_pre;
            $this->mon_sub_tot      = (($this->ult_pre * $this->cantidad)*100)/100;
            $this->cod_par          = $producto->cod_par;
            $this->cod_gen          = $producto->cod_gen;
            $this->cod_esp          = $producto->cod_esp;
            $this->cod_sub          = $producto->cod_sub;
            $this->producto_key     = count($this->detalle_productos);
        }
    }

    private function calcularTotal($productos)
    {
        $this->reset('monto_tot');

        foreach($productos as $producto)
        {
            $this->monto_tot += $producto['total'];
        }
    }

    private function reasignarRenglon($productos)
    {
        foreach($productos as $index => $producto)
        {
            $this->detalle_productos[$index]['nro_ren'] = $index + 1;
        }
    }

    public function updatedCantidad($cantidad)
    {
        $this->resetValidation();

        if($cantidad){
            $this->mon_sub_tot  = (($this->ult_pre * $cantidad)*100)/100;
            $this->verificarDisponibilidad($cantidad);
        }
    }

    public function updatedUltPre($ult_pre)
    {
        $this->resetValidation();

        if($ult_pre){
            $this->mon_sub_tot  = (($ult_pre * $this->cantidad)*100)/100;
            $this->verificarDisponibilidad($ult_pre);
        }
    }

    public function mostrarDetalle($index)
    {
        $this->producto_key = $index;
        $this->mostrar = true;

        $this->cod_prod     = $this->detalle_productos[$index]['fk_cod_mat'];
        $this->des_prod     = $this->detalle_productos[$index]['descripcion'];
        $this->cod_uni      = $this->detalle_productos[$index]['fk_cod_uni'];
        $this->des_uni      = $this->detalle_productos[$index]['des_uni_med'];
        $this->cantidad     = $this->detalle_productos[$index]['cantidad'];
        $this->ult_pre      = $this->detalle_productos[$index]['precio'];
        $this->mon_sub_tot  = $this->detalle_productos[$index]['total'];
        $this->cod_par      = $this->detalle_productos[$index]['cod_par'];
        $this->cod_gen      = $this->detalle_productos[$index]['cod_gen'];
        $this->cod_esp      = $this->detalle_productos[$index]['cod_esp'];
        $this->cod_sub      = $this->detalle_productos[$index]['cod_sub'];
        $this->cod_status   = $this->detalle_productos[$index]['sta_reg'];
        $this->renglon      = $this->detalle_productos[$index]['nro_ren'];
    }

    public function agregarRenglon()
    {
        $this->validate();

        $this->detalle_productos[] =    [
            'fk_cod_mat'        => $this->cod_prod,
            'descripcion'       => strtoupper($this->des_prod),
            'fk_cod_uni'        => $this->cod_uni,
            'des_uni_med'       => $this->des_uni,
            'cantidad'          => $this->cantidad,
            'precio'            => $this->ult_pre,
            'total'             => $this->mon_sub_tot,
            'cod_par'           => $this->cod_par,
            'cod_gen'           => $this->cod_gen,
            'cod_esp'           => $this->cod_esp,
            'cod_sub'           => $this->cod_sub,
            'sta_reg'           => $this->cod_status,
            'nro_ren'           => count($this->detalle_productos) + 1,
        ];

        if (($this->grupo == 'SV' || $this->grupo == 'SG') && count($this->detalle_productos) == 1) {
            $this->emit('swal:alert', [
                'tipo'      => 'warning',
                'mensaje'   => 'Las Solicitudes de SERVICIO o SERVICIO A VEHICULOS ' .
                                'solo pueden tener un Renglón en el Detalle'
            ]);
        }

        if(($this->grupo == 'SV' || $this->grupo == 'SG') && count($this->detalle_productos) > 1){
            $this->emit('swal:alert', [
                'tipo'      => 'warning',
                'mensaje'   => 'Las Solicitudes de SERVICIOS o SERVICIOS A VEHICULOS debe tener solo un renglón en el detalle'
            ]);

            array_pop($this->detalle_productos);
        }else{

            $this->calcularTotal($this->detalle_productos);

            $this->emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-producto','getDetalleProductos', $this->detalle_productos);

            $this->emit('cargarDetalle', ['detalle_productos' => $this->detalle_productos]);
        }

        $this->setDefault();

        $this->cod_prod = '';
    }

    public function modificarRenglon()
    {
        $this->mostrar = false;

        $this->detalle_productos[$this->producto_key]['fk_cod_mat']         = $this->cod_prod;
        $this->detalle_productos[$this->producto_key]['descripcion']        = strtoupper($this->des_prod);
        $this->detalle_productos[$this->producto_key]['fk_cod_uni']         = $this->cod_uni;
        $this->detalle_productos[$this->producto_key]['des_uni_med']        = $this->des_uni;
        $this->detalle_productos[$this->producto_key]['cantidad']           = $this->cantidad;
        $this->detalle_productos[$this->producto_key]['precio']             = $this->ult_pre;
        $this->detalle_productos[$this->producto_key]['total']              = $this->mon_sub_tot;
        $this->detalle_productos[$this->producto_key]['cod_par']            = $this->cod_par;
        $this->detalle_productos[$this->producto_key]['cod_gen']            = $this->cod_gen;
        $this->detalle_productos[$this->producto_key]['cod_esp']            = $this->cod_esp;
        $this->detalle_productos[$this->producto_key]['cod_sub']            = $this->cod_sub;
        $this->detalle_productos[$this->producto_key]['sta_reg']            = $this->cod_status;

        $this->reasignarRenglon($this->detalle_productos);

        $this->calcularTotal($this->detalle_productos);

        $this->setDefault();

        $this->cod_prod = '';

        $this->emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-producto','getDetalleProductos', $this->detalle_productos);

        $this->emit('cargarDetalle', ['detalle_productos' => $this->detalle_productos]);
    }

    public function eliminarRenglon()
    {
        $this->mostrar = false;

        unset($this->detalle_productos[$this->producto_key]);

        $this->detalle_productos = array_values($this->detalle_productos);

        if(count($this->detalle_productos) > 0)
        {
            $this->reasignarRenglon($this->detalle_productos);
        }

        $this->calcularTotal($this->detalle_productos);

        $this->setDefault();

        $this->cod_prod = '';

        $this->emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-producto','getDetalleProductos', $this->detalle_productos);

        $this->emit('cargarDetalle', ['detalle_productos' => $this->detalle_productos]);
    }

    public function cancelar()
    {
        $this->mostrar = false;

        $this->setDefault();

        $this->cod_prod = '';
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle');
    }
}
