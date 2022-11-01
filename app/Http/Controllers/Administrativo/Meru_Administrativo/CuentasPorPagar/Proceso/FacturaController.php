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
return $factura;
        // -----------------------------------------------------------------------
        // Verifica el status de la factura en la tabla
        // de recepcion de factura
        // para validar que la factura no este devuelta
        // o entregada
        // ------------------------------------------------------------------------
        // if ($factura->sta_fac != 0 ){
        //     alert()->error('Factura Recepcionada con estatus invalido para operación');
        //     return redirect()->back()->withInput();
        // }else{
        //     // -----------------------------------------------------------------------
        //     // Valida que la Factura Venga por orden de
        //     // Compra O Pagos Directos
        //     // -----------------------------------------------------------------------
        //     if ($factura->tipo_doc < 6) {
        //         // ----------------------------------------------------------
        //         // Valida que la factura no este
        //         // ingresada en el sistema
        //         // ----------------------------------------------------------
        //         $ingresada = Factura::where('ano_pro', $factura->ano_pro)
        //                                ->where('rif_prov',$factura->rif_prov)
        //                                ->where('num_fac', $factura->num_fac)
        //                                ->where('recibo',  $factura->recibo)
        //                                ->selectRaw("DISTINCT sta_fac, TO_CHAR(fecha,'dd/mm/yyyy') AS fecha,nro_doc,tipo_doc ")
        //                                ->first();

        //         if ($ingresada){

        //             switch ($ingresada->tipo_doc) {
        //                 case "1":
        //                     $descrip = "La Orden de Compra Nro: ";
        //                     break;
        //                 case "2":
        //                      $descrip = "La Orden de Servicios Nro: ";
        //                     break;
        //                 case "3":
        //                      $descrip = "La Orden de Servicios Nro: ";
        //                     break;
        //                 case "4":
        //                      $descrip = "La Certificacion de Servicios: ";
        //                     break;
        //                 case "5":
        //                      $descrip = "El Contrato Nro: ";
        //                     break;
        //             }
        //             alert()->error('Factura ya esta Ingresada en Sistema el '.$ingresada->fecha. '  Para Pagar '. $descrip. $factura->nro_doc. '. Por favor verifique.');
        //             return redirect()->back()->withInput();
        // //         }else{

        //            // Validar_Flujo(row_recep[0], row_recep[3], row_recep[4], row_recep[7], num_fac, row_recep[2], row_recep[5], row_recep[9]);
        //             $this->Validar_Flujo($factura->rif_prov,$factura->tipo_doc, $factura->nro_doc, $factura->ano_sol, $factura->num_fac, $factura->fecha_fac, $factura->statu_recep, $factura->recibo);
        //          }

        //     }else{
        //         alert('Error la Factura no viene por Orden de Compra, Actas de Servicios,Contrato o Certificacion de Servicios. Favor Verifique');
        //         return redirect()->back()->withInput();
        //     }
        // }
        // $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

        // try {


        //     //------------Validar_datos_ingresar-----------//
        //     $porcentaje_iva = $this->porcentaje_iva;
        //     dd($porcentaje_iva);
        //     $sw = false;
        // }        //     // -------------------------------------------------------------------------------------
        // //     // Validar que si existe partida de Iva entonces el % de iva debe ser
        // //     // distincto de 0
        //     // en caso contrario el % de iva debe ser 0
        //     // -------------------------------------------------------------------------------------
        //     // var grid1 = Utils.grids[1]; // Instanciar Grilla de Datos de Usuarios
        //     // var data = grid1.getData();
        //     // for (var i = 0; i < data.length; i++) {
        //     //     row_grilla = data[i];
        //     //     if (row_grilla[6] == row_iva[0] && row_grilla[7] == row_iva[1] && row_grilla[8] == row_iva[2] && row_grilla[9] == row_iva[3]) {
        //     //         sw = true;
        //     //     }
        //     // }
        //     // // Existe Partida de Iva
        //     // if (sw) {
        //     //     if (porcentaje_iva != 0) {
        //     //         bandera = true;
        //     //     } else {
        //     //         bandera = false;
        //     //         alert('Error el % de Iva no puede ser 0.\n Favor Verifque');
        //     //     }
        //     // } else { // No Existe partida de IVA el % debe ser 0
        //     //     if (porcentaje_iva == 0) {
        //     //         bandera = true;
        //     //     } else {
        //     //         bandera = false;
        //     //         alert('Error el % de Iva debe  ser 0.\n Favor Verifque');
        //     //     }
        //     // }


        //     DB::connection('pgsql')->beginTransaction();


        //     $nro_ncr        = $factura->nro_ncr;
        //     $por_anticipo   = $factura->por_anticipo;
        //     $nro_doc        = $factura->nro_doc;
        //     $res            = true;
        //     $grupo          = '';
        //     $ano_proceso    = $factura->ano_fiscal;
        //     $fecha_proceso  = $this->FechaSistema($ano_proceso,'Ymd' );


        //     //Buscar las Siglas del Documento
		//  	$res = $this->Retornar_siglas_Documentos($db,$conn,$datosDetalle,$tablasDetalle,$msj,$grupo);

        //      if (!$res)
        //           return $res;

        //     //Actualizar el status en la Recepcion de factura
        //     $status = '1';
        //     $res = $this->Actualiza_Status_Recepcion_Factura($db,$conn,$datosDetalle,$tablasDetalle,$msj,$status,$this->getCampo("ano_pro")->valor,$ano_proceso); //Dualidad

        //     if (!$res)
        //           return $res;

        //     //Buscar año de generación de Documento
        //     $ano_sol_doc = $factura->ano_sol;

        //     //---------------------------------------------------------------------------
		// 	//Si la Factura tiene porcentaje de anticipo se debe ingresar los datos
		// 	//en la tabla ant_amortizaciones que luego sera utilizados para el calculo
		// 	//de las retenciones y deducciones, para ello es necesario saber el numero de
		// 	//cuenta por cobrar asignado al proveedor
		// 	//--------------------------------------------------------------------------
        //     if ($por_anticipo != '0.00'){
		// 		//Buscar Cuenta x Cobrar del Proveedor
		// 		$cta_x_cobrar = '';
		// 		$res = $this->CuentaxCobrar_Proveedor($db,$conn,$datosDetalle,$tablasDetalle,$msj,$cta_x_cobrar);

		// 		if (!$res)
		// 			return $res;

		// 		//Ingresar Datos en la Tabla	ant_amortizaciones
		// 		$res = $this->ant_amortizaciones($db,$conn,$datosDetalle,$tablasDetalle,$msj,$cta_x_cobrar,$grupo,$ano_proceso);

		// 		if (!$res)
		// 			return $res;
		//  	}

        //     //--------------------------------------------------------------------------------
		// 	//--------------------------------------------------------------------------------
		//   	//Buscar la partida de IVA
		//   	$result_iva = '';
		//   	$res = $this->Retornar_Partida_IVA($db,$conn,$datosDetalle,$tablasDetalle,$msj,$result_iva,$ano_proceso); //Dualidad

		//   	if (!$res)
		// 		return $res;

        //     //-------------------------------------------------------------------------------------------
		// 	//Insertar en la Tabla cxp_detgastosfactura,para ello debo recorrer el segundo grid
		// 	//-------------------------------------------------------------------------------------------
        //     $facrecepfactura = FacRecepFactura::where('ano_pro',$factura->ano_pro)
        //                                       ->where('rif_prov',$factura->rif_prov)
        //                                       ->where('num_fac',$factura->num_fac)
        //                                       ->first();

		// 	foreach( $facrecepfactura->opsolservicio->opdetgastossolservicio as  $tabCols){

        //         if ($ano_fiscal != $ano_sol_doc){
        //             $res = $this->armar_centro_costo($db,$conn,$datosDetalle,$tablasDetalle,$msj,$tip_cod,$cod_pryacc,$cod_obj,
        //                                              $gerencia,$unidad,$cod_com,$row2,$ano_proceso,$ano_sol_doc); //Dualidad
        //             if (!$res)
        //                  return $res;
        //         }else{
        //             $tip_cod	= $row2["tip_cod"];
        //             $cod_pryacc = $row2["cod_pryacc"];
        //             $cod_obj	= $row2["cod_obj"];
        //             $gerencia	= $row2["gerencia"];
        //             $unidad		= $row2["unidad"];
        //             $cod_com	= armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);
        //         }

        //         //anticipo
        //         if ($row2["gasto"] == 'Si')
        //             $gasto='1';
        //         else
        //             $gasto='0';

        //         //LLenar la Tabla de la Estructura de gasto
        //         $monto_nc = 0;

        //         if (!empty($row2["presu_afectado"])){
        //                 if ($row2["presu_afectado"]=='Original')
        //                     $presu_afectado = '1';
        //                 else
        //                     $presu_afectado = '0';
        //         }else{
        //             $res = $db->execQuery($conn, 'c');
        //             $msj = "Error el campo Pagado no puede estar vacio en la grilla del gasto de la factura.\\nComuniquese con el Administrador del Sistema.";
        //             return $res;
        //         }

        //         $detnotaentrega = CxPDetGastosFactura::create([
        //             'ano_pro'            => $tabCols->ano_pro,
        //             'rif_prov'           => $tabCols->rif_prov,
        //             'num_fac'            => $tabCols->num_fac,
        //             'tip_cod'            => $tabCols->tip_cod,
        //             'cod_pryacc'         => $tabCols->cod_pryacc,
        //             'cod_obj'            => $tabCols->cod_obj,
        //             'gerencia'           => $tabCols->gerencia,
        //             'unidad'             => $tabCols->unidad,
        //             'cod_par'            => $tabCols->cod_par,
        //             'cod_gen'            => $tabCols->cod_gen,
        //             'cod_esp'            => $tabCols->cod_esp,
        //             'cod_sub'            => $tabCols->cod_sub,
        //             'gasto'              => $tabCols->gasto,
        //             'cod_com'
        //             'presu_afectado'
        //     ]);



        //     mto_tra
        //     sal_cau

        //     ano_sol
        //     nro_doc
        //     mto_nc

		// 	    	foreach ($colsv as &$row2){

		// 	    		//Partida Presupuestaria
		// 	            $cod_par    = $row2["cod_par"];
		// 				$cod_gen    = $row2["cod_gen"];
		// 				$cod_esp    = $row2["cod_esp"];
		// 				$cod_sub    = $row2["cod_sub"];


		// 		        $res = $this->Guarda_Estructura_Gasto_Factura($db,$conn,$datosDetalle,$tablasDetalle,$msj,$tip_cod,$cod_pryacc,$cod_obj,
		// 															  $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$row2["sal_cau"],
		// 															  $row2["sal_cau"],$gasto ,$ano_proceso,$monto_nc,$presu_afectado); //Dualidad
		// 			    if (!$res){
		// 			 	  return $res;
		// 			    }

		// 			}
		// 		}
		// 	}//Fin del foreach













        //     alert()->success('¡Éxito!', ' Factura Registrado Sastifactoriamente');

        //     DB::connection('pgsql')->commit();

        //     return redirect()->route('cuentasxpagar.proceso.factura.index');

        // }
        //     catch(\Illuminate\Database\QueryException $e){
        //         //dd($e->getMessage().' '.$msj);
        //         DB::connection('pgsql')->rollBack();
        //         alert()->error('¡Transacción Fallida!'.$msj.' '. $e->getMessage());
        //         return redirect()->back()->withInput();
        // }







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
        }


        return view($ruta, compact('factura','proveedores','cxptipodocumento'));

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
