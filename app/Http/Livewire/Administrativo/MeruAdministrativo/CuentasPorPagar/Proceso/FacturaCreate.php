<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\CuentasPorPagar\Proceso;

use Livewire\Component;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacRecepFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Factura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPDetNotaFactura;
use Illuminate\Support\Facades\DB;
use App\Models\Administrativo\Meru_Administrativo\Contabilidad\ConCuentasAporte;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPCabeceraFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxpDetComproFacturas;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPDetGastosFactura;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;

class FacturaCreate extends Component
{
    public $accion;
    public $factura;
    public $facrecepfactura;
    public $proveedores;
    public $cxptipodocumento;
    public $marcar;
    public $facturas;
    //------------Factura--------------*/
    public $rif_prov;
    public $num_fac;
    public $ano_pro;
    public $fondo;
    public $cuenta_contable;
    public $fec_fac;
    public $num_ctrl;
    public $fecha;
    public $recibo;
    public $sta_fac;
    public $sta_facd;
    public $tipo_doc;
    public $deposito_garantia;
    public $ano_sol;
    public $nro_doc;
    public $tipo_pago;
    public $por_iva;
    public $provisionada;
    public $por_anticipo;
    public $mto_anticipo;
    public $mto_amortizacion;
    public $servicio;

    //---------------Detalle------------------------//
    public $base_imponible;
    public $base_excenta;
    public $mto_nto;
    public $mto_iva;
    public $mto_fac;
    public $monto_original;
    public $ncr_sn;
    public $tot_ncr;
    public $mto_ncr;
    public $iva_ncr;
    public $num_nc;
    public $nro_ncr;
    public $fecha_cau;

    //---------------notas-----------------------
    public $base_imponible_n;
    public $base_excenta_n;
    public $mto_ord;
    public $mto_iva_n;
    //------------relaciones--------------------//
    public $cxpdetnotafacturas=[];
    public $detnotafacturas;
    public $cxpdetgastosfactura=[];
    public $detgastosfactura;
    public $cxpdetcomprofacturas=[];
    public $detcomprofacturas;
    public $detallegasto= [];
    public $flag = false;
    protected $listeners =['changeSelect'];

    public $listadonotas;
    public $listadogastos;
    public $listadoasientos;

    public $ano_solicitud_ordenes;
    public $lrif_prov;
    public $lrecibo;
    public $num_ctr;



    public function cargar_emit()
    {



        if ($this->accion=='cambiar')
            $this->emit('enableCambiarfactura');

        if ($this->accion=='anular' || $this->accion=='aprobar' || $this->accion=='reversar' || $this->accion=='modificar')
            $this->emit('enableAnularfactura');

        //Si se produce un alerta inhabilita el botón correspondiente para ejecutar la acciones
        if ($this->flag==true)
            $this->emit('enableBoton');

    }
    public function changeSelect($valor,$id)
    {
        $this->rif_prov= $valor ;
    }

    public function mount()
    {
        $this->rif_prov =   $this->factura->rif_prov;
        $this->num_fac  =   $this->factura->num_fac;
        $this->ano_pro  =   $this->factura->ano_pro;
        $this->recibo   =   $this->factura->recibo;

        switch($this->accion)
        {
            case 'cambiar':         { $this->cambiarFactura();     break; }
            case 'anular':          { $this->anularFactura();      break; }
            case 'aprobar':         { $this->aprobarFactura();     break; }
            case 'reversar':        { $this->reversarFactura();    break; }
            case 'modificar':       { $this->modificarFactura();    break; }
        }

        // if ($this->accion=='cambiar'){
        //     $this->cambiarFactura();
        // }
        // if ($this->accion=='anular'){
        //     $this->anularFactura();
        // }
        // if ($this->accion=='aprobar'){
        //     $this->aprobarFactura();
        // }
        // if ($this->accion=='reversar'){
        //     $this->reversarFactura();
        // }
        // if ($this->accion=='modificar'){
        //     $this->modificarFactura();
        // }
    }
    //---------------------------------------------------------------------------------------------------------------------------------
    // función para buscar los datos para crear factura
    //---------------------------------------------------------------------------------------------------------------------------------
    public function datosFactura (){
        $estado='';

        //dd($this->rif_prov.'|'.$this->num_fac.'|'.$this->ano_pro.'|'.$this->recibo);
        if (!empty($this->rif_prov)){
            if (!empty($this->num_fac)){
                if (!empty($this->ano_pro)){
                    if (!empty($this->recibo)){
                        //$this->buscar_datos('N');
                        $this->facrecepfactura = FacRecepFactura::where('rif_prov',$this->rif_prov)
                                                ->where('num_fac', $this->num_fac)
                                                ->where('ano_pro',$this->ano_pro)
                                                ->where('recibo',$this->recibo)
                                                ->first();
                        // dd($this->facrecepfactura );
                        if ($this->facrecepfactura==null) {
                            $this->emit('swal:alert', [
                                'tipo'    => 'warning',
                                'titulo'  => 'Error',
                                'mensaje' => 'Factura no ha sido recepcionada'
                            ]);
                        }else {
                            //-----------pestaña facturas-------------------------//
                            $this->rif_prov             = $this->facrecepfactura->rif_prov;
                            $this->num_fac              = $this->facrecepfactura->num_fac;
                            $this->ano_pro              = $this->facrecepfactura->ano_pro;
                            $this->fondo                = $this->facrecepfactura->opsolservicio->fondo;
                            $this->cuenta_contable      = $this->facrecepfactura->opsolservicio->cuenta_contable;
                            //dd($this->facrecepfactura->fec_fac);
                            if($this->facrecepfactura->fec_fac)
                                $this->fec_fac              = $this->facrecepfactura->fec_fac->format('Y-m-d');
                            $this->num_ctrl             = $this->facrecepfactura->num_ctrl;
                            $this->fecha                = now()->format('Y-m-d');
                            $this->recibo               = $this->facrecepfactura->recibo;
                            $this->sta_facd             = $this->facrecepfactura->getEstFac($this->facrecepfactura->sta_fac);
                            $this->sta_fac              = $this->facrecepfactura->sta_fac;
                            $this->tipo_doc             = $this->facrecepfactura->tipo_doc;
                            $this->deposito_garantia    = $this->facrecepfactura->opsolservicio->deposito_garantia;
                            $this->ano_sol              = $this->facrecepfactura->ano_sol;
                            $this->nro_doc              = $this->facrecepfactura->nro_doc;
                            $this->tipo_pago            = $this->facrecepfactura->opsolservicio->tip_pag;
                            $this->por_iva              = $this->facrecepfactura->opsolservicio->por_iva;
                            $this->provisionada         = $this->facrecepfactura->opsolservicio->provision;
                            $this->por_anticipo         = $this->facrecepfactura->opsolservicio->por_anticipo;
                            $this->mto_anticipo         = '0.00';
                            $this->mto_amortizacion     = '0.00';

                            $this->servicio             = 'O';

                            //-----------pestaña detalle-------------------------//
                            $this->base_imponible   = $this->facrecepfactura->opsolservicio->base_imponible;
                            $this->base_excenta     = $this->facrecepfactura->opsolservicio->base_exenta;
                            $this->mto_nto          = $this->facrecepfactura->opsolservicio->monto_neto;
                            $this->mto_iva          = $this->facrecepfactura->opsolservicio->monto_iva;
                            $this->mto_fac          = $this->facrecepfactura->opsolservicio->monto_total;
                            $this->monto_original   = $this->facrecepfactura->opsolservicio->monto_total;
                            $this->ncr_sn           = $this->facrecepfactura->opsolservicio->ncr_sn;
                            $this->tot_ncr          = $this->facrecepfactura->opsolservicio->tot_ncr;
                            $this->mto_ncr          = $this->facrecepfactura->opsolservicio->mto_ncr;
                            $this->iva_ncr          = $this->facrecepfactura->opsolservicio->iva_ncr;
                            $this->num_nc           = $this->facrecepfactura->opsolservicio->num_nc;
                            $this->nro_ncr          = $this->facrecepfactura->opsolservicio->nro_ncr;

                            $detnotafacturas = $this->facrecepfactura->cxpdetnotafacturas;

                            foreach($detnotafacturas as $estructura){

                                $this->cxpdetnotafacturas[] = [
                                        'marcar'                => 'Si',
                                        'ano_nota_entrega'      => $estructura->ano_nota_entrega,
                                        'nro_ent'               => $estructura->nro_ent,
                                        'nota_entrega'          => $estructura->nota_entrega,
                                        'base_imponible'        => $estructura->base_imponible,
                                        'base_excenta'          => $estructura->base_excenta,
                                        'mto_iva'               => $estructura->mto_iva,
                                        'mto_ord'               => $estructura->mto_ord
                                ];
                            }
                            $this->listadonotas =json_encode($this->cxpdetnotafacturas, true);
                            //------------Pestaña de gasto------------------------//
                            $detgastosfactura =$this->facrecepfactura->opsolservicio->opdetgastossolservicio;


                            foreach($detgastosfactura as $index => $gastos){

                                $this->cxpdetgastosfactura[] = [
                                    'gasto'             => ($gastos['gasto'] == 1) ? 'Si' : 'No',
                                    'tip_cod'           => $gastos['tip_cod'],
                                    'cod_pryacc'        => $gastos['cod_pryacc'],
                                    'cod_obj'           => $gastos['cod_obj'],
                                    'gerencia'          => $gastos['gerencia'],
                                    'unidad'            => $gastos['unidad'],
                                    'cod_par'           => $gastos['cod_par'],
                                    'cod_gen'           => $gastos['cod_gen'],
                                    'cod_esp'           => $gastos['cod_esp'],
                                    'cod_sub'           => $gastos['cod_sub'],
                                    'mto_tra'           => $gastos['mto_tra'],
                                    'mto_nc'            => '0.00',
                                    'sal_cau'           => $gastos['sal_cau'],
                                    'Original'          => $gastos['Original']
                                ];
                            }
                            $this->listadogastos =json_encode($this->cxpdetgastosfactura, true);
                            //------------Pestaña de asientos------------------------//
                            // $detcomprofacturas =$this->factura->cxpdetcomprofacturas;

                            // foreach($detcomprofacturas as $asientos){

                            //     $this->cxpdetcomprofacturas[] = [
                            //         'nro_ren'       => $asientos->nro_ren,
                            //         'cod_cta'       => $asientos->cod_cta,
                            //         'tipo'          => $asientos->tipo,
                            //         'monto'         => $asientos->monto
                            //     ];
                            // }

                           // $this->emit('enableCreatefactura');
                        }
                    }else{
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'Debe Ingresar Recibo'
                        ]);
                    }
                }else{
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Debe Ingresar Ano del Proceso'
                    ]);

                }
            }else{
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Debe Ingresar Numero de la factura.'
                ]);
            }
        }else{
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe Ingresar Rif del Proveedor'
            ]);
        }


    }

    //---------------------------------------------------------------------------------------------------------------------------------
    // función para buscar los datos para crear factura
    //---------------------------------------------------------------------------------------------------------------------------------
    public function datosFacturaReal(){
        $estado='';

        //dd($this->rif_prov.'|'.$this->num_fac.'|'.$this->ano_pro.'|'.$this->recibo);
        if (!empty($this->rif_prov)){
            if (!empty($this->num_fac)){
                if (!empty($this->ano_pro)){
                    if (!empty($this->recibo)){
                        //$this->buscar_datos('N');
                        $this->factura = Factura::where('rif_prov',$this->rif_prov)
                                                ->where('num_fac', $this->num_fac)
                                                ->where('ano_pro',$this->ano_pro)
                                                ->where('recibo',$this->recibo)
                                                ->first();
                        // dd($this->facrecepfactura );
                        if ($this->factura==null) {
                            $this->emit('swal:alert', [
                                'tipo'    => 'warning',
                                'titulo'  => 'Error',
                                'mensaje' => 'NO existen datos de Factura '
                            ]);
                        }else {
                            //-----------pestaña facturas-------------------------//
                            $this->rif_prov             = $this->factura->rif_prov;
                            $this->num_fac              = $this->factura->num_fac;
                            $this->ano_pro              = $this->factura->ano_pro;
                            $this->fondo                = $this->factura->fondo;
                            $this->cuenta_contable      = $this->factura->cuenta_contable;
                            if($this->factura->fec_fac)
                                $this->fec_fac          = $this->factura->fec_fac->format('Y-m-d');
                            $this->num_ctrl             = $this->factura->num_ctrl;
                            $this->fecha                =  $this->factura->fecha;
                            $this->recibo               = $this->factura->recibo;
                            $this->sta_facd             = $this->factura->getEstFac($this->factura->sta_fac);
                            $this->sta_fac              = $this->factura->sta_fac;
                            $this->tipo_doc             = $this->factura->tipo_doc;
                            $this->deposito_garantia    = $this->factura->deposito_garantia;
                            $this->ano_sol              = $this->factura->ano_sol;
                            $this->nro_doc              = $this->factura->nro_doc;
                            $this->tipo_pago            = $this->factura->tip_pago;
                            $this->por_iva              = $this->factura->mto_iva;
                            $this->provisionada         = $this->factura->provisionada;
                            $this->por_anticipo         = $this->factura->por_anticipo;
                            $this->mto_anticipo         = '0.00';
                            $this->mto_amortizacion     = '0.00';

                            $this->servicio             = 'O';

                            //-----------pestaña detalle-------------------------//
                            $this->base_imponible   = $this->factura->base_imponible;
                            $this->base_excenta     = $this->factura->base_excenta;
                            $this->mto_nto          = $this->factura->mto_nto;
                            $this->mto_iva          = $this->factura->mto_iva;
                            $this->mto_fac          = $this->factura->mto_fac;
                            $this->monto_original   = $this->factura->mto_nto;
                            $this->ncr_sn           = $this->factura->ncr_sn;
                            $this->tot_ncr          = $this->factura->tot_ncr;
                            $this->mto_ncr          = $this->factura->mto_ncr;
                            $this->iva_ncr          = $this->factura->iva_ncr;
                            $this->num_nc           = $this->factura->num_nc;
                            $this->nro_ncr          = $this->factura->nro_ncr;

                            $detnotafacturas = $this->factura->cxpdetnotasfacturas;

                            foreach($detnotafacturas as $estructura){

                                $this->cxpdetnotafacturas[] = [
                                        'marcar'                => 'Si',
                                        'ano_nota_entrega'      => $estructura->ano_nota_entrega,
                                        'nro_ent'               => $estructura->nro_ent,
                                        'nota_entrega'          => $estructura->nota_entrega,
                                        'base_imponible'        => $estructura->base_imponible,
                                        'base_excenta'          => $estructura->base_excenta,
                                        'mto_iva'               => $estructura->mto_iva,
                                        'mto_ord'               => $estructura->mto_ord
                                ];
                            }
                            $this->listadonotas =json_encode($this->cxpdetnotafacturas, true);
                            //------------Pestaña de gasto------------------------//
                            $detgastosfactura =$this->factura->cxpdetgastosfactura;


                            foreach($detgastosfactura as $index => $gastos){

                                $this->cxpdetgastosfactura[] = [
                                    'gasto'             => ($gastos['gasto'] == 1) ? 'Si' : 'No',
                                    'tip_cod'           => $gastos['tip_cod'],
                                    'cod_pryacc'        => $gastos['cod_pryacc'],
                                    'cod_obj'           => $gastos['cod_obj'],
                                    'gerencia'          => $gastos['gerencia'],
                                    'unidad'            => $gastos['unidad'],
                                    'cod_par'           => $gastos['cod_par'],
                                    'cod_gen'           => $gastos['cod_gen'],
                                    'cod_esp'           => $gastos['cod_esp'],
                                    'cod_sub'           => $gastos['cod_sub'],
                                    'mto_tra'           => $gastos['mto_tra'],
                                    'mto_nc'            => '0.00',
                                    'sal_cau'           => $gastos['sal_cau'],
                                    'Original'          => $gastos['Original']
                                ];
                            }
                            $this->listadogastos =json_encode($this->cxpdetgastosfactura, true);
                            //------------Pestaña de asientos------------------------//
                             $detcomprofacturas =$this->factura->cxpdetcomprofacturas;

                             foreach($detcomprofacturas as $asientos){

                                $this->cxpdetcomprofacturas[] = [
                                    'nro_ren'       => $asientos->nro_ren,
                                    'cod_cta'       => $asientos->cod_cta,
                                    'tipo'          => $asientos->tipo,
                                    'monto'         => $asientos->monto
                                ];
                            }
                            $this->listadoasientos =json_encode($this->cxpdetcomprofacturas, true);
                           // $this->emit('enableCreatefactura');
                        }
                    }else{
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'Debe Ingresar Recibo'
                        ]);
                    }
                }else{
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Debe Ingresar Ano del Proceso'
                    ]);

                }
            }else{
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Debe Ingresar Numero de la factura.'
                ]);
            }
        }else{
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe Ingresar Rif del Proveedor'
            ]);
        }


    }

     //---------------------------------------------------------------------------------------------------------------------------------
    // función para buscar los datos para crear una nuevafactura
    //---------------------------------------------------------------------------------------------------------------------------------
    public function nuevaFactura(){
        if (!empty($this->rif_prov)){
            if (!empty($this->num_fac)){
                if (!empty($this->ano_pro)){
                    if (!empty($this->recibo)){
                        $this->buscar_datos('N');

                        $this->datosFactura();
                        $this->emit('enableNuevafactura');
                    }else{
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'Debe Ingresar Recibo'
                        ]);
                    }
                }else{
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Debe Ingresar Ano del Proceso'
                    ]);

                }
            }else{
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Debe Ingresar Numero de la factura.'
                ]);
            }
        }else{
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe Ingresar Rif del Proveedor'
            ]);
        }
    }

    //---------------------------------------------------------------------------------------------------------------------------------
    // función para buscar los datos para cambiar factura
    //---------------------------------------------------------------------------------------------------------------------------------
    public function cambiarFactura(){
        if (!empty($this->rif_prov)){
            if (!empty($this->num_fac)){
                if (!empty($this->ano_pro)){
                    if (!empty($this->recibo)){
                      //  $this->buscar_datos('N');
                        $this->buscar_datos_acciones('M');
                        $this->datosFacturaReal();
                        $this->emit('enableCambiarfactura');
                    }else{
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'Debe Ingresar Recibo'
                        ]);
                    }
                }else{
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Debe Ingresar Ano del Proceso'
                    ]);

                }
            }else{
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Debe Ingresar Numero de la factura.'
                ]);
            }
        }else{
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe Ingresar Rif del Proveedor'
            ]);
        }

    }

    //---------------------------------------------------------------------------------------------------------------------------------
    // función para buscar los datos para anular factura
    //---------------------------------------------------------------------------------------------------------------------------------
    public function anularFactura(){
        if (!empty($this->rif_prov)){
            if (!empty($this->num_fac)){
                if (!empty($this->ano_pro)){
                    if (!empty($this->recibo)){
                      //  $this->buscar_datos('N');
                        $this->buscar_datos_acciones('B');
                        $this->datosFacturaReal();
                        $this->emit('enableAnularfactura');
                    }else{
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'Debe Ingresar Recibo'
                        ]);
                    }
                }else{
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Debe Ingresar Ano del Proceso'
                    ]);

                }
            }else{
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Debe Ingresar Numero de la factura.'
                ]);
            }
        }else{
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe Ingresar Rif del Proveedor'
            ]);
        }

    }
    //---------------------------------------------------------------------------------------------------------------------------------
    // función para reversar factura
    //---------------------------------------------------------------------------------------------------------------------------------
    public function reversarFactura(){
        if (!empty($this->rif_prov)){
            if (!empty($this->num_fac)){
                if (!empty($this->ano_pro)){
                    if (!empty($this->recibo)){
                      //  $this->buscar_datos('N');
                        $this->buscar_datos_acciones('R');
                        $this->datosFacturaReal();
                        $this->emit('enableReversarfactura');
                    }else{
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'Debe Ingresar Recibo'
                        ]);
                    }
                }else{
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Debe Ingresar Ano del Proceso'
                    ]);

                }
            }else{
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Debe Ingresar Numero de la factura.'
                ]);
            }
        }else{
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe Ingresar Rif del Proveedor'
            ]);
        }
    }
    //---------------------------------------------------------------------------------------------------------------------------------
    // función para Aprobar factura
    //---------------------------------------------------------------------------------------------------------------------------------
    public function aprobarFactura(){
        if (!empty($this->rif_prov)){
            if (!empty($this->num_fac)){
                if (!empty($this->ano_pro)){
                    if (!empty($this->recibo)){
                      //  $this->buscar_datos('N');
                        $this->buscar_datos_acciones('A');
                        $this->datosFacturaReal();
                        $this->emit('enableReversarfactura');
                    }else{
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'Debe Ingresar Recibo'
                        ]);
                    }
                }else{
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Debe Ingresar Ano del Proceso'
                    ]);

                }
            }else{
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Debe Ingresar Numero de la factura.'
                ]);
            }
        }else{
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe Ingresar Rif del Proveedor'
            ]);
        }

    }
    //---------------------------------------------------------------------------------------------------------------------------------
    // función para modificar asiento de factura
    //---------------------------------------------------------------------------------------------------------------------------------
    public function modificarFactura(){
        if (!empty($this->rif_prov)){
            if (!empty($this->num_fac)){
                if (!empty($this->ano_pro)){
                    if (!empty($this->recibo)){
                      //  $this->buscar_datos('N');
                        $this->buscar_datos_acciones('X');
                        $this->datosFacturaReal();
                      //  $this->emit('enableReversarfactura');
                      $this->emit('enableAnularfactura');
                    }else{
                        $this->emit('swal:alert', [
                            'tipo'    => 'warning',
                            'titulo'  => 'Error',
                            'mensaje' => 'Debe Ingresar Recibo'
                        ]);
                    }
                }else{
                    $this->emit('swal:alert', [
                        'tipo'    => 'warning',
                        'titulo'  => 'Error',
                        'mensaje' => 'Debe Ingresar Ano del Proceso'
                    ]);

                }
            }else{
                $this->emit('swal:alert', [
                    'tipo'    => 'warning',
                    'titulo'  => 'Error',
                    'mensaje' => 'Debe Ingresar Numero de la factura.'
                ]);
            }
        }else{
            $this->emit('swal:alert', [
                'tipo'    => 'warning',
                'titulo'  => 'Error',
                'mensaje' => 'Debe Ingresar Rif del Proveedor'
            ]);
        }

    }

    public function buscar_datos(){
        //-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -
        // Funcion para que busca los datos para
        //-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -
        // $rif_prov     = $this->rif_prov;
        // $num_fac      = $this->num_fac;
        // $ano_pro      = $this->ano_pro;
        // $recibo       = $this->recibo;
        // $ano_pro      = $this->ano_pro;

        if (!Empty($this->recibo)) {
            if (!Empty($this->rif_prov)) {
                if (!Empty($this->num_fac)) {
                    if (!Empty($this->ano_pro)) {
                        // seleccion
                        $queryProv = Beneficiario::where('rif_ben',$this->rif_prov)
                                   ->select('rif_ben','nom_ben')
                                   ->first();

                        if ( $queryProv) {
                            // -----------------------------------------
                            // Valida que la factura este recepcionada
                            // -------------------------------------------
                            $facrecepfactura = FacRecepFactura::with('opsolservicio:ano_pro,provision')
                                                                ->where('op.ano_pro',$this->ano_pro)
                                                                ->where('fac_recepfacturas.rif_prov',$this->rif_prov)
                                                                ->where('num_fac',$this->num_fac)
                                                                ->where('recibo',$this->recibo)
                                                                ->join('op_solservicio as op', function($q){
                                                                        $q->on('op.xnro_sol','fac_recepfacturas.nro_doc')
                                                                        ->where('op.ano_pro', DB::raw("ano_sol"));
                                                                })
                                                                ->selectRaw("fac_recepfacturas.rif_prov, num_fac, fec_fac,tipo_doc, nro_doc,fac_recepfacturas.sta_fac,
                                                                            case  when substr(nro_doc,0,3)='PD' then op.ano_pro else fac_recepfacturas.ano_pro end ,
                                                                            fac_recepfacturas.ano_sol,case  when substr(nro_doc,0,3)='PD' then op.provision else 'N' end ,recibo ")
                                                                ->first();

                                if ($facrecepfactura) {

                                    $this->ano_solicitud_ordenes = $facrecepfactura->ano_pro;
                                    $this->provisionada = $facrecepfactura->ano_sol;

                                    // -----------------------------------------------------------------------
                                    // Verifica el status de la factura en la tabla
                                    // de recepcion de factura
                                    // para validar que la factura no este devuelta
                                    // o entregada
                                    // ------------------------------------------------------------------------

                                    $prueba = $this->Validar_status('N',$facrecepfactura->sta_fac, $facrecepfactura->fec_fac);
                                    //dd($facrecepfactura->prueba);
                                    if ($this->Validar_status('N',$facrecepfactura->sta_fac, $facrecepfactura->fec_fac)) {
                                        // -----------------------------------------------------------------------
                                        // Valida que la Factura Venga por orden de
                                        // Compra O Pagos Directos
                                        // -----------------------------------------------------------------------
                                        if ($facrecepfactura->tipo_doc < 6) {
                                            // ----------------------------------------------------------
                                            // Valida que la factura no este
                                            // ingresada en el sistema
                                            // ----------------------------------------------------------
                                            $ingresada = Factura::where('ano_pro', $facrecepfactura->ano_pro)
                                                                ->where('rif_prov',$facrecepfactura->rif_prov)
                                                                ->where('num_fac', $facrecepfactura->num_fac)
                                                                ->where('recibo',  $facrecepfactura->recibo)
                                                                ->selectRaw("DISTINCT sta_fac, TO_CHAR(fecha,'dd/mm/yyyy') AS fecha,nro_doc,tipo_doc ")
                                                                ->first();

                                            if ($ingresada){
                                                switch ($ingresada->tipo_doc) {
                                                    case "1":
                                                        $descrip = "La Orden de Compra Nro: ";
                                                        break;
                                                    case "2":
                                                            $descrip = "La Orden de Servicios Nro: ";
                                                        break;
                                                    case "3":
                                                            $descrip = "La Orden de Servicios Nro: ";
                                                        break;
                                                    case "4":
                                                            $descrip = "La Certificacion de Servicios: ";
                                                        break;
                                                    case "5":
                                                            $descrip = "El Contrato Nro: ";
                                                        break;
                                                }
                                                alert()->error('Factura ya esta Ingresada en Sistema el '.$ingresada->fecha. '  Para Pagar '. $descrip. $facrecepfactura->nro_doc. '. Por favor verifique.');

                                                return redirect()->back()->withInput();

                                            } else {
                                                // Validar_Flujo(     row_recep[0],      row_recep[3],      row_recep[4],       row_recep[7],      num_fac,           row_recep[2],      row_recep[5],          row_recep[9]);
                                                $this->Validar_Flujo($facrecepfactura->rif_prov,$facrecepfactura->tipo_doc, $facrecepfactura->nro_doc, $facrecepfactura->ano_sol, $facrecepfactura->num_fac, $facrecepfactura->fec_fac, $facrecepfactura->statu_recep, $facrecepfactura->recibo);
                                            }

                                        }else{
                                            alert()->error('Error la Factura no viene por Orden de Compra, Actas de Servicios,Contrato o Certificacion de Servicios. Favor Verifique');

                                            return redirect()->back()->withInput();
                                        }
                                    }
                                } else {
                                    alert()->error('Factura aun no ha sido Recepcionada. Por favor verifique.');

                                    return redirect()->back()->withInput();
                                }
                        } else {
                            alert()->error('Rif de Proveedor No Existe. Favor Verifique');
                            $this->rif_prov = '';
                            $this->lrif_prov = '';

                            return redirect()->back()->withInput();
                        }
                    } else {
                        alert()->error('Debe Ingresar Ano del Proceso. Favor Verifique');

                        return redirect()->back()->withInput();
                    }
                } else {
                    alert()->error('Debe Ingresar Numero del Proceso. Favor Verifique');
                    $this->num_fac = '';

                    return redirect()->back()->withInput();
                }
            } else {
                alert()->error('Debe Ingresar Rif del Proveedor. Favor Verifique');
                $this->rif_prov = '';

                return redirect()->back()->withInput();
            }
        } else {
            alert()->error('Debe Ingresar Recibo.\nFavor Verifique');
            $this->lrecibo = '';

            return redirect()->back()->withInput();
        }
    }

    // -----------------------------------------------------------------------------------------
    // Funcion que valida si se puede recepcionar o aplicar cualquier accion
    // dependiendo de
    // los diferentes status de la factura
    // ------------------------------------------------------------------------------------------
    function Validar_status($accion, $valor, $fecha) {
        $rif_prov     = $this->rif_prov;
        $num_fac      = $this->num_fac;
        //dd($fecha);
        if ($fecha)
         //   dd(substr($fecha,0,4));
            $fecha_cau =  substr($fecha,0,4);
         $ano_fiscal = $this->ano_pro;
//dd($valor);
        switch ($accion) {
            // Nuevo
            case "N":
                if ($valor == '0') {
                    return true;
                } else {

                    $estado = $this->descrip_statu($valor);

                    //dd($estado);
                    alert()->error('Factura con '. $estado. ' Por favor verifique');
                    return false;
                    //return redirect()->back()->withInput();
                }
                // Reversar
            case "R":
                {
                    if ($valor == '1') {
                        if ($this->ano_fiscal != $fecha_cau) {
                            alert()->error('No se puede Reversar un Causado diferente al año Fiscal. Por favor verifique.');

                            return redirect()->back()->withInput();
                        } else {
                            return true;
                        }
                    } else {
                        if ($valor == '4') {
                            if ( $this->ano_fiscal !=  $this->fecha_cau) {
                                alert()->error('No se puede Reversar un Causado diferente al año Fiscal. Por favor verifique.');

                                return redirect()->back()->withInput();
                            } else {
                                return true;
                            }
                        } else {
                            $estado = $this->descripcion_statu_factura($valor);
                            alert()->error('Factura con Status Invalido.('.$estado.'). Por favor verifique.');

                            return redirect()->back()->withInput();
                        }
                    }
                }
                // Eliminar
            case "B":
                {
                    if ($valor == '0') {
                        return true;
                    } else {
                        if ($valor == '2') {
                            return true;
                        } else {
                            $estado = $this->descripcion_statu_factura($valor);
                            alert()->error('Factura con Status Invalido1.('.$estado.'). Por favor verifique.');

                            return redirect()->back()->withInput();
                        }
                    }

                }
                // Modificar Asiento
            case "X":
                {
                    if ($valor == '1') {
                        return true;
                    } else {
                        if ($valor == '2') {
                            return true;
                        } else {
                            $estado = $this->descripcion_statu_factura($valor);
                            $this->flag = true;
                            alert()->error('Factura con Status Invalido.('.$estado.'). Para poder Modificar el Asiento debe estar solo Aprobada, Por favor verifique.');

                            return redirect()->back()->withInput();
                        }
                    }

                }
                // Ver
            case "V":
                {
                    return true;

                }
                // Modificar o Aprobar
            default:
                {
                    if ($valor == '0') {
                        return true;
                    } else {
                        if ($valor == '2') {
                            return true;
                        } else {
                            $estado = $this->descripcion_statu_factura($valor);
                            alert()->error('Factura con Status Invalido3.('.$estado.'). Por favor verifique.');

                            return redirect()->back()->withInput();
                        }
                    }
                }
        }
    }
    // ------------------------------------------------------
    // Función que coloca las descripcion del status
    // ------------------------------------------------------
    function descrip_statu($valor) {
        $descrip_estado = "";
        if (!Empty($valor)) {
            switch ($valor) {
                case "0":
                    $descrip_estado = "Recepcionada";;
                    break;
                case "1":
                    $descrip_estado = "Expediente Registrado en Control del Gasto";;
                    break;
                case "2":
                    $descrip_estado = "Expediente Devuelto";;
                    break;
                case "3":
                    $descrip_estado = "Expediente Entregado";;
                    break;
            }
            return $descrip_estado;
        }
        return $descrip_estado;
    }



    public function render()
    {
        return view('livewire.administrativo.meru-administrativo.cuentas-por-pagar.proceso.factura-create',[
            'proveedores' => Proveedor::where('cod_edo','1')
                                    ->select('rif_prov','nom_prov')
                                    ->orderBy('nom_prov')
                                    ->get(),
            'cxptipodocumento' => CxPTipoDocumento::query()
                    ->where('status','1')
                    ->where('recp_factura','1')
                    ->get(),
            'ano_pro' => RegistroControl::periodoActual()
       ]);
    }

    // ------------------------------------------------------------------
    // Validar que la certificacion de servcios
    // realmente exista y que esten en el status correcto
    // presupuestariasmente comprometida
    // ------------------------------------------------------------------
    function Validar_Flujo($rif_proveedor, $tipo_doc, $nro_doc, $ano_sol, $num_fac, $fecha_fac, $statu_recep, $recib) {
        $bandera = true;
        $existen_facturas_ingresadas = false;
        $sq_datos_opsol_original = [];
        //$rif_prov = $("#rif_prov").val();
        $rec_val = $this->recibo;

        switch ($tipo_doc) {
            case "1":
                $descripcion = "Orden de Compra No tiene Notas de Entregas con status Valido.\n Por favor verifique.";
                $descrip_doc = "Orden de Compra";
                break;
            case "2":
                $descripcion = "Orden de Compra No tiene Actas de Servicios con status Valido.\n Por favor verifique.";
                $descrip_doc = "Orden de Compra";
                break;
            case "3":
                $descripcion = "Orden de Compra No tiene Actas de Servicios con status Valido.\n Por favor verifique.";
                $descrip_doc = "Orden de Compra";
                break;
            case "4":
                $descripcion = "Certificacion de Servicios NO Existe en el Sistema o Tiene Status Invalido.\nPor favor verifique.'";
                $descrip_doc = "Certificación se Servicios";
                break;
            case "5":
                $descripcion = "Contrato de Obras No tiene Actas de Servicios con status Valido.\n Por favor verifique.";
                $descrip_doc = "Contrato de Obra";
                break;
        }

        // --------------------------------------------------------------------
        // Buscar Los Datos del documento Para Garantizar
        // que realmente exista y este comprometida presupuestariamente
        // ----------------------------------------------------------------------

        //scriptPHP = "utilsPHP/selectInDB.Script.php"; // Scrip generico de seleccion
        switch ($tipo_doc) {
            case "1":
            case "2":
            case "3":
            case "5":

            $queryopsol = CxPCabeceraFactura::query()
                                            ->where('tipo_doc',$tipo_doc)
                                            ->where('ano_doc',$ano_sol)
                                            ->where('nro_doc',$nro_doc)
                                            ->where('tipo_pago','T')
                                            ->whereIn('statu_proceso',['1','4'])
                                            ->whereNull('cxp_cabecera_facturas.pago_manual')
                                            ->selectRaw("cxp_cabecera_facturas.rif_prov, cxp_cabecera_facturas.pago_manual, tipo_pago,'S' AS factura,cxp_cabecera_facturas.por_anticipo, monto_anticipo ,
                                                        SUM(monto_amortizacion) AS monto_amortizacion ,SUM(mto_iva) AS mto_iva, porcentaje_iva,
                                                        SUM(mto_nto) AS mto_nto, SUM(mto_tot) AS mto_tot,SUM(base_imponible) AS base_imponible,
                                                        SUM(base_excenta) AS base_excenta, cxp_cabecera_facturas.fondo,cxp_cabecera_facturas.cuenta_contable, tipo_doc,nro_doc,ano_doc,
                                                        monto_neto_doc,'N' AS deposito_garantia,'N' AS provision, '$rec_val' AS recibo")
                                            ->groupBy('cxp_cabecera_facturas.rif_prov','cxp_cabecera_facturas.pago_manual','tipo_pago','factura','cxp_cabecera_facturas.por_anticipo','monto_anticipo','porcentaje_iva',
                                                    'cxp_cabecera_facturas.fondo','cxp_cabecera_facturas.cuenta_contable','tipo_doc','nro_doc','ano_doc','monto_neto_doc','deposito_garantia')
                                            ->get();
                break;
            case "4":
                $queryopsol = CxPCabeceraFactura::query()
                                                ->where('tipo_doc',$tipo_doc)
                                                ->where('ano_doc',$ano_sol)
                                                ->where('nro_doc',$nro_doc)
                                                ->whereIn('statu_proceso',['1','4','3'])
                                                ->whereNull('cxp_cabecera_facturas.pago_manual')
                                                ->join('tes_beneficiarios as b', function($q){
                                                    $q->on('b.rif_ben','cxp_cabecera_facturas.rif_prov');
                                                 })
                                                ->join('op_solservicio as op', function($q){
                                                    $q->on('op.xnro_sol','cxp_cabecera_facturas.nro_doc')
                                                    ->where('op.ano_pro', DB::raw("cxp_cabecera_facturas.ano_pro"));
                                                 })
                                                ->selectRaw("cxp_cabecera_facturas.rif_prov, b.nom_ben,cxp_cabecera_facturas.pago_manual, tipo_pago,'S' AS factura,cxp_cabecera_facturas.por_anticipo, monto_anticipo ,
                                                             SUM(monto_amortizacion) AS monto_amortizacion ,SUM(mto_iva) AS mto_iva, porcentaje_iva,
                                                             SUM(mto_nto) AS mto_nto, SUM(mto_tot) AS mto_tot,SUM(cxp_cabecera_facturas.base_imponible) AS base_imponible,
                                                             SUM(base_excenta) AS base_excenta, cxp_cabecera_facturas.fondo,cxp_cabecera_facturas.cuenta_contable, tipo_doc,nro_doc,ano_doc,
                                                             monto_neto_doc, cxp_cabecera_facturas.deposito_garantia, provision, '$rec_val' AS recibo")
                                                ->groupBy('cxp_cabecera_facturas.rif_prov','b.nom_ben','cxp_cabecera_facturas.pago_manual','tipo_pago','factura','cxp_cabecera_facturas.por_anticipo','monto_anticipo','porcentaje_iva',
                                                'cxp_cabecera_facturas.fondo','cxp_cabecera_facturas.cuenta_contable','tipo_doc','nro_doc','ano_doc','monto_neto_doc','cxp_cabecera_facturas.deposito_garantia','provision')
                                                ->get();
                break;
        }
        if ($queryopsol != null)
            foreach($queryopsol as $sq_datos_opsol){
                // Validar que Exista la Certificacion
                if(!Empty($sq_datos_opsol->rif_prov)){
                    switch ($tipo_doc) {
                        case "1":
                            $mensaje_Error = "Notas de Entregas";
                            break;
                        case "2":
                            $mensaje_Error = "Actas de Aceptacion de Servicios";
                            break;
                        case "3":
                            $mensaje_Error = "Actas de Aceptacion de Servicios";
                            break;
                        case "5":
                            $mensaje_Error = "Actas de Aceptacion de Servicios";
                            break;
                }
                //-------------------------------------------------------------------------------------------------
                // Validar que el proveedor seleccionado en la recepcion sea el mismo asignado en la certificacion
                //-------------------------------------------------------------------------------------------------
                if ($sq_datos_opsol->rif_prov == $rif_proveedor) {
                        // ------------------------------------------------------------------------------------
                        // Validar que no este cancelada el monto total de la orden de
                        // compra o certificacion
                        // ------------------------------------------------------------------------------------
                        $Monto_Ordenes = $sq_datos_opsol->deposito_garantia;
                        $Monto_Orden = $Monto_Ordenes;

                        $queryProv = Factura::where('rif_prov',$rif_proveedor)
                                            ->where('ano_sol',$ano_sol)
                                            ->where('nro_doc',$nro_doc)
                                            ->where('recibo',$recib)
                                            ->selectRaw("CASE WHEN SUM(mto_nto) = null THEN 0 ELSE SUM(mto_nto) END AS sum, provisionada")
                                            ->groupBy('provisionada')
                                            ->get();
                        foreach($queryProv as $sq_datos_montos){

                            if ($sq_datos_montos->sum > 0) {
                                $Monto_Recepcionado = $sq_datos_montos->sum ;
                                $provisionado = $sq_datos_montos->provisionada;
                                if ($Monto_Recepcionado >= $Monto_Orden) {
                                    alert()->error('Ya ha sido Recepcionado el Monto Total de la'.$descrip_doc. '   Por favor verifique. ');
                                    $bandera = false;

                                    return redirect()->back()->withInput();
                                } else {
                                    // Si se define que es pago total y ya esxiste una
                                    // factura ingresada en sistema no debe permitir
                                    // ingresar otra factura
                                    if ($tipo_doc == '4' &&  $sq_datos_opsol->factura == 'T') {
                                        $bandera = false;
                                        alert()->error('El tipo de Pago de la Certificacion es total  y ya tiene una factura ingresada en sistema Por favor verifique. ');

                                        return redirect()->back()->withInput();
                                    }
                                }
                                $existen_facturas_ingresadas = true;
                            }
                            // ---------------------------------------------------------------------
                            // Buscar los Datos de la Factura
                            // ---------------------------------------------------------------------
                            if ($bandera) {
                                $sq_datos_opsol_original[0]     = $sq_datos_opsol->base_imponible; // Base
                                // Imponible
                                $sq_datos_opsol_original[1]     = $sq_datos_opsol->base_excenta; // Base
                                // Exenta
                                $sq_datos_opsol_original[2]     = $sq_datos_opsol->mto_nto; // Monto
                                // Neto
                                $sq_datos_opsol_original[3]     = $sq_datos_opsol->mto_iva; // Monto
                                // Iva
                                $sq_datos_opsol_original[4]     = $sq_datos_opsol->mto_tot;

                                if ($tipo_doc != '4') {
                                    // Si viene por Orden de Compra o Contrato LLenar la
                                    // Grilla
                                    $query_nota = CxPCabeceraFactura::where('ano_doc',$ano_sol)
                                                                    ->where('nro_doc',$nro_doc)
                                                                    ->whereIn('statu_proceso',['1','4'])
                                                                    ->whereNull('pago_manual')
                                                                    ->selectRaw("'Si' AS factura, ano_doc_asociado,doc_asociado,nota_entrega_prov,base_imponible,base_excenta,
                                                                                mto_iva,mto_tot")
                                                                    ->get();

                                    if ($query_nota != null){
                                        foreach($query_nota as $row_grip){
                                            $this->asignar_Resultado($sq_datos_opsol, 'N', $fecha_fac, $statu_recep);
                                            // asignar_resultados_calcular_amortizacion(sq_datos_opsol,'N',fecha_fac,statu_recep,'N');
                                            // LLenar La Estructura de Gastos
                                            $this->Llenar_Estructra_Gastos('N', $tipo_doc, $nro_doc, $sq_datos_opsol->tipo_pago, $ano_sol, $rif_proveedor);
                                        }
                                    }else{
                                        alert()->error($descrip_doc.' No tiene '. $mensaje_Error.' Ingresadas en Sistema, Por favor verifique');

                                        return redirect()->back()->withInput();
                                    }


                                }
                                else {
                                    //----------------------------------------------------------------
                                    // Si el proceso viene por pagos Directos y existen
                                    // facturas ya ingresadas
                                    // Recalcular los montos con los acumulados de las
                                    // facturas ya ingresadas
                                    if ($existen_facturas_ingresadas) {
                                        $query_Montos =Factura::where('rif_prov',$rif_proveedor)
                                                            ->where('nro_doc', $nro_doc)
                                                            ->where('recibo',$recib)
                                                            ->where('ano_sol', $ano_sol)
                                                            ->selectRaw("CASE WHEN SUM(base_imponible) ISNULL THEN 0 ELSE SUM(base_imponible) END AS base_imponible,
                                                                        CASE WHEN SUM(base_excenta) ISNULL THEN 0 ELSE SUM(base_excenta) END AS base_excenta,
                                                                        CASE WHEN SUM(mto_nto) ISNULL THEN 0 ELSE SUM(mto_nto) END AS mto_nto,
                                                                        CASE WHEN SUM(mto_iva) ISNULL THEN 0 ELSE SUM(mto_iva) END AS mto_iva,
                                                                        CASE WHEN SUM(mto_fac) ISNULL THEN 0 ELSE SUM(mto_fac) END AS mto_fac")
                                                            ->first();
//dd($rif_proveedor.'/'.$nro_doc.'/'.$recib.'/'.$ano_sol);
                                            if ($query_Montos != null) {
                                                // ----------------------------------------------------
                                                // Asignar nuevamente los montos
                                                // ----------------------------------------------------
                                                $sq_datos_opsol_original[0] = round($sq_datos_opsol->base_imponible - $query_Montos[0]->base_imponible, 2); // Base
                                                // Imponible
                                                $sq_datos_opsol_original[1] = round($sq_datos_opsol->base_excenta - $query_Montos[0]->base_excenta, 2); // Base
                                                // Exenta
                                                $sq_datos_opsol_original[2] = round($sq_datos_opsol->mto_nto - $query_Montos[0]->mto_nto, 2); // Monto
                                                // Neto
                                                $sq_datos_opsol_original[3] = round($sq_datos_opsol->mto_iva - $query_Montos[0]->mto_iva, 2); // Monto
                                                // Iva
                                                $sq_datos_opsol_original[4] = round($sq_datos_opsol->mto_tot - $query_Montos[0]->mto_fac, 2); // Monto
                                                // Iva round($nume
                                                // //Total
                                                // Factura
                                            }else {
                                                $sq_datos_opsol_original[0] = $sq_datos_opsol->base_imponible; // Base
                                                // Imponible
                                                $sq_datos_opsol_original[1] = $sq_datos_opsol->base_excenta; // Base
                                                // Exenta
                                                $sq_datos_opsol_original[2] = $sq_datos_opsol->mto_nto; // Monto
                                                // Neto
                                                $sq_datos_opsol_original[3] = $sq_datos_opsol->mto_iva; // Monto
                                                // Iva
                                                $sq_datos_opsol_original[4] = $sq_datos_opsol->mto_tot;
                                            }

                                            $this->asignar_resultados_calcular_amortizacion($sq_datos_opsol, 'N', $fecha_fac, $statu_recep, 'N');
                                            // LLenar La Estructura de Gastos
                                            $this->Llenar_Estructra_Gastos('N', $tipo_doc, $nro_doc, $sq_datos_opsol->tipo_pago, $ano_sol, $rif_proveedor);
                                    } else {
                                        $this->asignar_resultados_calcular_amortizacion($sq_datos_opsol, 'N', $fecha_fac, $statu_recep, 'N');
                                        // LLenar La Estructura de Gastos
                                        $this->Llenar_Estructra_Gastos('N', $tipo_doc, $nro_doc, $sq_datos_opsol->tipo_pago, $ano_sol, $rif_proveedor);
                                    }
                                }
                            }
                        }
                }else {
                    alert()->error('Error el Proveedor Seleccionado No esta Asociado a la ['.$descrip_doc.'] Favor Verifique la Recepcion de la Factura..' );

                    return redirect()->back()->withInput();
                }
            }//foreac
        }else {
            alert()->error($descripcion);

            return redirect()->back()->withInput();
        }
    }
    // ------------------------------------------------------------------------
    // Funcion que valida los datos al momento de ingresar la factura
    // ------------------------------------------------------------------------
    function Validar_Datos_Ingresar() {

        $this->accion = $this->accion;
        $porcentaje_iva = $this->por_iva;// Formatear_Campo($("#porcentaje_iva").val());
        $sw = false;
        // -------------------------------------------------------------------------------------
        // Validar que si existe partida de Iva entonces el % de iva debe ser
        // distincto de 0 en caso contrario el % de iva debe ser 0
        // -------------------------------------------------------------------------------------
        $detgastosfactura =$this->facrecepfactura->opsolservicio->opdetgastossolservicio;


        foreach($detgastosfactura as $index => $gastos){
            // Verifica si existe Partida de Iva
            if ($gastos['gasto'] != 1){
                $sw = true;
            }
        }


        // Existe Partida de Iva
        if ($sw) {
            if ( $this->por_iva != 0) {
                $bandera = true;
            } else {
                $bandera = false;
                alert()->error('Error el % de Iva no puede ser 0. Favor Verifque');
            }
        } else { // No Existe partida de IVA el % debe ser 0
            if ( $this->por_iva == 0) {
                $bandera = true;
            } else {
                $bandera = false;
                alert()->error('Error el % de Iva debe  ser 0. Favor Verifque');
            }
        }

        if ($bandera) {
            return  $this->Validar_Datos_Ingresar_Flujo($this->accion);
        } else {
            return false;
        }

    }

    // ------------------------------------------------------------------------
    // Funcion que valida los datos cuando el flujo viene por pagos directos
    // ------------------------------------------------------------------------
    function Validar_Datos_Ingresar_Flujo() {
        $this->accion = $this->accion;
        // num_ctrl = $("#num_ctrl").val();
        // deposito_garantia = $("#deposito_garantia").val();
        // mto_nto = $("#mto_nto").val();
        // recibo = $("#recibo").val();
        // tipo_doc = $("#tipo_doc").val();
        // fondo = $("#fondo").val();
        // cuenta_contable = $("#cuenta_contable").val();
        if (!Empty($this->fondo)) {
            if (!Empty($this->num_ctrl)) {
                if (!Empty($this->deposito_garantia)) {
                    if (!Empty($this->recibo)) {
                        if ($this->mto_nto != '0,00') {
                            if ( $this->validar_Estructura_Gasto()) { //elano
                                if ( $this->Validar_Montos_Nota_Amortizacion()) { //elano
                                    if ($this->tipo_doc != '4') {
                                        if ( $this->verificar()) {
                                            if ($this->fondo == 'E') {
                                                if (!Empty($this->cuenta_contable) || $this->cuenta_contable > 0) {

                                                    $query_iva = ConCuentasAporte::where('cta_con',$this->cuenta_contable)
                                                                                 ->select('cta_con')
                                                                                 ->first();

                                                    if (!$query_iva) {
                                                        alert()->error('Cuenta Contable No Existe.\nFavor Verifique');
                                                        $this->cuenta_contable ='';
                                                     } //else {
                                                    //     grabarEdit('N');
                                                    // }

                                                } else {
                                                    alert()->error('Debe Ingresar Cuenta Contable.\nFavor Verifique');
                                                }
                                            } //else {
                                              //  grabarEdit(accion);
                                            //}
                                        }
                                    } else {
                                        if ( $this->fondo == 'E') {
                                            if (!Empty($this->cuenta_contable) || $this->cuenta_contable > 0) {

                                                $query_iva = ConCuentasAporte::where('cta_con',$this->cuenta_contable)
                                                                            ->select('cta_con')
                                                                            ->first();


                                                if (!$query_iva) {
                                                    alert()->error('Cuenta Contable No Existe.\nFavor Verifique');
                                                    $this->cuenta_contable = '';

                                                 }// else {
                                                    //     grabarEdit(accion);
                                                    // }
                                            } else {
                                                alert()->error('Debe Ingresar Cuenta Contable.\nFavor Verifique');

                                            }
                                        } //else {
                                        //     grabarEdit(accion);
                                        // }

                                    }
                                }
                            }

                        } else {
                            alert()->error('Error el Monto Neto de la Factura no puede ser 0 . Por favor verifique.');

                        }
                    } else {
                        alert()->error('Debe Ingresar Tipo de Documento (Factura o Recibo). Por favor verifique.');


                    }
                } else {
                    alert()->error('Debe Ingresar Deposito en Garantia.\nPor favor verifique.');

                    $detgastosfactura =$this->facrecepfactura->opsolservicio->opdetgastossolservicio;


                    foreach($detgastosfactura as $index => $gastos){
                        // Verifica si existe Partida de Iva
                        if ($gastos['gasto'] != 1){
                            $sw = true;
                        }
                    }

                }

            } else {
                alert()->error("Debe Ingresar Numero de Control de la Factura .\nPor favor verifique.");


            }
        } else {
            alert()->error("Debe Ingresar tipo de Fondo .\nPor favor verifique.");

        }
    }


    // -------------------------------------------------------------------------------------------
    // Fucion que valida la Estructua de Gasto al momento de ingresar una factura
    // parcial
    // -------------------------------------------------------------------------------------------
    function validar_Estructura_Gasto() { //elano
        // gride = Utils.grids[1]; // Instanciar Grilla de Datos de Usuarios
        // data = gride.getData();
        $monto_neto = 0;
        $monto_iva = 0;
        $rango = 0.99;

        $detgastosfactura =$this->facrecepfactura->opsolservicio->opdetgastossolservicio;

        // ----------------------------------------------------------------------
        // Partida Presupuestaria de IVA 2 1 0 0 0 3 18 1 0
        // ------------------------------------------------------------------------
        foreach($detgastosfactura as $index => $gastos){
            // Verifica si existe Partida de Iva
            if ($gastos['gasto'] != 1){
                $monto_iva = $monto_iva + $gastos['mto_tra'];
            } else {
                $monto_neto = $monto_neto + $gastos['sal_cau'];

            }
        }

        $monto_neto = round($monto_neto, 2);
        //mto_nto = $("#mto_nto").val();
        $mto_nto = $this->Formatear_Campo($this->mto_nto);
        //mto_iva = $("#mto_iva").val();
        $mto_iva = $this->Formatear_Campo($this->mto_iva);
        $porcentaje_iva = $this->por_iva;
        //tipo_doc = $("#tipo_doc").val();

        if ($monto_neto != 0) {
            //if ($monto_neto != mto_nto){ //Juanjo
            if (($monto_neto < $mto_nto - $rango) || ($monto_neto > $mto_nto + $rango)) {
                $monto_netox = $monto_neto;
                $mto_ntox = $mto_nto;

                if ( $this->tipo_doc == '4') {
                    if ($monto_neto > $mto_nto + $rango) {
                         alert()->error("Error el Monto Neto de la Estructura de Gastos: " + $monto_netox + "\nes Mayor al Monto Neto de la Factura " + $mto_ntox + ".\nPor favor verifique.");
                        return false;
                    } else if ($monto_neto < $mto_nto - $rango) {
                         alert()->error("Error el Monto Neto de la Estructura de Gastos: " + $monto_netox + "\nes Menor al Monto Neto de la Factura " + $mto_ntox + ".\nPor favor verifique.");
                        return false;
                    }
                } else {
                    // -----------------------------------------------------------------------
                    // Verificar cuando viene por orden de compra
                    // si tiene anticipo la suma del gasto + el monto de la
                    // amortizacion
                    // debe ser igual al monto neto de la factura
                    // ------------------------------------------------------------------------
                    $por_anticipo = $this->por_anticipo;
                    $por_anticipo = $this->Formatear_Campo($por_anticipo);
                    $monto_netox = $monto_neto;
                    $mto_ntox = $mto_nto;

                    if ($por_anticipo != 0) {
                        $mto_amortizacion = $this->mto_amortizacion;
                        $mto_amortizacion = $this->Formatear_Campo( $mto_amortizacion);
                        $monto_estructura = $monto_neto +  $mto_amortizacion;
                        $monto_estructura = round($monto_estructura, 2);

                        //if(monto_estructura != $mto_nto){ //Juanjo
                        if (($monto_estructura < $mto_nto - $rango) || ($monto_estructura > $mto_nto + $rango)) {
                            $monto_estructurax = $monto_estructura;
                            if ($monto_estructura > $mto_nto + $rango) {
                                 alert()->error('Error el Monto Neto de la Estructura de Gastos + el anticipo: '.$monto_estructurax.' es Mayor al Monto Neto de la Factura '.$mto_ntox. ' Por favor verifique.');
                                return false;
                            } else if ($monto_estructura < $mto_nto - $rango) {
                                 alert()->error('Error el Monto Neto de la Estructura de Gastos + el anticipo: '.$monto_estructurax.' es Menor al Monto Neto de la Factura '.$mto_ntox.' Por favor verifique.');
                                return false;
                            }
                        } else
                            return true;
                    } else { // fin del else if(por_anticipo!=0)
                        if ($monto_neto > $mto_nto + $rango) {
                             alert()->error('Error el Monto Neto de la Estructura de Gastos: '.$monto_netox.' es Mayor al Monto Neto de la Factura '.$mto_ntox.' Por favor verifique.');
                            return false;
                        } else if ($monto_neto < $mto_nto - $rango) {
                             alert()->error('Error el Monto Neto de la Estructura de Gastos: '.$monto_netox.' es Menor al Monto Neto de la Factura '.$mto_ntox.' Por favor verifique.');
                            return false;
                        }
                    }
                } // fin de else f (tipo_doc == '4')
            } else {
                if ($monto_iva != 0) {
                    //if ($monto_iva != mto_iva){ //Juanjo
                    if (($monto_iva < $mto_iva - $rango) || ($monto_iva > $mto_iva + $rango)) {
                        $monto_ivax = $monto_iva;
                        $mto_ivax = $mto_iva;
                        if ($monto_iva > $mto_iva + $rango) {
                             alert()->error('Error el Monto Iva de la Estructura de Gastos: '.$monto_ivax.' es Mayor que el Monto Iva de la Factura '. $mto_ivax. 'Por favor verifique.');
                            return false;
                        } else {
                             alert()->error('Error el Monto Iva de la Estructura de Gastos: '.$monto_ivax.' es Menor que el Monto Iva de la Factura '.$mto_ivax.' Por favor verifique.');
                            return false;
                        }
                    } else
                        return true;
                } else {
                    if ($porcentaje_iva != '0,00') {
                         alert()->error('Error. EL Monto Iva a Causar no puede ser 0. Por favor verifique.');
                        return false;
                    } else
                        return true;
                }
            }
        } else {
             alert()->error('Error. El Monto Neto a Causar no puede ser 0. Por favor verifique.');
            return false;
        }
        return true;

    }

    // ------------------------------------------------------------------------
    // Funcion que valida que si existen amortizacion y/o nota de credito
    // la disminución de estos valores no superen al monto de la factura o
    // coloquen los montos de la misma en 0
    // ------------------------------------------------------------------------
    function Validar_Montos_Nota_Amortizacion() {
        $lncr_sn = $this->lncr_sn;
        $mto_amortizacion = $$this->mto_amortizacion;
        $mto_amortizacion =  $this->Formatear_Campo($mto_amortizacion);
        $tot_ncr =$this->tot_ncr;
        $tot_ncredito = $this->Formatear_Campo($tot_ncr);
        $mto_fac = $this->mto_fac;
        $mto_fac =  $this->Formatear_Campo($mto_fac);
        $total_amortizacion = 0;
        // ---------------------------------------------------------------------------------
        // Si la Factura tiene porcentaje de anticipo se debe validar
        // que el Monto Total de la Factura - Menos el Monto de la amortización no
        // sea <=0
        // -----------------------------------------------------------------------------------
        if ($mto_amortizacion != 0) {
            $total_amortizacion = $mto_fac - $mto_amortizacion;
            if ($total_amortizacion < 0) {
                alert()->error('Error, El Monto de la amortización supera al Monto Total de la Factura. Favor Verifique');
                return false;
            }
        }

        // ---------------------------------------------------------------------------------
        // Si la Factura tiene Nota de Creditos asociadas y porcentaje de Anticipo
        // se debe validar que el Monto Total de la Factura - el Monto de la
        // amortización
        // y - el total de la Nota de Creditos no sea <=0
        // -----------------------------------------------------------------------------------
        if (!Empty($lncr_sn)) {
            if ($lncr_sn == 'S') {
                if ( $this->Validar_Datos_NC()) {
                    if ($total_amortizacion != 0) {
                        $resul_final = ($total_amortizacion - $tot_ncredito);
                        if ($resul_final <= 0) {
                            alert()->error('Error, El Monto de la Amortizacion + Nota de Credito  superan al Monto de la Factura. Favor Verifique');
                            return false;
                        }
                    } else {
                        // ---------------------------------------------------------------------------------
                        // Si la Factura tiene Notas de Creditos asociadas
                        // que el Monto Total de la Factura - Menos el MOnto de la
                        // Nota de CRedito no sea <=0
                        // -----------------------------------------------------------------------------------
                        $resul_final = $mto_fac - $tot_ncredito;
                        if ($resul_final <= 0) {
                            alert()->error('Error,El Monto Total de la  Nota de Credito es superior o igual  al Monto Total de la Factura Favor Verifique');
                            return false;
                        }
                    }

                } else {
                    return false;
                }
            }
        }

        return true;

    }

    // ------------------------------------------------------------------------
    // Funcion que valida que todos los datos de la nota de credito esten
    // ingreados correctamente
    // ------------------------------------------------------------------------
    function Validar_Datos_NC() {
        $nro_ncr = $this->nro_ncr;
        $tot_ncr = $this->tot_ncr;
        if (!Empty($nro_ncr)) {
            if ($tot_ncr != '0,00') {
                return true;
            } else {
                 alert()->error('Total de la Nota de Credito no puede ser 0. Por favor verifique.');
                return false;
            }
        } else {
             alert()->error('Debe Ingresar Numero de la Nota de Credito. Por favor verifique.');
            return false;
        }
    }

    // -----------------------------------------------------------------------------
    // Funcion que verifica que al menos se seleccione una nota de entrega
    // para guardar los datos
    // -----------------------------------------------------------------------------
    function verificar() {
        $contador = 0;

        $detgastosfactura =$this->facrecepfactura->opsolservicio->opdetgastossolservicio;

        foreach($detgastosfactura as $index => $gastos){
            // Verifica si existe Partida de Iva
            if ($gastos['gasto'] == 1){
                $contador += 1;
            }
        }

        if ($contador == 0) {
            alert()->error('Debe Seleccionar Al menos una Nota de Entrega y/o Acta de Aceptación de Servicio.\n Verifique');
            return false;
        } else {
            return true;
        }
    }

    // -----------------------------------------------------------------------------------------
    // Funcion que llama a las diferentes funciones diseñadas en php con las
    // disntitas acciones
    // ------------------------------------------------------------------------------------------
    // function grabarEdit($valor) {
    //     $nota_credito = $this->ncr_sn;
    //     $funcion='';

    //     if (valGrilla()) {
    //         switch ($valor) {
    //             case "N":
    //                 if (confirm('Esta Seguro de Ingresar la Factura?')) {
    //                     $funcion = "Ingresar";
    //                     bandera = true;
    //                 } else {
    //                     $bandera = false;
    //                 }

    //                 break;

    //             case "M":
    //                 $funcion = "Modificar";
    //                 if (nota_credito == 'S') {
    //                     // Se debe eliminar primero la nota de credito para
    //                     // luego eliminar la factura
    //                     bandera = false;
    //                     alert('La Factura tiene asociada nota de creditos.\nDebe Anular primero la nota de credito asociada y luego Modificar la factura\nPor favor verifique.');
    //                 } else {
    //                     bandera = true;
    //                 }
    //                 break;
    //             case "E":
    //                 $funcion = "Eliminar";
    //                 if (nota_credito == 'S') {
    //                     // Se debe eliminar primero la nota de credito para
    //                     // luego eliminar la factura
    //                     bandera = false;
    //                     alert('La Factura tiene asociada nota de creditos.\nDebe Anular primero la nota de credito asociada y luego la factura\nPor favor verifique.');
    //                 } else {
    //                     bandera = true;
    //                 }
    //                 break;

    //             case "A":
    //                 $funcion = "Aprobar";
    //                 bandera = true;
    //                 break;
    //             case "R":
    //                 $funcion = "Reversar";
    //                 bandera = true;
    //                 break;
    //             case "X":
    //                 $funcion = "Modificar_Asiento";
    //                 bandera = true;
    //                 break;
    //         }

    //         if ($bandera) {
    //             xajax_multipleLlamado(Utils.frm.tablaPpal, Utils.frm.carpetaModulo, Utils.frm.tablaMaestra, funcion, Utils.datosDetalle, Utils.frm.tablasDetalle);
    //             Utils.__setBotoneraForma(Utils.BOTONERA, true, false);
    //             $("#" + Utils.forma).clearForm(); // Limpiar campos de entrada en
    //             // forma
    //             Utils.__clearGrids();
    //             Utils.__enableForma(Utils.forma, false, false); // Inactivar campos
    //             // de Entrada
    //             Utils.setdual0();
    //             $("#sta_fac").html("");
    //             Utils.tab.verTab('tab0');
    //         } else {
    //             cancelar();
    //         }

    //     }
    // }

    // ----------------------------------------------------------------------------------
    // Funcion que formatea los diferentes campos para poder realizar calculos
    // -----------------------------------------------------------------------------------
    function Formatear_Campo($valor) {

        //$valor = Utils.__unFormatNumber(valor, Utils.PUNTODECIMAL);
        $valor = (Empty($valor)) ? "0" : $valor;
        //if ($valor.indexOf(",") != -1) {
            $valor = str_replace($valor,",", ".");
        // };
        $valor = (Empty($valor)) ? 0 : floatval($valor);
        return $valor;
    }
    // -----------------------------------------------------------------------
    // Funcion para que busca los datos para las facturas recepcionadas
    // -----------------------------------------------------------------------
    function buscar_datos_acciones($accion) {
        //dd($this->rif_prov);
        // rif_prov = $("#rif_prov").val();
        // num_fac = $("#num_fac").val();
        // ano_pro = $("#ano_pro").val();
        // recibo = $("#recibo").val();
        // ano_pro = $.trim(ano_pro);
        $sq_datos_opsol=[];
        $row=[];
        $mensaje_Error='';
        $query_result='';
        // -----------------------------------------
        // Valida que la factura este recepcionada
        // -------------------------------------------
        $query= FacRecepFactura::where('rif_prov',$this->rif_prov)
                               ->where('num_fac', $this->num_fac)
                               ->where('ano_pro',$this->ano_pro)
                               ->where('recibo',$this->recibo)
                               ->where('tipo_doc','<','6')
                               ->where('sta_fac','!=','2')
                               ->selectRaw("DISTINCT rif_prov, num_fac, fec_fac,tipo_doc, nro_doc,sta_fac,ano_pro,ano_sol")
                               ->first();

        $ano_solicitud_ordenes = $query->ano_sol;

        if ($accion == 'V')
            $sta_rep = '';
        else
            $sta_rep = ' AND sta_rep = 1';

        // -----------------------------------------------------
        // Buscar el status de la factura en la tabla factura
        // -----------------------------------------------------
        $query_status =Factura::where('rif_prov',    $this->rif_prov)
                              ->where('num_fac',   $this->num_fac)
                              ->where('nro_doc',   $query->nro_doc)
                              ->where('recibo',    $this->recibo)
                              ->where('ano_pro',   $this->ano_pro)
                              ->where('ano_sol',   $ano_solicitud_ordenes);

        if ($accion == 'V')
            $query_status =  $query_status->select('sta_fac','num_ctrl','fec_apr')
                                   ->first();
        else
             $query_status =  $query_status->where('sta_rep',   '1')
                                    ->select('sta_fac','num_ctrl','fec_apr')
                                    ->first();


        $this->num_ctr= $query_status->num_ctrl;
        // -----------------------------------------------------------------------
        // Verifica el status de la factura en la tabla de recepcion
        // de factura
        // para validar que la factura no este devuelta o entregada
        // ------------------------------------------------------------------------
        if ($this->Validar_status($accion,  $query_status->sta_fac,  $query_status->fec_apr)) {
            // ------------------------------------------------------------------------------------
            // Buscar los datos de la Factura, pasandole como parametros
            // accion,tipo_doc,nro_doc
            // -------------------------------------------------------------------------------------


            $query_result = Factura::query()
                                   ->where('rif_prov',    $query->rif_prov)
                                   ->where('num_fac',   $this->num_fac)
                                   ->where('nro_doc',   $query->nro_doc)
                                   ->where('recibo',    $this->recibo)
                                   ->where('ano_pro',   $this->ano_pro)
                                   ->where('ano_sol',   $ano_solicitud_ordenes)
                                   ->join('tes_beneficiarios as b', function($q){
                                       $q->on('b.rif_ben','facturas.rif_prov');
                                   })
                                   ->selectRaw("rif_prov,b.nom_ben,sta_fac,tipo_pago,'S' AS factura,por_anticipo,mto_anticipo,mto_amortizacion,
                                                mto_iva,porcentaje_iva,mto_nto,mto_fac,base_imponible, base_excenta,fondo,cuenta_contable,tipo_doc,nro_doc,
                                                ncr_sn,nro_ncr,mto_ncr,iva_ncr,tot_ncr,num_ctrl,num_nc,recibo,TO_CHAR(facturas.fecha,'dd/mm/yyyy'),ano_sol,deposito_garantia,provisionada")
                                   ->first();


            if ( $query->tipo_doc != '4') {

                switch ($query->tipo_doc) {
                    case "1":
                        $mensaje_Error = "Notas de Entregas";
                        break;
                    case "2":
                        $mensaje_Error = "Actas de Aceptacion de Servicios";
                        break;
                    case "3":
                        $mensaje_Error = "Actas de Aceptacion de Servicios";
                        break;
                    case "5":
                        $mensaje_Error = "Actas de Aceptacion de Servicios";
                        break;
                }
                // ------------------------------------------------------
                // LLenar La Grilla de las Notas de
                // Entrega
                // ------------------------------------------------------
                if ($accion == 'M') {

                    $query_nota = CxPDetNotaFactura::query()
                                                   ->where('ano_pro',   $this->ano_pro)
                                                   ->where('rif_prov', $query->rif_prov)
                                                   ->where('num_fac',   $this->num_fac)
                                                   ->selectRaw(" 'Si' AS factura,ano_nota_entrega,grupo||'-'||nro_ent  as doc_aosiado,nota_entrega,base_imponible,base_excenta,mto_iva,mto_ord");


                    $query_cab = CxPCabeceraFactura::query()
                                                   ->where('ano_doc',   $ano_solicitud_ordenes)
                                                   ->where('nro_doc',   $query->nro_doc)
                                                   ->whereIn('statu_proceso',  ['1','4'])
                                                   ->whereNull('pago_manual')
                                                   ->selectRaw("'No' AS factura, ano_doc_asociado,doc_asociado,nota_entrega_prov,base_imponible,base_excenta,mto_iva,mto_tot")
                                                   ->union($query_nota)
                                                   ->get();

                    $query_nota = $query_cab;


                } else {
                    $query_nota = CxPDetNotaFactura::query()
                                                   ->where('ano_pro',   $this->ano_pro)
                                                   ->where('rif_prov',  $query->rif_prov)
                                                   ->where('num_fac',   $this->num_fac)
                                                   ->selectRaw("'Si' AS factura,ano_nota_entrega,grupo||'-'||nro_ent as doc_asociado,nota_entrega,base_imponible,base_excenta,mto_iva,mto_ord")
                                                   ->first();

                }
                if($query_nota){
                    //modificar grid falta revisar
                    foreach($query_nota as $row)
                        $this->mto_amortizacion =$query_result->mto_amortizacion;
                        $this->Asignar_Resultado($query_result, $accion, $query->fec_fac, $query->sta_fac);
                        $this->Llenar_Estructra_Gastos_acciones($accion, $query->tipo_doc, $query_result->tipo_pago, $this->ano_pro);
                }else{
                    alert()->error('Error Buscando '.$mensaje_Error.' Consulte a su Administrador de Sistema');
                }
            } else {
                // ------------------------------------------------------
                // Habilitar campos
                // ------------------------------------------------------

                if ( $query_result->tipo_pago == 'P') {
                    if ($accion == 'M') {
                        $this->emit('enableCambiarNotasfactura');
                    }
                    //-------------------------------------------------------------------------------
                    // Si el proceso viene por  pagos Directos  Recalcular los montos con
                    // los acumulados de las facturas ya ingresadas
                    //-------------------------------------------------------------------------------

                    $query_Montos = Factura::query()
                                           ->where('rif_prov',      $this->rif_prov)
                                           ->where('ano_sol',       $ano_solicitud_ordenes)
                                           ->where('nro_doc',       $query->nro_doc)
                                           ->where('num_fac','!=',  $this->num_fac)
                                           ->where('recibo',        $this->recibo)
                                           ->selectRaw("SUM(base_imponible) AS base_imponible, SUM(base_excenta) AS base_excenta, SUM(mto_nto) AS mto_nto,
                                                        SUM(mto_iva) AS mto_iva, SUM(mto_fac) AS mto_fac")
                                           ->first();



                    if ($query_Montos) {
                         $row_montos[0] = $query_Montos->base_imponible;
                         $row_montos[1] = $query_Montos->base_excenta;
                         $row_montos[2] = $query_Montos->mto_nto;
                         $row_montos[3] = $query_Montos->mto_iva;
                         $row_montos[4] = $query_Montos->mto_fac;
                    } else {
                         $row_montos[0] = 0;
                         $row_montos[1] = 0;
                         $row_montos[2] = 0;
                         $row_montos[3] = 0;
                         $row_montos[4] = 0;
                    }

                    $qsq_resul = CxPCabeceraFactura::query()
                                                   ->where('ano_doc',   $ano_solicitud_ordenes)
                                                   ->where('nro_doc',   $query->nro_doc)
                                                   ->whereNull('pago_manual')
                                                   ->select('base_imponible', 'base_excenta','mto_nto' ,'mto_iva', 'mto_tot')
                                                   ->first();


                    if ($qsq_resul){
                        //dd(json_encode($qsq_resul,true));
                        //foreach($qsq_resul as $sq_resul){
                            //dd($sq_resul->base_imponible);
                            if (($accion == 'M')) {
                                 // ----------------------------------------------------
                                // Asignar nuevamente los montos
                                // ----------------------------------------------------
                                $sq_datos_opsol_original[0] = $qsq_resul['base_imponible'] - $row_montos[0]; // Base
                                // Imponible
                                $sq_datos_opsol_original[1] = $qsq_resul['base_excenta']   - $row_montos[1]; // Base
                                // Exenta
                                $sq_datos_opsol_original[2] = $qsq_resul['mto_nto']        - $row_montos[2]; // Monto
                                // Neto
                                $sq_datos_opsol_original[3] = $qsq_resul['mto_iva']        - $row_montos[3]; // Monto
                                // Iva
                                $sq_datos_opsol_original[4] = $qsq_resul['mto_tot']        - $row_montos[4]; // Toal
                                // Factura
                            }
                        //}
                    } else {
                        alert()->error('Error buscando montos en la cabecera de la certificacion.');
                        return true;
                    }
                    $this->asignar_resultados_calcular_amortizacion( $query_result, $accion, $query->fec_fac, $query->sta_fac, $accion);
                    // LLenar La Estructura de Gastos
                    $this->Llenar_Estructra_Gastos_acciones($accion, $query->tipo_doc,  $query_result->tipo_pago, $this->ano_pro);

                }else {
                    // El Tipo de Pago del Flujo es Pago Total
                    $this->asignar_resultados_calcular_amortizacion($query_result, $accion, $query->fec_fac, $query->sta_fac, $accion);
                    // LLenar La Estructura de Gastos
                    $this->Llenar_Estructra_Gastos_acciones($accion, $query->tipo_doc,  $query_result->tipo_pago, $this->ano_pro);
                }
            }
        }else {
            alert()->error('Error al Consultar Datos de la Factura. Por favor verifique.');

            // campos de  Entrada
            $this->sta_fac = '';
        }
    }


    // ---------------------------------------------------------------------------------
    // Funcion que asigna el resultado del query en los diferentes campos de
    // pantallas
    // ---------------------------------------------------------------------------------
    function Asignar_Resultado($sq_datos_opsol, $accion, $fecha_fac, $statu_recep) {
        // Formatea la fecha de Factura en DD/MM/YYYY
        if (!Empty($fecha_fac)) {
            $fechafact = $fecha_fac;
            // year = fechafact.charAt(0) + fechafact.charAt(1) + fechafact.charAt(2) + fechafact.charAt(3);
            // month = fechafact.charAt(5) + fechafact.charAt(6);
            // day = fechafact.charAt(8) + fechafact.charAt(9);
            // fecha_fac = day + "/" + month + "/" + year;
            $sq_datos_opsol_original=[];

            $query_status =Factura::where('rif_prov',  $this->rif_prov)
                                  ->where('num_fac',   $this->num_fac)
                                  ->where('nro_doc',   $sq_datos_opsol->nro_doc)
                                  ->where('recibo',    $this->recibo)
                                  ->where('ano_pro',   $this->ano_pro)
                                  ->where('sta_rep',   1)
                                  ->select('sta_fac','num_ctrl','fec_apr')
                                  ->first();

           $fecha_fac = $fecha_fac->format('d/m/Y');
        }
        if ($accion == 'N') {
            $estado = $this->descrip_statu($statu_recep);
        } else {
            if ($query_status->sta_fac != null)
                $estado = $this->descripcion_statu_factura($query_status->sta_fac);
        }
        $this->sta_fac      = $estado;
        $this->fec_fac      = $fecha_fac;
        $this->tipo_doc     = $sq_datos_opsol->tipo_doc;
        $this->ltipo_doc    = $sq_datos_opsol->tipo_doc;
        $this->nro_doc      = $sq_datos_opsol->nro_doc;
        //$this->provisionada = $sq_datos_opsol[29]);
        //	$("#lprovisionada").$sq_datos_opsol[29]);

        if ($accion == 'N') {
            $this->ano_sol = $sq_datos_opsol->ano_sol;
            $this->provisionada = $sq_datos_opsol->provisionada;
            $this->lprovisionada = $sq_datos_opsol->provisionada;
            $this->recibo = $sq_datos_opsol->recibo;
            $this->lrecibo = $sq_datos_opsol->recibo;
            $fecha = $this->buscar_fecha();
            $this->fecha = $fecha;
            $this->ldeposito_garantia = $sq_datos_opsol->deposito_garantia;
            $this->deposito_garantia = $sq_datos_opsol->deposito_garantia;
        } else {
            $this->ano_sol = $sq_datos_opsol->ano_sol;
            $this->provisionada = $sq_datos_opsol->provisionada;
            $this->lprovisionada = $sq_datos_opsol->provisionada;
            $this->fecha = $sq_datos_opsol->fecha;
            $this->ldeposito_garantia = $sq_datos_opsol->deposito_garantia;
            $this->deposito_garantia = $sq_datos_opsol->deposito_garantia;
        }

        $this->tipo_pago = $sq_datos_opsol->tipo_pago;
        $this->ltipo_pago = $sq_datos_opsol->tipo_pago;
        //$sq_datos_opsol[5] = Utils.__formatNumber($sq_datos_opsol[5].toString(), 2, true, ".");
        $this->por_anticipo = $sq_datos_opsol->por_anticipo;
        //$sq_datos_opsol[6] = Utils.__formatNumber($sq_datos_opsol[6].toString(), 2, true, ".");
        $this->mto_anticipo = $sq_datos_opsol->mto_anticipo;
        // Porcentaje de Iva
       // $sq_datos_opsol[9] = Utils.__formatNumber($sq_datos_opsol[9].toString(), 2, true, ".");
        $this->porcentaje_iva = $sq_datos_opsol->porcentaje_iva;

        if ($accion != 'N') {
            $this->recibo = $sq_datos_opsol->recibo;
            $this->lrecibo = $sq_datos_opsol->recibo;
          //  $sq_datos_opsol[12] = Utils.__formatNumber($sq_datos_opsol[12].toString(), 2, true, ".");
            //    if ( $sq_datos_opsol_original[12] > 0 )
            $this->base_imponible = $sq_datos_opsol->base_imponible;
            /*   else
                $this->base_imponible = 0.00);
            */
            //$sq_datos_opsol[13] = Utils.__formatNumber($sq_datos_opsol[13].toString(), 2, true, ".");
            //	if ( $sq_datos_opsol_original[13] > 0 )
            $this->base_excenta = $sq_datos_opsol->base_excenta;
            /*	else
                    $this->base_excenta = 0.00);
            */
           // $sq_datos_opsol[10] = Utils.__formatNumber($sq_datos_opsol[10].toString(), 2, true, ".");
            //	if ( $sq_datos_opsol_original[10] > 0 )
            $this->mto_nto = $sq_datos_opsol->mto_nto;
            /*	else
                    $this->mto_nto = 0.00);
            */
           // $sq_datos_opsol[8] = Utils.__formatNumber($sq_datos_opsol[8].toString(), 2, true, ".");
            //	if ( $sq_datos_opsol_original[8] > 0 )
            $this->mto_iva = $sq_datos_opsol->mto_iva;
            /*	else
                    $this->mto_iva = 0.00);
            */
            //$sq_datos_opsol[11] = Utils.__formatNumber($sq_datos_opsol[11].toString(), 2, true, ".");
            //	if ( $sq_datos_opsol_original[11] > 0 )
            $this->mto_fac = $sq_datos_opsol->mto_fac;
            /*	else
                    $this->mto_fac = 0.00);
            */
            $this->monto_original = $sq_datos_opsol->monto_original;

        } else {
            //$sq_datos_opsol_original[0] = Utils.__formatNumber($sq_datos_opsol_original[0].toString(), 2, true, ".");
            //	if ( $sq_datos_opsol_original[0] > 0 )
            $this->base_imponible = $sq_datos_opsol_original->base_imponible;
            /*else
                $this->base_imponible = 0.00);
            */
            //$sq_datos_opsol_original[1] = Utils.__formatNumber($sq_datos_opsol_original[1].toString(), 2, true, ".");
            //if ( $sq_datos_opsol_original[1] > 0 )
            $this->base_excenta = $sq_datos_opsol_original->base_excenta;
            /*else
                $this->base_excenta = 0.00);
            */
            //$sq_datos_opsol_original[2] = Utils.__formatNumber($sq_datos_opsol_original[2].toString(), 2, true, ".");
            //	if ( $sq_datos_opsol_original[2] > 0 )
            $this->mto_nto = $sq_datos_opsol_original->mto_nto;
            /*	else
                    $this->mto_nto = 0.00);
            */
            //$sq_datos_opsol_original[3] = Utils.__formatNumber($sq_datos_opsol_original[3].toString(), 2, true, ".");
            //	if ( $sq_datos_opsol_original[3] > 0 )
            $this->mto_iva = $sq_datos_opsol_original->mto_iva;
            /*	else
                    $this->mto_iva = 0.00);
            */
          //  $sq_datos_opsol_original[4] = Utils.__formatNumber($sq_datos_opsol_original[4].toString(), 2, true, ".");
            //	if ( $sq_datos_opsol_original[4] > 0 )
            $this->mto_fac = $sq_datos_opsol_original->mto_fac;
            /*	else
                    $this->mto_fac = 0.00);
            */
            //monto_original = Utils.__formatNumber($sq_datos_opsol[11].toString(), 2, true, ".");
            $this->monto_original = $monto_original;

        }
        $this->fondo = $sq_datos_opsol->fondo;
        $this->lfondo = $sq_datos_opsol->fondo;
        $this->cuenta_contable = $sq_datos_opsol->cuenta_contable;

        // ---------------------------------------------------------------
        // Asigno Montos de la Nota de Entrega
        // Solo aplica para las acciones de borrar,aprobar,reversar,ver
        // ---------------------------------------------------------------
        if ($accion != 'N') {
            $this->num_ctrl = $sq_datos_opsol->num_ctrl;
            if ($sq_datos_opsol->tipo_doc == 'S') {
                $this->ncr_sn = $sq_datos_opsol->ncr_sn;
                $this->lncr_sn = $sq_datos_opsol->ncr_sn;
                $this->nro_ncr = $sq_datos_opsol->nro_ncr;
                $this->num_nc = $sq_datos_opsol->num_nc;
                //$sq_datos_opsol[20] = Utils.__formatNumber($sq_datos_opsol[20].toString(), 2, true, ".");
                $this->mto_ncr = $sq_datos_opsol->mto_ncr;
                //$sq_datos_opsol[21] = Utils.__formatNumber($sq_datos_opsol[21].toString(), 2, true, ".");
                $this->iva_ncr = $sq_datos_opsol->iva_ncr;
                //$sq_datos_opsol[22] = Utils.__formatNumber($sq_datos_opsol[22].toString(), 2, true, ".");
                $this->tot_ncr = $sq_datos_opsol->tot_ncr;
            }
        }
    }

    // ------------------------------------------------------------------------------
    // Funcion que hace el llamado a la funcion asigna los resultados de los montos
    // y a la funcion que calculas las amortizaciones si existen
    // -----------------------------------------------------------------------
    function asignar_resultados_calcular_amortizacion($sq_datos_opsol, $acciones, $fecha_fac, $statu_recep, $accion) {
        // --------------------------------------------------------
        // Buscar a la funcion que muestra los campos por pantalla
        // Cuando no viene por Pagos Parciales
        // --------------------------------------------------------
        $this->Asignar_Resultado($sq_datos_opsol, $acciones, $fecha_fac, $statu_recep);
        // --------------------------------
        // Calculo de Anticipo
        // --------------------------------
        if ($accion == 'M' || $accion == 'N') {
            if ($sq_datos_opsol->por_anticipo != '0,00') {
                $this->Calcular_Anticipo();
            } else {
                $this->mto_amortizacion = '0,00';
            }
        } else {
            $this->mto_amortizacion = $sq_datos_opsol->mto_amortizacion;
        }
    }
    // ------------------------------------------------------
    // Función que coloca las descripcion del status
    // ------------------------------------------------------
    function descripcion_statu_factura($valor) {
        $estado = "";

        if ($valor != null) {
        //    dd($valor);
            switch ($valor) {
                case "0":
                    $estado = "Con Expediente Registrado en Control del Gasto";;
                    break;
                case "1":
                    $estado = "Aprobada Presupuestariamente";;
                    break;
                case "2":
                    $estado = "Reversada Presupuestariamente";;
                    break;
                case "3":
                    $estado = "Aprobada Contablemente";;
                    break;
                case "4":
                    $estado = "Reversar Asiento Contable";;
                    break;
                case "5":
                    $estado = "Con Deducciones y Retenciones";;
                    break;
                case "6":
                    $estado = "En Cronograma de Pago";;
                    break;
                case "8":
                    $estado = "Con Cheque Impreso";;
                    break;
                case "9":
                    $estado = "Con Pago Parcial";;
                    break;
                return $estado;
            }
            return $estado;
        }
    }
    // -----------------------------------------------------------------------------------------------
    // Funcion que permite realizar los calculos necesarios cuando existe anticipo
    // -----------------------------------------------------------------------------------------------
    function Calcular_Anticipo() {
        // -----------------------------------------------------------------------------
        // Formatear los campos para realizar los calculos necesarios
        // -----------------------------------------------------------------------------
        $por_anticipo = $this->Formatear_Campo($this->por_anticipo);
        $mto_nto = $this->Formatear_Campo($this->mto_nto);
        // --------------------------------------------------------------------
        // Calculo del anticipo (Base Imponible * (Porcentaje de Anticipo/100))
        // ---------------------------------------------------------------------
        if ($por_anticipo != 0) {
            $monto_amortizacion = $mto_nto * ($por_anticipo / 100);
            $monto_amortizacion = $this->redondear($monto_amortizacion, 2);
            //$monto_amortizacionx = Utils.__formatNumber(monto_amortizacion.toString(), 2, true, ".");
            $this->mto_amortizacion = $monto_amortizacion;
        }
    }
    // -------------------------------------------------------------------------------------------
    // Fucion que buscas las estructuras de Gastos y crea la botonera
    // -------------------------------------------------------------------------------------------
    function Llenar_Estructra_Gastos_acciones($accion, $tipo_doc, $tipo_pago, $ano_proceso) {
        // rif_prov = $("#rif_prov").val();
        // num_fac = $("#num_fac").val();
        // nro_doc = $("#nro_doc").val();
        // ano_sol = $("#ano_sol").val();
        //scriptPHP = 'utilsPHP/selectInDB.Script.php'; // Scrip generico de seleccion
        $dgx = CxPDetGastosFactura::query()
                                  ->where('ano_pro',$ano_proceso)
                                  ->where('num_fac',$this->num_fac)
                                  ->where('rif_prov',$this->rif_prov)
                                  ->where('nro_doc',$this->nro_doc)
                                  ->where('ano_sol',$this->ano_sol)
                                  ->selectRaw("CASE WHEN gasto = '1' THEN 'Si' ELSE 'No' END AS gasto,
                                               tip_cod, cod_pryacc, cod_obj, gerencia, unidad,
                                               cod_par, cod_gen, cod_esp, cod_sub,mto_tra,
                                               mto_nc, sal_cau, CASE WHEN presu_afectado = '1' THEN 'Original'
                                               ELSE 'Afecta la 4.11' END as original")
                                 ->get();

        if($dgx != null){
            if ($tipo_doc == '4' && $tipo_pago == 'P') {
                if ($accion == 'M'){
                    foreach($dgx as $index => $gastos){

                        $this->cxpdetgastosfactura[] = [
                            'gasto'             => $gastos['gasto'] ,
                            'tip_cod'           => $gastos['tip_cod'],
                            'cod_pryacc'        => $gastos['cod_pryacc'],
                            'cod_obj'           => $gastos['cod_obj'],
                            'gerencia'          => $gastos['gerencia'],
                            'unidad'            => $gastos['unidad'],
                            'cod_par'           => $gastos['cod_par'],
                            'cod_gen'           => $gastos['cod_gen'],
                            'cod_esp'           => $gastos['cod_esp'],
                            'cod_sub'           => $gastos['cod_sub'],
                            'mto_tra'           => $gastos['mto_tra'],
                            'mto_nc'            => $gastos['mto_nc'],
                            'sal_cau'           => $gastos['sal_cau'],
                            'Original'          => $gastos['original']
                        ];
                    }
                }
            }
        }


        // -------------------------------------------------------------------------
        // Si la accion es aprobar
        // - Se debe descontar de las estructura de gasto el anticipo si viene por
        // certificacion
        // ya que cuando viene por orden de compra viene descontado
        // - Se debe descontar el monto de la nota de credito si aplica
        // -------------------------------------------------------------------------
        $tipo_doc       = $this->tipo_doc;
        $por_anticipo   = $this->por_anticipo;
        $por_anticipo   = $this->Formatear_Campo($por_anticipo);



       // CASE WHEN a.gasto = '1' THEN 'Si' ELSE 'No' END AS gasto,  -->row_grilla[0]
       // tip_cod,-->row_grilla[1]
       // cod_pryacc,-->row_grilla[2]
       // cod_obj,-->row_grilla[3]
       // gerencia,-->row_grilla[4]
       // unidad,-->row_grilla[5]
       // cod_par,-->row_grilla[6]
       // cod_gen,-->row_grilla[7]
       // cod_esp,-->row_grilla[8]
       // cod_sub,-->row_grilla[9]
       // mto_tra,-->row_grilla[10]
       // mto_nc,-->row_grilla[11]
       // sal_cau,-->row_grilla[12]
       // CASE WHEN presu_afectado = '1' THEN 'Original'  ELSE 'Afecta la 4.11' END as original-->row_grilla[13]

        $cont =0;
        if ($accion == 'A') {
            foreach($dgx as $index => $gastos){
                $monto_sal_cau = $gastos['sal_cau'];
                $monto_sal_nc  = $gastos['mto_nc'];
                $monto_anticipo = 0;

                if ($por_anticipo != 0 && $tipo_doc == '4') {
                    if ($gastos['cod_par'] == $row_iva[0] && $gastos['cod_gen'] == $row_iva[1] && $gastos['cod_esp'] == $row_iva[2] && $gastos['cod_sub'] == $row_iva[3])
                        $monto_anticipo = 0;
                    else {
                        $monto_anticipo = 0;
                        $monto_anticipo = $monto_sal_cau * ($por_anticipo / 100);
                        $monto_anticipo = $this->redondear($monto_anticipo, 2);
                    }
                }
                //Nota de Credito
                $lncr_sn[] = [
                        'nro_ncr' => '',
                        'mto_ncr' => "0",
                        'iva_ncr' => "0",
                        'tot_ncr' => "0,00",
                    ];
                $this->ajustarMontoCausar($this->ano_sol, $this->nro_doc, $tipo_doc, $monto_sal_cau, $monto_anticipo, $lncr_sn, $monto_sal_nc, $cont);
                $cont = $cont + 1;
                // $this->cxpdetgastosfactura[] = [
                //     'gasto'             => $gastos['gasto'] ,
                //     'tip_cod'           => $gastos['tip_cod'],
                //     'cod_pryacc'        => $gastos['cod_pryacc'],
                //     'cod_obj'           => $gastos['cod_obj'],
                //     'gerencia'          => $gastos['gerencia'],
                //     'unidad'            => $gastos['unidad'],
                //     'cod_par'           => $gastos['cod_par'],
                //     'cod_gen'           => $gastos['cod_gen'],
                //     'cod_esp'           => $gastos['cod_esp'],
                //     'cod_sub'           => $gastos['cod_sub'],
                //     'mto_tra'           => $gastos['mto_tra'],
                //     'mto_nc'            => $gastos['mto_nc'],
                //     'sal_cau'           => $gastos['sal_cau'],
                //     'Original'          => $gastos['original']
                // ];
            }
        }

        // ------------------------------------------------------
        // Si la accion es ver o modificar el asiento Contable
        // -------------------------------------------------------
        if ($accion == 'V' || $accion == 'X') {
            //Muestra el asiento contable

            $dax = CxpDetComproFacturas::query()
                                        ->where('ano_pro',$ano_proceso)
                                        ->where('num_fac',$this->num_fac)
                                        ->where('rif_prov',$this->rif_prov)
                                        ->where('nro_sol_orden',$this->nro_doc)
                                        ->where('ano_sol_doc',$this->ano_sol)
                                        ->where('nc',0)
                                        ->orderBy('nro_ren')
                                        ->get();
            if($dax != null){
                foreach($dax as $index =>  $asientos){

                    // $this->cxpdetcomprofacturas[] = [
                    //     'nro_ren'       => $asientos->nro_ren,
                    //     'cod_cta'       => $asientos->cod_cta,
                    //     'tipo'          => $asientos->tipo,
                    //     'monto'         => $asientos->monto
                    // ];

                    if ($accion == 'X') {
                        // Para poder modificar el asiento debe venir por fondo
                        // externo ,certtificacion de pago
                        // y tener anticipo
                        $fondo = $this->fondo;
                        $tipo_doc = $this->tipo_doc;
                        // $this->rif_prov0].disabled = true;
                        // $("#lrif_prov")[0].disabled = true;
                        // $("#num_fac")[0].disabled = true;
                        // $("#lano_pro")[0].disabled = true;
                        //$this->emit('enableCambiarAsiento');

                        if ($fondo == 'E' && $tipo_doc == '4') {
                            $por_anticipo = $this->por_anticipo;
                            $por_anticipo = $this->Formatear_Campo($por_anticipo);

                            if ($por_anticipo != 0) {
                                $this->cxpdetcomprofacturas[] = [
                                    'nro_ren'       => $asientos->nro_ren,
                                    'cod_cta'       => $asientos->cod_cta,
                                    'tipo'          => $asientos->tipo,
                                    'monto'         => $asientos->monto
                                ];


                            } else {
                                alert()->error('No Existe Asiento que Mostrar ya que la Certificacion de Pagos no tiene anticipo.Favor Verifique');

                            }
                        } else {
                            if ($fondo == 'E')
                                alert()->error('El asiento contable solo puede ser modificado cuando viene por Certificacion de Pagos');
                            else
                                alert()->error('El asiento contable solo puede ser modificado cuando se cancela con dinero externo.\nFavor Verifique');
                        }
                    }
                }
            }
            foreach($dax as $index =>  $asientos){
                $this->cxpdetcomprofacturas[] = [
                    'nro_ren'       => $asientos->nro_ren,
                    'cod_cta'       => $asientos->cod_cta,
                    'tipo'          => $asientos->tipo,
                    'monto'         => $asientos->monto
                ];
            }
        }
    }

    //--------------------------------------------------------------------
    // Función para ajustar el monto a Causar en Caso de que la
    // factura tenga Nota de Crédito o el documento tenga Anticipo
    //--------------------------------------------------------------------
    function ajustarMontoCausar($ano_pro, $xnro_sol, $tipo_doc, $monto_sal_cau, $monto_anticipo, $lncr_sn, $monto_sal_nc, $fila) {
        $mto_nc = $monto_sal_nc;

        if ($tipo_doc == '4') {

            //--------------------------------------------------------------------
            // Query para conocer si hay que causar el monto del Anticipo de una Certificación
            // ant_old = 1 (Causar)
            // anto_old = 0 (No Causar)
             //--------------------------------------------------------------------

             $dataPD = OpSolservicio::query()
                                 ->where('ano_pro',$ano_pro)
                                 ->where('xnro_sol',$xnro_sol)
                                 ->select('ant_old')
                                 ->first();


            if ($dataPD) {

                $mto_nc = $monto_sal_nc;

                if ($lncr_sn != 'S')
                    $mto_nc = 0;

                if ( $dataPD->ant_old == 1)
                    $Monto_causar = ($monto_sal_cau - $monto_anticipo) - $mto_nc;
                else
                    $Monto_causar =$monto_sal_cau - $mto_nc;

                $Monto_causar = round($Monto_causar, 2);
                //Monto_causax = Utils.__formatNumber(Monto_causar.toString(), 2, true, ".");

                //descripcion = $("#divGridscroll1  #scroll1 tbody tr#" + fila + " td#c12").html(Monto_causax);
            }

        } else {
            if ($lncr_sn != 'S')
                $mto_nc = 0;

            $Monto_causar = $monto_sal_cau - $mto_nc;
            $Monto_causar = $this->redondear($Monto_causar, 2);
            //$Monto_causax = Utils.__formatNumber(Monto_causar.toString(), 2, true, ".");
           // $descripcion = $("#divGridscroll1  #scroll1 tbody tr#" + fila + " td#c12").html(Monto_causax);
        }
    }

}
