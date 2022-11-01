<?php

namespace App\Http\Livewire\Administrativo\MeruAdministrativo\CuentasPorPagar\Proceso;

use Livewire\Component;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacRecepFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPDetNotaFactura;

class FacturaCreate extends Component
{
    public $accion='create';
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
    //------------relaciones--------------------//
    public $cxpdetnotafacturas=[];
    public $detnotafacturas;
    public $cxpdetgastosfactura=[];
    public $detgastosfactura;
    public $cxpdetcomprofacturas=[];
    public $detcomprofacturas;

    protected $listeners =['changeSelect'];
    public function cargar_emit()
    {
     //   $this->emit('alert', ['det' => $this->facturas]);
    }
    public function changeSelect($valor,$id)
    {   $this->rif_prov= $valor ;    }

    public function mount()
    {
    }
    public function datosFactura (){
        $estado='';

        //dd($this->rif_prov);
        if (!empty($this->rif_prov)){
            if (!empty($this->num_fac)){
                if (!empty($this->ano_pro)){
                    if (!empty($this->recibo)){
                        $this->facrecepfactura = FacRecepFactura::where('rif_prov',$this->rif_prov)
                                                ->where('num_fac', $this->num_fac)
                                                ->where('ano_pro',$this->ano_pro)
                                                ->where('recibo',$this->recibo)
                                                ->first();
                        // dd($facfactura );
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

                            $this->emit('enableCreatefactura');
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
}
