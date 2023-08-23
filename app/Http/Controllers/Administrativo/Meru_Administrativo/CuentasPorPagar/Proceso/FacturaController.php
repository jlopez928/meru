<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasPorPagar\Proceso;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Factura;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
use App\Models\Administrativo\Meru_Administrativo\General\Usuario;
use App\Http\Requests\Administrativo\Meru_Administrativo\CuentasPorPagar\Proceso\FacturaRequest;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacRecepFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPDetGastosFactura;
use App\Traits\funcFacturas;
use App\Models\Administrativo\Meru_Administrativo\Contabilidad\PlanContable;

use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{

    use funcFacturas;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.cuentasxpagar.proceso.factura.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $factura= new Factura();

        $proveedores = Beneficiario::whereIn('tipo',['P','E','O'])
                                   ->select('rif_ben','nom_ben')
                                   ->orderBy('nom_ben')
                                   ->get();

        $cxptipodocumento = CxPTipoDocumento::query()
                                            ->where('status','1')
                                            ->where('recp_factura','1')
                                            ->get();
        $accion = 'create';

        return view('administrativo.meru_administrativo.cuentasxpagar.proceso.factura.create', compact('factura','proveedores','cxptipodocumento','accion'));

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FacturaRequest $factura)
    {
        $ano_fiscal   = $this->anoPro;
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();
        $row2=[];
        $mto_ncr	  = 0;
        $iva_ncr	  = 0;
        $tot_ncr_ncr  = 0;
        $ncr_sn		  = 0;
        $nro_ncr	  = 0;
        if(!$factura->num_ctrl){
            alert()->error('Debe ingresar el Número de Control de la factura');
            return redirect()->back()->withInput();
        }
        try{
            DB::connection('pgsql')->beginTransaction();

            $nro_ncr        = $factura->nro_ncr;
            $por_anticipo   = $factura->por_anticipo;
            $nro_doc        = $factura->nro_doc;
            $res            = true;
            $grupo          = '';
            $ano_proceso    = $this->anoPro;
            $fecha_proceso  = $this->FechaSistema($ano_proceso,'Ymd' );

            //Buscar las Siglas del Documento
            $msj = "Error al buscar las Siglas del Documento. Comuniquese con el Administrador del Sistema.";
            $res = $this->Retornar_siglas_Documentos($msj,$grupo,$factura->tipo_doc);

            if(!$res){
                alert()->error($msj);
                return redirect()->back()->withInput();
            }

            //Actualizar el status en la Recepcion de factura
            $status = '1';
            $msj = "Error actualizando el status en la Recepcion de factura. Comuniquese con el Administrador del Sistema.";
            $res = $this->Actualiza_Status_Recepcion_Factura($msj,$status,$factura->ano_pro,$ano_proceso,$factura); //Dualidad

            if(!$res){
                alert()->error($msj);
                return redirect()->back()->withInput();
            }

            //Buscar año de generación de Documento
            $ano_sol_doc = $factura->ano_sol;

            //---------------------------------------------------------------------------
			//Si la Factura tiene porcentaje de anticipo se debe ingresar los datos
			//en la tabla ant_amortizaciones que luego sera utilizados para el calculo
			//de las retenciones y deducciones, para ello es necesario saber el numero de
			//cuenta por cobrar asignado al proveedor
			//--------------------------------------------------------------------------
            if ($por_anticipo != '0.00'){
				//Buscar Cuenta x Cobrar del Proveedor
				$cta_x_cobrar = '';
                $msj = "Error al Buscar Cuenta x Cobrar del Proveedor. Comuniquese con el Administrador del Sistema.";
				$res = $this->CuentaxCobrar_Proveedor($msj,$cta_x_cobrar,$factura);

			    if(!$res){
                    alert()->error($msj);
                    return redirect()->back()->withInput();
                }

				//Ingresar Datos en la Tabla	ant_amortizaciones
                $msj = "Ya existen datos del anticipo en la Tabla ant_amortizaciones. Comuniquese con el Administrador del Sistema.";
				$res = $this->ant_amortizaciones($msj,$cta_x_cobrar,$grupo,$ano_proceso,$factura);

                if(!$res){
                    alert()->error($msj);
                    return redirect()->back()->withInput();
                }
		 	}

            //--------------------------------------------------------------------------------
            //--------------------------------------------------------------------------------
            //Buscar la partida de IVA
            $result_iva = '';
            $presu_afectado = '0';
            $msj = "Error al buscar la partida de IVA. Comuniquese con el Administrador del Sistema.";
            $res = $this->Retornar_Partida_IVA($msj,$result_iva,$ano_proceso); //Dualidad

            if(!$res){
                alert()->error($msj);
                return redirect()->back()->withInput();
            }
            //-------------------------------------------------------------------------------------------
			//Insertar en la Tabla cxp_detgastosfactura,para ello debo recorrer el segundo grid
			//-------------------------------------------------------------------------------------------
            $facrecepfactura = FacRecepFactura::where('ano_pro',$factura->ano_pro)
                                              ->where('rif_prov',$factura->rif_prov)
                                              ->where('num_fac',$factura->num_fac)
                                              ->first();
            //return $facrecepfactura->opsolservicio->opdetgastossolservicio;
			foreach( $facrecepfactura->opsolservicio->opdetgastossolservicio as  $row2){
                $cod_par    = $row2->cod_par;
                $cod_gen    = $row2->cod_gen;
                $cod_esp    = $row2->cod_esp;
                $cod_sub    = $row2->cod_sub;
                if ($ano_fiscal != $ano_sol_doc){
                    $msj = "Error armando centro de costo. Comuniquese con el Administrador del Sistema.";
                    $res = $this->armar_centro_costo($msj,$tip_cod,$cod_pryacc,$cod_obj,
                                                     $gerencia,$unidad,$cod_com,$row2,$ano_proceso,$ano_sol_doc); //Dualidad
                    if(!$res){
                    alert()->error($msj);
                    return redirect()->back()->withInput();
                    }

                }else{
                    $tip_cod	= $row2->tip_cod;
                    $cod_pryacc = $row2->cod_pryacc;
                    $cod_obj	= $row2->cod_obj;
                    $gerencia	= $row2->gerencia;
                    $unidad		= $row2->unidad;
                    $msj = "Error armando código contable. Comuniquese con el Administrador del Sistema.";
                    $cod_com	= $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);

                    if(!$cod_com){
                        alert()->error($msj);
                        return redirect()->back()->withInput();
                    }
                }
                $gasto=$row2->gasto;
                //anticipo
                // if ($row2->gasto == 'S')
                //     $gasto='1';
                // else
                //     $gasto='0';

                //LLenar la Tabla de la Estructura de gastos
//return $row2;
                $monto_nc = 0;

                if (!empty($row2->presu_afectado)){
                        if ($row2->presu_afectado=='Original')
                            $presu_afectado = '1';
                        else
                            $presu_afectado = '0';
                }else{
                    $res = false;
                    $msj = "Error el campo Pagado no puede estar vacio en la grilla del gasto de la factura.\\nComuniquese con el Administrador del Sistema.";
                    if(!$res){
                        alert()->error($msj);
                        return redirect()->back()->withInput();
                    }
                }
                if ($ano_fiscal != $ano_sol_doc){
                    $msj = "Error armando centro de costo. Comuniquese con el Administrador del Sistema.";
                    $res = $this->armar_centro_costo($msj,$tip_cod,$cod_pryacc,$cod_obj,
                                                        $gerencia,$unidad,$cod_com,$row2,$ano_proceso,$ano_sol_doc); //Dualidad
                    if(!$res){
                        alert()->error($msj);
                        return redirect()->back()->withInput();
                    }
                }else{
                    $tip_cod	= $row2->tip_cod;
                    $cod_pryacc = $row2->cod_pryacc;
                    $cod_obj	= $row2->cod_obj;
                    $gerencia	= $row2->gerencia;
                    $unidad		= $row2->unidad;
                    $msj = "Error armando código contable. Comuniquese con el Administrador del Sistema.";
                    $cod_com	= $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);
                    if(!$res){
                        alert()->error($msj);
                        return redirect()->back()->withInput();
                    }
                }
                //anticipo
                $gasto=$row2->gasto;
                // if ($row2->gasto == 'Si')
                //     $gasto='1';
                // else
                //     $gasto='0';

                //LLenar la Tabla de la Estructura de gasto
                $monto_nc = 0;

                if (!empty($row2->presu_afectado)){
                    if ($row2->presu_afectado=='Original')
                        $presu_afectado = '1';
                    else
                        $presu_afectado = '0';
                }else{
                    $res = false;
                    $msj = "Error el campo Pagado no puede estar vacio en la grilla del gasto de la factura.\\nComuniquese con el Administrador del Sistema.";
                    if(!$res){
                        alert()->error($msj);
                        return redirect()->back()->withInput();
                    }
                }
                $res = $this->Guarda_Estructura_Gasto_Factura($facrecepfactura,$msj,$tip_cod,$cod_pryacc,$cod_obj,
                                                                $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$row2->sal_cau,
                                                                $row2->sal_cau,$gasto ,$ano_proceso,$monto_nc,$presu_afectado); //Dualidad
                if (!$res){
                    alert()->error('Fallo guardando estructura de gasto factura. Favor Verifique '.$msj);
                    return redirect()->back()->withInput();
                    //     return $res;
                }

                // CxPDetGastosFactura::create([
                //         'ano_pro'            => $facrecepfactura->ano_pro,
                //         'rif_prov'           => $facrecepfactura->rif_prov,
                //         'num_fac'            => $facrecepfactura->num_fac,
                //         'tip_cod'            => $row2->tip_cod,
                //         'cod_pryacc'         => $row2->cod_pryacc,
                //         'cod_obj'            => $row2->cod_obj,
                //         'gerencia'           => $row2->gerencia,
                //         'unidad'             => $row2->unidad,
                //         'cod_par'            => $row2->cod_par,
                //         'cod_gen'            => $row2->cod_gen,
                //         'cod_esp'            => $row2->cod_esp,
                //         'cod_sub'            => $row2->cod_sub,
                //         'gasto'              => $row2->gasto,
                //         'cod_com'            => $cod_com,
                //         'presu_afectado'     => $presu_afectado,
                //         'mto_tra'            => $row2->mto_tra,
                //         'sal_cau'            => $row2->sal_cau

                // ]);
                // if (!$res){
                //     return $res;
                // }
			}//Fin del foreach

            //---------------------------------------------------------------------------------
			//Si el flujo viene por orden de compra se deben actualizar la cabecera de las notas
			//de entregas y llenar  la tabla cxp_detnotasfacturas que guarda la asociacion
			//de las notas de entregas con la factura
			//---------------------------------------------------------------------------------
			if ($factura->tipo_doc != 4){
				//foreach($tablasDetalle as $key => $tabCols){
					//if ($key == "cxp_detnotasfacturas"){
				    //	$colsv = &$datosDetalle[$key]; //Filas con valores de columnas de detalle

                        foreach( $facrecepfactura->cxpdetnotafacturas as  $row){
				    	//foreach ($colsv as &$row){
							$marca = $row->seleccionar;

							//----------------------------------------------------------
							//Obtengo Numero de Nota de Entrega generada por el sistema,
							//sin tomar en cuenta el grupo
							//----------------------------------------------------------
						    $ano_entrega   = $row->ano_nota_entrega;
						    $pos		   = strlen($row->nro_ent);
						    $nro_ent	   = substr($row->nro_ent,3,($pos-2));
						    $cadena		   = $row->nota_entrega;
						    $nro_documento = substr($factura->nro_doc,3,strlen($factura->nro_doc));

						    if ($marca == 'Si'){
								//Actualizo la cabecera de la Nota de Entrega
							    $status = '4';
							    $numero_factura = $factura->num_fac;
								$res = $this->Actualizar_Cabecera_Nota_Entrega ($factura,$msj,$status,$nro_documento,$grupo,$nro_ent,$ano_sol_doc,$numero_factura,$ano_entrega);

							    if (!$res){
                                    alert()->error('Fallo al Actualizar la cabecera de la Nota de Entrega. Favor Verifique '.$msj);
                                    return redirect()->back()->withInput();
                                    //     return $res;
                                }
								//Ingresar el Registro en la tabla cxp_detnotasfacturas
								$res = $this->Inserta_cxp_detnotasfacturas($factura,$msj,$grupo,$nro_documento,$nro_ent,$row->mto_ord,$cadena,$row->base_imponible,$row->base_excenta,$row->to_iva,$ano_proceso,$row->ano_nota_entrega); //Dualidad

                                if (!$res){
                                    alert()->error('Fallo al Ingresar el Registro en la tabla cxp_detnotasfacturas. Favor Verifique '.$msj);
                                    return redirect()->back()->withInput();
                                    //     return $res;
                                }

								$res = $this->Actualizar_status_cabecera_factura($factura,$msj,$ano_sol_doc,$row->nro_ent,$row->ano_nota_entrega,'3',$factura->num_fac);

								if (!$res){
                                    alert()->error('Fallo al Iactualizar cabecera factura. Favor Verifique '.$msj);
                                    return redirect()->back()->withInput();
                                    //     return $res;
                                }
							}
						}//fin del foreach ($colsv as &$row)
					//}//fin del if ($key == "cxp_detnotasfacturas")
				//}//foreach($tablasDetalle as $key => $tabCols)
			}else{//Fin del if ($factura->tipo_doc")->valor!=4)
				//Actualizar la Cabecera de la Factura
				$res = $this->Actualizar_status_cabecera_factura($factura,$msj,$ano_sol_doc,$nro_doc,$ano_sol_doc,'3',$factura->num_fac);

				if (!$res){
                    alert()->error('Fallo al Iactualizar cabecera factura. Favor Verifique '.$msj);
                    return redirect()->back()->withInput();
                    //     return $res;
                }
			}

            //----------------------------------------------------------------------------------------
			//-----------------------------------------------------------------------------------------
			//Ingresar Datos en la Tabla Principal de Factura
			$status = '0';
			$res = $this->Ingresar_Tabla_Factura($factura,$msj,$status,$ano_sol_doc,$ano_proceso); //Dualidad

            if (!$res){
                alert()->error('Fallo al Ingresar Datos en la Tabla Principal de Factura. Favor Verifique '.$msj);
                return redirect()->back()->withInput();
                //     return $res;
            }




            // $msj = "Error Error guardando factura. Comuniquese con el Administrador del Sistema.";
            // Factura::create([
            //         'ano_pro'                   => $factura->ano_pro,
            //         'rif_prov'                  => $factura->rif_prov,
            //         'num_fac'                   => $factura->num_fac,
            //         'num_ctrl'                  => $factura->num_ctrl,
            //         'fec_fac'                   => $factura->fec_fac,
            //         'tipo_doc'                  => $factura->tipo_doc,
            //         'tipo_pago'                 => $factura->tipo_pago,
            //         'nro_doc'                   => $factura->nro_doc,
            //         'base_imponible'            => $factura->base_imponible,
            //         'base_excenta'              => $factura->base_excenta,
            //         'mto_nto'                   => $factura->mto_nto,
            //         'mto_iva'                   => $factura->mto_iva,
            //         'mto_fac'                   => $factura->mto_fac,
            //         'por_anticipo'              => $factura->por_anticipo,
            //         'mto_anticipo'              => $factura->mto_anticipo,
            //         'mto_amortizacion'          => $factura->mto_amortizacion,
            //         'ncr_sn'                    => $factura->ncr_sn,
            //         'nro_ncr'                   => $factura->nro_ncr,
            //         'mto_ncr'                   => $factura->mto_ncr,
            //         'iva_ncr'                   => $factura->iva_ncr,
            //         'tot_ncr'                   => $factura->tot_ncr,
            //         'usuario'                   => $usuario->usuario,
            //         'fecha'                     => $factura->fecha,
            //         'usua_apr'                  => $factura->usua_apr,
            //         'fec_apr'                   => $factura->fec_apr,
            //         'usua_anu'                  => $factura->usua_anu,
            //         'fec_anu'                   => $factura->fec_anu,
            //         'sta_fac'                   => $factura->sta_fac,
            //         'fec_sta'                   => $factura->fec_sta,
            //         'sol_pag'                   => $factura->sol_pag,
            //         'usua_pago'                 => $factura->usua_pago,
            //         'fec_pago'                  => $factura->fec_pago,
            //         'monto_original'            => $factura->monto_original,
            //         'porcentaje_iva'            => $factura->porcentaje_iva,
            //         'num_nc'                    => $factura->num_nc,
            //         'ano_sol'                   => $factura->ano_sol,
            //         'recibo'                    => $factura->recibo,
            //         'mod_fac'                   => $factura->mod_fac,
            //         'descuentos'                => $factura->descuentos,
            //         'monto_descuento'           => $factura->monto_descuento,
            //         'cuenta_contable'           => $factura->cuenta_contable,
            //         'fondo'                     => $factura->fondo,
            //         'pago_manual'               => $factura->pago_manual,
            //         'deposito_garantia'         => $factura->deposito_garantia,
            //         'deuda'                     => $factura->deuda,
            //         'tipo_nota'                 => $factura->tipo_nota,
            //         'ano_nota'                  => $factura->ano_nota,
            //         'base_imponible_nd'         => $factura->base_imponible_nd,
            //         'base_exenta_nd'            => $factura->base_exenta_nd,
            //         'observacion'               => $factura->observacion,
            //         'sta_rep'                   => 1,
            //         'referencia'                => $factura->referencia,
            //         'provisionada'              => $factura->provisionada,
            //         'servicio'                  => $factura->servicio,
            //         'monto_contrato'            => $factura->monto_contrato,
            //         'nro_reng'                  => $factura->nro_reng,
            //         //'id'
            //     ]);

                //  return $factura;


            alert()->success('¡Éxito!', ' Factura Registrado Sastifactoriamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.factura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            //dd($e->getMessage().' '.$msj);.' '. $e->getMessage()
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj);
            return redirect()->back()->withInput();

        }
    }




    /**
     * cambiar a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ReqFacturaRequestuest  $factura
     * @return \Illuminate\Http\Response
     */
    //--------------------------------------------------------------------------
    //         Funcion que modifica los Datos de la Factura
    //--------------------------------------------------------------------------
    public function cambiar(FacturaRequest $factura)
    {
        $ano_fiscal = $factura->ano_pro;


        $facfactura = Factura::where('ano_pro',$factura->ano_pro)
                             ->where('rif_prov',$factura->rif_prov)
                             ->where('num_fac',$factura->num_fac)
                             ->first();

        $presu_afectado =0;
        $ano_proceso = $ano_fiscal;

        try{
            DB::connection('pgsql')->beginTransaction();
            $por_anticipo =  $factura->por_anticipo;
            $nro_doc	  =  $factura->nro_doc;
            $mto_ncr	  = 0;
            $iva_ncr	  = 0;
            $tot_ncr_ncr  = 0;
            $ncr_sn		  = 0;
            $nro_ncr	  = 0;

            //-----------------------------------------------------------------------------
            //Valida que si existe nota de credito el % de iva sea igual al de la factura
            //----------------------------------------------------------------------------
            $ncr_sn = $factura->ncr_sn;

            if ($ncr_sn == 'S'){
                $res = $this->validar_iva_nota($factura,$msj);

                 if (!$res){
                    alert()->error('El % de iva sea igual al de la factura. Favor Verifique');
                    return redirect()->back()->withInput();
                    //     return $res;
                 }
            }

            //$ano_proceso   = $factura->ano_fiscal; //Dualidad

            $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );

            //Hacer el llamado a la funcion que asigna los valores de la nota de credito
            $this->inicializar_montos_NC($factura,$msj,$mto_ncr,$iva_ncr,$tot_ncr_ncr,$ncr_sn,$nro_ncr);

            //----------------------------------------------------------------------------
            //----------------------------------------------------------------------------
            //Funcion que devuelve el año de recepcion de la factura
            $ano_recp_fac = '';
            $res = $this->Retorna_ano_de_recepcion($factura,$msj,$ano_recp_fac);

             if (!$res){
                alert()->error('No se encontro el año de recepcion de la factura. Favor Verifique');
                return redirect()->back()->withInput();
                    //     return $res;
             }

            //Buscar año de generación de Documento
            $ano_sol_doc = $factura->ano_sol;

            $grupo = '';

            //Buscar las Siglas del Documento
            $res = $this->Retornar_siglas_Documentos($msj,$grupo,$factura->tipo_doc);

            // if (!$res)
            //     return $res;

            //Actualizar los montos , % de iva y el numero de control en la tabla factura
            $res = $this->Actualizar_Datos_Factura($factura,$msj);

             if (!$res){
                alert()->error('No se pudo actualizar los montos , % de iva y el numero de control en la tabla factura. Favor Verifique');
                return redirect()->back()->withInput();
                //     return $res;
             }

            //---------------------------------------------------------------------------
            //Si la Factura tiene porcentaje de anticipo se debe ingresar los datos
            //en la tabla ant_amortizaciones que luego sera utilizados para el calculo
            //de las retenciones y deducciones, para ello es necesario saber el numero de
            //cuenta por cobrar asignado al proveedor
            //--------------------------------------------------------------------------
            //return $por_anticipo;
            if ($por_anticipo != null && $por_anticipo != '0.00'){
                //Buscar Cuenta x Cobrar del Proveedor
                $cta_x_cobrar = '';
                $res = $this->CuentaxCobrar_Proveedor($msj,$cta_x_cobrar,$factura);

                if (!$res){
                    alert()->error('No se pudo encontrar la cuenta por cobrar asignado al proveedor. Favor Verifique '.$msj);
                    return redirect()->back()->withInput();
                    //     return $res;
                 }

                //Ingresar Datos en la Tabla ant_amortizaciones
                $res = $this->actualizar_Amortizacion($factura,$msj,$cta_x_cobrar,$grupo);

                if (!$res){
                    alert()->error('Fallo al ingresar Datos en la Tabla ant_amortizaciones. Favor Verifique');
                    return redirect()->back()->withInput();
                    //     return $res;
                 }
            }


            //--------------------------------------------------------------------------------
            //Buscar la partida de IVA
            //--------------------------------------------------------------------------------
            $result_iva = '';

            $res = $this->Retornar_Partida_IVA($msj,$result_iva,$ano_proceso); //Dualidad

            if (!$res){
                alert()->error('Fallo al buscar la partida de IVA. Favor Verifique');
                return redirect()->back()->withInput();
                //     return $res;
             }

            //Eliminar Registro del gasto
            //return $factura;
            //------------------------------------------------------------------------------------
            // Se almacena en arreglo las estructura de gastos
            //------------------------------------------------------------------------------------
            $detgastosfactura =$facfactura->cxpdetgastosfactura;

//return  $detgastosfactura;
            foreach($detgastosfactura as $index => $gastos){

                $cxpdetgastosfactura[] = [
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
                    'Original'          => $gastos['Original'],
                    'nro_doc'           => $gastos['nro_doc'],
                    'ano_sol'           => $gastos['ano_sol']
                ];
            }
            $res = $this->Eliminar_Gasto_Factura($factura,$msj);

            if (!$res){
                alert()->error('Fallo al eliminar Registro del gasto. Favor Verifique '.$msj);
                return redirect()->back()->withInput();
                //     return $res;
             }

            //---------------------------------------------------------------------------------
            //Si el flujo viene por orden de compra se deben actualizar la cabecera de las notas
            //de entregas y llenar nuevamente la tabla cxp_detnotasfacturas que guarda la
            //asociacion de las notas de entregas con la factura
            //---------------------------------------------------------------------------------
            if ($factura->tipo_doc != 4){
                //Eliminar las notas de entregas asociadas a la factura
                $res = $this->Eliminar_notas_entregas($factura,$msj);

                if (!$res){
                    alert()->error('Fallo al eliminar las notas de entregas asociadas a la factura. Favor Verifique');
                    return redirect()->back()->withInput();
                    //     return $res;
                 }

                $detnotafacturas = $this->factura->cxpdetnotasfacturas;

                foreach($detnotafacturas as $row){
                    $marca = $row->seleccionar;
                    //----------------------------------------------------------
                    //Obtengo Numero de Nota de Entrega generada por el sistema,
                    //sin tomar en cuenta el grupo
                    //----------------------------------------------------------
                    $ano_entrega   = $row->ano_nota_entrega;
                    $pos 		   = strlen($row->nro_ent);
                    $nro_ent 	   = substr($row->nro_ent,3,($pos-2));
                    $cadena  	   = $row->nota_entrega;
                    $nro_documento = substr($factura->nro_doc,3,strlen($factura->nro_doc));

                    if ($marca == 'Si'){
                        //Actualizo la cabecera de la Nota de Entrega
                        $status = '4';
                        $numero_factura = $factura->num_fac;
                        $res = $this->Actualizar_Cabecera_Nota_Entrega ($factura,$msj,$status,$nro_documento,$grupo,$nro_ent,$ano_sol_doc,$numero_factura,$ano_entrega);

                        if (!$res){
                            alert()->error('Fallo actualizndo la cabecera de la Nota de Entrega. Favor Verifique');
                            return redirect()->back()->withInput();
                            //     return $res;
                         }

                        //Ingresar el Registro en la tabla cxp_detnotasfacturas
                        $res = $this->Inserta_cxp_detnotasfacturas($factura,$msj,$grupo,$nro_documento,$nro_ent,$row->mto_ord,$cadena,$row->base_imponible,$row->base_excenta,$row->mto_iva,$ano_proceso,$row->ano_nota_entrega); //Dualidad

                        if (!$res){
                            alert()->error('Fallo el registro en la tabla cxp_detnotasfacturas. Favor Verifique');
                            return redirect()->back()->withInput();
                            //     return $res;
                         }

                        $res = $this->Actualizar_status_cabecera_factura($factura,$msj,$ano_sol_doc,$row->nro_ent,$row->ano_nota_entrega,'3',$factura->num_fac);

                        if (!$res){
                            alert()->error('Fallo actualizando status de cabecera factura. Favor Verifique');
                            return redirect()->back()->withInput();
                            //     return $res;
                         }

                    }else{
                        //-----------------------------------------------------------
                        //  Se actualiza el status original de  la nota de Entrega
                        //para poder utilizada en otra factura, ya que fue desmarcada
                        //        en el proceso de modificación de la factura
                        //-------------------------------------------------------------
                        //Jose dijo que si el grupo es BM el status = 5 en caso contrario es 7
                        if ($grupo!='BM')
                            $status='7';
                        else
                            $status='5';

                        $numero_factura = null;
                        $res = $this->Actualizar_Cabecera_Nota_Entrega ($factura,$msj,$status,$nro_documento,$grupo,$nro_ent,$ano_sol_doc,$numero_factura,$ano_entrega);

                        if (!$res){
                            alert()->error('Fallo actualizando status de cabecera Nota de Entrega. Favor Verifique');
                            return redirect()->back()->withInput();
                            //     return $res;
                         }

                        $res = $this->Actualizar_status_cabecera_factura($factura,$msj,$ano_sol_doc,$row->nro_ent,$row->ano_nota_entrega,'1',$factura->num_fac);

                        if (!$res){
                            alert()->error('Fallo actualizando status de cabecera factura. Favor Verifique');
                            return redirect()->back()->withInput();
                            //     return $res;
                         }
                    }

                }

            }//Fin del if ($factura->tipo_doc != 4)

            //---------------------------------
            //     Buscar la partida de IVA
            //---------------------------------
            $result_iva = '';
            $res = $this->Retornar_Partida_IVA($msj,$result_iva,$ano_proceso);


            if (!$res){
                alert()->error('Fallo al Buscar la partida de IVA. Favor Verifique '.$msj);
                return redirect()->back()->withInput();
                //     return $res;
             }

            //-------------------------------------------------------------------------------------------
            // Insertar en la Tabla cxp_detgastosfactura,para ello debo recorrer el segundo grid
            //-------------------------------------------------------------------------------------------


            //$detgastosfactura =$facFactura->cxpdetgastosfactura;
            //return $facFactura->cxpdetgastosfactura;
            if ($cxpdetgastosfactura != null) {

                foreach($cxpdetgastosfactura as $index => $row2){

                    $tip_cod	= 0;
                    $cod_pryacc = 0;
                    $cod_obj	= 0;
                    $gerencia	= 0;
                    $unidad		= 0;
                    $cod_par	= 0;
                    $cod_gen	= 0;
                    $cod_esp	= 0;
                    $cod_sub	= 0;
                    $cod_com	= 0;
                    //Partida Presupuestaria
                    $cod_par    = $row2['cod_par'];
                    $cod_gen    = $row2['cod_gen'];
                    $cod_esp    = $row2['cod_esp'];
                    $cod_sub    = $row2['cod_sub'];

                    if ($ano_fiscal != $ano_sol_doc){
                        $res = $this->armar_centro_costo($msj,$tip_cod,$cod_pryacc,$cod_obj,
                                                            $gerencia,$unidad,$cod_com,$row2,$ano_proceso,$ano_sol_doc);
                        if (!$res){
                            alert()->error('Fallo al arnar centro de costo. Favor Verifique');
                            return redirect()->back()->withInput();
                            //     return $res;
                        }

                    }else{
                        $tip_cod	= $row2["tip_cod"];
                        $cod_pryacc = $row2["cod_pryacc"];
                        $cod_obj	= $row2["cod_obj"];
                        $gerencia	= $row2["gerencia"];
                        $unidad		= $row2["unidad"];
                        $cod_com 	= $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);
                    }
                    $gasto=$row2["gasto"];
                    // if ($row2["gasto"]=='Si')
                    //     $gasto = '1';
                    // else
                    //     $gasto = '0';

                    //LLenar la Tabla de la Estructura de gasto
                    $monto_nc = 0;

                    if (!empty($row2["presu_afectado"])){
                        if ($row2["presu_afectado"]=='Original')
                            $presu_afectado = '1';
                        else
                            $presu_afectado='0';
                    }else{
                        $res = false;
                        $msj = "Error el campo Pagado no puede estar vacio en la grilla del gasto de la factura.\\nComuniquese con el Administrador del Sistema.";
                    //    return $res;
                    }

                    $res = $this->Guarda_Estructura_Gasto_Factura($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,
                                                                $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$row2["sal_cau"],
                                                                $row2["sal_cau"],$gasto,$ano_proceso,$monto_nc,$presu_afectado);

                    if (!$res){
                        alert()->error('Fallo al guardar la estructura de gasto. Favor Verifique '.$msj);
                        return redirect()->back()->withInput();
                        //     return $res;
                    }


                }
            }

           //return $res;
            alert()->success('¡Éxito!', ' Factura Cambiada Sastifactoriamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.factura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            //dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();

        }
    }

    /**
     * anular a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ReqFacturaRequestuest  $factura
     * @return \Illuminate\Http\Response
     */
    //--------------------------------------------------------------------------
    //         Funcion para anular los Datos de la Factura
    //--------------------------------------------------------------------------
    public function anular(FacturaRequest $factura)
    {
        //return $factura;
        $ano_fiscal = $this->anoPro;

        $facfactura = Factura::where('ano_pro',$factura->ano_pro)
                             ->where('rif_prov',$factura->rif_prov)
                             ->where('num_fac',$factura->num_fac)
                             ->first();
        try{
            DB::connection('pgsql')->beginTransaction();

            //return $facfactura;
            if ($factura->ncr_sn == 'S') {
                // Se debe eliminar primero la nota de credito para
                // luego eliminar la factura
                $bandera = false;
                alert()->error('La Factura tiene asociada nota de creditos. Debe Anular primero la nota de credito asociada y luego la factura\nPor favor verifique.');
                return redirect()->back()->withInput();
            } else {
                $bandera = true;
            }

            $por_anticipo  = $factura->por_anticipo;
            $ano_proceso   = $factura->ano_fiscal; //Dualidad
            $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );

            //Funcion que devuelve el año de recepcion de la factura
            $ano_recp_fac='';
            $res = $this->Retorna_ano_de_recepcion($factura,$msj,$ano_recp_fac);

            if (!$res){
                alert()->error('Fallo al devolver devuelve el año de recepcion de la factura.Por favor verifique.');
                return redirect()->back()->withInput();
            }

            //Buscar año de generación de Documento
            $ano_sol_doc = $factura->ano_sol;

            $grupo = '';
            //Buscar las Siglas del Documento
            $res = $this->Retornar_siglas_Documentos($msj,$grupo,$factura->tipo_doc);

            if (!$res){
                alert()->error('Fallo al buscar las Siglas del Documento.Por favor verifique.');
                return redirect()->back()->withInput();
            }

            //Funcion que devuelve el año de recepcion de la factura
            $ano_recp_fac = '';
            $res = $this->Retorna_ano_de_recepcion($factura,$msj,$ano_recp_fac);

            if (!$res){
                alert()->error('Fallo al devolve el año de recepcion de la factura. or favor verifique.');
                return redirect()->back()->withInput();
            }

            //Actualizar el status en la Recepcion de factura
            $status = '0';
            $res = $this->Actualiza_Status_Recepcion_Factura($msj,$status,$ano_recp_fac,$factura->ano_pro,$factura);

            if (!$res){
                alert()->error('Fallo al actualizar el status en la Recepcion de factura. or favor verifique.');
                return redirect()->back()->withInput();
            }

            //Insetar en facturas Borradas
            $res = $this->Insert_Facturas_borradas($facfactura,$msj);

            if (!$res){
                alert()->error('Fallo al Insetar en facturas Borradas. Por favor verifique.');
                return redirect()->back()->withInput();
            }

            //Borrar la Factura
            $res = $this->Borrar_Factura($factura,$msj);

            if (!$res){
                alert()->error('Fallo al borrar la Factura. Por favor verifique.'.$msj);
                return redirect()->back()->withInput();
            }
            //---------------------------------------------------------------------------
            //Si la Factura tiene porcentaje de anticipo se debe eliminar los datos
            //                   en la tabla ant_amortizaciones
            //--------------------------------------------------------------------------

            if ($por_anticipo != '0.00' && $por_anticipo != null){
                $nro_documento=substr($factura->nro_doc,3,strlen($factura->nro_doc));
               // return $factura;
                $res = $this->Borrar_Amortizacion($facfactura,$msj,$factura->nro_doc);

                if (!$res){
                    alert()->error('Fallo al eliminar los datos en la tabla ant_amortizaciones. Por favor verifique.'.$msj);
                    return redirect()->back()->withInput();
                }
            }

            //--------------------------------------------------------------------------
            //            SI EL FLUJO VIENE POR ORDEN DE COMPRA
            //---------------------------------------------------------------------------
            if ($factura->tipo_doc !=4 ){
                //Eliminar las notas de entregas asociadas a la factura
                $res = $this->Eliminar_notas_entregas($factura,$msj);

                if (!$res){
                    alert()->error('Fallo al Eliminar las notas de entregas asociadas a la factura. Por favor verifique.'.$msj);
                    return redirect()->back()->withInput();
                }

                //-------------------------------------------------------------------------------------
                //      Update el status en el encabezado de la nota de Entrega asociadas a la factura
                //--------------------------------------------------------------------------------------
                $detnotafacturas = $this->factura->cxpdetnotasfacturas;

                foreach($detnotafacturas as $row){
                    $marca = $row->seleccionar;

                    if ($marca == 'Si'){
                        //----------------------------------------------------------
                        //Obtengo Numero de Nota de Entrega generada por el sistema,
                        //sin tomar en cuenta el grupo
                        //----------------------------------------------------------
                        $ano_entrega = $row->ano_nota_entrega;
                        $pos = strlen($row->nro_ent);
                        $nro_ent = substr($row->nro_ent,3,($pos-2));
                        $cadena = $row->nota_entrega;
                        $nro_documento = substr($factura->nro_doc,3,strlen($factura->nro_doc));

                        //Jose dijo que si el grupo es BM el status = 5 en caso contrario es 7
                        if ($grupo != 'BM')
                            $status = '7';
                        else
                            $status = '5';

                        $numero_factura = null;
                        $res = $this->Actualizar_Cabecera_Nota_Entrega ($factura,$msj,$status,$nro_documento,$grupo,$nro_ent,$ano_sol_doc,$numero_factura,$ano_entrega);

                        if (!$res){
                            alert()->error('Fallo al actualizar cabecera nota entrega. Por favor verifique.'.$msj);
                            return redirect()->back()->withInput();
                        }
                        $res = $this->Actualizar_status_cabecera_factura($factura,$msj,$ano_sol_doc,$row->nro_ent,$row->ano_nota_entrega,'4',$factura->num_fac);

                        if (!$res){
                            alert()->error('Fallo al actualizar status de cabecera factura. Por favor verifique.'.$msj);
                            return redirect()->back()->withInput();
                        }
                    }
                }
            }else{ //fin del  if ($factura->tipo_doc !=4 )
                $res = $this->Actualizar_status_cabecera_factura($factura,$msj,$ano_sol_doc,$factura->nro_doc,$ano_sol_doc,'4',$factura->num_fac);

                if (!$res){
                    alert()->error('Fallo al actualizar status de cabecera factura. Por favor verifique.'.$msj);
                    return redirect()->back()->withInput();
                }
            }

            //Eliminar Registro del gasto
            $res = $this->Eliminar_Gasto_Factura($factura,$msj);

            if (!$res){
                alert()->error('Fallo al Eliminar Registro del gasto. Por favor verifique.'.$msj);
                return redirect()->back()->withInput();
            }

            alert()->success('¡Éxito!', ' Factura Anulada Satisfactoriamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.factura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            //dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();

        }


    }

     /**
     * cambiar a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ReqFacturaRequestuest  $factura
     * @return \Illuminate\Http\Response
     */
    //--------------------------------------------------------------------------
    //         Funcion para aprobar los Datos de la Factura
    //--------------------------------------------------------------------------
    public function aprobar(FacturaRequest $factura)
    {
//return $factura;

        $ano_fiscal = $this->anoPro;

        $facfactura = Factura::where('ano_pro',$factura->ano_pro)
                             ->where('rif_prov',$factura->rif_prov)
                             ->where('num_fac',$factura->num_fac)
                             ->first();
        try{
            DB::connection('pgsql')->beginTransaction();

            //---------------------------------------------------------------------------------------------------------

			$ncr_sn 	   = $factura->ncr_sn;
			$res 		   = true;
			$ano_proceso   = $ano_fiscal; //Dualidad
			$fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd');
			$sta_fac 	   = '1';//Aprobada Presupuestariamente
		    $usu 		   = 'usua_apr';
		    $fecha 		   = 'fec_apr';

		    $res = $this->Actualizar_statu_Factura($factura,$msj,$sta_fac,$usu,$fecha);

            if (!$res){
                alert()->error('Fallo actualizando status de la factura. Por favor verifique.'.$msj);
                return redirect()->back()->withInput();
            }

			//Buscar la Partida de Iva
			$result_iva = '';
			$res = $this->Retornar_Partida_IVA($msj,$result_iva,$ano_proceso); //Dualidad

            if (!$res){
                alert()->error('Fallo buscando la Partida de Iva. Por favor verifique.'.$msj);
                return redirect()->back()->withInput();
            }
		    //---------------------------------------------------------------------------------
		    //Si Existe Nota de Credito se debe buscar las partidas presupuestrias afectadas
		    //---------------------------------------------------------------------------------
			$result_nc = '';

			if ($ncr_sn == 'S'){
				$res = $this->Partidas_Nota_credito($factura,$msj,$result_nc);

                if (!$res){
                    alert()->error('Fallo buscando las partidas presupuestrias afectadas de nota de crédito. Por favor verifique.'.$msj);
                    return redirect()->back()->withInput();
                }
			}

			//-------------------------------------------------------------------------------------
			// Si el flujo viene por orden de compra :
			// - Se debe cambiar el status de la factura en aprobada Presupuestariamente
			// - Crear el asiento Contable del Iva
			// - Realizar el Causado presupuestario de la partida de IVA
			// - Si posee Nota de Credito se debe Ajustar el compromiso y el causado
			// - Realizar el asiento contable correspondiente a la nota de credito
			// - Verificar si existe cambio en la alicuota de IVA Ajustar el compromiso de la partida de IVA
			// - Si posee Nota de Debito se debe:
			// - Comprometer el monto de la factura + el monto iva de la nota de debito
			// - Realizar el asiento contable correspondiente a la nota de debito
			//--------------------------------------------------------------------------------------

	        //--------------------------------------------------------------------------------------
	        // Cuando el Flujo viene por Certificacion de Pagos Directos o Contrato de Obras:
	        // Realiza el causado  presupuestario tanto del gasto como de la partida de IVA
	        // Realizar el asiento Contable tanto del gasto como de la partida de IVA
	        // Si posee Nota de Credito se debe Modificar el compromiso
	        // - Realizar el asiento contable correspondiente a la nota de credito
	        // -------------------------------------------------------------------------------------------------
	        // Si tiene Amortizaciòn se debe crear el asiento correspondiente a credito de la amortizacion
	        // Cuando se cancela con dinero Externo solo se crea el asiento de IVA
            // Cuando se cancela con dinero Interno se debe crear el asiento del IVA y del Gasto
	        //-------------------------------------------------------------------------------------------------

	        switch ($factura->tipo_doc){
				case  '1':
				case  '2':
				case  '3':
				case '4':
				case '5':
					if ($factura->provisionada=='S'){

						$res = $this->Aprobar_Factura_provision($factura,$msj,$result_iva,$result_nc,$ano_proceso); //Dualidad

                    }
					else

						$res = $this->Aprobar_Factura($factura,$msj,$result_iva,$result_nc,$ano_proceso); //Dualidad


                      //  if (!$res)
					//	return $res;
                      //  dd($res);
					break;
			}//Fin del switch




            alert()->success('¡Éxito!', ' Factura Aprobada Sastifactoriamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.factura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            //dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj);
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Factura $factura,$valor)
    {
        //$factura = Factura::where('id',$id)->get();
         //return $factura;
        $proveedores = Beneficiario::whereIn('tipo',['P','E','O'])
                                   ->select('rif_ben','nom_ben')
                                   ->orderBy('nom_ben')
                                   ->get();

        $cxptipodocumento = CxPTipoDocumento::query()
                                            ->where('status','1')
                                            ->where('recp_factura','1')
                                            ->get();
        switch ($valor) {
            case "show":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.factura.show';
                break;
            case "cambiar":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.factura.cambiar';
                break;
            case "anular":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.factura.anular';
                break;
            case "aprobar":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.factura.aprobar';
                break;
            case "reversar":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.factura.reversar';
                break;
            case "modificar":
                $ruta='administrativo.meru_administrativo.cuentasxpagar.proceso.factura.modificar';
                break;
        }


        return view($ruta, compact('factura','proveedores','cxptipodocumento'));

    }
    /**
     * Reversar a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ReqFacturaRequestuest  $factura
     * @return \Illuminate\Http\Response
     */
    //--------------------------------------------------------------------------
    //         Funcion para Reversar los Datos de la Factura
    //--------------------------------------------------------------------------
    public function reversar(FacturaRequest $factura)
    {
        //return $factura;
        $ano_fiscal = $this->anoPro;
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

        $facfactura = Factura::where('ano_pro',$factura->ano_pro)
                             ->where('rif_prov',$factura->rif_prov)
                             ->where('num_fac',$factura->num_fac)
                             ->first();

        try{
            DB::connection('pgsql')->beginTransaction();
            //----------------------------------------------------------------

            if ($factura->provisionada == 'S')
               $res = $this->Datos_reversar_PROVISION($factura,$msj);
            else
                $res = $this->Datos_reversar($factura,$msj);



            //----------------------------------------------------------------
            alert()->success('¡Éxito!', ' Factura Reversada Sastifactoriamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.factura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            //dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();

        }

    }

    //--------------------------------------------------------------------------
    //         Funcion para modificar asientos de la Factura
    //--------------------------------------------------------------------------
    public function modificar(FacturaRequest $factura)
    {
        //return $factura;
        $ano_fiscal = $this->anoPro;
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

        $facfactura = Factura::where('ano_pro',$factura->ano_pro)
                             ->where('rif_prov',$factura->rif_prov)
                             ->where('num_fac',$factura->num_fac)
                             ->first();

        try{
            DB::connection('pgsql')->beginTransaction();
            //----------------------------------------------------------------

			$ano_proceso   = $facfactura->ano_fiscal; //Dualidad
			$fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );

		    //Eliminar Asiento Contable
		    $nc  = '0';
			$res = $this->Eliminar_comprobante_contable($factura,$msj,'0');

			if (!$res){
                alert()->error('Fallo! al eliminar comprobante contable');
                 return redirect()->back()->withInput();
            }
			$x = 1;
            //------------Pestaña de asientos------------------------//
            $detcomprofacturas =$this->factura->cxpdetcomprofacturas;

            foreach($detcomprofacturas as $row2){

                //Validar que la cuenta exista en la tabla de aportes
                $res =  PlanContable::where('cod_cta',$row2->cod_cta)
                                    ->where('ano_cta',$ano_proceso)
                                    ->select('cod_cta')
                                    ->first();


                if (empty( $res->cta_con)){
                    $res = false;
                    $msj = "Cuenta Contable [" . $row2["cod_cta"] . "] no EXiste en la Tabla con_ctas_aportes.\\nFavor Verifique.";
                    alert()->error($msj);
                    return redirect()->back()->withInput();
                }else{
                    $res = $this->Crear_Comprobante($factura,$msj,$factura->nro_doc,$x,$row2->cod_cta,
                                                    $row2->monto,$row2->tipo,'0',$factura->ano_sol,$factura->ano_pro);

                    if (!$res){
                        alert()->error('Fallo al crear comprobante');
                        return redirect()->back()->withInput();
                    }
                    $x = $x + 1;
                }
			}



            //----------------------------------------------------------------
            alert()->success('¡Éxito!', ' Asientos Modificados Sastifactoriamente');

            DB::connection('pgsql')->commit();

            return redirect()->route('cuentasxpagar.proceso.factura.index');

        }   catch(\Illuminate\Database\QueryException $e){
            //dd($e->getMessage().' '.$msj);
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
