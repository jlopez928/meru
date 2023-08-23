<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\Compras\Proceso;

use Livewire\Component;
use App\Traits\WithSorting;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Administrativo\Meru_Administrativo\Compras\Bien;
use App\Models\Administrativo\Meru_Administrativo\General\Unidad;
use App\Models\Administrativo\Meru_Administrativo\Compras\Producto;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;
use App\Models\Administrativo\Meru_Administrativo\Compras\Comprador;
use App\Models\Administrativo\Meru_Administrativo\Compras\TipoCompra;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetOfertaPro;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetSolicitud;
use App\Models\Administrativo\Meru_Administrativo\Compras\EncSolicitud;
use App\Models\Administrativo\Meru_Administrativo\Compras\ServicioBien;
use App\Models\Administrativo\Meru_Administrativo\Compras\CausaAnulacion;
use App\Models\Administrativo\Meru_Administrativo\Compras\CorrSolCompras;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetOrdenCompra;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetSolicitudDet;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetSolCotizacion;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_administrativo\Configuracion\UnidadTributaria;

class Solicitud extends Component
{
    use WithPagination, WithSorting;

    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $paginate = '10';
    public $showTab = 'tab1';
    public $modulo;
    public $descripcionModulo;
    public $accion;
    public $solicitud;
    public $opcion;
    public $centro_costo_unidades = [];
    // TODO Revisar anoPro y fechaGuardar en livewire
    public $anoPro;
    public $fechaGuardar;

    // Tab Encabezado
    public $ano_pro;
    public $grupo;
    public $cla_sol;
    public $clases = [];
    public $nro_req;
    public $jus_sol;
    public $fec_emi;
    public $fec_rec;
    public $fec_com_cont;
    public $fec_anu;
    public $fec_rec_cont;
    public $fec_dev_com;
    public $fec_pcom;
    public $fec_com;
    public $fec_dev_cont;
    public $fec_reasig;
    public $fec_aut;
    public $gru_ram;
    public $fk_cod_ger;
    public $cod_uni;
    public $unidades = [];
    public $pri_sol;
    public $aplica_pre;
    public $cierre;
    public $anexos;
    public $estatus = '';
    public $donacion;

    // Tab Detalle
    public $tip_cod;
    public $cod_pryacc;
    public $cod_obj;
    public $gerencia;
    public $unidad;
    public $productos = [];
    public $detalle_productos = [];
    public $cod_prod;
    public $des_prod;
    public $det_cod_uni;
    public $des_uni;
    public $cantidad = 0;
    public $ult_pre = 0;
    public $mon_sub_tot = 0;
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

    // Tab Producto
    public $prod_productos = [];
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

    // Tab Bienes/Vehiculos
    public $vehiculos = [];

    // Tab Contratante
    public $contratante;
    public $fk_cod_com;
    public $licita;

    // Tab Anulacion
    public $fk_cod_cau;
    public $cau_dev;
    public $cau_reasig;

    // Tab Doc Asociados
    public $cotizaciones;
    public $ofertas;
    public $ordenes;

    protected $listeners =  [
                                // Modulo Unidad
                                'crearSolicitud',
                                'modificarAnexoSolicitud',
                                'modificarSolicitud',
                                'anularSolicitud',
                                'reversarSolicitud',
                                'copiarSolicitud',
                                'activarSolicitud',
                                'precomprometerSolicitud',

                                // Modulo Compras
                                'recepcionarSolicitud',
                                'devolverSolicitud',
                                'asignarCompradorSolicitud',
                                'reasignarSolicitud',

                                // Modulo Contrataciones
                                'contratacionRecepcionarSolicitud',
                                'contratacionDevolverSolicitud',
                                'asignarContratacionCompradorSolicitud',
                                'contratacionReasignarSolicitud',

                                // Modulo Presupuesto
                                'presupuestoAprobarSolicitud',
                            ];

    protected $validationAttributes = [

        // Tab Encabezado
        'cla_sol'       => 'clase',
        'jus_sol'       => 'justificación',
        'fec_emi'       => 'fecha emisión',
        'fec_anu'       => 'fecha anulación',
        'fec_pcom'      => 'fecha aprobar',
        'gru_ram'       => 'grupo-ramo',
        'fk_cod_ger'    => 'gerencia',
        'pri_sol'       => 'prioridad',
        'fec_rec'       => 'fecha rec. en logística',
        'fec_dev_com'   => 'fecha dev. desde compra',
        'fec_reasig'    => 'fecha reasignación',
        'fec_com'       => 'fecha asig. del comprador logística',
        'fec_rec_cont'  => 'fecha rec. en contrataciones',
        'fec_dev_cont'  => 'fecha dev. desde contrataciones',
        'fec_com_cont'  => 'fecha asig. del comprador contrataciones',
        'fec_aut'       => 'fecha conformación presupuestaria',

        // Tab Detalle
        'cod_prod'       => 'producto',
        'des_prod'       => 'descripción',
        'ult_pre'        => 'precio',
        'mon_sub_tot'    => 'total',

        // Tab Contratante
        'fk_cod_com'     => 'comprador',
        'licita'         => 'tipo de compra',

        // Tab Anulacion
        'fk_cod_cau'     => 'causa de la anulación',
        'cau_dev'        => 'causa de la devolución',
        'cau_reasig'     => 'causa de la reasignación'
    ];

    public function rules(){
        return [
                    'ano_pro'       => 'required',
                    'grupo'         => 'required',
                    'cla_sol'       => 'required',
                    'jus_sol'       => 'required',
                    'fec_emi'       => 'required',
                    'gru_ram'       => 'required',
                    'fk_cod_ger'    => 'required',
                    'pri_sol'       => 'required',
                    'monto_tot'     => [
                                            'required',
                                            function($attribute,$value,$fail){
                                                if($value == '0' || $value == '0,00'){
                                                    $fail('El monto total de la Solicitud no puede ser cero');
                                                }
                                            }
                                        ]
                ];
    }

    public function mount()
    {
        $this->sort = 'cod_corr';
        $this->direction = 'desc';

        if($this->solicitud->ano_pro){

            // Tab Encabezado
            $this->ano_pro      = $this->solicitud->ano_pro;
            $this->grupo        = $this->solicitud->grupo;
            $this->cla_sol      = $this->solicitud->cla_sol;
            $this->clases       = EncSolicitud::obtenerClases($this->grupo);
            $this->nro_req      = $this->solicitud->nro_req;
            $this->jus_sol      = $this->solicitud->jus_sol;
            $this->fec_emi      = $this->solicitud->fec_emi;
            $this->fec_rec      = ($this->accion == 'recepcionar') ? date('Y-m-d') : $this->solicitud->fec_rec;
            $this->fec_com_cont = ($this->accion == 'contratacion_comprador') ? date('Y-m-d') : $this->solicitud->fec_com_cont;
            $this->fec_anu      = ($this->accion == 'anular' || $this->accion == 'reversar' || $this->accion == 'presupuesto_reversar') ? date('Y-m-d') : $this->solicitud->fec_anu;
            $this->fec_rec_cont = ($this->accion == 'contratacion_recepcionar') ? date('Y-m-d') : $this->solicitud->fec_rec_cont;
            $this->fec_dev_com  = ($this->accion == 'devolver') ? date('Y-m-d') : $this->solicitud->fec_dev_com;
            $this->fec_pcom     = ($this->accion == 'precomprometer') ? date('Y-m-d') : $this->solicitud->fec_pcom;
            $this->fec_com      = ($this->accion == 'compra_comprador') ? date('Y-m-d') : $this->solicitud->fec_com;
            $this->fec_dev_cont = ($this->accion == 'contratacion_devolver') ? date('Y-m-d') : $this->solicitud->fec_dev_cont;
            $this->fec_reasig   = ($this->accion == 'reasignar' || $this->accion == 'contratacion_reasignar') ? date('Y-m-d') : $this->solicitud->fec_reasig;
            $this->fec_aut      = ($this->accion == 'presupuesto_aprobar') ? date('Y-m-d') : $this->solicitud->fec_aut;
            $this->gru_ram      = $this->solicitud->gru_ram;
            $this->fk_cod_ger   = $this->solicitud->fk_cod_ger;
            $this->cod_uni      = $this->solicitud->cod_uni;
            $this->unidades     = Unidad::obtenerUnidades($this->fk_cod_ger);
            $this->pri_sol      = $this->solicitud->pri_sol;
            $this->aplica_pre   = $this->solicitud->aplica_pre;
            $this->cierre       = $this->solicitud->cierre;
            $this->anexos       = $this->solicitud->anexos;
            $this->estatus      = $this->solicitud->estado->descripcion;
            $this->donacion     = $this->solicitud->donacion;

            // Tab Detalle
            $this->getCentroCostoGerencia($this->fk_cod_ger);
            $this->productos = Producto::getProductos($this->gru_ram, $this->grupo);
            $this->detalle_productos = $this->solicitud->detalles->toArray();
            $this->calcularTotal($this->detalle_productos);

            // Tab Producto
            $this->prod_productos = $this->solicitud->productos->toArray();

            // Tab Bienes/Vehiculos
            if($this->solicitud->grupo == 'SV'){
                $servicios = $this->solicitud->vehiculos;

                $servicios->map( function($item, $key){
                    $this->vehiculos[] =    [
                                                'cod_corr' => $item->cod_corr,
                                                'placa'    => $item->bien->placa,
                                                'modelo'   => $item->bien->modelo,
                                                'marca'    => $item->bien->marca
                                            ];
                });
            }

            // Tab Contratante
            $this->contratante = $this->solicitud->contratante;
            $this->fk_cod_com  = $this->solicitud->fk_cod_com;
            $this->licita      = $this->solicitud->licita;

            // Tab Anulacion
            $this->fk_cod_cau = $this->solicitud->fk_cod_cau;
            $this->cau_dev    = $this->solicitud->cau_dev;
            $this->cau_reasig = $this->solicitud->cau_reasig;

            // Tab Doc Asociados
            if ($this->solicitud->sta_sol == '8' || $this->solicitud->sta_sol == '9' || $this->solicitud->sta_sol == '10' || $this->solicitud->sta_sol == '11'){
                $this->mostrarCotizaciones($this->ano_pro, $this->grupo, $this->nro_req);
            }
            if ($this->solicitud->sta_sol == '9' || $this->solicitud->sta_sol == '10' || $this->solicitud->sta_sol == '11'){
                $this->mostrarOfertas($this->ano_pro, $this->grupo, $this->nro_req);
            }
            if ($this->solicitud->sta_sol == '10' || $this->solicitud->sta_sol == '11'){
                $this->mostrarOrdenes($this->ano_pro, $this->grupo, $this->nro_req);
            }
        }else{
            // Tab Encabezado
            $this->ano_pro      = session('ano_pro');
            $this->fec_emi      = date('Y-m-d');
            $this->pri_sol      = 'N';
            $this->aplica_pre   = $this->opcion;
            $this->cierre       = '0';
            $this->donacion     = 'N';

            // Tab Contratante
            $this->contratante = 'L';
        }
    }

    private function getEncSolicitud($ano_pro, $grupo, $nro_req)
    {
        return  EncSolicitud::query()
                            ->select([
                                'com_encsolicitud.ano_pro',
                                'com_encsolicitud.grupo',
                                'com_encsolicitud.nro_req',
                                'com_encsolicitud.cla_sol',
                                'com_encsolicitud.jus_sol',
                                'com_encsolicitud.fec_emi',
                                'com_encsolicitud.fec_anu',
                                'com_encsolicitud.fec_imp',
                                'com_encsolicitud.fec_aut',
                                'com_encsolicitud.fec_pcom',
                                'com_encsolicitud.fec_rec',
                                'com_encsolicitud.fec_sta',
                                'com_encsolicitud.fk_cod_ger',
                                'com_encsolicitud.pri_sol',
                                'com_encsolicitud.monto_tot',
                                'com_encsolicitud.fk_cod_com',
                                'com_encsolicitud.fec_com',
                                'com_encsolicitud.licita',
                                'com_encsolicitud.sta_sol',
                                'com_encsolicitud.sta_ant',
                                'com_encsolicitud.fec_rec_adm',
                                'com_encsolicitud.gru_ram',
                                'com_encsolicitud.cod_uni',
                                'com_encsolicitud.fk_cod_cau',
                                'com_encsolicitud.anexos',
                                'com_encsolicitud.cau_dev',
                                'com_encsolicitud.fec_dev_pre',
                                'com_encsolicitud.fec_dev_com',
                                'com_encsolicitud.aplica_pre',
                                'c.tip_cod',
                                'c.cod_pryacc',
                                'c.cod_obj',
                                'c.gerencia',
                                'c.unidad',
                                'com_encsolicitud.fec_rec_cont',
                                'com_encsolicitud.fec_dev_cont',
                                'com_encsolicitud.fec_com_cont',
                                'com_encsolicitud.contratante',
                                'd.descripcion as sta_des',
                                'com_encsolicitud.fec_reasig',
                                'com_encsolicitud.cau_reasig',
                                'com_encsolicitud.cierre'
                            ])
                            ->join('gerencias as b', 'b.cod_ger', 'com_encsolicitud.fk_cod_ger')
                            ->join('pre_centrocosto as c', function($q){
                                    $q->on('c.ano_pro', 'com_encsolicitud.ano_pro')
                                        ->on('c.cod_cencosto', 'b.centro_costo');
                            })
                            ->join('com_estatus as d', function($q){
                                    $q->on('d.siglas','com_encsolicitud.sta_sol')
                                    ->where('d.modulo', 'solicitud');
                            })
                            ->where('com_encsolicitud.ano_pro', $ano_pro)
                            ->where('com_encsolicitud.grupo', $grupo)
                            ->where('com_encsolicitud.nro_req', $nro_req)
                            ->first();

    }

    private function setDefaultDetalle()
    {
        $this->reset([
                        'des_prod',
                        'det_cod_uni',
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
                $this->des_status = EncSolicitud::obtenerStatusRenglon($this->cod_status);

                $this->emit('swal:alert', [
                    'tipo'      => 'warning',
                    'mensaje'   => '<div align="left">La Estructura de Gastos ['.CentroCosto::generarCodCentroCosto($this->tip_cod,$this->cod_pryacc,$this->cod_obj,$this->gerencia,$this->unidad).'.'.Producto::generarCodPartida($this->cod_par,$this->cod_gen,$this->cod_esp,$this->cod_sub).'] No Existe.'.'<br><br>Por favor verifique.</div>',
                    'width'     => '650px'
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
                        $this->emit('swal:alert', [
                            'tipo'      =>  'warning',
                            'mensaje'   =>  '<div align="left">La Estructura de Gastos ['.$cod_com.'] No tiene Disponibilidad: <br>'.
                                            '* Disponibilidad: '. number_format($mto_dis, 2,',','.') .'<br>'.
                                            '* Monto que esta Solicitando: '. number_format($mon_tot1, 2,',','.') .'<br>'.
                                            '* Monto Total por Pre-Comprometer en la Solicitud Actual: '. number_format($mon_tot, 2,',','.') .'<br>'.
                                            'Puede Solicitar traspasos para Procesar la Solicitud.</div>',
                            'width'     =>  '700px'
                        ]);

                        $this->cod_status = 'D';
                    }else{
                        $this->cod_status = '0';
                    }
                }

                $this->des_status = EncSolicitud::obtenerStatusRenglon($this->cod_status);
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

    public function updatedCodProd($cod_prod)
    {
        $this->setDefaultDetalle();
        $this->resetValidation();
        $this->mostrar = false;

        if($cod_prod)
        {
            $producto = Producto::query()
                                ->with('unidadmedida:cod_uni,des_uni')
                                ->where('cod_prod', $cod_prod)
                                ->where('gru_ram', $this->gru_ram)
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
            $this->det_cod_uni      = $producto->cod_uni;
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
        $this->det_cod_uni  = $this->detalle_productos[$index]['fk_cod_uni'];
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
        $this->validate([
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
                        ]);

        $this->detalle_productos[] =    [
            'fk_cod_mat'        => $this->cod_prod,
            'descripcion'       => strtoupper($this->des_prod),
            'fk_cod_uni'        => $this->det_cod_uni,
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
                'mensaje'   => '<div align="left">Las Solicitudes de SERVICIO o SERVICIO A VEHICULOS<br>solo pueden tener un renglón en el detalle.</div>',
                'width'     => '650px'
            ]);
        }

        if(($this->grupo == 'SV' || $this->grupo == 'SG') && count($this->detalle_productos) > 1){
            $this->emit('swal:alert', [
                'tipo'      => 'warning',
                'mensaje'   => '<div align="left">Las Solicitudes de SERVICIOS o SERVICIOS A VEHICULOS<br>debe tener solo un renglón en el detalle.</div>',
                'width'     => '650px'
            ]);

            array_pop($this->detalle_productos);
        }else{

            $this->calcularTotal($this->detalle_productos);

            // Se carga el tab de Producto
            $this->getDetalleProductos($this->detalle_productos);
        }

        $this->setDefaultDetalle();

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

        $this->setDefaultDetalle();

        $this->cod_prod = '';

        // Se actualiza el tab de Producto
        $this->getDetalleProductos($this->detalle_productos);
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

        $this->setDefaultDetalle();

        $this->cod_prod = '';

        // Se actualiza el tab de Producto
        $this->getDetalleProductos($this->detalle_productos);
    }

    public function cancelar()
    {
        $this->mostrar = false;

        $this->setDefaultDetalle();

        $this->cod_prod = '';
    }

    public function updatedGrupo($grupo)
    {
        if($grupo){
            $this->clases = match($grupo) {
                                'BM'        => [['cod_cla' => 'C', 'des_cla' => 'COMPRA'],['cod_cla' => 'A', 'des_cla' => 'ALMACEN']],
                                'SV', 'SG'  => [['cod_cla' => 'S', 'des_cla' => 'SERVICIO']],
                                default     => []
                            };
        }
    }

    private function getCentroCostoGerencia($cod_ger)
    {
        $centro_costo = EncSolicitud::obtenerCentroCosto($cod_ger);

        if(count($centro_costo) != 0){
            $this->tip_cod     = $centro_costo[0];
            $this->cod_pryacc  = $centro_costo[1];
            $this->cod_obj     = $centro_costo[2];
            $this->gerencia    = $centro_costo[3];
            $this->unidad      = $centro_costo[4];
        }
    }

    public function updatedFkCodGer($cod_ger)
    {
        $this->asignarCentroCosto($cod_ger);
    }

    public function updatedGruRam($gru_ram)
    {

        if(is_null($this->grupo)){
            $this->emit('swal:alert', [
                'tipo'      => 'warning',
                'mensaje'   => '<div align="left">Debe seleccionar un Grupo.<br><br>Por favor Verifique.</div>',
            ]);
        }else{
            $this->productos = Producto::getProductos($gru_ram, $this->grupo);
        }
    }

    private function dameCreditoAdicional($cod_ger){

        foreach($this->centro_costo_unidades as $index => $centro_costo_unidad){
            if ($cod_ger == $index) {
                return $centro_costo_unidad;
            }
        }

        $this->emit('swal:alert', [
            'tipo'      => 'warning',
            'mensaje'   => '<div align="left">Error validando el centro de costo de la gerencia.<br><br> Comuniquese con su administrador de Sistema.</div>',
            'width'     => '550px'
        ]);

        return 2;
    }

    private function asignarCentroCosto($cod_ger){
        // TODO Validar la gerencia del usuario
        $gerencia_usuario = auth()->user()->usuario->gerencia?->cod_ger ?? null;
        $bandera = false;
        $credito_adicional = '0';

        if ($cod_ger != '')
        {
            if ($gerencia_usuario != 100) {
                $this->centro_costo_unidades = EncSolicitud::obtenerCentroCostoUnidades($this->ano_pro);

                $credito_adicional = $this->dameCreditoAdicional($cod_ger);

                $bandera = $credito_adicional == '0' ? true : false;
            } else {
                $bandera = true;
            }

            if($bandera){
                $this->unidades = Unidad::obtenerUnidades($cod_ger);

                $centro_costo = EncSolicitud::obtenerCentroCosto($cod_ger);

                if(count($centro_costo) != 0){
                    $this->tip_cod     = $centro_costo[0];
                    $this->cod_pryacc  = $centro_costo[1];
                    $this->cod_obj     = $centro_costo[2];
                    $this->gerencia    = $centro_costo[3];
                    $this->unidad      = $centro_costo[4];
                }else{
                    $this->emit('swal:alert', [
                        'tipo'      => 'warning',
                        'mensaje'   => '<div align="left">La Gerencia Selecionada no tiene un Centro de Costo Valido.<br><br>Por favor Verifique.</div>',
                        'width'     => '550px'
                    ]);
                }
            }else{
                if ($credito_adicional == '1') {
                    $this->emit('swal:alert', [
                        'tipo'      => 'warning',
                        'mensaje'   => '<div align="left">Usted No tiene Permiso para realizar Solicitudes por la Gerencia Seleccionada.<br><br>Por favor Verifique.</div>',
                        'width'     => '700px'
                    ]);
                }
            }
        }else{
            $this->tip_cod    = '';
            $this->cod_pryacc = '';
            $this->cod_obj    = '';
            $this->gerencia   = '';
            $this->unidad     = '';
            // Livewire.emitTo('administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle','resetProductos')
        }
    }

    public function getYearsProperty()
    {
        return RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
    }

    public function getRamosProperty()
    {
        return Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
    }

    public function getGerenciasProperty()
    {
        return Gerencia::query()
                            ->where('status', 1)
                            ->when($this->opcion != null, function($q){
                                $q->where('aplica_pre', $this->opcion);
                            })
                            ->orderBy('des_ger')
                            ->pluck('des_ger','cod_ger');
    }

    //************************************************************/
    //**************** Funciones de Productos ********************/
    //************************************************************/
    private function getDetalleProductos($detalle_productos)
    {
        $this->prod_productos = array_values($this->productosAgrupadosPorCodigo($detalle_productos));

        $this->reasignarRenglonProducto($this->prod_productos);
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

    private function reasignarRenglonProducto($productos)
    {
        foreach($productos as $index => $producto)
        {
            $this->prod_productos[$index]['nro_ren'] = $index + 1;
        }
    }

    public function setDefaultProducto()
    {
        $this->reset([
                        'prod_productos',
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
        $this->prod_cod_prod    = $this->prod_productos[$index]['fk_cod_mat'];
        $this->prod_des_prod    = $this->prod_productos[$index]['des_bien'];
        $this->prod_cod_uni     = $this->prod_productos[$index]['fk_cod_uni'];
        $this->prod_des_uni     = $this->prod_productos[$index]['des_uni_med'];
        $this->prod_cantidad    = $this->prod_productos[$index]['cantidad'];
        $this->prod_cant_ord    = $this->prod_productos[$index]['cant_ord'];
        $this->prod_cant_sal    = $this->prod_productos[$index]['cant_sal'];
        $this->prod_precio      = $this->prod_productos[$index]['pre_ref'];
        $this->prod_total       = $this->prod_productos[$index]['tot_ref'];
        $this->prod_cod_par     = $this->prod_productos[$index]['cod_par'];
        $this->prod_cod_gen     = $this->prod_productos[$index]['cod_gen'];
        $this->prod_cod_esp     = $this->prod_productos[$index]['cod_esp'];
        $this->prod_cod_sub     = $this->prod_productos[$index]['cod_sub'];
        $this->prod_cod_status  = $this->prod_productos[$index]['sta_reg'];
    }

    //************************************************************/
    //************ Funciones de Bienes/Vehiculos *****************/
    //************************************************************/
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPaginate()
    {
        $this->resetPage();
    }

    public function updatedVehiculos($value)
    {
        if (count($value) > 16) {
            $this->emit('swal:alert', [
                'tipo'      => 'warning',
                'mensaje'   => '<div align="left">No puede seleccionar mas de 16 Vehiculos</div>',
            ]);

            array_pop($this->vehiculos);
        }
    }

    public function agregarVehiculo(Bien $bien)
    {
        $this->vehiculos[] =    [
                                    'cod_corr'  => $bien->cod_corr,
                                    'placa'     => $bien->placa,
                                    'modelo'    => $bien->modelo,
                                    'marca'     => $bien->marca
                                ];
    }

    public function eliminarVehiculo($index)
    {
        unset($this->vehiculos[$index]);

        $this->vehiculos = array_values($this->vehiculos);
    }

    //************************************************************/
    //**************** Funciones de Contratante ******************/
    //************************************************************/
    public function getCompradoresProperty()
    {
        return Comprador::with('usuariot:usuario,nombre')->where('sta_reg', 1)->get(['cod_com','usu_com'])->sortBy('usuariot.nombre')->pluck('usuariot.nombre','cod_com');
    }

    public function getTipoDeComprasProperty()
    {
        return TipoCompra::query()->whereNot('cod_tipocompra', '0')->orderBy('des_tipocompra')->pluck('des_tipocompra','cod_tipocompra');
    }

    private function getRangosUnidadTributaria($licita = null)
    {
        return TipoCompra::query()->rangos($licita)->get(['cod_tipocompra','ut_bie_ser_des','ut_bie_ser_has']);
    }

    public function updatedLicita($licita)
    {
        $this->evaluarTipoCompra($licita);
    }

    private function evaluarTipoCompra($licita)
    {
        $monto = $this->monto_tot;
        $unidad_tributaria = UnidadTributaria::getUltimaUnidadTributaria();

        $bs_ut = $unidad_tributaria->bs_ut;

        if(!is_null($unidad_tributaria)){

            $rangos_unidad_tributaria = $this->getRangosUnidadTributaria($licita);

            if(count($rangos_unidad_tributaria) > 0){
                $row2 = $rangos_unidad_tributaria->first();
                $mon_ut = $monto / $bs_ut;

                if ($row2->ut_bie_ser_has > 0) {
                    if (!(($mon_ut > $row2->ut_bie_ser_des) && ($mon_ut <= $row2->ut_bie_ser_has))) {
                        $this->emit('swal:alert', [
                            'tipo'      =>  'warning',
                            'mensaje'   =>  '<div align="left">El MONTO de la solicitud no corresponde al rango en UNIDADES <br>'.
                                            'TRIBUTARIAS (UT) definidas para el TIPO DE COMPRA seleccionado, <br>'.
                                            'porque debe encontrarse en un valor superior a '. $row2->ut_bie_ser_des .' UT '.
                                            'hasta '. $row2->ut_bie_ser_has .' UT:<br><br>'.
                                            '* El monto de la Solicitud en Bs. '. $monto .'.<br>'.
                                            '* El monto de la Solicitud en UT. '. $mon_ut .'.<br><br>'.
                                            'Por favor Verifique.</div>',
                            'width'     =>  '650px'
                        ]);
                    }
                }else{
                    if (!($mon_ut > $row2->ut_bie_ser_des)) {
                        $this->emit('swal:alert', [
                            'tipo'      =>  'warning',
                            'mensaje'   =>  '<div align="left">El MONTO de la solicitud no corresponde al rango en UNIDADES <br>'.
                                            'TRIBUTARIAS (UT) definidas para el TIPO DE COMPRA seleccionado, <br>'.
                                            'porque debe encontrarse en un valor superior a '.$row2->ut_bie_ser_des.' UT:<br><br>'.
                                            '* El monto de la Solicitud en Bs. '. $monto .'.<br>'.
                                            '* El monto de la Solicitud en UT. '. $mon_ut .'.<br><br>'.
                                            'Por favor Verifique.</div>',
                            'width'     =>  '650px'
                        ]);
                    }
                }
            }else{
                $this->emit('swal:alert', [
                    'tipo'      => 'warning',
                    'mensaje'   => '<div align="left">El Tipo de Compra no existe en tabla.<br><br>Por favor Verifique.</div>',
                ]);
            }

        }else{
            $this->emit('swal:alert', [
                'tipo'      => 'warning',
                'mensaje'   => '<div align="left">No Existen valores de Conversion para la UNIDAD TRIBUTARIA.<br><br>Por favor Verifique.</div>',
                'width'     => '600px'
            ]);
        }
    }

    //************************************************************/
    //**************** Funciones de Anulacion ********************/
    //************************************************************/
    public function getCausaAnulacionProperty()
    {
        return CausaAnulacion::query()->orderBy('des_cau')->pluck('des_cau','cod_cau');
    }

    //************************************************************/
    //**************** Funciones de Doc Asociados ****************/
    //************************************************************/
    private function mostrarCotizaciones($ano_pro, $grupo, $nro_req)
    {
        $cotizaciones = DetSolCotizacion::getCotizaciones($ano_pro, $grupo, $nro_req);

        foreach ($cotizaciones as $cotizacion) {
            $this->cotizaciones .= $cotizacion.PHP_EOL;
        }
    }

    private function mostrarOfertas($ano_pro, $grupo, $nro_req)
    {
        $ofertas = DetOfertaPro::getOfertas($ano_pro, $grupo, $nro_req);

        foreach ($ofertas as $oferta) {
            $this->ofertas .= $oferta.PHP_EOL;
        }
    }

    private function mostrarOrdenes($ano_pro, $grupo, $nro_req)
    {
        $ordenes = DetOrdenCompra::getOrdenes($ano_pro, $grupo, $nro_req);

        foreach ($ordenes as $orden) {
            $this->ordenes .= $orden.PHP_EOL;
        }
    }

    //************************************************************/
    //**************** Funciones de Botones **********************/
    //************************************************************/

    //************************************************************/
    //******Modulo: Unidad ***************************************/
    //************************************************************/
    //******Crear Solicitud **************************************/
    public function confirmCrearSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de CREAR la Solicitud?',
            'funcion'   => 'crearSolicitud'
        ]);
    }

    public function crearSolicitud()
    {
        // TODO Falta la salida en pdf y envio de correo electronico

        // $this->validate();

        try {
            // Buscar el CorrelativoComprobante
            $corr_nro_req = CorrSolCompras::getCorrSolCompras($this->anoPro, $this->grupo);

            $nro_req = $corr_nro_req > 0 ? $corr_nro_req : 1;

            $procede = true;

            ds($this->detalle_productos);
            ds($this->vehiculos);
            ds($corr_nro_req);
            ds($nro_req);

            if(count($this->detalle_productos) == 0)
            {
                $this->emit('swal:alert', [
                    'tipo'      =>  'warning',
                    'mensaje'   =>  '<div align="left">Debe agregar al menos un Bien/Material/Servicio en el<br>'.
                                    'detalle de la Solicitud<br><br>'.
                                    'Por favor verifique.</div>'
                ]);

                $procede = false;

                $this->showTab = 'tab2';
            }

            if((count($this->vehiculos) == 0) && ($this->grupo == "SV"))
            {
                $this->emit('swal:alert', [
                    'tipo'      =>  'warning',
                    'mensaje'   =>  '<div align="left">Debe agregar al menos un Bien al que se le aplique el<br>'.
                                    'servicio especificado en la Solicitud<br><br>'.
                                    'Por favor verifique.</div>'
                ]);

                $procede = false;

                $this->showTab = 'tab4';
            }

            if($procede){
                DB::connection('pgsql')->transaction(function () use($nro_req){

                    // Se Busca la ultima unidad tributaria
                    $unidadTributaria = UnidadTributaria::getUltimaUnidadTributaria();

                    $total_bs_ut = round($this->monto_tot / $unidadTributaria->bs_ut, 2);

                    // TODO Probar cambiandola por $this->contratante
                    $contratante = EncSolicitud::obtenerUnidadContratante($this->grupo, $total_bs_ut);

                    EncSolicitud::create([
                        'ano_pro'      =>   $this->anoPro,
                        'grupo'        =>   $this->grupo,
                        'nro_req'      =>   $nro_req,
                        'fk_cod_ger'   =>   $this->fk_cod_ger,
                        'fec_emi'      =>   $this->fechaGuardar,
                        'cla_sol'      =>   $this->cla_sol,
                        'pri_sol'      =>   $this->pri_sol,
                        'jus_sol'      =>   strtoupper($this->jus_sol),
                        'sta_sol'      =>   0,
                        'fec_sta'      =>   $this->fechaGuardar,
                        'usuario'      =>   auth()->user()->id,
                        'monto_tot'    =>   $this->monto_tot,
                        'donacion'     =>   $this->donacion,
                        'gru_ram'      =>   $this->gru_ram,
                        'cod_uni'      =>   $this->cod_uni,
                        'anexos'       =>   strtoupper($this->anexos),
                        'aplica_pre'   =>   $this->aplica_pre,
                        'contratante'  =>   $contratante
                    ]);

                    foreach ($this->prod_productos as $producto) {

                        $cod_com = DetSolicitud::getCodCom($this->tip_cod, $this->cod_pryacc, $this->cod_obj, $this->gerencia, $this->unidad, $producto['cod_par'], $producto['cod_gen'], $producto['cod_esp'], $producto['cod_sub']);

                        DetSolicitud::create([
                            'nro_req'     => $nro_req,
                            'grupo'       => $this->grupo,
                            'ano_pro'     => $this->anoPro,
                            'nro_ren'     => $producto['nro_ren'],
                            'fk_cod_mat'  => $producto['fk_cod_mat'],
                            'des_bien'    => $producto['des_bien'],
                            'fk_cod_uni'  => $producto['fk_cod_uni'],
                            'des_uni_med' => $producto['des_uni_med'],
                            'cantidad'    => $producto['cantidad'],
                            'sal_can'     => $producto['cantidad'],
                            'tip_cod'     => $this->tip_cod,
                            'cod_pryacc'  => $this->cod_pryacc,
                            'cod_obj'     => $this->cod_obj,
                            'gerencia'    => $this->gerencia,
                            'unidad'      => $this->unidad,
                            'cod_par'     => $producto['cod_par'],
                            'cod_gen'     => $producto['cod_gen'],
                            'cod_esp'     => $producto['cod_esp'],
                            'cod_sub'     => $producto['cod_sub'],
                            'pre_ref'     => $producto['pre_ref'],
                            'tot_ref'     => $producto['tot_ref'],
                            'sta_reg'     => '0',
                            'cod_com'     => $cod_com,
                            'cant_sal'    => $producto['cantidad']
                        ]);
                    }

                    foreach ($this->detalle_productos as $detalle) {
                        DetSolicitudDet::create([
                            'ano_pro'     => $this->anoPro,
                            'grupo'       => $this->grupo,
                            'nro_req'     => $nro_req,
                            'nro_ren'     => $detalle['nro_ren'],
                            'fk_cod_mat'  => $detalle['fk_cod_mat'],
                            'descripcion' => strtoupper($detalle['descripcion']),
                            'fk_cod_uni'  => $detalle['fk_cod_uni'],
                            'des_uni_med' => $detalle['des_uni_med'],
                            'cantidad'    => $detalle['cantidad'],
                            'precio'      => $detalle['precio'],
                            'total'       => $detalle['total'],
                            'cod_par'     => $detalle['cod_par'],
                            'cod_gen'     => $detalle['cod_gen'],
                            'cod_esp'     => $detalle['cod_esp'],
                            'cod_sub'     => $detalle['cod_sub'],
                            'sta_reg'     => '0'
                        ]);
                    }

                    if ($this->grupo=='SV')
                    {
                        foreach ($this->vehiculos as $vehiculo) {
                            ServicioBien::create([
                                'nro_req'     => $nro_req,
                                'grupo'       => $this->grupo,
                                'ano_pro'     => $this->anoPro,
                                'cod_corr'    => $vehiculo['cod_corr'],
                            ]);
                        }
                    }

                    // Actualizar el correlativo
                    CorrSolCompras::incCorrSolCompras($this->anoPro, $this->grupo, $nro_req == 1 ? 2 : $nro_req + 1);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="left">SOLICITUD CREADA:<br>'.
                                    '* <strong>Número: </strong>'. $nro_req .'<br>'.
                                    '* <strong>Grupo: </strong>'. $this->grupo .'<br>'.
                                    '* <strong>Año: </strong>'. $this->anoPro .'</div>',
                    'width'     =>  '400px'
                ]);

                return to_route('compras.proceso.solicitud.unidad.index');
            }
        } catch (\Exception $ex) {
            $this->emit('swal:alert', [
                'tipo'      =>  'error',
                'mensaje'   =>  str($ex)->limit(250),
                'width'     =>  '650px'
            ]);
        }
    }

    //************************************************************/
    //******Modificar Anexos Solicitud ***************************/
    public function confirmModificarAnexoSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de Modificar Anexos de la Solicitud?',
            'funcion'   => 'modificarAnexoSolicitud'
        ]);
    }

    public function modificarAnexoSolicitud()
    {
        $this->validate([
            'anexos'    => 'required',
        ]);

        try {
            DB::connection('pgsql')->transaction(function (){
                $usuario        = auth()->user()->id;
                $fecha_sistema  = $this->fechaGuardar;

                EncSolicitud::query()
                                ->where('ano_pro', $this->ano_pro)
                                ->where('grupo', $this->grupo)
                                ->where('nro_req', $this->nro_req)
                                ->update([
                                            'anexos'    => $this->anexos,
                                            'fec_ane'   => $fecha_sistema,
                                            'usuario'   => $usuario,
                                        ]);
            });

            $this->emit('swal:alert', [
                'tipo'      =>  'success',
                'mensaje'   =>  '<div align="left">Anexos de la Solicitud de Compras '.$this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .' Modificado Exitosamente.</div>',
                'width'     =>  '650px'
            ]);

            return to_route('compras.proceso.solicitud.unidad.index');
        } catch (\Exception $ex) {
            $this->emit('swal:alert', [
                'tipo'      =>  'error',
                'mensaje'   =>  str($ex)->limit(250),
                'width'     =>  '650px'
            ]);
        }
    }

    //************************************************************/
    //******Modificar Solicitud **********************************/
    public function confirmModificarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de MODIFICAR la Solicitud?',
            'funcion'   => 'modificarSolicitud'
        ]);
    }

    public function modificarSolicitud()
    {
        // TODO Falta la salida en pdf y envio de correo electronico

        $this->validate();

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if(($solicitud->sta_sol == '3') || ($solicitud->sta_sol == '51'))
        {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud no este Anulada o Reversada<br>'.
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'. $solicitud->sta_des .'</strong></div>',
                'width'     =>  '600px'
            ]);
        }else{
            try {
                DB::connection('pgsql')->transaction(function () use($solicitud){

                    // Se Busca la ultima unidad tributaria
                    $unidadTributaria = UnidadTributaria::getUltimaUnidadTributaria();

                    $total_bs_ut = round($this->monto_tot / $unidadTributaria->bs_ut, 2);

                    // Se obtiene la unidad Contratante
                    $contratante = EncSolicitud::obtenerUnidadContratante($this->grupo, $total_bs_ut);

                    // Se actualiza el tab Encabezado
                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                        'fk_cod_ger'   =>   $this->fk_cod_ger,
                                        'fec_emi'      =>   $this->fechaGuardar,
                                        'cla_sol'      =>   $this->cla_sol,
                                        'pri_sol'      =>   $this->pri_sol,
                                        'jus_sol'      =>   strtoupper($this->jus_sol),
                                        'sta_sol'      =>   0,
                                        'fec_sta'      =>   $this->fechaGuardar,
                                        'usuario'      =>   auth()->user()->id,
                                        'fecha'        =>   $this->fechaGuardar,
                                        'monto_tot'    =>   $this->monto_tot,
                                        'gru_ram'      =>   $this->gru_ram,
                                        'cod_uni'      =>   $this->cod_uni,
                                        'anexos'       =>   strtoupper($this->anexos),
                                        'contratante'  =>   $contratante
                                    ]);

                    // Se eliminan los detalles de la solicitud
                    $solicitud->detalles()->delete();

                    // Se eliminan los productos de la solicitud
                    $solicitud->productos()->delete();

                    // Se eliminan los vehiculos de la solicitud
                    $solicitud->vehiculos()->delete();

                    // Se actualiza el tab Producto
                    foreach ($this->prod_productos as $producto) {

                        $cod_com = DetSolicitud::getCodCom($this->tip_cod, $this->cod_pryacc, $this->cod_obj, $this->gerencia, $this->unidad, $producto['cod_par'], $producto['cod_gen'], $producto['cod_esp'], $producto['cod_sub']);

                        DetSolicitud::create([
                            'nro_req'     => $this->nro_req,
                            'grupo'       => $this->grupo,
                            'ano_pro'     => $this->anoPro,
                            'nro_ren'     => $producto['nro_ren'],
                            'fk_cod_mat'  => $producto['fk_cod_mat'],
                            'des_bien'    => $producto['des_bien'],
                            'fk_cod_uni'  => $producto['fk_cod_uni'],
                            'des_uni_med' => $producto['des_uni_med'],
                            'cantidad'    => $producto['cantidad'],
                            'sal_can'     => $producto['cantidad'],
                            'tip_cod'     => $this->tip_cod,
                            'cod_pryacc'  => $this->cod_pryacc,
                            'cod_obj'     => $this->cod_obj,
                            'gerencia'    => $this->gerencia,
                            'unidad'      => $this->unidad,
                            'cod_par'     => $producto['cod_par'],
                            'cod_gen'     => $producto['cod_gen'],
                            'cod_esp'     => $producto['cod_esp'],
                            'cod_sub'     => $producto['cod_sub'],
                            'pre_ref'     => $producto['pre_ref'],
                            'tot_ref'     => $producto['tot_ref'],
                            'sta_reg'     => '0',
                            'cod_com'     => $cod_com,
                            'cant_sal'    => $producto['cantidad']
                        ]);
                    }

                    // Se actualiza el tab Detalle
                    foreach ($this->detalle_productos as $detalle) {
                        DetSolicitudDet::create([
                            'ano_pro'     => $this->anoPro,
                            'grupo'       => $this->grupo,
                            'nro_req'     => $this->nro_req,
                            'nro_ren'     => $detalle['nro_ren'],
                            'fk_cod_mat'  => $detalle['fk_cod_mat'],
                            'descripcion' => strtoupper($detalle['descripcion']),
                            'fk_cod_uni'  => $detalle['fk_cod_uni'],
                            'des_uni_med' => $detalle['des_uni_med'],
                            'cantidad'    => $detalle['cantidad'],
                            'precio'      => $detalle['precio'],
                            'total'       => $detalle['total'],
                            'cod_par'     => $detalle['cod_par'],
                            'cod_gen'     => $detalle['cod_gen'],
                            'cod_esp'     => $detalle['cod_esp'],
                            'cod_sub'     => $detalle['cod_sub'],
                            'sta_reg'     => '0'
                        ]);
                    }

                    // Se actualiza el tab Bienes/Vehiculos
                    if ($this->grupo=='SV')
                    {
                        foreach ($this->vehiculos as $vehiculo) {
                            ServicioBien::create([
                                'nro_req'     => $this->nro_req,
                                'grupo'       => $this->grupo,
                                'ano_pro'     => $this->anoPro,
                                'cod_corr'    => $vehiculo->cod_corr,
                            ]);
                        }
                    }

                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> MODIFICADA Exitosamente</div>',
                    'width'     =>  '600px'
                ]);

            return to_route('compras.proceso.solicitud.unidad.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //*********Anular Solicitud **********************************/
    public function confirmAnularSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de ANULAR la Solicitud?',
            'funcion'   => 'anularSolicitud'
        ]);
    }

    public function anularSolicitud()
    {
        $this->validate([
            'fec_anu'       => 'required',
            'fk_cod_cau'    => 'required',
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if(($solicitud->sta_sol != '0') && ($solicitud->sta_sol != '41') && ($solicitud->sta_sol != '12'))
        {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor verifique que la solicitud tenga algún estatus:<br>'.
                                '<strong>CREADA o CONFORMADA EN PRESUPUESTO</strong><br><br>'.
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'. $solicitud->sta_des .'</strong></div>',
                'width'     =>  '500px'
            ]);

        }else{
            try{
                DB::connection('pgsql')->transaction(function (){

                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                                'usu_sta'       => auth()->user()->id,
                                                'fec_anu'       => $this->fechaGuardar,
                                                'fec_sta'       => $this->fechaGuardar,
                                                'sta_sol'       => '3',
                                                'fk_cod_cau'    => $this->fk_cod_cau,
                                                'sta_ant'       => '3',
                                                'fec_ant'       => $this->fechaGuardar
                                            ]);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> ANULADA Exitosamente</div>',
                    'width'     =>  '600px'
                ]);

                return to_route('compras.proceso.solicitud.unidad.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                            'tipo'      =>  'error',
                            'mensaje'   =>  str($ex)->limit(250),
                            'width'     =>  '650px'
                        ]);
            }
        }
    }

    //************************************************************/
    //*********Reversar Solicitud ********************************/
    public function confirmReversarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de ANULAR PRESUPUESTARIAMENTE la Solicitud?',
            'funcion'   => 'reversarSolicitud'
        ]);
    }

    public function reversarSolicitud()
    {
        // TODO Falta la salida en pdf y envio de correo electronico

        $this->validate([
            'fec_anu'       => 'required',
            'fk_cod_cau'    => 'required',
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if(($solicitud->sta_sol != '5') && ($solicitud->sta_sol != '61') && ($solicitud->sta_sol != '63'))
        {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                "* <strong>APROBADA</strong><br>".
                                "* <strong>DEVUELTA EN LOGISTICA</strong><br>".
                                "* <strong>DEVUELTA EN CONTRATACIONES</strong><br><br>".
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'.$solicitud->sta_des .'</strong></div>',
                'width'     =>  '500px'
            ]);
        }else{

            try {
                DB::connection('pgsql')->transaction(function () use($solicitud){
                    $usuario				= auth()->user()->id;
                    $ano_fiscal				= $this->anoPro;
                    $fecha_sistema          = $this->fechaGuardar;

                    $tip_ope				= 9;
                    $sol_tip				= 'OC';
                    $num_doc				= $this->grupo."-".$this->nro_req;
                    $concepto				= "REVERSO DE PRE-COMPROMISO DE SOLICITUD DE COMPRAS";
                    $reverso				= 1;

                    if ($this->aplica_pre == '1')
                    {
                        $result_pre = DetSolicitud::getEstructurasPresupuestarias($this->ano_pro, $this->grupo, $this->nro_req);

                        if(count($result_pre) == 0)
                        {
                            $this->emit('swal:alert', [
                                'tipo'      =>  'warning',
                                'mensaje'   =>  '<div align="center">No se Encontraron estructuras presupuestarias</div>',
                                'width'     =>  '450px'
                            ]);
                        }

                        foreach ($result_pre as $item) {
                            if($item->mto_tra != 0)
                            {
                                DB::connection('pgsql')->select("  SELECT *
                                                                    FROM movimientopresupuestario('$ano_fiscal','$item->cod_com', '$sol_tip',
                                                                    '$tip_ope', '$num_doc', '$item->mto_tra', '', '$concepto', '$reverso',
                                                                    '$usuario', '$this->ano_pro', '', '', '0', '$fecha_sistema')");
                            }
                        }
                    }

                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                                'usu_sta'       => $usuario,
                                                'sta_ant'       => $solicitud->sta_sol,
                                                'fec_ant'       => $solicitud->fec_sta,
                                                'fec_anu'       => $fecha_sistema,
                                                'fec_sta'       => $fecha_sistema,
                                                'sta_sol'       => '51',
                                                'fk_cod_cau'    => $this->fk_cod_cau
                                            ]);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> ha sido REVERSADA Exitosamente</div>',
                    'width'     =>  '600px'
                ]);

                return to_route('compras.proceso.solicitud.unidad.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //*********Copiar Solicitud **********************************/
    public function confirmCopiarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      =>  'warning',
            'mensaje'   =>  '<div align="center">¿Está seguro de REACTIVAR EN UNIDAD la Solicitud? <br>'.
                            'Se Creara una nueva Solicitud copia de la actual</div>',
            'funcion'   =>  'copiarSolicitud'
        ]);
    }

    public function copiarSolicitud()
    {
        // TODO Falta la salida en pdf y envio de correo electronico

        $corr_nro_req   = CorrSolCompras::getCorrSolCompras($this->anoPro, $this->grupo);

        $solicitud      = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if(($solicitud->sta_sol != '3') && ($solicitud->sta_sol != '51'))
        {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                '* <strong>ANULADA EN UNIDAD SOLICITANTE</strong><br>'.
                                '* <strong>ANULADA EN ADMINISTRACIÓN</strong><br><br>'.
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'.$solicitud->sta_des .'</strong></div>',
                'width'     =>  '450px'
            ]);
        }else{

            try {
                DB::connection('pgsql')->transaction(function () use($corr_nro_req, $solicitud){

                    EncSolicitud::create([
                        'ano_pro'      =>   $this->anoPro,
                        'grupo'        =>   $solicitud->grupo,
                        'nro_req'      =>   $corr_nro_req,
                        'fk_cod_ger'   =>   $solicitud->fk_cod_ger,
                        'fk_cod_cau'   =>   null,
                        'fec_emi'      =>   $this->fechaGuardar,
                        'cla_sol'      =>   $solicitud->cla_sol,
                        'pri_sol'      =>   $solicitud->pri_sol,
                        'jus_sol'      =>   $solicitud->jus_sol,
                        'sta_sol'      =>   0,
                        'fec_sta'      =>   $this->fechaGuardar,
                        'sta_ant'      =>   0,
                        'usuario'      =>   auth()->user()->id,
                        'fecha'        =>   $this->fechaGuardar,
                        'hora'         =>   now()->toTimeString(),
                        'monto_tot'    =>   $solicitud->monto_tot,
                        'donacion'     =>   $solicitud->donacion,
                        'gru_ram'      =>   $solicitud->gru_ram,
                        'cod_uni'      =>   $solicitud->cod_uni,
                        'anexos'       =>   $solicitud->anexos
                    ]);

                    foreach ($solicitud->productos as $producto) {
                        DetSolicitud::create([
                            'nro_req'     => $corr_nro_req,
                            'grupo'       => $producto->grupo,
                            'ano_pro'     => $this->anoPro,
                            'nro_ren'     => $producto->nro_ren,
							'fk_cod_mat'  => $producto->fk_cod_mat,
                            'des_bien'    => $producto->des_bien,
                            'fk_cod_uni'  => $producto->fk_cod_uni,
                            'des_uni_med' => $producto->des_uni_med,
							'cantidad'    => $producto->cantidad,
                            'sal_can'     => $producto->sal_can,
                            'tip_cod'     => $producto->tip_cod,
                            'cod_pryacc'  => $producto->cod_pryacc,
							'cod_obj'     => $producto->cod_obj,
                            'gerencia'    => $producto->gerencia,
                            'unidad'      => $producto->unidad,
                            'cod_par'     => $producto->cod_par,
							'cod_gen'     => $producto->cod_gen,
                            'cod_esp'     => $producto->cod_esp,
                            'cod_sub'     => $producto->cod_sub,
                            'pre_ref'     => $producto->pre_ref,
							'tot_ref'     => $producto->tot_ref,
                            'sta_reg'     => $producto->sta_reg,
                            'cod_com'     => $producto->cod_com
                        ]);
                    }

                    foreach ($solicitud->detalles as $detalle) {
                        DetSolicitudDet::create([
                            'ano_pro'     => $this->anoPro,
                            'grupo'       => $detalle->grupo,
                            'nro_req'     => $corr_nro_req,
                            'nro_ren'     => $detalle->nro_ren,
							'fk_cod_mat'  => $detalle->fk_cod_mat,
                            'descripcion' => $detalle->descripcion,
                            'fk_cod_uni'  => $detalle->fk_cod_uni,
                            'des_uni_med' => $detalle->des_uni_med,
							'cantidad'    => $detalle->cantidad,
                            'precio'      => $detalle->precio,
                            'total'       => $detalle->total,
                            'cod_par'     => $detalle->cod_par,
							'cod_gen'     => $detalle->cod_gen,
                            'cod_esp'     => $detalle->cod_esp,
                            'cod_sub'     => $detalle->cod_sub,
                            'sta_reg'     => $detalle->sta_reg,
                        ]);
                    }

                    if ($solicitud->grupo == 'SV')
                        {
                        foreach ($solicitud->vehiculos as $vehiculo) {
                            ServicioBien::create([
                                'ano_pro'     => $this->anoPro,
                                'grupo'       => $vehiculo->grupo,
                                'nro_req'     => $corr_nro_req,
                                'cod_corr'    => $vehiculo->cod_corr,
                            ]);
                        }
                    }

                    CorrSolCompras::incCorrSolCompras($this->anoPro, $solicitud->grupo, $corr_nro_req + 1);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="left">SOLICITUD CREADA:<br>'.
                                    '* <strong>Número: </strong>'.$corr_nro_req.'<br>'.
                                    '* <strong>Grupo: </strong>'.$this->grupo.'<br>'.
                                    '* <strong>Año: </strong>'.$this->anoPro.'</div>',
                    'width'     =>  '350px'
                ]);

                return to_route('compras.proceso.solicitud.unidad.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //*********Activar Solicitud *********************************/
    public function confirmActivarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      =>  'warning',
            'mensaje'   =>  '<div align="center">¿Está seguro de ACTIVAR la Solicitud?</div>',
            'funcion'   =>  'activarSolicitud'
        ]);
    }

    public function activarSolicitud()
    {
        $campos = [
            'fec_anu'       => null,
            'fec_sta'       => $this->fechaGuardar,
            'sta_sol'       => '0',
            'fk_cod_cau'    => null,
            'sta_ant'       => '3',
            'usu_sta'       => auth()->user()->id,
            'fec_ant'       => $this->fechaGuardar
        ];

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if($solicitud->sta_ant == 2)
        {
            $datos = array_merge($campos,['fec_aut' => $this->fechaGuardar]);
        }elseif($solicitud->sta_ant == 0)
        {
            $datos = array_merge($campos,['fec_emi' => $this->fechaGuardar]);
        }else{
            $datos = $campos;
        }

        if(($solicitud->sta_sol != '3') || ($solicitud->fk_cod_cau != '15'))
        {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus: <br>'.
                                '<strong>ANULADA EN UNIDAD SOLICITANTE</strong><br><br>'.
                                'Estatus Actual de la Solicitud:<br>'.
                                '<strong>'. $solicitud->sta_des .'</strong></div>',
                'width'     =>  '450px'
            ]);
        }else{
            try {
                DB::connection('pgsql')->transaction(function () use ($datos) {

                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update($datos);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud ACTIVADA Exitosamente.</div>',
                ]);

                return to_route('compras.proceso.solicitud.unidad.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //*********Precomprometer Solicitud **************************/
    public function confirmPrecomprometerSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      =>  'warning',
            'mensaje'   =>  '<div align="center">¿Está seguro de PRE-COMPROMETER la Solicitud?</div>',
            'funcion'   =>  'precomprometerSolicitud'
        ]);
    }

    public function precomprometerSolicitud()
    {
        $this->validate([
            'fec_pcom'       => 'required',
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if($solicitud->sta_sol != '12')
        {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus: <br>'.
                                '<strong>CONFORMADA EN PRESUPUESTO</strong><br><br>'.
                                'Estatus Actual de la Solicitud:<br>'.
                                '<strong>'. $solicitud->sta_des .'</strong></div>',
                'width'     =>  '450px'
            ]);
        }else{

            try {
                $consulta = EncSolicitud::estructuraGastoSolicitudSeleccionada($solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req);
                $mensaje = '';

                if (count($consulta) != 0) {
                    foreach($consulta as $item)
                    {
                        if($item->dif_dis_tot < 0)
                        {
                            $mensaje .= '* [<strong>'. $item->cod_com .'</strong>]'.
                                        '<br> Disponibilidad en Partida: '. $item->mto_dis .
                                        '<br> Monto a Pre-Comprometer en la Solicitud Actual: '. $item->sum_tot_ref .
                                        '<br> Monto Faltante en Partida para procesar Solicitud: '. ($item->dif_dis_tot * -1) .'<br><br>';
                        }
                    }

                    if ($mensaje != "") {
                        $this->emit('swal:alert', [
                            'tipo'      =>  'warning',
                            'mensaje'   =>  '<div align="left">Disculpe para este momento no tienen disponibilidad las <br>'.
                                            'Estructuras de Gastos siguientes:<br><br>'.
                                            $mensaje.
                                            'Puede Solicitar traspasos para Procesar la Solicitud.</div>',
                            'width'     =>  '600px'
                        ]);

                    } else {
                        DB::connection('pgsql')->transaction(function () use($solicitud){
                            $usuario				= auth()->user()->id;
                            $ano_fiscal				= $this->anoPro;
                            $fecha_sistema          = $this->fechaGuardar;

                            $tip_ope				= 8;
                            $sol_tip				= 'OC';
                            $num_doc				= $this->grupo."-".$this->nro_req;
                            $concepto				= "PRE-COMPROMISO DE SOLICITUD DE COMPRA";
                            $reverso				= '0';

                            if ($this->aplica_pre == '1')
                            {
                                $result_pre = DetSolicitud::getEstructurasPresupuestarias($this->ano_pro, $this->grupo, $this->nro_req);

                                if(count($result_pre) == 0)
                                {
                                    $this->emit('swal:alert', [
                                        'tipo'      =>  'warning',
                                        'mensaje'   =>  '<div align="center">No se Encontraron estructuras presupuestarias</div>',
                                    ]);
                                }

                                foreach ($result_pre as $item) {
                                    if($item->mto_tra != 0)
                                    {
                                        DB::connection('pgsql')->select("  SELECT *
                                                                            FROM movimientopresupuestario('$ano_fiscal','$item->cod_com', '$sol_tip',
                                                                            '$tip_ope', '$num_doc', '$item->mto_tra', '', '$concepto', '$reverso',
                                                                            '$usuario', '$this->ano_pro', '', '', '0', '$fecha_sistema')");
                                    }
                                }
                            }

                            EncSolicitud::query()
                                            ->where('ano_pro', $this->ano_pro)
                                            ->where('grupo', $this->grupo)
                                            ->where('nro_req', $this->nro_req)
                                            ->update([
                                                        'usu_sta'       => $usuario,
                                                        'fec_pcom'      => $fecha_sistema,
                                                        'fec_sta'       => $fecha_sistema,
                                                        'sta_sol'       => '5',
                                                        'sta_ant'       => $solicitud->sta_sol,
                                                        'fec_ant'       => $solicitud->fec_sta
                                                    ]);
                        });
                    }
                } else {
                    $this->emit('swal:alert', [
                        'tipo'      =>  'warning',
                        'mensaje'   =>  '<div align="left">ERROR: No se encontró coincidencias en el Maestro de Ley <br>'.
                                        'para las Estructuras de Gastos de la Solicitud Seleccionada <br><br>'.
                                        'Por favor verifique</div>',
                        'width'     =>  '550px'
                    ]);
                }

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras '. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .' ha sido APROBADA Exitosamente</div>',
                    'width'     =>  '650px'
                ]);

                return to_route('compras.proceso.solicitud.unidad.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //******Modulo: Compras **************************************/
    //************************************************************/
    //******Recepcionar Solicitud ********************************/
    public function confirmRecepcionarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de RECIBIR EN LOGISTICA la Solicitud?',
            'funcion'   => 'recepcionarSolicitud'
        ]);
    }

    public function recepcionarSolicitud()
    {
        $this->validate([
            'fec_rec'       => 'required'
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if ($solicitud->sta_sol != 'TR') {
            if ($solicitud->contratante != 'L') {
                $this->emit('swal:alert', [
                    'tipo'      =>  'warning',
                    'mensaje'   =>  '<div align="left">Por favor Verifique que la Unidad Contratante de la Solicitud sea Logistica</div>',
                    'width'     =>  '650px'
                ]);
            } else {
                if ($solicitud->sta_sol != '5' && $solicitud->sta_sol != '61') {
                    $this->emit('swal:alert', [
                        'tipo'      =>  'warning',
                        'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                        "* <strong>PRECOMPROMETIDA</strong><br>".
                                        "* <strong>DEVUELTA EN LOGISTICA</strong><br>".
                                        "* <strong>TRANSFERIDA</strong><br><br>".
                                        'Estatus Actual de la Solicitud: <br>'.
                                        '<strong>'.$solicitud->sta_des .'</strong></div>',
                        'width'     =>  '500px'
                    ]);
                }else{
                    try {
                        DB::connection('pgsql')->transaction(function () use($solicitud){
                            $usuario				= auth()->user()->id;
                            $fecha_sistema          = $this->fechaGuardar;

                            EncSolicitud::query()
                                            ->where('ano_pro', $this->ano_pro)
                                            ->where('grupo', $this->grupo)
                                            ->where('nro_req', $this->nro_req)
                                            ->update([
                                                        'cau_dev'       => '',
                                                        'fec_dev_com'   => null,
                                                        'fk_cod_com'    => null,
                                                        'fec_com'       => null,
                                                        'licita'        => '0',
                                                        'usu_sta'       => $usuario,
                                                        'fec_rec'       => $fecha_sistema,
                                                        'fec_sta'       => $fecha_sistema,
                                                        'sta_sol'       => '6',
                                                        'sta_ant'       => $solicitud->sta_sol,
                                                        'fec_ant'       => $solicitud->fec_sta,
                                                    ]);
                        });

                        $this->emit('swal:alert', [
                            'tipo'      =>  'success',
                            'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> RECIBIDA EN COMPRAS Exitosamente</div>',
                            'width'     =>  '600px'
                        ]);

                        return to_route('compras.proceso.solicitud.compra_recibir.index');
                    } catch (\Exception $ex) {
                        $this->emit('swal:alert', [
                            'tipo'      =>  'error',
                            'mensaje'   =>  str($ex)->limit(250),
                            'width'     =>  '650px'
                        ]);
                    }
                }
            }
        }
    }

    //************************************************************/
    //******Devolver Solicitud ***********************************/
    public function confirmDevolverSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de DEVOLVER DESDE LOGISTICA la Solicitud?',
            'funcion'   => 'devolverSolicitud'
        ]);
    }

    public function devolverSolicitud()
    {
        // TODO Falta la salida en pdf y envio de correo electronico

        $this->validate([
            'fec_dev_com'   => 'required',
            'cau_dev'       => 'required'
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if ($solicitud->sta_sol != '6' && $solicitud->sta_sol != '61' && $solicitud->sta_sol != '7') {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                "* <strong>RECIBIDA EN LOGISTICA</strong><br>".
                                "* <strong>DEVUELTA EN LOGISTICA</strong><br>".
                                "* <strong>CON COMPRADOR ASIGNADO EN LOGISTICA</strong><br><br>".
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'.$solicitud->sta_des .'</strong></div>',
                'width'     =>  '500px'
            ]);
        }else{
            try {
                DB::connection('pgsql')->transaction(function () use($solicitud){
                    $usuario	    = auth()->user()->id;
                    $fecha_sistema  = $this->fechaGuardar;

                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                                'usu_sta'       => $usuario,
                                                'cau_dev'       => strtoupper($this->cau_dev),
                                                'fec_dev_com'   => $fecha_sistema,
                                                'sta_sol'       => '61',
                                                'fec_sta'       => $fecha_sistema,
                                                'sta_ant'       => $solicitud->sta_sol,
                                                'fec_ant'       => $solicitud->fec_sta,
                                            ]);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> DEVUELTA EN LOGÍSTICA Exitosamente<br>'.
                                    'A Continuación saldrá IMPRESO el Formato de DEVOLUCION DESDE LOGÍSTICA</div>',
                    'width'     =>  '600px'
                ]);

                return to_route('compras.proceso.solicitud.compra_recibir.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //******Asignar Comprador Solicitud **************************/
    public function confirmAsignarCompradorSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de ASIGNAR COMPRADOR a la Solicitud?',
            'funcion'   => 'asignarCompradorSolicitud'
        ]);
    }

    public function asignarCompradorSolicitud()
    {
        $this->validate([
            'fec_com'       => 'required',
            'fk_cod_com'    => 'required',
            'licita'        => [
                                    'required',
                                    function($attribute,$value,$fail){
                                        if($value == '0' || $value == ''){
                                            $fail('El campo tipo de compra es obligatorio.');
                                        }
                                    }
                                ]
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if ($solicitud->sta_sol != '6' && $solicitud->sta_sol != '7') {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                "* <strong>RECIBIDA EN COMPRA</strong><br><br>".
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'.$solicitud->sta_des .'</strong></div>',
                'width'     =>  '500px'
            ]);
        }else{
            try {
                DB::connection('pgsql')->transaction(function () use($solicitud){
                    $usuario	    = auth()->user()->id;
                    $fecha_sistema  = $this->fechaGuardar;

                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                                'usu_sta'       => $usuario,
                                                'fk_cod_com'    => $this->fk_cod_com,
                                                'fec_com'       => $fecha_sistema,
                                                'licita'        => $this->licita,
                                                'fec_sta'       => $fecha_sistema,
                                                'sta_sol'       => '7',
                                                'sta_ant'       => $solicitud->sta_sol,
                                                'fec_ant'       => $solicitud->fec_sta,
                                            ]);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> se le ha ASIGNADO COMPRADOR EN LOGISTICA Exitosamente</div>',
                    'width'     =>  '650px'
                ]);

                return to_route('compras.proceso.solicitud.compra_recibir.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //******Reasignar Solicitud **********************************/
    public function confirmReasignarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de REASIGNAR la Solicitud?',
            'funcion'   => 'reasignarSolicitud'
        ]);
    }

    public function reasignarSolicitud()
    {
        $this->validate([
            'fec_reasig'    => 'required',
            'cau_reasig'    => 'required'
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if ($solicitud->sta_sol != 'TR' && $solicitud->sta_sol != '6' && $solicitud->sta_sol != '7') {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                "* <strong>RECIBIDA EN LOGISTICA</strong><br>".
                                "* <strong>CON COMPRADOR ASIGNADO EN LOGISTICA</strong><br><br>".
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'.$solicitud->sta_des .'</strong></div>',
                'width'     =>  '500px'
            ]);
        }else{
            try {
                DB::connection('pgsql')->transaction(function () use($solicitud){
                    $usuario	    = auth()->user()->id;
                    $fecha_sistema  = $this->fechaGuardar;
                    $contratante	= $this->contratante == 'L' ? 'C' : 'L';

                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                                'usu_sta'       => $usuario,
                                                'cau_reasig'    => strtoupper($this->cau_reasig),
                                                'fec_reasig'    => $fecha_sistema,
                                                'usu_reasig'    => $usuario,
                                                'contratante'   => $contratante,
                                                'sta_sol'       => 'TR',
                                                'fec_sta'       => $fecha_sistema,
                                                'sta_ant'       => $solicitud->sta_sol,
                                                'fec_ant'       => $solicitud->fec_sta,
                                            ]);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> REASIGNADA Exitosamente</div>',
                    'width'     =>  '600px'
                ]);

                return to_route('compras.proceso.solicitud.compra_recibir.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //******Modulo: Contrataciones *******************************/
    //************************************************************/
    //******Recepcionar Solicitud ********************************/
    public function confirmContratacionRecepcionarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de RECIBIR EN CONTRATACIONES la Solicitud?',
            'funcion'   => 'contratacionRecepcionarSolicitud',
            'width'     => '550px'
        ]);
    }

    public function contratacionRecepcionarSolicitud()
    {
        $this->validate([
            'fec_rec_cont'  => 'required'
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if ($solicitud->sta_sol != 'TR') {
            if ($solicitud->contratante != 'C') {
                $this->emit('swal:alert', [
                    'tipo'      =>  'warning',
                    'mensaje'   =>  '<div align="left">Por favor Verifique que la Unidad Contratante de la Solicitud sea Contrataciones</div>',
                    'width'     =>  '700px'
                ]);
            } else {
                if ($solicitud->sta_sol != '5' && $solicitud->sta_sol != '63') {
                    $this->emit('swal:alert', [
                        'tipo'      =>  'warning',
                        'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                        "* <strong>PRECOMPROMETIDA</strong><br>".
                                        "* <strong>DEVUELTA EN CONTRATACIONES</strong><br><br>".
                                        'Estatus Actual de la Solicitud: <br>'.
                                        '<strong>'.$solicitud->sta_des .'</strong></div>',
                        'width'     =>  '500px'
                    ]);
                }else{
                    try {
                        DB::connection('pgsql')->transaction(function () use($solicitud){
                            $usuario				= auth()->user()->id;
                            $fecha_sistema          = $this->fechaGuardar;

                            EncSolicitud::query()
                                            ->where('ano_pro', $this->ano_pro)
                                            ->where('grupo', $this->grupo)
                                            ->where('nro_req', $this->nro_req)
                                            ->update([
                                                        'cau_dev'       => '',
                                                        'fec_dev_cont'  => null,
                                                        'fk_cod_com'    => null,
                                                        'fec_com_cont'  => null,
                                                        'licita'        => '0',
                                                        'usu_sta'       => $usuario,
                                                        'fec_rec_cont'  => $fecha_sistema,
                                                        'fec_sta'       => $fecha_sistema,
                                                        'sta_sol'       => '62',
                                                        'sta_ant'       => $solicitud->sta_sol,
                                                        'fec_ant'       => $solicitud->fec_sta,
                                                    ]);
                        });

                        $this->emit('swal:alert', [
                            'tipo'      =>  'success',
                            'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> RECIBIDA EN CONTRATACIONES Exitosamente</div>',
                            'width'     =>  '750px'
                        ]);

                        return to_route('compras.proceso.solicitud.contratacion_recibir.index');
                    } catch (\Exception $ex) {
                        $this->emit('swal:alert', [
                            'tipo'      =>  'error',
                            'mensaje'   =>  str($ex)->limit(250),
                            'width'     =>  '650px'
                        ]);
                    }
                }
            }
        }
    }

    //************************************************************/
    //******Devolver Solicitud ***********************************/
    public function confirmContratacionDevolverSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de DEVOLVER DESDE CONTRATACIONES la Solicitud?',
            'funcion'   => 'contratacionDevolverSolicitud',
            'width'     => '600px'
        ]);
    }

    public function contratacionDevolverSolicitud()
    {
        // TODO Falta la salida en pdf y envio de correo electronico

        $this->validate([
            'fec_dev_cont'  => 'required',
            'cau_dev'       => 'required'
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if ($solicitud->sta_sol != '62' && $solicitud->sta_sol != '63' && $solicitud->sta_sol != '71') {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                "* <strong>RECIBIDA EN CONTRATACIONES</strong><br>".
                                "* <strong>DEVUELTA EN CONTRATACIONES</strong><br>".
                                "* <strong>CON COMPRADOR ASIGNADO EN CONTRATACIONES</strong><br><br>".
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'.$solicitud->sta_des .'</strong></div>',
                'width'     =>  '550px'
            ]);
        }else{
            try {
                DB::connection('pgsql')->transaction(function () use($solicitud){
                    $usuario	    = auth()->user()->id;
                    $fecha_sistema  = $this->fechaGuardar;

                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                                'usu_sta'       => $usuario,
                                                'cau_dev'       => strtoupper($this->cau_dev),
                                                'fec_dev_cont'  => $fecha_sistema,
                                                'sta_sol'       => '63',
                                                'fec_sta'       => $fecha_sistema,
                                                'sta_ant'       => $solicitud->sta_sol,
                                                'fec_ant'       => $solicitud->fec_sta,
                                            ]);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> DEVUELTA EN CONTRATACIONES Exitosamente<br>'.
                                    'A Continuación saldrá IMPRESO el Formato de DEVOLUCION DESDE CONTRATACIONES</div>',
                    'width'     =>  '750px'
                ]);

                return to_route('compras.proceso.solicitud.contratacion_recibir.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //******Asignar Comprador Solicitud **************************/
    public function confirmContratacionCompradorSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de ASIGNAR COMPRADOR a la Solicitud?',
            'funcion'   => 'asignarContratacionCompradorSolicitud'
        ]);
    }

    public function asignarContratacionCompradorSolicitud()
    {
        $this->validate([
            'fec_com_cont'  => 'required',
            'fk_cod_com'    => 'required',
            'licita'        => [
                                    'required',
                                    function($attribute,$value,$fail){
                                        if($value == '0' || $value == ''){
                                            $fail('El campo tipo de compra es obligatorio.');
                                        }
                                    }
                                ]
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if ($solicitud->sta_sol != '62' && $solicitud->sta_sol != '71') {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                "* <strong>RECIBIDA EN CONTRATACIONES</strong><br>".
                                "* <strong>COMPRADOR ASIGNADO EN CONTRATACIONES</strong><br><br>".
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'.$solicitud->sta_des .'</strong></div>',
                'width'     =>  '500px'
            ]);
        }else{
            try {
                DB::connection('pgsql')->transaction(function () use($solicitud){
                    $usuario	    = auth()->user()->id;
                    $fecha_sistema  = $this->fechaGuardar;

                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                                'usu_sta'       => $usuario,
                                                'fk_cod_com'    => $this->fk_cod_com,
                                                'fec_com_cont'  => $fecha_sistema,
                                                'licita'        => $this->licita,
                                                'fec_sta'       => $fecha_sistema,
                                                'sta_sol'       => '71',
                                                'sta_ant'       => $solicitud->sta_sol,
                                                'fec_ant'       => $solicitud->fec_sta,
                                            ]);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> se le ha ASIGNADO COMPRADOR EN CONTRATACIONES Exitosamente</div>',
                    'width'     =>  '650px'
                ]);

                return to_route('compras.proceso.solicitud.contratacion_recibir.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //******Reasignar Solicitud **********************************/
    public function confirmContratacionReasignarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de REASIGNAR la Solicitud?',
            'funcion'   => 'contratacionReasignarSolicitud'
        ]);
    }

    public function contratacionReasignarSolicitud()
    {
        $this->validate([
            'fec_reasig'    => 'required',
            'cau_reasig'    => 'required'
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if ($solicitud->sta_sol != 'TR' && $solicitud->sta_sol != '62' && $solicitud->sta_sol != '71') {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                "* <strong>RECIBIDA EN CONTRATACIONES</strong><br>".
                                "* <strong>CON COMPRADOR ASIGNADO EN CONTRATACIONES</strong><br><br>".
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'.$solicitud->sta_des .'</strong></div>',
                'width'     =>  '550px'
            ]);
        }else{
            try {
                DB::connection('pgsql')->transaction(function () use($solicitud){
                    $usuario	    = auth()->user()->id;
                    $fecha_sistema  = $this->fechaGuardar;
                    $contratante	= $this->contratante == 'L' ? 'C' : 'L';

                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                                'usu_sta'       => $usuario,
                                                'cau_reasig'    => strtoupper($this->cau_reasig),
                                                'fec_reasig'    => $fecha_sistema,
                                                'usu_reasig'    => $usuario,
                                                'contratante'   => $contratante,
                                                'sta_sol'       => 'TR',
                                                'fec_sta'       => $fecha_sistema,
                                                'sta_ant'       => $solicitud->sta_sol,
                                                'fec_ant'       => $solicitud->fec_sta,
                                            ]);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> REASIGNADA Exitosamente</div>',
                    'width'     =>  '600px'
                ]);

                return to_route('compras.proceso.solicitud.contratacion_recibir.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //******Modulo: Presupuesto **********************************/
    //************************************************************/
    //******Conformación Presupuestaria Solicitud ****************/
    public function confirmPresupuestoAprobarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de CONFORMAR EN PRESUPUESTO la Solicitud?',
            'funcion'   => 'presupuestoAprobarSolicitud',
            'width'     => '550px'
        ]);
    }

    public function presupuestoAprobarSolicitud()
    {
        $this->validate([
            'fec_aut'    => 'required'
        ]);

        $solicitud = $this->getEncSolicitud($this->ano_pro, $this->grupo, $this->nro_req);

        if(($solicitud->sta_sol != '0'))
        {
            $this->emit('swal:alert', [
                'tipo'      =>  'warning',
                'mensaje'   =>  '<div align="left">Por favor Verifique que la Solicitud tenga estatus:<br>'.
                                "* <strong>CREADA</strong><br><br>".
                                'Estatus Actual de la Solicitud: <br>'.
                                '<strong>'.$solicitud->sta_des .'</strong></div>',
                'width'     =>  '500px'
            ]);
        }else{
            try {

                DB::connection('pgsql')->transaction(function (){
                    EncSolicitud::query()
                                    ->where('ano_pro', $this->ano_pro)
                                    ->where('grupo', $this->grupo)
                                    ->where('nro_req', $this->nro_req)
                                    ->update([
                                                'usu_sta'  => auth()->user()->id,
                                                'fec_aut'  => $this->fechaGuardar,
                                                'fec_sta'  => $this->fechaGuardar,
                                                'sta_sol'  => '12',
                                                'sta_ant'  => '12',
                                                'fec_ant'  => $this->fechaGuardar
                                            ]);
                });

                $this->emit('swal:alert', [
                    'tipo'      =>  'success',
                    'mensaje'   =>  '<div align="center">Solicitud de Compras <strong>'. $this->grupo.'-'.$this->nro_req.'-'.$this->ano_pro .'</strong> CONFORMADA EN PRESUPUESTO Exitosamente</div>',
                    'width'     =>  '650px'
                ]);

                return to_route('compras.proceso.solicitud.presupuesto.index');
            } catch (\Exception $ex) {
                $this->emit('swal:alert', [
                    'tipo'      =>  'error',
                    'mensaje'   =>  str($ex)->limit(250),
                    'width'     =>  '650px'
                ]);
            }
        }
    }

    //************************************************************/
    //******Anular Presupuestaro Solicitud ***********************/
    public function confirmPresupuestoReversarSolicitud()
    {
        $this->emit('swal:confirm', [
            'tipo'      => 'warning',
            'mensaje'   => '¿Está seguro de ANULAR PRESUPUESTARIAMENTE la Solicitud?',
            'funcion'   => 'reversarSolicitud',
            'width'     => '550px'
        ]);
    }

    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.compras.proceso.solicitud', [
            'headers' => [
                ['name' => 'Cód. Correlativo', 'align' => 'left', 'sort' => 'cod_corr'],
                ['name' => 'Placa', 'align' => 'left', 'sort' => 'placa'],
                ['name' => 'modelo', 'align' => 'left', 'sort' => 'modelo'],
                ['name' => 'marca', 'align' => 'left', 'sort' => 'marca'],
                'Acción'
            ],
            'vehiculosList' => Bien::query()
                                ->select('cod_corr', 'placa', 'modelo', 'marca')
                                ->whereNotIn('cod_corr', array_column($this->vehiculos, 'cod_corr'))
                                ->when($this->search != '', function($query) {
                                    $query->where('cod_corr', 'like', '%'.$this->search.'%')
                                        ->orWhere('placa', 'like', '%'.strtoupper($this->search).'%')
                                        ->orWhere('modelo', 'like', '%'.strtoupper($this->search).'%')
                                        ->orWhere('marca', 'like', '%'.strtoupper($this->search).'%');
                                })
                                ->orderBy($this->sort, $this->direction)
                                ->paginate($this->paginate)
        ]);
    }
}
