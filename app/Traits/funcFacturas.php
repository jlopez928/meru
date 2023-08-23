<?php

    namespace App\Traits;

    use App\Http\Livewire\Administrativo\MeruAdministrativo\CuentasPorPagar\Proceso\FacturaCreate;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPCabeceraFactura;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Factura;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacRecepFactura;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
    use App\Models\Administrativo\Meru_Administrativo\Contabilidad\PlanContable;
    use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
    use App\Models\Administrativo\Meru_Administrativo\Contabilidad\AntAmortizacion;
    use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
    use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
    use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
    use App\Models\Administrativo\Meru_Administrativo\Presupuesto\PreNotaDeCredito;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPDetGastosFactura;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPDetNotaFactura;
    use App\Models\Administrativo\Meru_Administrativo\Compra\EncNotaEntrega;
    use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
    use App\Models\Administrativo\Meru_Administrativo\General\Usuario;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\FacturaBorrada;
    use App\Models\Administrativo\Meru_Administrativo\Presupuesto\PreDetNotaDeCredito;
    use App\Models\Administrativo\Meru_Administrativo\Presupuesto\PreMovimiento;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxpDetComproFacturas;
    use Illuminate\Support\Facades\DB;
    use App\Models\Administrativo\Meru_Administrativo\Formulacion\ControlPresupuesto;


trait funcFacturas
{
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


    // -----------------------------------------------------------------------------------------
    // Funcion que valida si se puede recepcionar o aplicar cualquier accion
    // dependiendo de
    // los diferentes status de la factura
    // ------------------------------------------------------------------------------------------
    function Validar_status($accion, $valor, $fecha) {
        // rif_prov = $("#rif_prov").val();
        // num_fac = $("#num_fac").val();
        // num_fac = $("#num_fac").val();
        //dd($fecha);
         $fecha_cau = $fecha->format('Y');
        // ano_fiscal = Utils.ano_pro;
        switch ($accion) {
            // Nuevo
            case "N":
                if ($valor == '0') {
                    return true;
                } else {
                    $estado = $this->descrip_statu($valor);
                    alert()->error('Factura con '. $estado. ' Por favor verifique');
                    //return redirect()->back()->withInput();
                    return false;
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
                            alert()->error('Factura con Status Invalido.('.$estado.'). Por favor verifique.');
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
                            alert()->error('Factura con Status Invalido.('.$estado.'). Por favor verifique.');
                            return redirect()->back()->withInput();
                        }
                    }
                }
        }
    }

    //-----------------------------------------------------------------------------------
    //          Funcion retornar las siglas del tipo de documento
    //---------------------------------------------------------------------------------
    function Retornar_siglas_Documentos(&$msj,&$grupo,$tipo_doc){
        $res = true;

        $result_grupo = CxPTipoDocumento::query()
                        ->where('cod_tipo',$tipo_doc)
                        ->select('siglas')
                        ->first();

        if (!empty($result_grupo->siglas))
                $grupo  = $result_grupo->siglas;
        else{
            $grupo = '';
            $msj = "Error al Consultar las Siglas del Documento.\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }


    //---------------------------------------------------------------------------------
    // Funcion que formatea un entero a unaFormatear cadena de caracteres,
    // ejm: entero = 100, si se necesita de 6 caracteres queda: 000100
    // @param {string} $valor: Valor de cadena sin formatear
    // @param {integer} $n: Numero de ceros(0) concatenados a la izquierda
    // @return $str -> Cadena formateada
    //---------------------------------------------------------------------------------
    function formatear($valor,$n){
        $str = '';
        $n = $n-strlen($valor);
        for($i=0;$i<$n;$i++){
            $str .='0';
        }
        $str .=$valor;
        return $str;
    }

    public function FechaSistema($ano_pro, $format = 'YmdHis')
    { /// no es necesario se debe eliminar por la variable global
        date_default_timezone_set("America/Caracas");
        $ano_actual = date('Y');

        if ($ano_pro!=$ano_actual){
            $fecha1 = "$ano_pro-12-31 20:00";
            $fecha = date($format, strtotime($fecha1));
        }else{
            $fecha = date($format);
        }
        return $fecha;
    }


    //----------------------------------------------------------------------
    //     Funcion que actualiza el status de la Recepción Factura
    //----------------------------------------------------------------------
    function Actualiza_Status_Recepcion_Factura(&$msj,$status,$ano_recepcion,$ano_factura,$factura){
        $ano_proceso   = $factura->ano_fiscal; //Dualidad
        $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd');
        $res = true;

        //Cuando se anula la factura de debe actualizar la fecha de ingreso de factura
        if ($status == '0')
            $ano_factura = '1900';

            $res = FacRecepFactura::where('ano_pro',  $ano_recepcion)
                                  ->where('rif_prov', $factura->rif_prov)
                                  ->where('num_fac', $factura->num_fac)
                                  ->where('recibo', $factura->recibo)
                                  ->update(['sta_fac'           =>$status,
                                            'fec_sta'           => $fecha_proceso,
                                            'ano_ing_factura'   => $ano_factura,
                                           ]);

        if (!$res){
            $msj = "Error al Actualizar el status de la Recepcion de Facturas. omuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }


    //--------------------------------------------------------------------
    // Funcion que Retorna la Cuenta x Cobrar al Proveedor
    //--------------------------------------------------------------------
    function CuentaxCobrar_Proveedor(&$msj,&$cta_x_cobrar,$factura){
        $res = true;

        $result = Beneficiario::where('rif_ben',$factura->rif_prov )
                                     ->select('cta_x_cobrar')
                                     ->first();

        if (!empty($result->cta_x_cobrar)){
            $result_cuenta = PlanContable::where('cod_cta', $result->cta_x_cobrar)
                                         ->select('cod_cta')
                                         ->first();

            if (!empty($result_cuenta->cod_cta))
                    $cta_x_cobrar  = $result->cta_x_cobrar;
            else{
                $res = false;
                $msj = "Cuenta x Cobrar asociada al proveedor [ " . $result->cta_x_cobrar. " ] No Existe en Plan Contable. Por favor verifique.";
                return $res;
            }
        }else{
            $res = false;
            $msj = "El Beneficiario no tiene Cuenta x Cobrar asociada. Por favor verifique.";
            return $res;
        }

        return $res;
    }
    //-----------------------------------------------------------------
    // Funcion que ingresa el Registro en la Tabla de Amortizaciones
    //-----------------------------------------------------------------
    function ant_amortizaciones(&$msj,$cta_x_cobrar,$grupo,$ano_fiscal,$factura){
        $res = true;
        //dd($cta_x_cobrar."|".$grupo."|".$ano_fiscal);
        $res =  AntAmortizacion::create([
                        'ano_pro'               => $ano_fiscal ,
                        'grupo'                 => $grupo,
                        'nro_doc'               => $factura->nro_doc,
                        'rif_ben'               => $factura->rif_prov,
                        'nro_factura'           => $factura->num_fac,
                        'total_anticipo'        => $factura->mto_anticipo,
                        'mto_amortizacion'      => $factura->mto_amortizacion,
                        'cta_x_cobraranticipo'  => $cta_x_cobrar,
                    ]);


        if (!$res){
            $msj = "Error al Insertar Datos de la Amortizacion. Comuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }

    //------------------------------------------------------------------------------
    //            Function que retorna la partida de iva para el año Fiscal
    //------------------------------------------------------------------------------
    function Retornar_Partida_IVA(&$msj,&$result_iva,$ano_fiscal){
        $res = true;
        $result_iva  = Registrocontrol::where('ano_pro',$ano_fiscal)
                                      ->select('tip_codi', 'cod_pryacci', 'cod_obji', 'gerenciai', 'unidadi', 'cod_pari','cod_geni','cod_espi', 'cod_subi')
                                      ->first();
//dd($result_iva);

        if (!empty($result_iva->tip_codi))
            return $res;
        else{
            $res = false;
            $msj = "Error al Consultar Partidas de Iva para el año fiscal. Comuniquese con el Administrador del Sistema.";
            return $res;
        }
    }

    //--------------------------------------------------------------------------------------------------------
    //Funcion que arma el centro de costo validando que si cambio le concatena el nuevo centro de costo
    //                       asociado a la gerencia
    //-------------------------------------------------------------------------------------------------------
    function armar_centro_costo(&$msj,&$tip_cod,&$cod_pryacc,&$cod_obj,
                                &$gerencia,&$unidad,&$cod_com,$row2,$ano_fiscal,$ano_doc){
        $res = true;

        //Concatenar el centro de Costo que trae la orden o la certificacion
        $cod_centro = $this->Concatenar_Centro_Costo($row2["tip_cod"],$row2["cod_pryacc"],$row2["cod_obj"],$row2["gerencia"],$row2["unidad"]);

        //partida presupuestaria que trae la orden o la certificacion
        $partida = $this->Concatenar_Partida($row2["cod_par"],$row2["cod_gen"],$row2["cod_esp"],$row2["cod_sub"]);
        //Busca el centro de costo actual
        $centro_costo = '';
        $res = $this->Buscar_centro_costo_actual($msj,$cod_centro,$centro_costo,$partida,$ano_fiscal,$ano_doc);

        if (!$res)
            return $res;

        //Validar Centro de Costo
        if ($cod_centro == $centro_costo){
            //La Estrucutra presupuestaria queda tal cual como viene
            $cod_com = $cod_centro . "." . $partida;
            $tip_cod = $row2["tip_cod"];
            $cod_pryacc = $row2["cod_pryacc"];
            $cod_obj = $row2["cod_obj"];
            $gerencia = $row2["gerencia"];
            $unidad = $row2["unidad"];
            }else{
                //Se debe cambiar el centro de costo por el que posee actualmente la gerencia
                $cod_com = $centro_costo . "." . $partida;
                $tip_cod = $this->Desconcatenar_Centro_Costo($centro_costo,0,2);
                $cod_pryacc = $this->Desconcatenar_Centro_Costo($centro_costo,3,2);
                $cod_obj = $this->Desconcatenar_Centro_Costo($centro_costo,6,2);
                $gerencia = $this->Desconcatenar_Centro_Costo($centro_costo,9,2);
                $unidad = $this->Desconcatenar_Centro_Costo($centro_costo,12,2);
        }

        return $res;
    }
    //----------------------------------------------------------------------------------
    //                Funcion que Concatena el centro de costo
    //-----------------------------------------------------------------------------------
    function Concatenar_Centro_Costo($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad){
        $str_centro = $this->formatear($tip_cod, 2) . "." . $this->formatear($cod_pryacc,2) . "." .$this->formatear($cod_obj,2) . "." .
        $this->formatear($gerencia, 2) . "." . $this->formatear($unidad, 2);
        return $str_centro;
    }


    //----------------------------------------------------------------------------------
    //                Funcion que Concatena la Partida
    //-----------------------------------------------------------------------------------
    function Concatenar_Partida($cod_par,$cod_gen,$cod_esp,$cod_sub){
        $str_partida = $this->formatear($cod_par, 2) . "." . $this->formatear($cod_gen, 2) . "." . $this->formatear($cod_esp, 2) . "." .
                        $this->formatear($cod_sub, 2);
        return $str_partida;
    }


		//-------------------------------------------------------------------------------------
    //          Funcion que busca si el centro de costo ha sido modificado
    //--------------------------------------------------------------------------------------
    function Buscar_centro_costo_actual(&$msj,$centro_viejo,&$centro_costo,$partida,$ano_fiscal,$ano_doc){
        $res = true;
        $result_centro_costo = CentroCosto::query()
                                    ->where('ano_pro', $ano_doc)
                                    ->where('cod_cencosto', $centro_viejo)
                                    ->get(['ajust_ctrocosto']);

        if (!empty($result_centro_costo[0]->ajust_ctrocosto)){
            $centro_costo = $result_centro_costo[0]->ajust_ctrocosto;

            //----------------------------------------------------------------------------
            //Validar que el centro de costo exista en pre_maestro_ley para el año fiscal
            //----------------------------------------------------------------------------
            $estructura_presupuestaria = $centro_costo . "." . $partida;
            $result = MaestroLey::query()
                             ->where('ano_pro', $ano_fiscal)
                             ->where('cod_com', $estructura_presupuestaria)
                             ->get();

            if (!$result){
                $centro_costo='';
                $res	=false;
                $msj 	= "Partida Presupuestaria [" . $estructura_presupuestaria  . " ] No Existe en Pre Maestro Ley .\\nPor favor verifique.";
                return $res;
            }
        }else{
            $result_centro_costo = CentroCosto::query()
                                            ->where('ano_pro', $ano_fiscal)
                                            ->where('cod_cencosto', $centro_viejo)
                                            ->get(['ajust_ctrocosto']);



            if (!empty($result_centro_costo[0]->ajust_ctrocosto)){
                $centro_costo  = $result_centro_costo[0]->ajust_ctrocosto;

                //----------------------------------------------------------------------------
                //Validar que el centro de costo exista en pre_maestro_ley para el año fiscal
                //----------------------------------------------------------------------------
                $estructura_presupuestaria = $centro_costo . "." . $partida;

                $result = MaestroLey::query()
                                    ->where('ano_pro', $ano_fiscal)
                                    ->where('cod_com', $estructura_presupuestaria)
                                    ->get();

                if (!$result){
                    $centro_costo = '';
                    $res = false;
                    $msj = "Partida Presupuestaria [ " . $estructura_presupuestaria . " ] No Existe en Pre Maestro Ley. Por favor verifique.";
                    return $res;
                }
            }else{
                $res = false;
                $msj = "Error.\\nGerencia no tiene Asociado Centro de Costo.\\nComuniquese con el Administrador del Sistema.";
                return $res;
            }
        }

        return $res;
    }
    //----------------------------------------------------------------------------------
    //                Funcion que desconcatena el centro de costo
    //-----------------------------------------------------------------------------------
    function Desconcatenar_Centro_Costo($Centro,$posicion_inicio,$posicion_fin){
        $Centro = substr($Centro, $posicion_inicio, $posicion_fin);
        return $Centro;
    }

    //----------------------------------------------------------------------------------
    //                Funcion que codigo contable
    //-----------------------------------------------------------------------------------
    function armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub){
        $str = $this->formatear($tip_cod,2).".". $this->formatear($cod_pryacc,2).".".$this->formatear($cod_obj,2).".".
               $this->formatear($gerencia,2).".".$this->formatear($unidad,2).".".    $this->formatear($cod_par,2).".".
               $this->formatear($cod_gen,2).".". $this->formatear($cod_esp,2).".".   $this->formatear($cod_sub,2);
        return $str;
    }

    //--------------------------------------------------------------------------------
    //Funcion que valida que el % de la nota de credito sea igual al de la factura
    //-------------------------------------------------------------------------------
    function validar_iva_nota($factura,&$msj){
        $res = true;

        $result_ncredito = PreNotaDeCredito::query()
                         ->where('num_nc', $factura->num_nc)
                         ->where('rif_prov',$factura->rif_prov)
                         ->where('num_fac', $factura->num_fac)
                         ->where('ano_pro',$factura->ano_pro)
                         ->select('por_iva_nc')
                         ->get();

        if (empty($result_ncredito[0]->por_iva_nc)){
            $res = false;
            $msj = 'Error Consultando % de Iva de  la Nota de Credito. Comuniquese con el Administrador del Sistema.';
            return $res;
        }else{
            if ($result_ncredito[0]->por_iva_nc != $factura->porcentaje_iva){
                $res = false;
                $msj = "Error % de Iva de  la Nota de Credito [" . $result_ncredito->por_iva_nc . "]\\n es diferente al % de la Factura [" . $factura->porcentaje_iva . "]. Por favor verifique.";
                return $res;
            }
        }

        return $res;
    }

    //-------------------------------------------------------------------------------
    //   Funcion que inicializa los montos de las notas de Credito
    //-------------------------------------------------------------------------------
    function inicializar_montos_NC($factura,&$msj,&$mto_ncr,&$iva_ncr,&$tot_ncr_ncr,&$ncr_sn,&$nro_ncr){
        $mto_ncr 	 = $factura->mto_ncr;
        $iva_ncr 	 = $factura->iva_ncr;
        $tot_ncr_ncr = $factura->tot_ncr;
        $ncr_sn 	 = $factura->ncr_sn;
        $nro_ncr 	 = $factura->nro_ncr;

        if ($mto_ncr == '')
            $mto_ncr = 0;

        if ($iva_ncr == '')
            $iva_ncr = 0;

        if ($ncr_sn == '')
            $ncr_sn = 'N';

        if ($nro_ncr == '')
            $nro_ncr = '-';

        if ($tot_ncr_ncr == '')
            $tot_ncr_ncr = 0;
    }

    //------------------------------------------------------------------------------------
    //  Funcion que retorna el año del Documento desde la tabla de rececpion de factura
    //------------------------------------------------------------------------------------
    function Retorna_ano_de_recepcion($factura,&$msj,&$ano_sol_com){
        $res = true;

        $result_ano = FacRecepFactura::where('ano_ing_factura',  $factura->ano_pro)
                                     ->where('rif_prov', $factura->rif_prov)
                                     ->where('num_fac', $factura->num_fac)
                                     ->where('recibo', $factura->recibo)
                                     ->select('ano_pro')
                                     ->first();

        if (!empty($result_ano->ano_pro))
            $ano_sol_com  = $result_ano->ano_pro;
        else{
            $res = false;
            $msj = "Error al consultar año de la Recepcion de Factura. Comuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }

    //-------------------------------------------------------------------------------------------
    //    Funcion que  Actualizar los montos y el numero de control en la tabla factura
    //-------------------------------------------------------------------------------------------
    function Actualizar_Datos_Factura($factura,&$msj){
        $res = true;

        $res = Factura::where('ano_pro',  $factura->ano_pro)
                      ->where('rif_prov', $factura->rif_prov)
                      ->where('num_fac', $factura->num_fac)
                      ->where('nro_doc', $factura->nro_doc)
                      ->where('tipo_doc', $factura->tipo_doc)
                      ->update(['num_ctrl'           =>$factura->num_ctrl,
                              'fondo'              => $factura->fondo,
                              'recibo'             => $factura->recibo,
                              'cuenta_contable'    =>$factura->cuenta_contable,
                              'base_imponible'     => $factura->base_imponible,
                              'base_excenta'       => $factura->base_excenta,
                              'mto_nto'            =>$factura->mto_nto,
                              'mto_iva'            => $factura->mto_iva,
                              'mto_fac'            => $factura->mto_fac,
                              'deuda'              =>$factura->mto_fac,
                              'por_anticipo'       => $factura->fecha_proceso,
                              'mto_anticipo'       => $factura->mto_anticipo,
                              'mto_amortizacion'   =>$factura->mto_amortizacion,
                              'porcentaje_iva'     => $factura->porcentaje_iva
                              ]);

        if (!$res){
            $msj = "Error al Actualizar el registro de la Factura. \\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }
    //-------------------------------------------------------------------------------------------------------
    //  Funcion que actualiza en la tabla de amortizaciones cuando se modifican los datos de la factura
    //-------------------------------------------------------------------------------------------------------
    function actualizar_Amortizacion($factura,&$msj,$cta_x_cobrar,$grupo){
        $res = true;
        $res = AntAmortizacion::where('ano_pro',    $factura->ano_pro)
                              ->where('rif_ben',    $factura->rif_prov)
                              ->where('nro_factura',$factura->num_fac)
                              ->where('nro_doc',    $factura->nro_doc)
                              ->where('grupo',      $grupo)
                              ->update(['total_anticipo'           => $factura->mto_anticipo,
                                        'mto_amortizacion'         => $factura->mto_amortizacion,
                                        'cta_x_cobraranticipo'     => $cta_x_cobrar
                                       ]);

            if (!$res){
                $msj = "Error al Actualizar el registro de Amortizaciones. Comuniquese con su Administrador de Sistema.";
                return $res;
            }

            return $res;
    }


    //------------------------------------------------------------------------------------------------
    // Eliminar El registro de la tabla cxp_detgastosfactura
    //-------------------------------------------------------	-------------------------------------
    function Eliminar_Gasto_Factura($factura,&$msj){
        $res = true;

        $res = CxPDetGastosFactura::where('ano_pro',$factura->ano_pro)
                                  ->where('rif_prov',$factura->rif_prov)
                                  ->where('num_fac',$factura->num_fac)
                                  ->where('nro_doc',$factura->nro_doc)
                                  ->where('ano_sol',$factura->ano_sol)
                                  ->delete();

        if (!$res){
            $msj = "Error al Eliminar el Gasto. Comuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }

    //-------------------------------------------------------------------------------------------
    //Funcion que elimina el registro de la asociacion de las nota de entregas con la factura
    //-------------------------------------------------------------------------------------------
    function Eliminar_notas_entregas($factura,&$msj){
        $res = true;

        $res = CxPDetNotaFactura::where('ano_pro',$factura->ano_pro)
                                ->where('rif_prov',$factura->rif_prov)
                                ->where('num_fac',$factura->num_fac)
                                ->delete();

        if (!$res){
            $msj = "Error a borra Registro de Notas de Entregas o Actas se Aceptaciòn de Servicios \\n asociadas a la factura\\nComuniquese con su Administrador de Sistema.";
            return $res;
        }

        return $res;
    }
    //------------------------------------------------------------------------------------
    //Actualizar cabecera de las Notas de Entregas o Actas de Aceptación de Servicios
    //-----------------------------------------------------------------------------------
    function Actualizar_Cabecera_Nota_Entrega ($factura,&$msj,$status,$nro_doc,$grupo,$nro_ent,$ano_sol_orden,
                                                $numero_de_factura,$ano_entrega){

        $ano_proceso   = $factura->ano_fiscal; //Dualidad
        $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );
        $res = true;
        $res = EncNotaEntrega::where('ano_ord_com',$ano_sol_orden)
                             ->where('fk_rif_con',$factura->rif_prov)
                             ->where('fk_nro_ord',$nro_ent)
                             ->where('fk_ano_pro',$ano_entrega)
                             ->update(['sta_ent'     => $status,
                                       'sta_ant'     => 'sta_ent',
                                       'fec_sta'     => $fecha_proceso,
                                       'num_fac'     => $numero_de_factura
                                      ]);


        if (!$res){
            $msj = "Error al actualizar el status de la Nota de Entrega. Comuniquese con su Administrador de Sistema.";
            return $res;
        }

        return $res;
    }
	//-------------------------------------------------------------------------------------------------------------------------
    //Funcion que inserta los datos en la tabla cxp_detnotasfacturas para guardar la asociacion notas de entregas /facturas
    //-------------------------------------------------------------------------------------------------------------------------
    function Inserta_cxp_detnotasfacturas($factura,&$msj,$grupo,$nro_doc,$nro_ent,$monto,
                                            $nota_entrega,$base_imponible,$base_excenta,$mto_iva,$ano_fiscal,$ano_nota_entrega){
        $res = true;

        $res = CxPDetNotaFactura::create([
                    'ano_pro'            => $$ano_fiscal,
                    'rif_prov'           => $factura->rif_prov,
                    'num_fac'            => $factura->num_fac,
                    'grupo'              => $grupo,
                    'nro_ent'            => $nro_ent,
                    'nro_doc'            => $nro_doc,
                    'mto_ord'            => $monto,
                    'nota_entrega'       => $nota_entrega,
                    'base_imponible'     => $base_imponible,
                    'base_excenta'       => $base_excenta,
                    'mto_iva'            => $mto_iva,
                    'ano_nota_entrega'   => $ano_nota_entrega
                    ]);

        if (!$res){
            $msj = "Error asociando Notas de Entrega o Actas de Aceptaciòn de Servicios a la factura.\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }

    //---------------------------------------------------------------------------
    // Funcion que actualiza el status de la cabecera de la factura
    // Tabla comun para todos los procesos 3= 'Factura Ingresada'
    //                                    4= 'Factura Eliminada'
    //                                    1= Sin ingresar Factura
    //---------------------------------------------------------------------------
    function Actualizar_status_cabecera_factura($factura,&$msj,$ano_doc,$doc_asociado,$ano_doc_asociado,$status_proceso,$numero_factura){
        $ano_proceso   = $factura->ano_fiscal; //Dualidad
        $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );
        $res = true;

        $res = CxPCabeceraFactura::where('rif_prov',        $factura->rif_prov)
                                 ->where('tipo_doc',        $factura->tipo_doc)
                                 ->where('nro_doc',         $factura->nro_doc)
                                 ->where('ano_doc',         $ano_doc)
                                 ->where('doc_asociado',    $doc_asociado )
                                 ->where('ano_doc_asociado',$ano_doc_asociado )
                                 ->update(['statu_proceso'   => $status_proceso,
                                           'fec_sta'         => $fecha_proceso,
                                           'num_fac'         => $numero_factura
                                          ]);

        if (!$res){
            $msj = "Error Actualizando cabecera de factura. Comuniquese con su Administrador de Sistema.";
            return $res;
        }

        return $res;
    }
    //------------------------------------------------------------------------------
    //            Function que Guarda la Estructura de Gasto
    //------------------------------------------------------------------------------
    function Guarda_Estructura_Gasto_Factura($factura,&$msj,$tip_cod,$cod_pryacc,$cod_obj,
                                             $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$monto_tra,
                                             $saldo_causar,$gasto,$ano_fiscal,$monto_nc,$presu_afectado){
        $res = true;
//dd($factura);
        $res = CxPDetGastosFactura::create([
                        'ano_pro'            => $ano_fiscal,
                        'rif_prov'           => $factura->rif_prov,
                        'num_fac'            => $factura->num_fac,
                        'tip_cod'            => $tip_cod,
                        'cod_pryacc'         => $cod_pryacc,
                        'cod_obj'            => $cod_obj,
                        'gerencia'           => $gerencia,
                        'unidad'             => $unidad,
                        'cod_par'            => $cod_par,
                        'cod_gen'            => $cod_gen,
                        'cod_esp'            => $cod_esp,
                        'cod_sub'            => $cod_sub,
                        'cod_com'            => $cod_com,
                        'mto_tra'            => $monto_tra,
                        'sal_cau'            => $saldo_causar,
                        'gasto'              => $gasto,
                        'ano_sol'            => $factura->ano_sol,
                        'nro_doc'            => $factura->nro_doc,
                        'mto_nc'             => $monto_nc,
                        'presu_afectado'     => $presu_afectado
                ]);

        if (!$res){
            $msj = "Error al Insertar el Gasto de la Factura. Cominuquese con su Administrador de sistema.";
            return $res;
        }

        return $res;
    }

	    //--------------------------------------------------------------------
		//Funcion que Ingresa los Campos en la Tabla Factura
		//--------------------------------------------------------------------
		function Ingresar_Tabla_Factura($factura,&$msj,$status,$ano_sol_com,$ano_fiscal){
			$ano_proceso   = $factura->ano_fiscal; //Dualidad
			$fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );
			$res = true;
            $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();
			//--------------------------------------------------------------------------
			//Valida que si los montos de la nota de credito vienen vacio asignarles 0
		    //--------------------------------------------------------------------------
			$mto_ncr 	 = 0;
			$iva_ncr 	 = 0;
			$tot_ncr_ncr = 0;
			$ncr_sn 	 = 0;
			$nro_ncr 	 = 0;

			if (empty($factura->fondo))
				$fondo = 'I';
			else
				$fondo = $factura->fondo;

			//Hacer el llamado a la funcion que asigna los valores de la nota de credito
			$this->inicializar_montos_NC($factura,$msj,$mto_ncr,$iva_ncr,$tot_ncr_ncr,$ncr_sn,$nro_ncr);
             //Si la factura proviene de un contrato se debe validar si es de servio o de obra
             //para validar luego en la solicitud de pago si a la factura se le puede aplicar
             // la retención 1 * 1000
             if ($factura->tipo_doc=='5' || $factura->tipo_doc=='4'){
                $result_tipo_contrato = OpSolservicio::where('ano_pro',$factura->ano_pro)
                                                    ->where('xnro_sol',$factura->nro_doc)
                                                    ->select('tip_contrat')
                                                    ->first();

             	if ($result_tipo_contrato->tip_contrat=='S')
             		$servicio  = 'S';
             	else{
             		$servicio='O';
             	}
             }else{
             	$servicio='O';
             }
			//---------------------------------------------------------------------------
			//---------------------------------------------------------------------------

              Factura::create([
                    'ano_pro'                   => $factura->ano_pro,
                    'rif_prov'                  => $factura->rif_prov,
                    'num_fac'                   => $factura->num_fac,
                    'num_ctrl'                  => $factura->num_ctrl,
                    'fec_fac'                   => $factura->fec_fac,
                    'tipo_doc'                  => $factura->tipo_doc,
                    'tipo_pago'                 => $factura->tipo_pago,
                    'nro_doc'                   => $factura->nro_doc,
                    'base_imponible'            => $factura->base_imponible,
                    'base_excenta'              => $factura->base_excenta,
                    'mto_nto'                   => $factura->mto_nto,
                    'mto_iva'                   => $factura->mto_iva,
                    'mto_fac'                   => $factura->mto_fac,
                    'por_anticipo'              => $factura->por_anticipo,
                    'mto_anticipo'              => $factura->mto_anticipo,
                    'mto_amortizacion'          => $factura->mto_amortizacion,
                    'ncr_sn'                    => $ncr_sn,
                    'nro_ncr'                   => $nro_ncr,
                    'mto_ncr'                   => $mto_ncr,
                    'iva_ncr'                   => $iva_ncr,
                    'tot_ncr'                   => $tot_ncr_ncr,
                    'usuario'                   => $usuario->usuario,
                    'fecha'                     => $fecha_proceso,
                    //'usua_apr'                  => $factura->usua_apr,
                    //'fec_apr'                   => $factura->fec_apr,
                    //'usua_anu'                  => $factura->usua_anu,
                   // 'fec_anu'                   => $factura->fec_anu,
                    'sta_fac'                   => $status,
                    'fec_sta'                   => $fecha_proceso,
            //         'sol_pag'                   => $factura->sol_pag,
            //         'usua_pago'                 => $factura->usua_pago,
            //         'fec_pago'                  => $factura->fec_pago,
                    'monto_original'            => $factura->monto_original,
                    'porcentaje_iva'            => $factura->porcentaje_iva,
            //         'num_nc'                    => $factura->num_nc,
                    'ano_sol'                   => $ano_sol_com,
                    'recibo'                    => $factura->recibo,
            //         'mod_fac'                   => $factura->mod_fac,
            //         'descuentos'                => $factura->descuentos,
            //         'monto_descuento'           => $factura->monto_descuento,
                    'cuenta_contable'           => $factura->cuenta_contable,
                    'fondo'                     => $fondo,
            //         'pago_manual'               => $factura->pago_manual,
                    'deposito_garantia'         => $factura->deposito_garantia,
                    'deuda'                     => $factura->deuda,
            //         'tipo_nota'                 => $factura->tipo_nota,
            //         'ano_nota'                  => $factura->ano_nota,
            //         'base_imponible_nd'         => $factura->base_imponible_nd,
            //         'base_exenta_nd'            => $factura->base_exenta_nd,
            //         'observacion'               => $factura->observacion,
            //         'sta_rep'                   => 1,
            //         'referencia'                => $factura->referencia,
                    'provisionada'              => $factura->provisionada,
                    'servicio'                  => $servicio,
            //         'monto_contrato'            => $factura->monto_contrato,
            //         'nro_reng'                  => $factura->nro_reng,
            //         //'id'
                ]);
			/*$query_Insert = "INSERT INTO facturas(ano_pro, rif_prov, num_fac, num_ctrl, fec_fac, tipo_doc, tipo_pago,
						                          nro_doc, base_imponible, base_excenta, mto_nto, mto_iva, mto_fac,
						                          por_anticipo, mto_anticipo, mto_amortizacion, ncr_sn, nro_ncr,
						                          mto_ncr, iva_ncr, tot_ncr, usuario, fecha, sta_fac, fec_sta,
						                          monto_original,porcentaje_iva,ano_sol,recibo,fondo,cuenta_contable,
						                          deposito_garantia,deuda,provisionada,servicio)
		                        	VALUES (" . $ano_fiscal . "," .
						                    "'" . $factura->rif_prov . "'," .
						                    "'" . $factura->num_fac . "'," .
						                    "'" . $factura->num_ctrl . "'," .
						                    "'" . $factura->fec_fac . "'," .
						                    "'" . $factura->tipo_doc . "'," .
						                    "'" . $factura->tipo_pago . "'," .
						                    "'" . $factura->nro_doc . "'," .
						                    "" . $factura->base_imponible . "," .
						                    "" . $factura->base_excenta . "," .
						                    "" . $factura->mto_nto . "," .
						                    "" . $factura->mto_iva . "," .
						                    "" . $factura->mto_fac . "," .
						                    "" . $factura->por_anticipo . "," .
						                    "" . $factura->mto_anticipo . "," .
						                    "" . $factura->mto_amortizacion . "," .
						                    "'". $ncr_sn . "'," .
						                    "'". $nro_ncr . "'," .
						                    "" . $mto_ncr . "," .
						                    "" . $iva_ncr . "," .
						                    "" . $tot_ncr_ncr.",".
						                    "'" . $_SESSION['LOGIN'] . "'," .
					                        "'$fecha_proceso'," .
					                        "'" . $status . "'," .
					                        "'$fecha_proceso'," .
					                        "" . $factura->monto_original . "," .
					                        "" . $factura->porcentaje_iva . "," .
					                        "" . $ano_sol_com . "," .
					                        "'" . $factura->recibo . "'," .
					                        "'" . $fondo."'," .
					                        "'" . $factura->cuenta_contable . "'," .
					                        "'" . $factura->deposito_garantia . "'," .
					                        "" . $factura->mto_fac . ",".
					                        "'". $factura->provisionada ."'," .
			                                "'". $servicio ."')";*/

			//$res = $db->execQuery($conn, $query_Insert);

			if (!$res){
				$msj = "Error al Ingresar el Registro de la Factura.\\nComuniquese con su Administrados de Sistema. ";
				return $res;
			}

			return $res;
		}
    //--------------------------------------------------------------------------
    //         Funcion que inserta la Tabla Historico de Facturas
    //--------------------------------------------------------------------------
    function Insert_Facturas_borradas($factura,&$msj){
        $ano_proceso   = $factura->ano_fiscal; //Dualidad
        $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );
        $res = true;
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();
        //----------------------------------------------------
        //Insertar Registro en la Tabla Historico de Facturas
        //----------------------------------------------------

        FacturaBorrada::create([
            'ano_pro'                   => $factura->ano_pro,
            'rif_prov'                  => $factura->rif_prov,
            'num_fac'                   => $factura->num_fac,
            'num_ctrl'                  => $factura->num_ctrl,
            'fec_fac'                   => $factura->fec_fac,
            'tipo_doc'                  => $factura->tipo_doc,
            'tipo_pago'                 => $factura->tipo_pago,
            'nro_doc'                   => $factura->nro_doc,
            'base_imponible'            => $factura->base_imponible,
            'base_excenta'              => $factura->base_excenta,
            'mto_nto'                   => $factura->mto_nto,
            'mto_iva'                   => $factura->mto_iva,
            'mto_fac'                   => $factura->mto_fac,
            'por_anticipo'              => $factura->por_anticipo,
            'mto_anticipo'              => $factura->mto_anticipo,
            'mto_amortizacion'          => $factura->mto_amortizacion,
            'ncr_sn'                    => $factura->ncr_sn,
            'nro_ncr'                   => $factura->nro_ncr,
            'mto_ncr'                   => $factura->mto_ncr,
            'iva_ncr'                   => $factura->iva_ncr,
            'tot_ncr'                   => $factura->tot_ncr_ncr,
            'usuario'                   => $factura->usuario,
            'fecha'                     => $factura->fecha_proceso,
            'usua_apr'                  => $factura->usua_apr,
            'fec_apr'                   => $factura->fec_apr,
            'usua_anu'                  => $factura->usua_anu,
            'fec_anu'                   => $factura->fec_anu,
            'sta_fac'                   => $factura->sta_fac,
            'fec_sta'                   => $fecha_proceso,
            'sol_pag'                   => $factura->sol_pag,
            'usua_pago'                 => $factura->usua_pago,
            'fec_pago'                  => $factura->fec_pago,
            'monto_original'            => $factura->monto_original,
            'porcentaje_iva'            => $factura->porcentaje_iva,
            'num_nc'                    => $factura->num_nc,
            'ano_sol'                   => $factura->ano_sol,
            'recibo'                    => $factura->recibo,
            'mod_fac'                   => $factura->mod_fac,
            'usuario_borro'             => $usuario->usuario,
            'fecha_borrada'             => $fecha_proceso
        ]);

        if (!$res){
            $msj = "Error Insertando en Historico de Facturas.\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
	}

    //----------------------------------------------------------------------
    //             Delete de la tabla principal( facturas)
    //----------------------------------------------------------------------
    function Borrar_Factura($factura,&$msj){
        $res = true;

        $res = Factura::where('ano_pro',$factura->ano_pro)
                      ->where('rif_prov',$factura->rif_prov)
                      ->where('num_fac',$factura->num_fac)
                      ->where('recibo',$factura->recibo)
                      ->delete();

        if (!$res){
            $msj = "Error al Eliminar Registro de la Factura.\\n Comuniquese con su Administrador de Sistema.";
            return $res;
        }

        return $res;
    }

    //-------------------------------------------------------------------------------------------
    //            Function que Borra el Registro de la Tabla  ant_amortizaciones
    //-------------------------------------------------------------------------------------------
    function Borrar_Amortizacion($factura,&$msj,$nro_documento){
        $res = true;
        $res = AntAmortizacion::where('ano_pro',$factura->ano_pro)
                              ->where('rif_ben',$factura->rif_prov)
                              ->where('nro_factura',$factura->num_fac)
                              ->where('nro_doc',$nro_documento)
                              ->delete();

        if (!$res){
            $msj = "Error al Eliminar el registro de Amortizacion. Comuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }

    //----------------------------------------------------------------------------------------
    //                   Funcion que Actualiza el Status de la Factura
    //----------------------------------------------------------------------------------------
    function Actualizar_statu_Factura($factura,&$msj,$sta_fac,$usuarios,$fecha){
        $ano_proceso   = $factura->ano_fiscal; //Dualidad
        $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );
        $res = true; //Modificado
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

        $res = Factura::where('ano_pro',  $factura->ano_pro)
                      ->where('rif_prov', $factura->rif_prov)
                      ->where('num_fac',  $factura->num_fac)
                      ->where('nro_doc',  $factura->nro_doc)
                      ->where('tipo_doc', $factura->tipo_doc)
                      ->update(['sta_fac'   => $sta_fac,
                                'fec_sta'   => $fecha_proceso,
                                'usua_apr'  => $usuario->usuario,
                                'fec_apr'   => $fecha_proceso
                      ]);

        if (!$res){
            $msj = "Error Actualizando status de Factura.\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }

    //-------------------------------------------------------------------------------------------
    //Funcion que devuelve las partidas presupuestarias afectadas por las Notas de Credito
    //-------------------------------------------------------------------------------------------
    function Partidas_Nota_credito($factura,&$msj,&$result_nc){
        $res = true;

        $result_nc = PreDetNotaDeCredito::where('num_nc',   $factura->num_nc)
                                        ->where('rif_prov', $factura->rif_prov)
                                        ->where('num_fac',  $factura->num_fac)
                                        ->where('ano_pro',  $factura->ano_pro)
                                        ->select('tip_cod', 'cod_pryacc', 'cod_obj','gerencia', 'unidad', 'cod_par', 'cod_gen','cod_esp', 'cod_sub', 'mon_nc')
                                        ->get();


        if (empty($result_nc[0]->cod_par)){
            $res = false;
            $msj = "Error Consultando Partidas Presupuestarias de la Nota de Credito.\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }

    //----------------------------------------------------------------------------------------------------
    //Funcion que realiza todos los procesos necesarios de la orden de compra para la opcion de aprobar
    //----------------------------------------------------------------------------------------------------
    function Aprobar_Factura_provision($factura,&$msj,$result_iva,$result_nc,$ano_fiscal){
        $nro_doc	  = $factura->nro_doc;
        $ncr_sn		  = $factura->ncr_sn;
        $por_anticipo = $factura->por_anticipo;
        $x   = 1000;
        $res = true;
        $y	 = 1000;
        $xx = 1;

        //dd($res);
        $facfactura = Factura::where('ano_pro',$factura->ano_pro)
                             ->where('rif_prov',$factura->rif_prov)
                             ->where('num_fac',$factura->num_fac)
                             ->first();
        //Funcion que Busca el año de la Recepcion de la Factura
        $ano_recp_fac = '';
        $res = $this->Retorna_ano_de_recepcion($factura,$msj,$ano_recp_fac);

        if (!$res)
            return $res;

        //Buscar año de generación de Documento
        $ano_sol_doc = $factura->ano_sol;
        $grupo = '';

        //Buscar las Siglas del Documento
        $res = $this->Retornar_siglas_Documentos($msj,$grupo);

        if (!$res)
            return $res;

        $mto_iva = 0;
        $mto_nto = 0;
        $porc_iva = 0;
        $bas_impon = 0;
        $mto_iva_aprobdo = 0;
        $mto_nto_aprob = 0;
        //Query para retornar mto de IVA en certificacion
        $res = $this->Retorna_mto_Iva($factura,$msj,$ano_sol_doc,$mto_iva,$mto_nto);

        if (!$res)
            return $res;

        $res = $this->Retorna_porc_Ivafac($factura,$msj,$ano_sol_doc,$porc_iva,$bas_impon);

        if (!$res)
            return $res;


        $res = $this->Retorna_mto_Ivatotfats($factura,$msj,$ano_sol_doc,$mto_iva_aprobdo,$mto_nto_aprob); //juanjo
        //file_put_contents(dirname(__FILE__)."/aqui--ver030.txt",print_r($mto_iva_aprobdo,true));
        $mto_iva_aprobdo3 = $mto_iva_aprobdo;
        //file_put_contents(dirname(__FILE__)."/aqui--ver30.txt",print_r($mto_iva_aprobdo3,true));
        if (!$res)
            return $res;

        $icont = 0;
        //----------------------------------------------
        //----------------------------------------------

        $detgastosfactura =$facfactura->cxpdetgastosfactura;

        //return  $detgastosfactura;
        foreach($detgastosfactura as $index => $gastos){
            $icont = $icont + 1 ;

            $tip_cod           = $gastos['tip_cod'];
            $cod_pryacc        = $gastos['cod_pryacc'];
            $cod_obj           = $gastos['cod_obj'];
            $gerencia          = $gastos['gerencia'];
            $unidad            = $gastos['unidad'];
            $cod_par           = $gastos['cod_par'];
            $cod_gen           = $gastos['cod_gen'];
            $cod_esp           = $gastos['cod_esp'];
            $cod_sub           = $gastos['cod_sub'];

            $cod_com_Viejo = $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);

            if ($ano_fiscal != $ano_sol_doc){
                $res = $this->armar_centro_costo($msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_com,$row2,$ano_fiscal,$ano_sol_doc);
                if (!$res)
                    return $res;
            }else
                $cod_com = armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);

            if ($cod_com_Viejo != $cod_com){
                //Se debe Modificar la estructura de gasto por la nueva

                $res = CxPDetGastosFactura::where('ano_pro',  $factura->ano_pro)
                                          ->where('rif_prov', $factura->rif_prov)
                                          ->where('num_fac',  $factura->num_fac)
                                          ->where('cod_com',  $cod_com_Viejo)
                                          ->where('nro_doc',  $factura->nro_doc)
                                          ->where('ano_sol',  $factura->ano_sol)
                                          ->update(['tip_cod'       => $tip_cod,
                                                    'cod_pryacc'    => $cod_pryacc,
                                                    'cod_obj'       => $cod_obj,
                                                    'gerencia'      => $gerencia,
                                                    'unidad'        => $unidad,
                                                    'cod_par'       => $cod_par,
                                                    'cod_gen'       => $cod_gen,
                                                    'cod_esp'       => $cod_esp,
                                                    'cod_sub'       => $cod_sub,
                                                    'cod_com'       => $cod_com
                                          ]);

                if (!$res){
                    $msj = "Error Modificando la Estructura de Gasto por cambio de centro de costo [" . $cod_com . "]Comuniquese con su Administrador de sistema";
                    return $res;
                }
            }
            $nota_entrega = '';

            if ($result_iva->cod_par == $row2["cod_par"] && $result_iva->cod_gen == $row2["cod_gen"]
                && $result_iva->cod_esp == $row2["cod_esp"] && $result_iva->cod_sub == $row2["cod_sub"]){
                //---------------------------------------------------------------------
                // Buscar las cuentas contables asociadas para crear el asiento contable
                //----------------------------------------------------------------------
                $cta_x_pagar = '';
                $cuenta_contable = '';

                //------------------------------------------------------------------------------------------
                // Si es un deposito en garantia no se debe crear asiento contable
                //------------------------------------------------------------------------------------------
                if ($factura->deposito_garantia=='N'){
                    //----------------------------------------------------
                    // Buscar las cuentas por pagar y la cuenta de activo
                    //----------------------------------------------------
                    $cuenta = "cta_activo";
                    $desc_x_pagar = "cta_x_pagar";
                    $res = $this->Buscar_Cuentas_Contables_x_Partidas($msj,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$cuenta_contable,$cta_x_pagar,$cuenta,$desc_x_pagar);

                    if (!$res)
                        return $res;
                    $cta_x_pagar_iva = $cta_x_pagar;
                    $cuenta_contable_iva = $cuenta_contable;
                }

                $mto_iva_fac = $factura->mto_iva;

                $tot_ajust_iva = $mto_iva - $mto_iva_aprobdo ;

                $tipo_proceso='mto_dis';
                    if ($tot_ajust_iva < 0){ //if mto iva certificacion > mto iva factura
                        $monto_comprometer = abs($tot_ajust_iva);
                        $res = $this->Validar_Monto_a_Procesar($msj,$ano_fiscal,$monto_comprometer,$cod_com,$tipo_proceso);

                        if (!$res)
                            return $res;
                        $accion  = '-';
                        $accion2 = '-';
                        $decripcion = "AJUSTE DEL COMPROMISO POR CAMBIO EN MONTO DE IVA EN LA FACTURA: " . $factura->num_fac;
                        $des_proceso = 'Por Concepto de Cambio de IVA';
                        $lgastos2[$j]=array("cod_par"=>		$cod_par,
                                "cod_gen"=>		$cod_gen,
                                "cod_esp"=>		$cod_esp,
                                "cod_sub"=>		$cod_sub,
                                "cod_ger"=>		$gerencia,
                                "ctro_costo"=>	$centro_costo,
                                "cta_x_pagar"=>	$cta_x_pagar,
                                "mto_tra"=>		($totrecep*$pre_uni)-((($totrecep*$pre_uni)*$porc_ant)/100));
                        //lista de ajuste presupuestario
                        $lajuste_presup[$icont]= array("ano_sol_doc"            => $ano_sol_doc,
                                                        "concepto"			    => $concepto,
                                                        "tip_cod"			    => $tip_cod,
                                                        "cod_pryacc"		    => $cod_pryacc,
                                                        "cod_obj"			    => $cod_obj,
                                                        "gerencia"			    => $gerencia,
                                                        "unidad"			    => $unidad,
                                                        "cod_par"			    => $cod_par,
                                                        "cod_gen"			    => $cod_gen,
                                                        "cod_esp"			    => $cod_esp,
                                                        "cod_sub"			    => $cod_sub,
                                                        "cod_com"			    => $cod_com,
                                                        "monto_comprometer"     => $monto_comprometer,
                                                        "accion"			    => $accion,
                                                        "accion2"			    => $accion2,
                                                        "decripcion"		    => $decripcion,
                                                        "result_iva"		    => $result_iva,
                                                        "ano_fiscal"		    => $ano_fiscal,
                                                        "des_proceso"		    => $des_proceso);


                        $lajuste_cont[$icont]= array("nro_doc"				=> $nro_doc,
                                                     "monto_comprometer"	=> $monto_comprometer,
                                                     "cuenta_contable"		=> $cuenta_contable_iva,
                                                     "acciones"				=> 'DB',
                                                     "ano_sol_doc"			=> $ano_sol_doc,
                                                     "ano"					=> $factura->ano_pro);

                    }
                    //lista de causado presupuestario
                    $restar = '+';
                    $concepto	= "CAUSADO DE LA PARTIDA DE IVA SEGUN FACTURA NRO: " . $factura->num_fac;
                    $tip_ope	= 50;
                    $status_reg = "1";
                    $sol_tip	= "IF";
                    $num_fac = $factura->num_fac;
                    $tipo_proceso = 'mto_com';

                    $res = $this->Validar_Monto_a_Procesar($msj,$ano_fiscal,$row2["sal_cau"],$cod_com,$tipo_proceso);

                    if (!$res)
                        return $res;

                    $lcaus_presup[$icont]= array("sal_cau"	    => $row2["sal_cau"],
                                                "cod_com"	    => $cod_com,
                                                "restar"		=> $restar,
                                                "ano_fiscal"	=> $ano_fiscal,
                                                "tip_cod"	    => $tip_cod,
                                                "cod_pryacc"	=> $cod_pryacc,
                                                "cod_obj"	    => $cod_obj,
                                                "gerencia"	    => $gerencia,
                                                "unidad"		=> $unidad,
                                                "cod_par"	    => $cod_par,
                                                "cod_gen"	    => $cod_gen,
                                                "cod_esp"	    => $cod_esp,
                                                "cod_sub"	    => $cod_sub,
                                                "concepto"	    => $concepto,
                                                "tip_ope"	    => $tip_ope,
                                                "sol_tip"   	=> $sol_tip,
                                                "status_reg"	=> $status_reg,
                                                "ano_sol_doc"   => $ano_sol_doc,
                                                "nro_doc"	    => $factura->nro_doc,
                                                "num_fac"	    => $num_fac,
                                                "sol_tip"	    => $sol_tip);



                    $lcaus_cont[$icont]= array("nro_doc"				=> $nro_doc,
                                                "monto_comprometer"		=> $row2["sal_cau"],
                                                "cuenta_contable"		=> $cuenta_contable_iva,
                                                "acciones"				=> 'DB',
                                                "ano_sol_doc"			=> $ano_sol_doc,
                                                "ano"					=> $factura->ano_pro);

                    $lcaus_cont[$icont+1]= array("nro_doc"				=> $nro_doc,
                            "monto_comprometer"		=> $row2["sal_cau"],
                            "cuenta_contable"		=> $cta_x_pagar_iva,
                            "acciones"				=> 'CR',
                            "ano_sol_doc"			=> $ano_sol_doc,
                            "ano"					=> $factura->ano_pro);

            }else{ //sino es la partida presupuestaria de iva

                $cuenta = "cta_provision";
                $desc_x_pagar = "cta_x_pagar";
                $res = $this->Buscar_Cuentas_Contables_x_Partidas($msj,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$cuenta_contable,$cta_x_pagar,$cuenta,$desc_x_pagar);


                if (!$res)
                    return $res;

                $Provisio = $cuenta_contable;
                if ($factura->tipo_doc == 4){

                    $cta_x_pagar 	 = '';
                    $cuenta_contable = '';

                    if ($row2["gasto"]=='Si'){
                        $cuenta = "cta_gasto";
                        $desc_x_pagar = "cta_x_pagar";
                    }else{
                        $cuenta = "cta_activo";
                        $desc_x_pagar = "cta_x_pagar_activo";
                    }
                    $res = $this->Buscar_Cuentas_Contables_x_Partidas($msj,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$cuenta_contable,$cta_x_pagar,$cuenta,$desc_x_pagar);

                    if (!$res)
                        return $res;

                    $mto_nto_fac = $factura->mto_nto;
                    $tot_ajust_nto = $mto_nto - ($mto_nto_aprob) ;

                    if ($tot_ajust_nto < 0){ //if mto iva certificacion > mto iva factura
                            $monto_comprometer = abs($tot_ajust_nto);
                            $tipo_proceso = 'mto_dis';
                            $res = $this->Validar_Monto_a_Procesar($msj,$ano_fiscal,$monto_comprometer,$cod_com,$tipo_proceso);

                            if (!$res)
                                return $res;
                            $accion		 = "-";
                            $accion2	 = "+";
                            $decripcion	 = "AJUSTE DEL COMPROMISO POR CAMBIO EN MONTO NETO EN LA FACTURA: " . $factura->num_fac;
                            $des_proceso ='Por Concepto de cambio monto neto';

                            //lista de ajuste presupuestario
                            $lajuste_presup[$icont]=array("ano_sol_doc"		    => $ano_sol_doc,
                                                            "concepto"			=> $concepto,
                                                            "tip_cod"			=> $tip_cod,
                                                            "cod_pryacc"		=> $cod_pryacc,
                                                            "cod_obj"			=> $cod_obj,
                                                            "gerencia"			=> $gerencia,
                                                            "unidad"			=> $unidad,
                                                            "cod_par"			=> $cod_par,
                                                            "cod_gen"			=> $cod_gen,
                                                            "cod_esp"			=> $cod_esp,
                                                            "cod_sub"			=> $cod_sub,
                                                            "cod_com"			=> $cod_com,
                                                            "monto_comprometer" => $monto_comprometer,
                                                            "accion"			=> $accion,
                                                            "accion2"			=> $accion2,
                                                            "decripcion"		=> $decripcion,
                                                            "result_iva"		=> $result_iva,
                                                            "ano_fiscal"		=> $ano_fiscal,
                                                            "des_proceso"		=> $des_proceso);

                            //$res = $this->Crear_Comprobante($db,$conn,$datosDetalle,$tablasDetalle,$msj,$nro_doc,1,$cuenta_contable,$monto_dif_comp,$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                            $lajuste_cont[$icont]=array("nro_doc"				=> $nro_doc,
                                                        "monto_comprometer"		=> $monto_comprometer,
                                                        "cuenta_contable"		=> $cuenta_contable,
                                                        "acciones"				=> 'DB',
                                                        "ano_sol_doc"			=> $ano_sol_doc,
                                                        "ano"					=> $factura->ano_pro);
                    }

                    $restar = '+';
                    $concepto 	 = "CAUSADO DE CERTIFICACION DE PAGOS DIRECTOS SEGUN FACTURA: " . $factura->num_fac;
                    $tip_ope	= 50;
                    $status_reg = "1";
                    $sol_tip	= "IF";
                    $num_fac = $factura->num_fac;
                    $tipo_proceso = 'mto_com';

                    $res = $this->Validar_Monto_a_Procesar($msj,$ano_fiscal,$row2["sal_cau"],$cod_com,$tipo_proceso);

                    if (!$res)
                        return $res;
                    $lcaus_presup[$icont]=array("ano_sol_doc"       => $ano_sol_doc,
                                                "concepto"	        => $concepto,
                                                "tip_cod"	        => $tip_cod,
                                                "cod_pryacc"	    => $cod_pryacc,
                                                "cod_obj"	        => $cod_obj,
                                                "gerencia"	        => $gerencia,
                                                "unidad"		    => $unidad,
                                                "cod_par"	        => $cod_par,
                                                "cod_gen"	        => $cod_gen,
                                                "cod_esp"	        => $cod_esp,
                                                "cod_sub"	        => $cod_sub,
                                                "cod_com"	        => $cod_com,
                                                "sal_cau"	        => $row2["sal_cau"],
                                                "restar"		    => $restar,
                                                "ano_fiscal"	    => $ano_fiscal,
                                                "tip_ope"	        => $tip_ope,
                                                "sol_tip"	        => $sol_tip,
                                                "status_reg"	    => $status_reg,
                                                "nro_doc"	        => $factura->nro_doc,
                                                "num_fac"	        => $num_fac,
                                                "sol_tip"	        => $sol_tip);


                    //$res = $this->Crear_Comprobante($db,$conn,$datosDetalle,$tablasDetalle,$msj,$nro_doc,4,$Provisio,$factura->mto_nto,'CR',0,$ano_sol_doc,$factura->ano_pro);
                    $lcaus_cont[$icont]=array ("nro_doc"				=> $nro_doc,
                                                "monto_comprometer"	    => $factura->mto_nto,
                                                "cuenta_contable"		=> $Provisio,
                                                "acciones"				=> 'DB',
                                                "ano_sol_doc"			=> $ano_sol_doc,
                                                "ano"					=> $factura->ano_pro);

                    $lcaus_cont[$icont+3]=array ("nro_doc"				=> $nro_doc,
                                                "monto_comprometer"	    => $row2["sal_cau"],
                                                "cuenta_contable"		=> $cta_x_pagar,
                                                "acciones"				=> 'CR',
                                                "ano_sol_doc"			=> $ano_sol_doc,
                                                "ano"					=> $factura->ano_pro);
                }
            }
        } //foreach($detgastosfactura as $index => $gastos)

        //asiento presupuestario


        $n=count($lajuste_presup);
        for($i=1;$i<$n+1;$i++){
            $row=$lajuste_presup[$i];
            extract($row);
            $res = $this->Ajustar_Compromiso_provision($factura,$msj,$ano_sol_doc,$concepto,$tip_cod,$cod_pryacc,$cod_obj,
                                                    $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$monto_comprometer,$accion,$accion2,
                                                    $decripcion,$result_iva,$ano_fiscal,$des_proceso);
            if (!$res)
                return $res;

        }

        $tot_causad = 0 ;
        $n=count($lcaus_presup);
        for($i=1;$i<$n+1;$i++){
            $row=$lcaus_presup[$i];
            extract($row);
            $res = $this->Causar_Partida_Pre_Maestro_Ley($msj,$sal_cau,$cod_com,$restar,$ano_fiscal);

            if (!$res)
                return $res;

            $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,
                                               $cod_gen,$cod_esp,$cod_sub,$sal_cau,$concepto,$tip_ope,$sol_tip,$status_reg,$ano_sol_doc,
                                               $nro_doc,'',$num_fac,0.00,$ano_fiscal,'1');
            $tot_causad = $tot_causad + $sal_cau ;

            if (!$res)
                return $res;
        }
        // asiento contable

        $con_com = 1 ;
        $tot_comp = 0 ;
        $n=count($lajuste_cont);
        for($i=1;$i<$n+1;$i++){
            $row=$lajuste_cont[$i];
            extract($row);
            $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$con_com,$cuenta_contable,$monto_comprometer,$acciones,'0',$ano_sol_doc,$ano);
            $tot_comp= $tot_comp + $monto_comprometer;

            if (!$res)
                return $res;

            $con_com++;
        }



        if ($tot_comp != 0){
            $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$con_com,$Provisio,$tot_comp,'CR','0',$ano_sol_doc,$this->getCampo("ano_pro")->valor);
            $con_com++;
            if (!$res)
                return $res;
        }

        if (!$res)
            return $res;


        $n=count($lcaus_cont);
        for($i=1;$i<$n+1;$i++){
            $row=$lcaus_cont[$i];
            extract($row);
            $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$con_com,$cuenta_contable,$monto_comprometer,$acciones,'0',$ano_sol_doc,$ano);

            if (!$res)
                return $res;

            $con_com++;
        }

        return $res;
    }

	//------------------------------------------------------------------------------------------------------
    //Funcion que Retorna el monto de IVa desde la Orden de Compra para validar si existe cambio en iva
    //              cuando el Proveedor trae las facturas asociadas a la orden de compra
    //-------------------------------------------------------------------------------------------------------
    function Retorna_mto_Iva($factura,&$msj,$ano_sol_orden,&$mto_iva,&$mto_nto){
        $res = true;

        $result_mto_iva  = CxPCabeceraFactura::where('nro_doc',         $factura->nro_doc)
                                             ->where('ano_doc',         $ano_sol_orden)
                                             ->select('mto_iva','mto_nto')
                                             ->first();


        if (!empty($result_mto_iva->mto_iva)){
            $mto_iva  = $result_mto_iva->mto_iva;
            $mto_nto  = $result_mto_iva->mto_nto;
        }else{
            $res = false;
            $msj = "Error al Consultar el monto de Iva en la Orden de Compra.\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }
    //------------------------------------------------------------------------------------------------------
    //Funcion que Retorna el porcentaje del iva de la factura
    //-------------------------------------------------------------------------------------------------------
    function Retorna_porc_Ivafac($factura,&$msj,$ano_sol_orden,&$porc_iva,&$bas_impon){
        $res = true;

        $result_porc_iva  = CxPCabeceraFactura::where('nro_doc',         $factura->nro_doc)
                                        ->where('ano_doc',         $ano_sol_orden)
                                        ->select('base_imponible','porcentaje_iva')
                                        ->first();


        if (!empty($result_porc_iva->porcentaje_iva)){
            $porc_iva  = $result_porc_iva->porcentaje_iva;
            $bas_impon  = $result_porc_iva->base_imponible;
        }
        else{
            $res = false;
            $msj = "Error al Consultar el porcentaje de Iva en la Orden de la Certificacion.\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }
    //------------------------------------------------------------------------------------------------------
    //Funcion que Retorna el monto de IVa total afectado en facturas aprobadas
    //
    //-------------------------------------------------------------------------------------------------------
    function Retorna_mto_Ivatotfats($factura,&$msj,$ano_sol_orden,&$mto_iva_aprob,&$mto_nto_aprob){
        $res = true;

        $result_mto_iva = Factura::where('sta_fac',  1)
                                  ->where('ano_sol',  $ano_sol_orden)
                                  ->where('nro_doc',  $factura->nro_doc)
                                  ->selectRaw("case when (sum (mto_nto))>0 then sum (mto_nto) else 0 end as mto_nto,
                                            case when (sum (mto_iva))>0 then sum (mto_iva) else 0 end as mto_iva")
                                  ->first();



            $mto_iva_aprob  = $result_mto_iva->mto_iva;
            $mto_nto_aprob  = $result_mto_iva->mto_nto;

        return $res;
    }

    //------------------------------------------------------------------------------------------------------------------
    //             Funcion que Busca la cuenta de activo y por pagar asociadas a una partida presupuestarias
    //------------------------------------------------------------------------------------------------------------------
    function Buscar_Cuentas_Contables_x_Partidas(&$msj,$cod_par,$cod_gen,$cod_esp,
                                                    $cod_sub,$cod_com,&$cuenta_contable,&$cta_x_pagar,$cuenta,$desc_x_pagar){

       // dd($cuenta_contable.'/'.$cta_x_pagar);
       $res = true;
       if (Empty($cuenta_Contable))
            $cuenta_Contable = '';
        if (Empty($cuenta_x_pagar))
            $cuenta_x_pagar = '';
        $res = true;
        $result_cuenta_contable2 = PreMovimiento::where('cod_par',$cod_par )
                                                ->where('cod_gen',$cod_gen )
                                                ->where('cod_esp',$cod_esp )
                                                ->where('cod_sub',$cod_sub )
                                                //->select($cuenta_contable,$cta_x_pagar)
                                                ->get();

        $cuenta_contable = $result_cuenta_contable2[0]->cta_activo;
        $cta_x_pagar = $result_cuenta_contable2[0]->cta_x_pagar_activo;

        if (trim($cuenta_contable) == '' || trim($cta_x_pagar == '')){
            if ($cuenta_Contable == ''){
                if ($cuenta == 'cta_gasto')
                    $descrip_cuenta = "Gasto";
                else
                    $descrip_cuenta="Activo";

                $res = false;
                $msj = "La Partida [" . $cod_com . "] NO tiene asociada Cuenta  Contable de " . $descrip_cuenta . ".\\nPor favor verifique.";
                return $res;
            }else{
                if ($cuenta_x_pagar == 'cta_x_pagar')
                    $descrip_cuenta="Por Pagar";
                else
                    $descrip_cuenta="Por Pagar de Activo";

                $res = false;
                $msj = "La Partida [" . $cod_com . "] NO tiene asociada Cuenta Contable de " . $descrip_cuenta . ".\\nPor favor verifique.";
                return $res;
            }
        }

        return $res;
    }

    //-----------------------------------------------------------------------------
    //         Funcion que valida si existe disponibilidad presupuestaria
    //                          para comprometer o causar
    //-----------------------------------------------------------------------------
    function Validar_Monto_a_Procesar(&$msj,$ano_fiscal,$monto,$cod_com,$accion){
        $res = true;

        $res = MaestroLey::where('ano_pro',$ano_fiscal )
                         ->where('cod_com',$cod_com )
                         ->select($accion)
                         ->first();

        if (count($res) > 0){
            $monto_procesado = $res->$accion;
            $monto_procesado= $monto_procesado - $monto;

            if ($monto_procesado >= 0)
                return true;
            else{
                $res = false;

                if ($accion = 'mto_dis')
                    $descripcion = 'Comprometer';
                else
                    $descripcion = 'Causar';

                $msj = "Error No Existe disponibilidad Presupuestaria para " . $descripcion . " la partida [" . $cod_com . "].\\nPor favor verifique.";
                return $res;
            }
        }else{
            $res = false;
            $msj = "Error Validando Patida Presupuestaria [" . $cod_com . "].\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }
        return $res;
    }

    //--------------------------------------------------------------
    // ajustar compriso provision
    //--------------------------------------------------------------
    function Ajustar_Compromiso_provision($factura,&$msj,$ano_sol_orden,$concepto,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,
                                           $cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$monto_Comprometer,$accion,$accion2,$decripcion,$result_iva,$ano_fiscal,
                                           $des_proceso){
        //Realizar el ajuste en Pre Maestro Ley
        $res = $this->Ajustar_Compromiso_Pre_Maestro_Ley($msj,$monto_Comprometer,$cod_com,$accion,$accion2,$ano_fiscal,$des_proceso);

        if (!$res)
            return $res;

        $cod_com_viejo = '';

        //Si es la partida de IVA
        if ($result_iva[0]["cod_pari"] == $cod_par && $result_iva[0]["cod_geni"] == $cod_gen &&
        $result_iva[0]["cod_espi"] == $cod_esp && $result_iva[0]["cod_subi"] == $cod_sub){
            $res = $this->Buscar_Partida_Iva_Inicial_del_Documento($msj,$ano_sol_orden,$cod_com_viejo,$cod_com,$ano_fiscal);

            if (!$res)
                return $res;
        }else{
            $cod_com_viejo = '';
            $centro_actual = $this->Concatenar_Centro_Costo($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad);
            $partida	   = $this->Concatenar_Partida($cod_par,$cod_gen,$cod_esp,$cod_sub);
            $res		   = $this->Buscar_centro_costo_viejo($msj,$centro_actual,$cod_com_viejo,$ano_sol_orden);

            if (!$res)
                return $res;

            $cod_com_viejo = $cod_com_viejo . "." . $partida;
        }

        $monto_compromiso = 0;
        $nro_enl_anular	  = '';
        $tip_ope		  = '';
        $sol_tipo		  = '';

        //------------------------------------------------------------------
        //             Realizar el Ajuste del compromiso
        //------------------------------------------------------------------
        $status_reg = "1";
        $tip_ope	= 95;
        $sol_tipo	= 'AJ';

        if ($accion == '-'){
            //$monto_a_comprometer	   = $monto_compromiso - $monto_Comprometer;
            $monto_a_comprometer	   = $monto_Comprometer;
            $monto_ajustado_compromiso = $monto_Comprometer * (-1);
        }else{
            $monto_a_comprometer	   = $monto_compromiso + $monto_Comprometer;
            $monto_ajustado_compromiso = $monto_Comprometer;
        }

        //----------------------------------------------
        // Insertar el momento Presupuestario
        //----------------------------------------------
        $num_fac	  = $factura->num_fac;
        $nota_entrega = '';
        $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,
                $unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$monto_a_comprometer,$decripcion,$tip_ope,
                $sol_tipo,$status_reg,$ano_sol_orden,$factura->nro_doc,$nota_entrega,
                $num_fac,$monto_ajustado_compromiso,$ano_fiscal,'1');

        if (!$res)
            return $res;

        //----------------------------------------------------
        // Leer numero de registro que se termina de ingresar
        //----------------------------------------------------
        $nro_enl = '';
        $estado  = '1';

        //---------------------------------------------------------------------------
        //    Actualizar status en Pre Movimiento del movimiento anterior en 2
        //---------------------------------------------------------------------------
        $res = $this->Actualizar_status_Pre_Movimiento($factura,$msj,$nro_enl,$nro_enl_anular);

        if (!$res)
            return $res;

        return $res;
    }

    //-------------------------------------------------------------------------------------------------------
    //Funcion que ajusta los montos del compromiso y el disponible en pre-maestro ley
    //-------------------------------------------------------------------------------------------------------
    function Ajustar_Compromiso_Pre_Maestro_Ley(&$msj,$monto_comprometer,$cod_com,$accion,$accion2,$ano_fiscal,$des_proceso){
        $res = true;
        // $accion		 = "-";
        // $accion2	 = "+";
        $res = MaestroLey::where('ano_pro',$ano_fiscal )
                         ->where('cod_com',$cod_com );

        $res = $res."->update(['mto_com'   => DB::raw('mto_com'".$accion.$monto_comprometer."),
                                'mto_dis'   => DB::raw('mto_dis'".$accion2 .$monto_comprometer.")
                          ])";


        // $q1 = "UPDATE pre_maestroley
        //         SET mto_com = mto_com " . $accion . " " . $monto_comprometer . ",
        //             mto_dis = mto_dis " . $accion2 . " " . $monto_comprometer . "
        //         WHERE ano_pro = " . $ano_fiscal . " AND
        //                 cod_com = '" . $cod_com . "'";

        //$res = $db->execQuery($conn, $q1);

        if (!$res){
            $msj = "Error ajustando saldos de Compromiso en pre_maestroley " . $des_proceso . " para la partida [" . $cod_com . "] Comuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }
    //------------------------------------------------------------------
    //Se ubica la partida de Iva que se afecto en el año del compromiso
    //       para trabajar la anulacion de dichos registros
    //-------------------------------------------------------------------
    function Buscar_Partida_Iva_Inicial_del_Documento(&$msj,$ano_sol_orden,&$cod_iva_viejo,$cod_com,$ano_fiscal){
        if ($ano_sol_orden != $ano_fiscal){
            //Buscar la partida de Iva que estubo activa para el año del documento

            $result_iva_v  = Registrocontrol::where('ano_pro',$ano_sol_orden)
                                            ->select('tip_codi', 'cod_pryacci', 'cod_obji', 'gerenciai', 'unidadi', 'cod_pari','cod_geni','cod_espi', 'cod_subi')
                                            ->first();

            if (!empty($result_iva_v->tip_codi)){
                $iva = $result_iva_v;
                $cod_iva_viejo = $this->armar_cod_com($iva->tip_codi,$iva->cod_pryacci,$iva->cod_obji,$iva->gerenciai,	$iva->unidadi,$iva->cod_pari,$iva->cod_geni,$iva->cod_espi,$iva->cod_subi);
            }else{
                $res = $db->execQuery($conn, 'c');
                $msj = "Error Consultando Partida de IVA en Registro y Control para el año " . $ano_sol_orden . "\\nComuniquese con el Administrador del Sistema.";
                return $res;
            }
        }else
            $cod_iva_viejo = $cod_com;

        return 	$cod_iva_viejo;
    }
    //-------------------------------------------------------------------------------------
    //          Funcion que busca si el centro de costo ha sido modificado
    //--------------------------------------------------------------------------------------
    function Buscar_centro_costo_viejo(&$msj,$centro_actual,&$centro_costo,$ano){
        $res = true;
        $result_centro_costo     = CentroCosto::where('ajust_ctrocosto', $centro_actual)
                                       ->where('ano_pro', $ano)
                                       ->select('cod_cencosto')
                                       ->first();

        if (empty($result_centro_costo->cod_cencost)){
            $res = false;
            $msj = "Error buscando centro de costo viejo " . $centro_actual . "  para el año " . $ano . ". Comuniquese con el Administrador del Sistema.";
            return $res;
        }else
            $centro_costo  = $result_centro_costo->cod_cencosto;

        return $res;
    }
    //-------------------------------------------------------------------------------------------------------
    //                    Funcion que actualiza el monto del causado en Pre_Maestro_Ley
    //-------------------------------------------------------------------------------------------------------
    function Causar_Partida_Pre_Maestro_Ley(&$msj,$valor,$cod_com,$accion,$ano_fiscal){
        $res = true;

//dd($accion.'/'.$valor);
        $res = MaestroLey::where('ano_pro',$ano_fiscal )
                        ->where('cod_com',$cod_com );
//dd($res."->update(['mto_cau'   => DB::raw('mto_cau'".$accion.$valor.")])"); //.$accion.$valor.")])");
       $res =$res->update(['mto_cau'   => DB::raw("mto_cau $accion $valor")]);

        if (!$res){
            $msj = "Error Actualizando el Causado de la Partida [" . $cod_com . "] Comuniquese con su Administrador de sistema.";
            return $res;
        }

        return $res;
    }
    //-------------------------------------------------------------------------------------------------------------
    //                               FUNCION QUE REALIZA EL MOVIMIENTO PRESUPUESTARIO
    //-------------------------------------------------------------------------------------------------------------
    function Insertar_Presupuesto($factura,&$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,
                                    $unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$saldo,$concepto,$tip_ope,$sol_tip,$sta_reg,
                                    $ano_sol_orden,$nro_doc,$nota,$numero_factura,$mto_transaccion,$ano_fiscal,$cierre_presu){

        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();
        $ano_proceso   = $factura->ano_fiscal; //Dualidad
        $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );
        $hora_Proceso  = $this->FechaSistema($ano_proceso,'His');
        $nro_enl = 0;
        $cod_com = $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);

        //----------------------------------------------------
        //Valida que otro proceso no se ejecuto mientras se
        //  esta actualizando la partida presupuestaria
        //----------------------------------------------------

        $res = $this->insert_cod($ano_fiscal,$cod_com);

        if (!$res){
            $res = false;
            $msj = "Error al Insertar [" . $cod_com . "] en insert_cod. \\nIntente Nuevamente Procesar el Registro";
            return $res;
        }

        //-------------------------------------------------------------------------------
        //    INGRESAR EL MOVIMIENTO PRESUESTARIO Y ACTUALIZAR EL MONTO DE LA PARTIDA
        //--------------------------------------------------------------------------------
        $datos["ano_pro"] 		  = $ano_fiscal;
        $datos["cierre"]    	  = $cierre_presu;
        $datos["tip_ope"] 		  = $tip_ope;//Causado sobre Compromiso|R|
        $datos["sol_tip"] 		  = $sol_tip;
        $datos["num_doc"] 		  = $nro_doc;
        $datos["fec_pos"] 		  = $fecha_proceso;
        $datos["fec_tra"] 		  = $factura->fec_fac;
        $datos["nom_elab"] 		  = $usuario->usuario;
        $datos["concepto"] 		  = $concepto;
        $datos["nro_enl"] 		  = NULL;
        $datos["ord_pag"] 		  = NULL;
        $datos["fec_pag"] 		  = NULL;
        $datos["sta_reg"] 		  = $sta_reg;
        $datos["fec_sta"] 		  = $factura->fec_sta;
        $datos["hor_sta"]		  = $hora_Proceso;
        $datos["tip_cod"]    	  = $tip_cod;
        $datos["cod_pryacc"] 	  = $cod_pryacc;
        $datos["objetivo"]   	  = $cod_obj;
        $datos["gerencia"]   	  = $gerencia;
        $datos["unidad"]     	  = $unidad;
        $datos["cod_par"]    	  = $cod_par;
        $datos["cod_gen"]    	  = $cod_gen;
        $datos["cod_esp"]    	  = $cod_esp;
        $datos["cod_sub"]    	  = $cod_sub;
        $datos["cod_com"]    	  = $cod_com;
        $datos["mto_tra"]    	  = $saldo;
        $datos["nro_enl"]    	  = $nro_enl;
        $datos["ano_doc"]    	  = $ano_sol_orden;
        $datos["num_fac"]		  = $numero_factura;
        $datos["nota_entrega"]    = $nota;
        $datos["mto_transaccion"] = $mto_transaccion;

        if ($tip_ope == 50){
            //-------------------------------------------------
            //Validar que el causado no supere el compromiso
            //------------------------------------------------
            $res = MaestroLey::where('ano_pro',$ano_fiscal )
                             ->where('cod_com',$cod_com )
                             ->select('mto_cau','mto_com')
                             ->first();


            if ($res){
                $monto_causado 	  = $res->mto_cau;
                $monto_compromiso = $res->mto_com;
                $monto_compromiso = $monto_compromiso;
                $monto_compromiso = $monto_compromiso * 1;
                $monto_causado	  = $monto_causado * 1;
                $monto_causado	  = round($monto_causado,2);
                $monto_compromiso = round($monto_compromiso,2);

                if ($monto_compromiso >= $monto_causado){
                    //-----------------------------------------------
                    // Insert Generico que inserta en Pre_Movimiento
                    // se encuentra en utilsPHP/sacoGen.Script.php
                    //-----------------------------------------------
                    $res = $this->insert_preMovimientos($datos);

                    if (!$res){
                        $res =false;
                        $res = 0;
                        $msj = "Error al Insertar en Pre_Movimientos la partida presupuestaria [" . $cod_com . "] Comuniquese con el Administrador del Sistema.";
                        return $res;
                    }

                    //-----------------------------------------------------------------
                    // libera la partida para que pueda ser utilizada por otro proceso
                    //-----------------------------------------------------------------
                    $res = $this->delete_cod($ano_fiscal,$cod_com);

                    if (!$res){
                        $res = false;
                        $msj = "Error al Borrar en delete_cod.  Intente Nuevamente Procesar el Registro";
                        return $res;
                    }
                }else{
                    $res = false;
                    $msj = "Error el Compromiso es Insuficiente para Causar la Partida [" . $cod_com . "]. Por favor verifique.";
                    return $res;
                }
            }else{
                $res =false;
                $msj = "Error Validando Patida Presupuestaria [" . $cod_com . "]. Comuniquese con el Administrador del Sistema.";
                return $res;
            }
        }else{
            //----------------------------------------------
            // Insert Generico que inserta en Pre_Movimiento
            // se encuentra en utilsPHP/sacoGen.Script.php
            //----------------------------------------------
            $res = $this->insert_preMovimientos($datos);

            if (!$res){
                $res = false;
                $res = 0;
                $msj = "Error al Insertar en Pre_Movimientos la partida presupuestaria [" . $cod_com . "] Comuniquese con el Administrador del Sistema.";
                return $res;
            }

            //-----------------------------------------------------------------
            // libera la partida para que pueda ser utilizada por otro proceso
            //------------------------------------------------------------------
            $res =$this->delete_cod($ano_fiscal,$cod_com);

            if (!$res){
                $res =false;
                $msj = "Error al Borrar en delete_cod. Intente Nuevamente Procesar el Registro";
                return $res;
            }
        }

        return $res;
    }
    //----------------------------------------------
     // Funcion que inserta en tabla de control de los movimientos presupuestarios
     // @param {integer} $ano_pro: Año en proceso
     // @param {string} $cod_com: Codigo compuesto de la estructura de gastos
    //----------------------------------------------
    function insert_cod($ano_pro,$cod_com){
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

        $res=ControlPresupuesto::create([
            'ano_pro'               => $ano_pro,
            'cod_com'               => $cod_com,
            'usuario'               => $usuario->usuario
        ]);

        return $res;
    }
    //----------------------------------------------
    // Funcion que inserta los pre-movimientos presupuestarios
    //----------------------------------------------

    function insert_preMovimientos($datos){


        try{
            DB::connection('pgsql')->beginTransaction();
            $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

            // require_once($url."classesPHP/tablas/presupuesto/pre_movimientos.Class.php");
            // $tabla = new pre_movimientos();
            // $columnas = $tabla->getColumnas();
            // $db = getDB();



            $columnas = [];
            $columnas['num_reg'] = '';
            $columnas['ano_pro'] = '';
            $columnas['num_mes'] = '';
            $columnas['tip_ope'] = '';
            $columnas['sol_tip'] = '';
            $columnas['num_doc'] = '';
            $columnas['fec_tra'] = '';
            $columnas['tip_cod'] = '';
            $columnas['cod_pryacc'] = '';
            $columnas['objetivo'] = '';
            $columnas['gerencia'] = '';
            $columnas['unidad'] = '';
            $columnas['cod_par'] = '';
            $columnas['cod_gen'] = '';
            $columnas['cod_esp'] = '';
            $columnas['cod_sub'] = '';
            $columnas['cod_com'] = '';
            $columnas['ced_ben'] = '';
            $columnas['concepto'] = '';
            $columnas['mto_tra'] = '';
            $columnas['sdo_mod'] = '';
            $columnas['sdo_apa'] = '';
            $columnas['sdo_pre'] = '';
            $columnas['sdo_com'] = '';
            $columnas['sdo_cau'] = '';
            $columnas['sdo_dis'] = '';
            $columnas['sdo_pag'] = '';
            $columnas['nro_enl'] = '';
            $columnas['sta_reg'] = '';
            $columnas['usuario'] = '';
            $columnas['fecha'] = '';
            $columnas['usua_anu'] = '';
            $columnas['fec_anu'] = '';
            $columnas['ano_doc'] = '';
            $columnas['nota_entrega'] = '';
            $columnas['num_fac'] = '';
            $columnas['mto_transaccion'] = '';
            $columnas['cierre'] = '';
            $columnas['manual'] = '';
            $columnas['feha_auditoria'] = '';
            $columnas['referencia'] = '';

            $res = 0;
            //if ($db) {
                // $db->setTipoResult("array"); // Indicar a clase que devuelva resultset en un Array
                // $conn=$db->conectar();
                $columnas["ano_pro"] = $datos["ano_pro"];
                // $where = "ano_pro= ".$datos["ano_pro"];


                $result  = Registrocontrol::where('ano_pro',$datos["ano_pro"])
                                        ->select('mes_pre')
                                        ->first();

                $row = $result; #Obtener Fila
                $mes= $row->mes_pre;
                $columnas["num_mes"] = $mes;
                $columnas["tip_ope"] = $datos["tip_ope"];


                if(($columnas["tip_ope"] == 20) && (($datos["sol_tip"] == 'OC') || ($datos["sol_tip"] == 'CO'))){

                    $result = PreMovimiento::where('sta_reg',1 )
                                        ->where('ano_pro',$datos["ano_doc"] )
                                        ->where('sol_tip',$datos["sol_tip"] )
                                        ->where('tip_ope',10 )
                                        ->where(DB::raw("trim(num_doc)"),$datos["num_doc"] )
                                        ->where('cod_com',$datos["cod_com_viejo"] )
                                        ->get();

                    if ($result){
                        $fila = $result[0];
                        $datos["nro_enl"] = $fila->num_reg;
                        $datos["sta_ant"] = $fila->sta_reg;
                        $datos["fec_ant"] = $fila->fec_sta;

                        $update_premovimiento = PreMovimiento::where('num_reg',$fila->num_reg)
                                                            ->update(['sta_reg'  => '2',
                                                                    'usua_anu' => $usuario->usuario,
                                                                    'fec_anu'  => DB::raw("timestamp('Ymd')")]);

                                            dd($update_premovimiento);
                    }
                }
                if(($columnas["tip_ope"] == 9)){
                    $result = PreMovimiento::where('sta_reg',1 )
                                        ->where('ano_pro',$datos["ano_doc"] )
                                        ->where('sol_tip',$datos["sol_tip"] )
                                        ->where('tip_ope',8 )
                                        ->where(DB::raw("trim(num_doc)"),$datos["num_doc"] )
                                        ->where('cod_com',$datos["cod_com_viejo"] )
                                        ->get();

                    if ($result){
                        $fila = $result[0];
                        $datos["nro_enl"] = $fila->num_reg;
                        $datos["sta_ant"] = $fila->sta_reg;
                        $datos["fec_ant"] = $fila->fec_sta;

                        PreMovimiento::where('num_reg',$fila["num_reg"])
                                    ->update(['sta_reg'  => '2',
                                            'usua_anu' => $usuario->usuario,
                                            'fec_anu'  => DB::raw("timestamp('Ymd')")]);


                    }
                }
                if(($columnas["tip_ope"] == 60) && ($datos["sol_tip"] == 'AN')){

                    $result = PreMovimiento::where('sta_reg',1 )
                                        ->where('ano_pro',$datos["ano_doc"] )
                                        ->where('sol_tip',$datos["sol_tip"] )
                                        ->where('tip_ope',50 )
                                        ->where(DB::raw("trim(num_doc)"),$datos["num_doc"] )
                                        ->where('cod_com',$datos["cod_com_viejo"] )
                                        ->get();

                    if ($result){
                        $fila = $result[0];
                        $datos["nro_enl"] = $fila->num_reg;
                        $datos["sta_ant"] = $fila->sta_reg;
                        $datos["fec_ant"] = $fila->fec_sta;

                        PreMovimiento::where('num_reg',$fila["num_reg"])
                                    ->update(['sta_reg'  => '2',
                                            'usua_anu' => $usuario->usuario,
                                            'fec_anu'  => DB::raw("timestamp('Ymd')")]);

                    }
                }
                if(($columnas["tip_ope"] == 50) && ($datos["sol_tip"] == 'AN')){

                    $result = PreMovimiento::where('sta_reg',1 )
                                        ->where('ano_pro',$datos["ano_doc"] )
                                        ->where('sol_tip',$datos["sol_tip"] )
                                        ->where('tip_ope',60 )
                                        ->where(DB::raw("trim(num_doc)"),$datos["num_doc"] )
                                        ->where('cod_com',$datos["cod_com_viejo"] )
                                        ->get();

                    if ($result){
                        $fila = $result[0];
                        $datos["nro_enl"] = $fila->num_reg;
                        $datos["sta_ant"] = $fila->sta_reg;
                        $datos["fec_ant"] = $fila->fec_sta;

                        PreMovimiento::where('num_reg',$fila["num_reg"])
                                    ->update(['sta_reg'  => '2',
                                            'usua_anu' => $usuario->usuario,
                                            'fec_anu'  => DB::raw("timestamp('Ymd')")]);
                    }
                }
                //-----------------------------------------------------------------
                //						Pagado Directo
                //-------------------------------------------------------------------
                if(($columnas["tip_ope"] == 62)){
                    //----------Ubicamos Registros Presupuestarios Originales-------------//

                    $result = PreMovimiento::where('sta_reg',1 )
                                            ->where('ano_pro',$datos["ano_pro"] )
                                            ->where('sol_tip',$datos["sol_tip"] )
                                            ->where('tip_ope',61)
                                            ->where(DB::raw("trim(num_doc)"),$datos["num_doc"] )
                                            ->where('cod_com',$datos["cod_com"] )
                                            ->get();

                    $fila = $result[0];
                    $datos["nro_enl"] = $fila->num_reg;
                    $datos["sta_ant"] = $fila->sta_reg;
                    $datos["fec_ant"] = $fila->fec_sta;
                    //----------Actualizamos Datos del Registro Presupuestario Original--------//

                    PreMovimiento::where('ano_pro',$datos["ano_pro"])
                                ->where('sol_tip',$datos["sol_tip"] )
                                ->where('tip_ope',61)
                                ->where('sta_reg',1 )
                                ->where(DB::raw("trim(num_doc)"),$datos["num_doc"] )
                                ->where('cod_com',$datos["cod_com"] )
                                ->update(['sta_reg'  => '2',
                                        'usua_anu' => $usuario->usuario,
                                        'fec_anu'  => DB::raw("timestamp('Ymd')")]);
                }
                //-----------------------------------------------------------------
                //						Causado Directo
                //-------------------------------------------------------------------
                if($columnas["tip_ope"]== 40){

                    $result = PreMovimiento::where('sta_reg',1 )
                                        ->where('ano_pro',$datos["ano_pro"] )
                                        ->where('sol_tip',$datos["sol_tip"] )
                                        ->where('tip_ope',30)
                                        ->where('sta_reg','1')
                                        ->where(DB::raw("trim(num_doc)"),$datos["num_doc"] )
                                        ->where('cod_com',$datos["cod_com"] )
                                        ->get();


                    if ($result){
                        $fila = $result[0];
                        $datos["nro_enl"] = $fila->num_reg;
                        $datos["sta_ant"] = $fila->sta_reg;
                        $datos["fec_ant"] = $fila->fec_sta;

                        PreMovimiento::where('ano_pro',$datos["ano_pro"])
                                    ->where('sol_tip',$datos["sol_tip"] )
                                    ->where('tip_ope',30)
                                    ->where('sta_reg','1' )
                                    ->where(DB::raw("trim(num_doc)"),$datos["num_doc"] )
                                    ->where('cod_com',$datos["cod_com"] )
                                    ->update(['sta_reg'  => '2',
                                                'usua_anu' => $usuario->usuario,
                                                'fec_anu'  => DB::raw("timestamp('Ymd')")]);
                }
                }

                $columnas["sol_tip"]        = $datos["sol_tip"];
                $columnas["num_doc"]        = $datos["num_doc"];
                $columnas["tip_cod"]        = $datos["tip_cod"];
                $columnas["cod_pryacc"]     = $datos["cod_pryacc"];
                $columnas["objetivo"]       = $datos["objetivo"];
                $columnas["gerencia"]       = $datos["gerencia"];
                $columnas["unidad"]         = $datos["unidad"];
                $columnas["cod_par"]        = $datos["cod_par"];
                $columnas["cod_gen"]        = $datos["cod_gen"];
                $columnas["cod_esp"]        = $datos["cod_esp"];
                $columnas["cod_sub"]        = $datos["cod_sub"];
                $columnas["cod_com"]        = $datos["cod_com"];

                if(!empty($datos["mto_transaccion"])){
                        $columnas["mto_transaccion"] = $datos["mto_transaccion"];
                }
                if(!empty($datos["num_fac"])){
                        $columnas["num_fac"] = $datos["num_fac"];
                }
                if(!empty($datos["nota_entrega"])){
                        $columnas["nota_entrega"] = $datos["nota_entrega"];
                }
                if(!empty($datos["ano_doc"])){
                    $columnas["ano_doc"] = $datos["ano_doc"];
                }
                if(!empty($datos["ced_ben"])){
                    $columnas["ced_ben"] = $datos["ced_ben"];
                }
                $columnas["concepto"] = $datos["concepto"];
                $columnas["mto_tra"]  = $datos["mto_tra"];

                //Leer Saldos Actuales en Maestro de Ley
                $result = MaestroLey::where('ano_pro',$datos["ano_pro"] )
                                    ->where('cod_com',$datos["cod_com"] )
                                    ->select('mto_mod','mto_apa','mto_pre','mto_com','mto_cau','mto_dis','mto_pag')
                                    ->get();

                $nn = count($result);

                if ($nn>0){
                    $fila = $result[0]; #Obtener Fila
                    $columnas["sdo_mod"] = $fila->mto_mod;
                    $columnas["sdo_apa"] = $fila->mto_apa;
                    $columnas["sdo_pre"] = $fila->mto_pre;
                    $columnas["sdo_com"] = $fila->mto_com;
                    $columnas["sdo_cau"] = $fila->mto_cau;
                    $columnas["sdo_dis"] = $fila->mto_dis;
                    $columnas["sdo_pag"] = $fila->mto_pag;
                }
                if(!empty($datos["nro_enl"])){
                    $columnas["nro_enl"] = $datos["nro_enl"];
                }else{
                    $columnas["nro_enl"] = "";
                }

                $columnas["sta_reg"] = $datos["sta_reg"];

                if(!empty($datos["usua_anu"])){
                    $columnas["usua_anu"] = $datos["usua_anu"];
                }
                if(!empty($datos["fec_anu"])){
                    $columnas["fec_anu"] = $datos["fec_anu"];
                }
                if(!empty($datos["fec_tra"])){

                    $columnas["fec_tra"] = $datos["fec_tra"];
                }

                if(empty($datos["fecha"])){
                    $columnas["fecha"] = now()->format('Y-m-d');
                }else{
                    $columnas["fecha"] = $datos["fecha"];
                }

                if(isset($datos['cierre'])){
                    $columnas['cierre'] = $datos['cierre'];
                }else{
                    $columnas['cierre'] = '1';
                }

                $columnas["usuario"] = $usuario->usuario;
                //$res .= $db->execQuery($conn, "incluir", $tabla->getNombre(), $columnas);
                //dd($columnas);
            $res = PreMovimiento::create(['num_mes'           => $columnas["num_mes"] ,
                                'ano_pro'           => $columnas["ano_pro"],
                                'tip_ope'           => $columnas["tip_ope"],
                                'mto_tra'           => $columnas["mto_tra"],
                                'usuario'           => $columnas["usuario"],
                                'sol_tip'           => $columnas["sol_tip"],
                                'num_doc'           => $columnas["num_doc"],
                                'fec_tra'           => $columnas["fec_tra"],
                                'tip_cod'           => $columnas["tip_cod"],
                                'cod_pryacc'        => $columnas["cod_pryacc"],
                                'objetivo'          => $columnas["objetivo"],
                                'gerencia'          => $columnas["gerencia"],
                                'unidad'            => $columnas["unidad"],
                                'cod_par'           => $columnas["cod_par"],
                                'cod_gen'           => $columnas["cod_gen"],
                                'cod_esp'           => $columnas["cod_esp"],
                                'cod_sub'           => $columnas["cod_sub"],
                                'cod_com'           => $columnas["cod_com"],
                                'ced_ben'           => !empty($columnas["ced_ben"])?$columnas["ced_ben"]:'',
                                'concepto'          => $columnas["concepto"],
                                'sdo_mod'           => $columnas["sdo_mod"],
                                'sdo_apa'           => $columnas["sdo_apa"],
                                'sdo_pre'           => $columnas["sdo_pre"],
                                'sdo_com'           => $columnas["sdo_com"],
                                'sdo_cau'           => $columnas["sdo_cau"],
                                'sdo_dis'           => $columnas["sdo_dis"],
                                'sdo_pag'           => $columnas["sdo_pag"],
                                'nro_enl'           => $columnas["nro_enl"],
                                'sta_reg'           => $columnas["sta_reg"],
                                'fecha'             => $columnas["fecha"],
                                'usua_anu'          => !empty($columnas["usua_anu"])?$columnas["ced_ben"]:'',
                                'ano_doc'           => $columnas["ano_doc"],
                                'nota_entrega'      => !empty($columnas["nota_entrega"])?$columnas["nota_entrega"]:'',
                                'num_fac'           => !empty($columnas["num_fac"])?$columnas["num_fac"]:'',
                                'cierre'            => $columnas["cierre"],
                                'manual'                => !empty($columnas["manual"])?$columnas["manual"]:'',
                                'mto_transaccion'   => !empty($columnas["mto_transaccion"])?$columnas["mto_transaccion"]:0
                                ]);

            DB::connection('pgsql')->commit();
            return true;
        }   catch(\Illuminate\Database\QueryException $e){

                DB::connection('pgsql')->rollBack();
                $msj = 'A ocurrido un ERROR en la transaccion.\nPor Favor Intente de Nuevo.';
                return false;
        }
              //  dd($res);

        return $res;
    }

    //-----------------------------------------------------------------
    // Funcion que elimina en tabla de control de los movimientos presupuestarios
    // @param {integer} $ano_pro: Año en proceso
    // @param {string} $cod_com: Codigo compuesto de la estructura de gastos
    //-----------------------------------------------------------------
    function delete_cod($ano_pro,$cod_com) {
        //$db = getDB();
        $res = 0;
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

        $res=ControlPresupuesto::where('ano_pro',$ano_pro)
                               ->where('usuario',$usuario->usuario)
                               ->delete();

        return $res;
    }


    //----------------------------------------------------------------------------------------------------
    //                 Guarda Registro del comprobante en las tablas de facturas
    //---------------------------------------------------------------------------------------------------
    function Crear_Comprobante($factura,&$msj,$nro_doc,$x,$cuenta_contable,$valor,$accion,$nc,$ano_doc,$ano_fiscal){

        $res = true;
        $res = CxpDetComproFacturas::create(['ano_pro'          => $ano_fiscal,
                                             'num_fac'          => $factura->num_fac,
                                             'nro_sol_orden'    => $nro_doc,
                                             'nro_ren'          => $x,
                                             'cod_cta'          => $cuenta_contable,
                                             'tipo'             => $accion,
                                             'monto'            => $valor,
                                             'nc'               => $nc,
                                             'ano_sol_doc'      => $ano_doc,
                                             'rif_prov'         => $factura->rif_prov
                                            ]);


        if (!$res){
            $msj = "Error Creando Comprobante Contable para la Cuenta (" . $accion . ") " . $cuenta_contable . ".\\n Comuniquese con su Adminsitrados de Sistema.";
            return $res;
        }

        return $res;
    }
    //----------------------------------------------------------------------------------------------------
    //Funcion que realiza todos los procesos necesarios de la orden de compra para la opcion de aprobar
    //----------------------------------------------------------------------------------------------------
    function Aprobar_Factura($factura,&$msj,$result_iva,$result_nc,$ano_fiscal){
        $nro_doc	  = $factura->nro_doc;
        $ncr_sn		  = $factura->ncr_sn;
        $nro_doc	  = $factura->nro_doc;
        $por_anticipo = $factura->por_anticipo;
        $x   = 1000;
        $res = true;
        $y	 = 1000;
        $icont = 0;
        $cta_x_pagar = '';

        //----------------------------------------------------------------------------
        //Valida que si existe nota de credito el % de iva sea igual al de la factura
        //----------------------------------------------------------------------------
        $ncr_sn = $factura->ncr_sn;

        if ($ncr_sn == 'S'){
            $res = $this->validar_iva_nota($factura,$msj);

            if (!$res)
                return $res;
        }

        //Funcion que Busca el año de la Recepcion de la Factura
        $ano_recp_fac = '';
        $res = $this->Retorna_ano_de_recepcion($factura,$msj,$ano_recp_fac);

        if (!$res)
            return $res;

        //Buscar año de generación de Documento
        $ano_sol_doc = $factura->ano_sol;
        $grupo = '';

        //Buscar las Siglas del Documento
        $res = $this->Retornar_siglas_Documentos($msj,$grupo,$factura->tipo_doc);

        if (!$res)
            return $res;

        $por_iva = 0;

        //Query para retornar % de IVA
        $res = $this->Retorna_Porcenta_Iva($factura,$msj,$ano_sol_doc,$por_iva);

        if (!$res)
            return $res;

        if ($por_anticipo != '0.00' && $por_anticipo != null){
            //Buscar la Cuenta x cobrar del proveedor
            $cta_x_cobrar = '';
            $res = $this->CuentaxCobrar_Proveedor($msj,$cta_x_cobrar,$factura);

            if (!$res)
                return $res;
        }

        //----------------------------------------------
        //----------------------------------------------

        $facfactura = Factura::where('ano_pro',$factura->ano_pro)
                        ->where('rif_prov',$factura->rif_prov)
                        ->where('num_fac',$factura->num_fac)
                        ->first();

        $detgastosfactura =$facfactura->cxpdetgastosfactura;

        foreach($detgastosfactura as $index => $row2){  //pendiente no es lo que esta en el grid es lo que esta guardado

            $icont = $icont + 1 ;
            //Partida Presupuestaria
            $tip_cod	= $row2["tip_cod"];
            $cod_pryacc = $row2["cod_pryacc"];
            $cod_obj	= $row2["cod_obj"];
            $gerencia	= $row2["gerencia"];
            $unidad		= $row2["unidad"];
            $cod_par    = $row2["cod_par"];
            $cod_gen    = $row2["cod_gen"];
            $cod_esp    = $row2["cod_esp"];
            $cod_sub    = $row2["cod_sub"];
            $cod_com_Viejo = $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);
            //dd($res);
            if ($ano_fiscal != $ano_sol_doc){
                $res = $this->armar_centro_costo($msj,$tip_cod,$cod_pryacc,
                                                $cod_obj,$gerencia,$unidad,$cod_com,$row2,$ano_fiscal,$ano_sol_doc);
                                               // dd($res);
                if (!$res)
                    return $res;
            }else
                $cod_com = $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);

            if ($cod_com_Viejo != $cod_com){
                //Se debe Modificar la estructura de gasto por la nueva
                $res = CxPDetGastosFactura::where('ano_pro',  $factura->ano_pro)
                                          ->where('rif_prov', $factura->rif_prov)
                                          ->where('num_fac',  $factura->num_fac)
                                          ->where('cod_com',  $cod_com_Viejo)
                                          ->where('nro_doc',  $factura->nro_doc)
                                          ->where('ano_sol',  $factura->ano_sol)
                                          ->update(['tip_cod'       => $tip_cod,
                                                  'cod_pryacc'    => $cod_pryacc,
                                                  'cod_obj'       => $cod_obj,
                                                  'gerencia'      => $gerencia,
                                                  'unidad'        => $unidad,
                                                  'cod_par'       => $cod_par,
                                                  'cod_gen'       => $cod_gen,
                                                  'cod_esp'       => $cod_esp,
                                                  'cod_sub'       => $cod_sub,
                                                  'cod_com'       => $cod_com
                                                  ]);

                if (!$res){
                    $msj = "Error Modificando la Estructura de Gasto por cambio de centro de costo [" . $cod_com . "]. Comuniquese con su Administrador de sistema.";

                    return $res;
                }
            }

            $nota_entrega = '';

            if ($result_iva->cod_pari == $row2->cod_par && $result_iva->cod_geni == $row2->cod_gen
                && $result_iva->cod_espi == $row2->cod_esp && $result_iva->cod_subi == $row2->cod_sub){
                //---------------------------------------------------------------------
                // Buscar las cuentas contables asociadas para crear el asiento contable
                //----------------------------------------------------------------------
                $cta_x_pagar = '';
                $cuenta_contable = '';

                //------------------------------------------------------------------------------------------
                // Si es un deposito en garantia no se debe crear asiento contable
                //------------------------------------------------------------------------------------------
                if ($factura->deposito_garantia=='N'){
                    //----------------------------------------------------
                    // Buscar las cuentas por pagar y la cuenta de activo
                    //----------------------------------------------------
                    $cuenta = "cta_activo";
                    $desc_x_pagar = "cta_x_pagar_activo";
                    $res = $this->Buscar_Cuentas_Contables_x_Partidas($msj,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$cuenta_contable,$cta_x_pagar,$cuenta,$desc_x_pagar);

                    if (!$res)
                        return $res;
                }

                //-------------------------------------------------------------------------------------
                //Si es la partida de iva se debe validar que el porcentaje de la factura sea igual
                //al ingresado en la orden  de compra en caso contrario se debe hacer un ajuste al
                //          compromiso por concepto de cambio en la alicuota de iva
                //------------------------------------------------------------------------------------
                $por_iva_fac = $factura->porcentaje_iva;

                if ($por_iva != $por_iva_fac){
                    //------------------------------------------------------------------------
                    //Verificar si es % de la factura es mayor al de la certificacion se
                    //debe aumentar el compromiso y disminuir el disponible en caso contrario
                    //    se debe disminuir el compromiso y aumentar el disponible
                    //------------------------------------------------------------------------
                    $base_imponible = $factura->base_imponible;
                    $monto_iva_certificacion = round(($base_imponible * ($por_iva / 100)) * 100) / 100;
                    $monto_iva_factura = round(($base_imponible * ($por_iva_fac / 100)) * 100) / 100;

                    if ($por_iva > $por_iva_fac){
                        $monto_comprometer = $monto_iva_certificacion - $monto_iva_factura;
                        $sumar 	 = false;
                        $accion	 = '-';
                        $accion2 = '+';
                    }else{
                        $monto_comprometer = $monto_iva_factura - $monto_iva_certificacion;
                        $sumar	 = true;
                        $accion  = '+';
                        $accion2 = '-';

                        //------------------------------------------------------------
                        //Valida que exista disponibilidad presupuestaria por ese
                        //           monto que se va a comprometer
                        //--------------------------------------------------------------
                        $tipo_proceso = 'mto_dis';
                        $res = $this->Validar_Monto_a_Procesar($msj,$ano_fiscal,$monto_comprometer,$cod_com,$tipo_proceso);

                        if (!$res)
                            return $res;
                    }

                    //Ajustar el Compromiso por cambio en la alicuota de IVA
                    $decripcion = "AJUSTE DEL COMPROMISO POR CAMBIO EN LA ALICUOTA DE IVA EN LA FACTURA: " . $factura->num_fac;
                    $des_proceso = 'Por Concepto de Cambio en la Alicuota de IVA';
                    $res = $this->Ajustar_Compromiso($factura,$msj,$ano_sol_doc,$concepto,$tip_cod,$cod_pryacc,$cod_obj,
                                                    $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$monto_comprometer,$accion,$accion2,
                                                    $decripcion,$result_iva,$ano_fiscal,$des_proceso);
                    if (!$res)
                        return $res;
                }
                /*****************************************************/
                $provision = $factura->provisionada;

                //----------------------------------------------------------------------------------
                //Verificar si Existe Nota de Credito para disminuir el monto de la partida de iva
                //----------------------------------------------------------------------------------
                if ($ncr_sn == 'S')
                    $valor = $row2["mto_nc"];
                else
                    $valor = 0;

                if ($valor != 0){
                    //Ajustar el Compromiso por concepto de nota de Credito
                    $accion		 = "-";
                    $accion2     = "+";
                    $decripcion  = "AJUSTE DEL COMPROMISO POR CONCEPTO DE NOTA DE CREDITO NRO: " . $factura->nro_ncr . " EN LA FACTURA: " . $factura->num_fac;
                    $des_proceso = 'Por Concepto de Nota de Credito';
                    $res = $this->Ajustar_Compromiso($factura,$msj,$ano_sol_doc,$concepto,$tip_cod,$cod_pryacc,$cod_obj,
                                                    $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$valor,$accion,$accion2,$decripcion,
                                                    $result_iva,$ano_fiscal,$des_proceso);

                    if (!$res)
                        return $res;

                    //------------------------------------------------------------------
                    // Si es un deposito en garantia no se debe crear asiento contable
                    //------------------------------------------------------------------
                    if ($factura->deposito_garantia == 'N'){
                        //Crear el Asiento Contable de la Nota de Credito
                        $y = $y+1;
                        $acciones = 'DB';
                        $nc  = '1';
                        //alert ('aqui--11');
                        $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$y,$cta_x_pagar,$valor,$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                        if (!$res)
                            return $res;

                        $y = $y + 1;
                        $acciones = 'CR';
                        $nc  = '1';
                        //alert ('aqui--12');
                        $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$y,$cuenta_contable,$valor,$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                        if (!$res)
                            return $res;
                    }
                }

                //-------------------------------------------------------------------
                //    Realizar el Movimiento presupestario del Causado
                //-------------------------------------------------------------------
                $restar = '+';
                $res = $this->Causar_Partida_Pre_Maestro_Ley($msj,$row2["sal_cau"],$cod_com,$restar,$ano_fiscal);

                if (!$res)
                    return $res;

                //------------------------------------------------------------------
                $concepto	= "CAUSADO DE LA PARTIDA DE IVA SEGUN FACTURA NRO: " . $factura->num_fac;
                $tip_ope	= 50;
                $status_reg = "1";
                $sol_tip	= "IF";

                //------------------------------------------------
                // Insertar el momento Presupuestario del causado
                //------------------------------------------------
                $num_fac = $factura->num_fac;
                $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,
                                                    $cod_gen,$cod_esp,$cod_sub,$row2["sal_cau"],$concepto,$tip_ope,$sol_tip,$status_reg,$ano_sol_doc,
                                                    $factura->nro_doc,$nota_entrega,$num_fac,0.00,$ano_fiscal,'1');
                if (!$res)
                    return $res;
            }else{ //sino es la partida presupuestaria de iva
                //-------------------------------------------------------------------------------------
                //        Si existe Notas de credito
                //        - El compromiso se debe ajustar
                //        - Se debe ajustar el causado de la notas de entregas o actas de aceptacion
                //          se servicios asociadas a las facturas
                //-------------------------------------------------------------------------------------
                    if ($ncr_sn == 'S'){
                    $valor = $row2["mto_nc"];

                    if ($valor != 0){
                        if ($row2["gasto"] == 'Si'){
                            $cuenta = "cta_gasto";
                            $desc_x_pagar = "cta_x_pagar";
                        }else{
                            $cuenta = "cta_activo";
                            $desc_x_pagar = "cta_x_pagar_activo";
                        }

                        $cta_x_pagar = '';
                        $cuenta_contable = '';

                        //-------------------------------------------------------------------
                        // Si es un deposito en garantia no se debe crear asiento contable
                        //-------------------------------------------------------------------
                        if ($factura->deposito_garantia == 'N'){
                            //--------------------------------------------------------------
                            // Buscar las cuentas por pagar y la cuenta de gasto o activo
                            //---------------------------------------------------------------
                            $res = $this->Buscar_Cuentas_Contables_x_Partidas($msj,$cod_par,$cod_gen,$cod_esp,
                                                                            $cod_sub,$cod_com,$cuenta_contable,$cta_x_pagar,$cuenta,$desc_x_pagar);

                            if (!$res)
                                return $res;
                        }

                        //Ajustar el compromiso por concepto de nota de credito
                        $accion		 = "-";
                        $accion2	 = "+";
                        $decripcion	 = "AJUSTE DEL COMPROMISO POR CONCEPTO DE NOTA DE CREDITO NRO: " . $factura->nro_ncr . " EN LA FACTURA: " . $factura->num_fac;
                        $des_proceso ='Por Concepto de Nota de Credito';
                        $res = $this->Ajustar_Compromiso($factura,$msj,$ano_sol_doc,$concepto,$tip_cod,$cod_pryacc,
                                                        $cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$valor,$accion,
                                                        $accion2,$decripcion,$result_iva,$ano_fiscal,$des_proceso);
                        if (!$res)
                            return $res;

                        //---------------------------------------------------------------------
                        // Si viene por Orden de Compra o contrato se debe ajustar el causado
                        // Si viene por pago Directo se debe Causar el gasto
                        //---------------------------------------------------------------------
                        switch ($factura->tipo_doc){
                            case '1':
                            case '2':
                            case '3':
                            case '5':
                                $result_notas = CxPDetNotaFactura::where('ano_pro',$factura->ano_pro)
                                                                 ->where('rif_prov',$factura->rif_prov)
                                                                 ->where('num_fac',$factura->num_fac)
                                                                 ->select('ano_nota_entrega', 'rif_prov', 'num_fac', 'grupo','nro_ent', 'mto_ord', 'nro_doc')
                                                                 ->get();

                                if (count($result_notas) > 0){
                                    //Dividir el monto de la nota de credito entre las notas de entregas asociadas a la factura
                                    $total_registro_notas = count($result_notas);
                                    $monto_Modificado_causado = $valor/$total_registro_notas;

                                    for ($i = 0 ; $i < count($result_notas) ; $i ++){
                                        $nota_entr = $result_notas[$i]->grupo . '-' . $result_notas[$i]->nro_ent . '-' . $result_notas[$i]->ano_nota_entrega;
                                        //--------------------------------------------------------------------------------
                                        //Ajustar el causado de las Notas de Entregas asociadas a las facturas
                                        //--------------------------------------------------------------------------------
                                        $decripcion= "AJUSTE DEL CAUSADO POR CONCEPTO DE NOTA DE CREDITO NRO:" . $factura->nro_ncr . " EN LA FACTURA: " . $factura->num_fac;
                                        $res = $this->Ajustar_Causado($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,
                                                                        $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$ano_sol_orden,$cod_com,
                                                                        $monto_Modificado_causado,$decripcion,$nota_entr,'2',$ano_sol_doc,$ano_fiscal,'-');
                                        if (!$res)
                                            return $res;
                                    }
                                }else{
                                    $res = false;
                                    $msj = "Error Consultado Notas de Entregas asociadas a la Factura.Comuniquese con su Administrado Sistema.";
                                    return $res;
                                }

                                break;
                            case '4': //Causar el Gasto
                                //--------------------------
                                //  Actualizar el Causado
                                //--------------------------
                                $restar = '+';
                                $res = $this->Causar_Partida_Pre_Maestro_Ley($msj,$row2["sal_cau"],$cod_com,$restar,$ano_fiscal);

                                if (!$res)
                                    return $res;

                                //------------------------------------------
                                //Ingresar Registro en Pre_Movimientos
                                //-------------------------------------------
                                $concepto 	= "CAUSADO DE CERTIFICACION DE PAGOS DIRECTOS SEGUN FACTURA: " . $factura->num_fac;
                                $tip_ope 	= 50;
                                $status_reg = "1";
                                $sol_tip 	= "IF";
                                $num_fac 	= $factura->num_fac;
                                $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,
                                                                    $unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$row2["sal_cau"],$concepto,$tip_ope,
                                                                    $sol_tip,$status_reg,$ano_sol_doc,$factura->nro_doc,$nota_entrega,
                                                                    $num_fac,0.00,$ano_fiscal,'1');
                                if (!$res)
                                    return $res;

                                break;
                        }//Fin del switch

                        //------------------------------------------------------------------
                        // Si es un deposito en garantia no se debe crear asiento contable
                        //------------------------------------------------------------------
                        if ($factura->deposito_garantia == 'N'){
                            $y = $y+1;

                            //-------------------------------------------------
                            //Crear el Asiento Contable de la Nota de Credito
                            //-------------------------------------------------
                            $acciones = 'DB';
                            $nc	 = '1';
                            $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$y,$cta_x_pagar,$valor,$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                            if (!$res)
                                return $res;

                            $y = $y+1;
                            $acciones = 'CR';
                            $nc	 = '1';
                            $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$y,$cuenta_contable,$valor,$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                            if (!$res)
                                return $res;
                        }
                    }else{//if ($valor!=0)
                        if ($factura->tipo_doc == 4){
                            //---------------------------------------------------------------------
                            //Existe Notas de Credito pero la partida no se ve afecta por la misma
                            //---------------------------------------------------------------------
                            //Ingresar Registro en Pre_Movimientos
                            //-------------------------------------------
                            //-------------------------------------------------------------------
                            //    Realizar el Movimiento presupestario del Causado
                            //-------------------------------------------------------------------
                            $restar = '+';
                            $res = $this->Causar_Partida_Pre_Maestro_Ley($msj,$row2["sal_cau"],$cod_com,$restar,$ano_fiscal);

                            if (!$res)
                                return $res;

                            $concepto 	= "CAUSADO DE CERTIFICACION DE PAGOS DIRECTOS SEGUN FACTURA: " . $factura->num_fac;
                            $tip_ope 	= 50;
                            $status_reg = "1";
                            $sol_tip 	= "IF";

                            //------------------------------------------------
                                // Insertar el momento Presupuestario del causado
                                //------------------------------------------------
                            $num_fac = $factura->num_fac;
                                $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,
                                                                $unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$row2["sal_cau"],$concepto,$tip_ope,
                                                                $sol_tip,$status_reg,$ano_sol_doc,$factura->nro_doc,$nota_entrega,
                                                                $num_fac,0.00,$ano_fiscal,'1');
                                if (!$res)
                                return $res;
                        }
                    }
                }else{//fin del  if ($ncr_sn=='S')
                    if ($factura->tipo_doc == 4){
                        //------------------------------------------
                            //No Existe Notas de Creditos
                        //------------------------------------------
                        //Ingresar Registro en Pre_Movimientos
                        //-------------------------------------------
                        //-------------------------------------------------------------------
                        //    Realizar el Movimiento presupestario del Causado
                        //-------------------------------------------------------------------
                        $restar = '+';
                        $res = $this->Causar_Partida_Pre_Maestro_Ley($msj,$row2["sal_cau"],$cod_com,$restar,$ano_fiscal);

                        if (!$res)
                            return $res;

                        $concepto 	 = "CAUSADO DE CERTIFICACION DE PAGOS DIRECTOS SEGUN FACTURA: " . $factura->num_fac;
                        $tip_ope	 = 50;
                        $status_reg = "1";
                        $sol_tip	 = "IF";

                        //----------------------------------------------
                            //Insertar el momento Presupuestario del causado
                            //------------------------------------------------
                            $num_fac = $factura->num_fac;
                            $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,
                                                            $unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$row2["sal_cau"],$concepto,$tip_ope,$sol_tip,
                                                            $status_reg,$ano_sol_doc,$factura->nro_doc,$nota_entrega,$num_fac,0.00,
                                                            $ano_fiscal,'1');
                            if (!$res)
                            return $res;
                    }
                }
            }//fin else que no es la partida de iva

            //------------------------------------------------------------------------------
            // Si existe Nota de credito o anticipo debo actualizar la estructura de gasto
            //------------------------------------------------------------------------------
            if ($por_anticipo != '0.00' || ($ncr_sn == 'S')){
                $res = $this->Actualizar_Gasto_Factura($factura,$msj,$row2["sal_cau"],$cod_com);

                if (!$res)
                    return $res;
            }

            //----------------------------------------------------------------------------------------
            //                        Crear el asiento contable
            //---------------------------------------------------------------------------------------------------------------
            // Cuando se cancela con dinero Externo solo se crea el asiento de IVA y por defecto cuenta contable db contra
            // cuenta por cobrar al proveedor por los montos de la amortizacion
            // Cuando se cancela con dinero Interno se debe crear el asiento del IVA y del Gasto si viene por pago directo
            // Cuando se cancela con dinero Interno se debe crear el asiento del IVA si viene Orden de Compra o Contrato
            //---------------------------------------------------------------------------------------------------------------
            //---------------------------------------------------------------------
            // Buscar las cuentas contables asociadas para crear el asiento contable
            //----------------------------------------------------------------------
            //----------------------------------------------------
            // Buscar las cuentas por pagar y la cuenta de activo
            //----------------------------------------------------
            $cta_x_pagar 	 = '';
            $cuenta_contable = '';

            if ($row2["gasto"]=='Si'){
                $cuenta = "cta_gasto";
                $desc_x_pagar = "cta_x_pagar";
            }else{
                $cuenta = "cta_activo";
                $desc_x_pagar = "cta_x_pagar_activo";
            }

            //------------------------------------------------------------------
            //Si es un deposito en garantia no se debe crear asiento contable
            //------------------------------------------------------------------
            if ($factura->deposito_garantia == 'N'){
                $res = $this->Buscar_Cuentas_Contables_x_Partidas($msj,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$cuenta_contable,$cta_x_pagar,$cuenta,$desc_x_pagar);

                if (!$res)
                return $res;
            }

            if ($factura->fondo =='I'){
                if ($result_iva[0]["cod_pari"] == $row2["cod_par"] && $result_iva[0]["cod_geni"] == $row2["cod_gen"]
                && $result_iva[0]["cod_espi"] == $row2["cod_esp"] && $result_iva[0]["cod_subi"] == $row2["cod_sub"]){
                    //------------------------------------------------------------------------------------------
                    //Si es un deposito en garantia no se debe crear asiento contable
                    //------------------------------------------------------------------------------------------
                    if ($factura->deposito_garantia == 'N'){ //Juanjo duda
                        $x = $x + 1;
                        $acciones = 'DB';
                        $nc = '0';
                        $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$x,$cuenta_contable,$row2["mto_tra"],$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                        if (!$res)
                            return $res;

                        $x = $x + 1;
                        $acciones = 'CR';
                        $nc = '0';
                        $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$x,$cta_x_pagar,$row2["mto_tra"],$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                        if (!$res)
                            return $res;
                    }
                }else{
                    if ($factura->tipo_doc == 4 ){
                        //------------------------------------------------------------------------------------------
                        //Si es un deposito en garantia no se debe crear asiento contable
                        //------------------------------------------------------------------------------------------
                        if ($factura->deposito_garantia=='N'){
                            $x = $x + 1;
                            $acciones = 'DB';
                            $nc = '0';
                            $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$x,$cuenta_contable,$row2["mto_tra"],$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                            if (!$res)
                                return $res;

                            $x = $x + 1;
                            $acciones = 'CR';
                            $nc = '0';

                            if ($row2["mto_nc"] != 0)
                                $monto_Asiento = $row2["sal_cau"] + $row2["mto_nc"];
                            else
                                $monto_Asiento = $row2["sal_cau"];

                            $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$x,$cta_x_pagar,$monto_Asiento,$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                            if (!$res)
                            return $res;
                        }
                    }
                } //Fin de el else si es la partida de IVA
            }else{//Fin de si se cancela con dinero INTERNO
                if ($result_iva[0]["cod_pari"] == $row2["cod_par"] && $result_iva[0]["cod_geni"] == $row2["cod_gen"]
                    && $result_iva[0]["cod_espi"] == $row2["cod_esp"] && $result_iva[0]["cod_subi"] == $row2["cod_sub"]){
                    //--------------------------------------------------------------------
                    // Si es un deposito en garantia no se debe crear asiento contable
                    //--------------------------------------------------------------------
                    if ($factura->deposito_garantia=='N'){
                        $x = $x + 1;
                        $acciones = 'DB';
                        $nc  = '0';
                        $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$x,$cuenta_contable,
                                                        $row2["mto_tra"],$acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                        if (!$res)
                            return $res;

                        $x = $x + 1;
                        $acciones = 'CR';
                        $nc  = '0';
                        $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$x,$cta_x_pagar,
                                                        $row2["mto_tra"],$acciones,$nc,$ano_sol_doc,$factura->ano_pro);
                        if (!$res)
                            return $res;
                    }
                }
            }
        }//fin del Foreach


        //--------------------------------------------------------------------
        // Si es un deposito en garantia no se debe crear asiento contable
        //------------------------------------------------------------------------
        if ($factura->deposito_garantia == 'N'){
            $cta_x_cobrar = '';
            //----------------------------------------------------------------------------------
            //          Si existe Anticipo debo crear el Asiento Contable de la Amortización
            //----------------------------------------------------------------------------------
            if ($por_anticipo!='0.00'){
                if ($factura->tipo_doc == 4 ){
                    if ($factura->fondo == 'I'){
                        $x = $x + 1;
                        $acciones = 'CR';
                        $nc  = '0';
                        $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$x,$cta_x_cobrar,$factura->mto_amortizacion,
                                                        $acciones,$nc,$ano_sol_doc,$factura->ano_pro);

                        if (!$res)
                            return $res;
                    }else{//fin del fondo=I
                        //-----------------------------------------------------------------------------------------------------------------------
                        // Si existe Anticipo debo crear el Asiento Contable de la Amortización afectando la cuenta de Pasivo del Ente Externo
                        //-----------------------------------------------------------------------------------------------------------------------
                        if ($por_anticipo != '0.00'){
                            $x = $x + 1;
                            $acciones = 'DB';
                            $nc  = '0';
                            $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$x,$factura->cuenta_contable,
                                                            $factura->mto_amortizacion,$acciones,$nc,$ano_sol_doc,$factura->ano_pro);
                            if (!$res)
                                return $res;

                            $x = $x + 1;
                            $acciones = 'CR';
                            $nc  = '0';
                            $res = $this->Crear_Comprobante($factura,$msj,$nro_doc,$x,$cta_x_cobrar,$factura->mto_amortizacion,$acciones,$nc,$ano_sol_doc,$factura->ano_pro);
                            if (!$res)
                                return $res;
                        }
                    }
                }
            }

            //--------------------------------------------------------------------------------------
            //               Reorganizar los renglones del Comprobante Contable
            //--------------------------------------------------------------------------------------
            $result_nro_ren = CxpDetComproFacturas::where('ano_pro',$factura->ano_pro)
                                                  ->where('num_fac',$factura->num_fac)
                                                  ->where('nc','0')
                                                  ->where('nro_sol_orden',$nro_doc)
                                                  ->selectRaw("ano_pro, num_fac, cod_cta, tipo, SUM(monto) AS monto, ccosto,
                                                               nro_sol_orden, nc,ano_sol_doc,rif_prov")
                                                  ->groupBy('ano_pro', 'num_fac', 'cod_cta', 'tipo', 'ccosto', 'nro_sol_orden',
                                                  'nc', 'ano_sol_doc', 'rif_prov')
                                                  ->OrderBy('tipo','desc')
                                                  ->get();



            if (!empty( $result_nro_ren[0]->num_fac)){
                $correlativo = 1;
                $nc  = '0';
                $res = $this->Eliminar_comprobante_contable($factura,$msj,$nc);

                if (!$res)
                    return $res;

                $n = 0;

                for ($i = 0; $i < count($result_nro_ren); $i ++){
                    $n = $n + 1;
                    $res = CxpDetComproFacturas::create(['ano_pro'         => $result_nro_ren[$i]->ano_pro,
                                                         'num_fac'          => $result_nro_ren[$i]->num_fac,
                                                         'nro_sol_orden'    => $result_nro_ren[$i]->nro_sol_orden,
                                                         'nro_ren'          => $n,
                                                         'cod_cta'          => $result_nro_ren[$i]->cod_cta,
                                                         'tipo'             => $result_nro_ren[$i]->tipo,
                                                         'monto'            => $result_nro_ren[$i]->monto,
                                                         'ccosto'           => $result_nro_ren[$i]->ccosto,
                                                         'nc'               => '0',
                                                         'ano_sol_doc'      => $result_nro_ren[$i]->ano_sol_doc,
                                                         'rif_prov'         => $result_nro_ren[$i]->rif_prov
                                                        ]);

                    if (!$res){
                        $msj = "Error al Actualizando  Comprobante Contable .\\nComuniquese con su Administrador de sistema.";
                        return $res;
                    }

                    $correlativo = $correlativo + 1;
                }
            }else{
                if ($factura->tipo_doc == 4){
                        if ($factura->fondo == 'E'){
                            if ($por_anticipo != '0.00'){
                                $res = $db->execQuery($conn, 'c');
                                $msj = "Error al generar el Comprobante Contable.\\n Comuniquese con su Administrador de sistema.";
                                return $res;
                            }else{
                                if ($factura->porcentaje_iva!='0.00'){
                                    $res = $db->execQuery($conn, 'c');
                                    $msj = "Error al generar el Comprobante Contable.\\n Comuniquese con su Administrador de sistema.";
                                    return $res;
                                }
                            }
                        }else{
                            $res = false;
                            $msj = "Error al generar el Comprobante Contable.\\n Comuniquese con su Administrador de sistema.";
                            return $res;
                        }
                }else{
                    //Viene por orden de compra o Contrato de obra y no posee iva por tanto no se debe crear asiento alguno
                    if ($factura->porcentaje_iva!='0.00'){
                        $res = false;
                        $msj = "Error al generar el Comprobante Contable de IVA. Comuniquese con su Administrador de sistema.";
                        return $res;
                    }
                }
            }

            //--------------------------------------------------------------------------------------
            //               Reorganizar los renglones de la Nota de Credito
            //--------------------------------------------------------------------------------------
            if ($ncr_sn=='S'){

                $result_nro_ren = CxpDetComproFacturas::where('ano_pro',$factura->ano_pro)
                                                      ->where('num_fac',$factura->num_fac)
                                                      ->where('nc','1')
                                                      ->where('nro_sol_orden',$factura->nro_doc)
                                                      ->where('ano_sol_doc',$factura->ano_sol)
                                                      ->selectRaw("ano_pro, num_fac, cod_cta, tipo, SUM(monto) AS monto, ccosto,
                                                                   nro_sol_orden, nc,ano_sol_doc,rif_prov")
                                                      ->groupBy('ano_pro', 'num_fac', 'cod_cta', 'tipo', 'ccosto', 'nro_sol_orden',
                                                    'nc', 'ano_sol_doc', 'rif_prov')
                                                    ->OrderBy('tipo','desc')
                                                    ->get();

                if (!empty($result_nro_ren[0]->num_fac)){
                    $correlativo = 1;
                    $nc  = '1';
                    $res = $this->Eliminar_comprobante_contable($factura,$msj,$nc);

                    if (!$res)
                        return $res;

                    $n = 0;

                    for ($i = 0 ; $i < count($result_nro_ren) ; $i ++){
                        $n = $n + 1;

                        $res = CxpDetComproFacturas::create(['ano_pro'          => $result_nro_ren[$i]->ano_pro,
                                                             'num_fac'          => $result_nro_ren[$i]->num_fac,
                                                             'nro_sol_orden'    => $result_nro_ren[$i]->nro_sol_orden,
                                                             'nro_ren'          => $n,
                                                             'cod_cta'          => $result_nro_ren[$i]->cod_cta,
                                                             'tipo'             => $result_nro_ren[$i]->tipo,
                                                             'monto'            => $result_nro_ren[$i]->monto,
                                                             'ccosto'           => $result_nro_ren[$i]->ccosto,
                                                             'nc'               => '1',
                                                             'ano_sol_doc'      => $result_nro_ren[$i]->ano_sol_doc,
                                                             'rif_prov'         => $result_nro_ren[$i]->rif_prov
                                                            ]);

                        if (!$res){
                            $msj = "Error al Actualizando  Comprobante Contable de la Nota de Credito.\\nComuniquese con su Administrador de sistema.";
                            return $res;
                        }

                        $correlativo = $correlativo + 1;
                    }
                }else{
                    $res = false;
                    $msj = "Error al Consultar el Comprobante Contable de la Nota de Credito.\\nComuniquese con su Administrador de sistema.";
                    return $res;
                }
            }
        }

        return $res;
    }
	//------------------------------------------------------------------------------------------------------
    //Funcion que Retorna el % de IVa desde la Orden de Compra para validar si existe cambio en la alicuota
    //              cuando el Proveedor trae las facturas asociadas a la orden de compra
    //-------------------------------------------------------------------------------------------------------
    function Retorna_Porcenta_Iva($factura,&$msj,$ano_sol_orden,&$por_iva){
        $res = true;

        $result_por_va = CxPCabeceraFactura::where('nro_doc',         $factura->nro_doc)
                                           ->where('ano_doc',         $ano_sol_orden)
                                           ->select('porcentaje_iva')
                                           ->first();


        if (!empty($result_por_va->porcentaje_iva))
            $por_iva  = $result_por_va->porcentaje_iva;
        else{
            $res = false;
            $msj = "Error al Consultar el % de Iva en la Orden de Compra.\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }
	//----------------------------------------------------------------------------------------------------------------------------------------------
    //                        Funcion para ajustar el compromiso ppara cualquier concepto
    //----------------------------------------------------------------------------------------------------------------------------------------------
    function Ajustar_Compromiso($factura,&$msj,$ano_sol_orden,$concepto,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,
                                $cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$monto_Comprometer,$accion,$accion2,$decripcion,$result_iva,$ano_fiscal,
                                $des_proceso){
        //Realizar el ajuste en Pre Maestro Ley
        $res = $this->Ajustar_Compromiso_Pre_Maestro_Ley($msj,$monto_Comprometer,$cod_com,$accion,$accion2,$ano_fiscal,$des_proceso);

        if (!$res)
            return $res;

        $cod_com_viejo = '';

        //Si es la partida de IVA
        if ($result_iva[0]["cod_pari"] == $cod_par && $result_iva[0]["cod_geni"] == $cod_gen &&
            $result_iva[0]["cod_espi"] == $cod_esp && $result_iva[0]["cod_subi"] == $cod_sub){
            $res = $this->Buscar_Partida_Iva_Inicial_del_Documento($msj,$ano_sol_orden,$cod_com_viejo,$cod_com,$ano_fiscal);

            if (!$res)
                return $res;
        }else{
            $cod_com_viejo = '';
            $centro_actual = $this->Concatenar_Centro_Costo($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad);
            $partida	   = $this->Concatenar_Partida($cod_par,$cod_gen,$cod_esp,$cod_sub);
            $res		   = $this->Buscar_centro_costo_viejo($msj,$centro_actual,$cod_com_viejo,$ano_sol_orden);

            if (!$res)
                return $res;

            $cod_com_viejo = $cod_com_viejo . "." . $partida;
        }

        $monto_compromiso = 0;
        $nro_enl_anular	  = '';
        $tip_ope		  = '';
        $sol_tipo		  = '';
        $operaciones	  = "('10','90','94','95')";
        $res = $this->Buscar_ultimo_Registro($msj,$ano_sol_orden,$cod_com_viejo,$cod_com,$monto_compromiso,$nro_enl_anular,$tip_ope,$sol_tipo,$operaciones,$mto_transaccion);

        if (!$res)
            return $res;

        //------------------------------------------------------------------
        //             Realizar el Ajuste del compromiso
        //------------------------------------------------------------------
        $status_reg = "1";
        $tip_ope	= 90;
        $sol_tipo	= 'AJ';

        if ($accion == '-'){
            $monto_a_comprometer	   = $monto_compromiso - $monto_Comprometer;
            $monto_ajustado_compromiso = $monto_Comprometer * (-1);
        }else{
            $monto_a_comprometer	   = $monto_compromiso + $monto_Comprometer;
            $monto_ajustado_compromiso = $monto_Comprometer;
        }

        //----------------------------------------------
        // Insertar el momento Presupuestario
        //----------------------------------------------
        $num_fac	  = $factura->num_fac;
        $nota_entrega = '';
        $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,
                                            $unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$monto_a_comprometer,$decripcion,$tip_ope,
                                            $sol_tipo,$status_reg,$ano_sol_orden,$factura->nro_doc,$nota_entrega,
                                            $num_fac,$monto_ajustado_compromiso,$ano_fiscal,'1');

        if (!$res)
            return $res;

        //----------------------------------------------------
        // Leer numero de registro que se termina de ingresar
        //----------------------------------------------------
        $nro_enl = '';
        $estado  = '1';
        $res = $this->Busca_Registro_Actual($factura,$msj,$ano_sol_orden,$cod_com,$nro_enl_anular,$nro_enl,$estado);

        if (!$res)
            return $res;

        //---------------------------------------------------------------------------
        //    Actualizar status en Pre Movimiento del movimiento anterior en 2
        //---------------------------------------------------------------------------
        $res = $this->Actualizar_status_Pre_Movimiento($factura,$msj,$nro_enl,$nro_enl_anular);

        if (!$res)
            return $res;

        return $res;
    }

    //------------------------------------------------------------------------------------------------------
    //Funcion que busca el ultimo registro vivo para un compromiso dependiendo la estructura presupuestaria
    //------------------------------------------------------------------------------------------------------
    function Buscar_ultimo_Registro_Causado($factura,&$msj,$ano_sol_orden,$cod_com_buscar,$cod_com,
            &$monto_compromiso,&$nro_enl_anular,&$tip_ope,&$sol_tipo,$operaciones,$nota_entr){
        $res = true;

        if (empty($nota_entr))
            $nota_entr =  'is null';
        else
            $nota_entr = "='" . $nota_entr . "'";

        $result_monto = PreMovimiento::where('ano_doc',$ano_sol_orden )
                                        ->whereIn('tip_ope',[$operaciones] )
                                        ->orWhere('num_doc', '=', $factura->nro_doc)
                                        ->where('sta_reg','1' )
                                        ->where('nota_entrega',$nota_entr )
                                        ->whereIn('cod_com',[$cod_com_buscar,$cod_com] )
                                        ->where('num_fac', $factura->num_fac)
                                        ->get();

        if (!empty($result_monto[0]->tip_ope)){
            $monto_compromiso = $result_monto[0]->mto_tra;
            $nro_enl_anular   = $result_monto[0]->num_reg;
            $tip_ope		  = $result_monto[0]->tip_ope;
            $sol_tipo		  = $result_monto[0]->sol_tip;
        }else{
            $res =false;
            $msj = "Error al buscar Registro anterior del CausadoJC. Comuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }
	//------------------------------------------------------------------------------------------------------
    //Funcion que busca el ultimo registro vivo para un compromiso dependiendo la estructura presupuestaria
    //------------------------------------------------------------------------------------------------------
    function Buscar_ultimo_Registro(&$msj,$ano_sol_orden,$cod_com_buscar,$cod_com,
                                    &$monto_compromiso,&$nro_enl_anular,&$tip_ope,&$sol_tipo,$operaciones,&$mto_transaccion){

        $res = true;

        $result_monto = PreMovimiento::where('ano_doc',$ano_sol_orden )
                                     ->whereIn('tip_ope',[$operaciones] )
                                     ->orWhere('num_doc', '=', $factura->nro_doc)
                                     ->where('sta_reg','1' )
                                     ->whereIn('cod_com',[$cod_com_buscar,$cod_com] )
                                     ->orderBy('num_reg','desc')
                                     ->get();


        if (!empty($result_monto[0]->tip_ope)){
            $monto_compromiso = $result_monto[0]->mto_tra;
            $nro_enl_anular	  = $result_monto[0]->num_reg;
            $tip_ope		  = $result_monto[0]->tip_ope;
            $sol_tipo		  = $result_monto[0]->sol_tip;
            $mto_transaccion  = $result_monto[0]->mto_transaccion;
        }else{
            $res = false;
            $msj = "Error al buscar Registro anterior del Compromiso. Comuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
	}

	//------------------------------------------------------------------------------------------------------
    //Funcion que busca el ultimo registro vivo para un compromiso dependiendo la estructura presupuestaria
    //------------------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------------
    //Busca el Registro que genera el ultimo movimiento presupuestario de Ajuste de Compromiso
    //---------------------------------------------------------------------------------------------------
    function Busca_Registro_Actual($factura,&$msj,$ano_sol_orden,$cod_com,$nro_enl_anular,&$nro_enl,$status){
        $res = true;

        $result2 = PreMovimiento::where('ano_doc',$ano_sol_orden )
                                ->where('num_doc', $factura->nro_doc)
                                ->where('sta_reg',$status  )
                                ->where('cod_com',$cod_com )
                                ->orderBy('num_reg','desc')
                                ->get();

        if (!empty($result2[0]->tip_ope))
            $nro_enl = $result2[0]->num_reg;
        else{
            $res = false;
            $msj = "Error consultando numero de registro en pre_movimiento. Comuniquese con su Administrado Sistema.";
            return $res;
        }

        return $res;
    }
    //-------------------------------------------------------------------------------------------------------
    //          Funcion que ajusta los montos del compromiso y el disponible en pre-maestro ley
    //-------------------------------------------------------------------------------------------------------
    function Actualizar_status_Pre_Movimiento($factura,&$msj,$nro_enl,$nro_enl_anular){
        $ano_proceso   = $factura->ano_fiscal; //Dualidad
        $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );
        $res = true;
        $usuario = Usuario::where('cedula', auth()->user()->cedula)->select('usuario')->first();

        $res = PreMovimiento::where('num_reg',$nro_enl_anular )
                            ->update(['sta_reg'  => '2',
                                        'usua_anu', $usuario->usuario,
                                        'fec_anu'  => $fecha_proceso,
                                        'nro_enl'  => $nro_enl,
                                    ]);

        if (!$res){
            $msj = "Error al Actualizar el status en [pre_movimientos] del registro: " . $nro_enl_anular . " \\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }
    //----------------------------------------------------------------------------------------------------------------------------------------------
    //                        Funcion para ajustar el causado para cualquier concepto
    //----------------------------------------------------------------------------------------------------------------------------------------------
    function Ajustar_Causado($factura,&$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,
                                $cod_sub,$ano_sol_orden,$cod_com,$valor,$decripcion,$nota_entrega,$tipo,$ano_sol_doc,$ano_fiscal,$restar){

                                    $res = $this->Causar_Partida_Pre_Maestro_Ley($msj,$valor,$cod_com,$restar,$ano_fiscal);

        if (!$res)
            return $res;

        $cod_com_viejo = '';

        if ($result_iva->cod_pari == $cod_par && $result_iva->cod_geni == $cod_gen
            && $result_iva->cod_espi == $cod_esp && $result_iva->cod_subi == $cod_sub){
            $res = $this->Buscar_Partida_Iva_Inicial_del_Documento($$msj,$ano_sol_orden,$cod_com_viejo,$cod_com,$ano_fiscal);

            if (!$res)
                return $res;
        }else{
            $cod_com_viejo = '';
            $centro_actual = $this->Concatenar_Centro_Costo($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad);
            $partida	   = $this->Concatenar_Partida($cod_par,$cod_gen,$cod_esp,$cod_sub);
            $res = $this->Buscar_centro_costo_viejo($msj,$centro_actual,$cod_com_viejo,$ano_sol_orden);

            if (!$res)
                return $res;

            $cod_com_viejo = $cod_com_viejo . "." . $partida;
        }

        $monto_causado	 = 0;
        $nro_enl_anular	 = '';
        $tip_ope		 = '';
        $sol_tipo		 = '';
        $operaciones	 = "('50','91')";
        $mto_transaccion = 0.00;

        if ($tipo=='1'){//Viene por Certificacion
            $res = $this->Buscar_ultimo_Registro($msj,$ano_sol_orden,$cod_com_viejo,$cod_com,
                                                 $monto_causado,$nro_enl_anular,$tip_ope,$sol_tipo,$operaciones,$mto_transaccion);
            if (!$res)
                return $res;
        }else{ //Viene por orden de compra
            $res = $this->Buscar_Notas_Entregas_Causadas($factura,$msj,$ano_sol_orden,$nota_entrega,$monto_causado,$cod_com,$cod_com_viejo);

            if (!$res)
                return $res;
        }

        //------------------------------------------------------------------
        //             Realizar el Ajuste del Causado
        //------------------------------------------------------------------
        $status_reg = "1";
        $tip_ope	= 91;
        $sol_tipo	= 'AJ';
        $monto_a_causar = $monto_causado - $valor;
        $monto_ajustado_causado = $valor * (-1);

        //----------------------------------------------
        //Insertar el momento Presupuestario
        //------------------------------------------------
        $num_fac = $factura->num_fac;
        $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,
                                            $monto_a_causar,$decripcion,$tip_ope,$sol_tipo,$status_reg,$ano_sol_orden,$this->getCampo("nro_doc")->valor,$nota_entrega,
                                            $num_fac,$monto_ajustado_causado,$ano_fiscal,'1');
        if (!$res)
            return $res;

        //----------------------------------------------------
        //Leer numero de registro que se termina de ingresar
        //----------------------------------------------------
        $nro_enl = '';
        $estado  = '1';
        $res = $this->Busca_Registro_Actual($factura,$msj,$ano_sol_orden,$cod_com,$nro_enl_anular,$nro_enl,$estado);

        if (!$res)
            return $res;

        if ($tipo == '1'){//Viene por Certificacion
            $res = $this->Actualizar_status_Pre_Movimiento($factura,$msj,$nro_enl,$nro_enl_anular);

            if (!$res)
                return $res;
        }else{
            $result_notas = PreMovimiento::where('ano_doc',$ano_sol_orden )
                                         ->whereIn('tip_ope',['50','91'] )
                                         ->where('nota_entrega', $nota_entrega)
                                         ->where('num_doc',$factura->nro_doc  )
                                         ->where('sta_reg','1' )
                                         ->whereIn('sol_tip',['NE','AJ','IF','CO'] )
                                         ->whereIn('tip_ope',[$cod_com,$cod_com_viejo] )
                                         ->where('num_reg','!=', $nro_enl)
                                         ->get();

            if (!empty($result_notas[0]->tip_ope)){
                for ( $i = 0 ; $i < count($result_notas) ; $i ++){
                    //---------------------------------------------------------------------------
                    //       Actualizar status en Pre Movimiento del movimiento anterior en 2
                    //Esto se desarrollo de esta manera porque existe varios registros causados
                    //    para la misma nota de entrega y la misma partida presupuestaria
                    //---------------------------------------------------------------------------
                    $res = $this->Actualizar_status_Pre_Movimiento($factura,$msj,$nro_enl,$result_notas[$i]->num_reg);

                    if (!$res)
                        return $res;
                }
            }else{
                $res = false;
                $msj = "Error Anulando el Registro del Causado de la Nota de Entrega:[" . $nota_entrega . "]\\n Comuniquese con su Administrado Sistema.";
                return $res;
            }
        }

        return	$res;
    }

    //--------------------------------------------------------------------------------------------------------------------------
    //                Function que suma el monto causado de la nota de entrega
    //--------------------------------------------------------------------------------------------------------------------------
    function Buscar_Notas_Entregas_Causadas($factura,&$msj,$ano_sol_orden,$nota_entr,&$monto_causado_ne,$cod_com,$cod_com_viejo2){
        $res = true;

        $result_monto_ne2 = PreMovimiento::where('ano_doc',$ano_sol_orden )
                                         ->whereIn('tip_ope',['50','91'] )
                                         ->where('nota_entrega', $nota_entr)
                                         ->where('num_doc',$factura->nro_doc)
                                         ->where('sta_reg','1' )
                                         ->whereIn('sol_tip',['NE','AJ','IF','CO'] )
                                         ->whereIn('tip_ope',[$cod_com,$cod_com_viejo2] )
                                         ->get();

        if (!empty($result_monto_ne2[0]["sum"]))
            $monto_causado_ne = $result_monto_ne2[0]->sum;
        else{
            $res = false;
            $msj = "Error consultando Registro de Causado de la Nota de Entrega:" . $nota_entr . "\\n Comuniquese con su Administrado Sistema.";
            return $res;
        }

        return $res;
    }
    //----------------------------------------------------------------------------------------------------
    //Funcion que actualiza el Saldo a Causar en la Tablas del Gasto de la Factura
    //----------------------------------------------------------------------------------------------------
    function Actualizar_Gasto_Factura($factura,&$msj,$monto_Causar,$cod_com){
        $res = true;

        $res = CxPDetGastosFactura::where('ano_pro',$factura->ano_pro)
                                  ->where('rif_prov',$factura->rif_prov)
                                  ->where('num_fac',$factura->num_fac)
                                  ->where('cod_com',$cod_com)
                                  ->where('nro_doc',$factura->nro_doc)
                                  ->where('ano_sol',$factura->ano_sol)
                                  ->update(['sal_cau'  =>  $monto_Causar]);

        if (!$res){
            $msj = "Error Modificando el Saldo a Causar.Comuniquese con su Administrador de sistema.";
            return $res;
        }

        return $res;
    }

    //----------------------------------------------------------------------------------------------------
    //Funcion que elimina el comprobante contable de las tablas de factura
    //----------------------------------------------------------------------------------------------------
    function Eliminar_comprobante_contable($factura,&$msj,$nc){
        $res = true;
        $res = CxpDetComproFacturas::where('ano_pro',$factura->ano_pro)
                                    ->where('num_fac',$factura->num_fac)
                                    ->where('nc',$nc)
                                    ->where('rif_prov',$factura->rif_prov)
                                    ->where('nro_sol_orden',$factura->nro_doc)
                                    ->where('ano_sol_doc',$factura->ano_sol)
                                    ->delete();

        if (!$res){
            $msj = "Error Eliminando Comprobante Contables. Comuniquese con su Administrador de sistema.";
            return $res;
        }

        return $res;
    }
    //--------------------------------------------------------------------------
    //Funcion que realiza las acciones correspondiente a la accion de Reversar
    //--------------------------------------------------------------------------
    function Datos_reversar_PROVISION($factura,&$msj){

        $result_iva    = '';
        $x             = 1;
        $ncr_sn		   = $factura->ncr_sn;
        $nro_ncr	   = $factura->nro_ncr;
        $por_anticipo  = $factura->por_anticipo;
        $nro_doc	   = $factura->nro_doc;
        $res		   = true;
        $grupo		   ='';
        $icont         = 0;
        $ano_proceso   = $factura->ano_fiscal; //Dualidad
        $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );

        //Buscar las Siglas del Documento
        $res = $this->Retornar_siglas_Documentos($msj,$grupo,$factura->tipo_doc);

        if (!$res)
            return $res;

        //Actualizar el status en la cabecera de la factura
        $sta_fac = '2';
        $usu	 = 'usua_anu';
        $fecha	 = 'fec_anu';
        $res	 = $this->Actualizar_statu_Factura($factura,$msj,$sta_fac,$usu,$fecha);

        if (!$res)
            return $res;

        //Buscar año de generación de Documento
        $ano_sol_doc = $factura->ano_sol;

        //Buscar la Partida de Iva
        $result_iva = '';
        $res = $this->Retornar_Partida_IVA($msj,$result_iva,$ano_proceso); //Dualidad

        if (!$res)
            return $res;



        $nc  = '0';
        $res = $this->Eliminar_comprobante_contable($factura,$msj,$nc);

        if (!$res)
            return $res;

        $facfactura = Factura::where('ano_pro', $factura->ano_pro)
                             ->where('rif_prov',$factura->rif_prov)
                             ->where('num_fac', $factura->num_fac)
                             ->first();

        $detgastosfactura =$facfactura->cxpdetgastosfactura;

        //return  $detgastosfactura;
        foreach($detgastosfactura as $index => $row2){
            $icont = $icont + 1 ;

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
            $cod_par    = $row2["cod_par"];
            $cod_gen    = $row2["cod_gen"];
            $cod_esp    = $row2["cod_esp"];
            $cod_sub    = $row2["cod_sub"];

            if ($ano_proceso != $ano_sol_doc){
                $res = $this->armar_centro_costo($msj,$tip_cod,$cod_pryacc,
                        $cod_obj,$gerencia,$unidad,$cod_com,$row2,$ano_proceso,$ano_sol_doc);

                if (!$res)
                    return $res;
            }else{
                $tip_cod	= $row2["tip_cod"];
                $cod_pryacc = $row2["cod_pryacc"];
                $cod_obj	= $row2["cod_obj"];
                $gerencia	= $row2["gerencia"];
                $unidad		= $row2["unidad"];
                $cod_com	= $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);
            }

            $sal_cau = $row2["sal_cau"];
            $saldo_estructura = $sal_cau;

            if ($result_iva[0]["cod_pari"] == $row2["cod_par"] && $result_iva[0]["cod_geni"] == $row2["cod_gen"]
                && $result_iva[0]["cod_espi"] == $row2["cod_esp"] && $result_iva[0]["cod_subi"] == $row2["cod_sub"]){

                //-----------------------------------------------------------------
                // Realizar el Movimiento  del Reverso presupestario del Causado
                //-----------------------------------------------------------------
                $decripcion = "REVERSO DEL CAUSADO DE LA PARTIDA DE IVA SEGUN FACTURA: " . $factura->num_fac;
                $tip_ope    = 60;
                $sol_tipo	= 'IF';
                $nota_entr  = '';
                $res = $this->Reverso_Causado($factura,$msj,$ano_sol_doc,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,
                        $cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$row2["sal_cau"],$decripcion,$result_iva,$ano_proceso,
                        $tip_ope,$sol_tipo,$nota_entr,'1','-');

                if (!$res)
                    return $res;
                $fact=$factura->num_fac;

                $result_pmov = PreMovimiento::where('num_fac',$factura->num_fac )
                                            ->where('num_doc',$nro_doc )
                                            ->where('tip_ope',95 )
                                            ->where('ano_doc',$ano_sol_doc )
                                            ->where('cod_com',$cod_com )
                                            ->select('mto_tra')
                                            ->first();

                if ($result_pmov){
                    $monto=$result_pmov->mto_tra;
                    $decripcion = "REVERSO DEL COMPROMISO DE LA PARTIDA DE IVA SEGUN FACTURA: " . $factura->num_fac;

                    $result = DB::select("SELECT *
                                            FROM movimientopresupuestario('$ano_proceso','$cod_com','AJ','96','$nro_doc',$monto,'','$decripcion',
                                                                        '1','$usu','$ano_sol_doc','','$fact','0.00','$fecha_proceso')");

                    if(!$result)
                    {
                        $msj .= "Error EN FUNCION movimientopresupuestario1";
                        return $result;
                    }
                }

            }else{//sino es la partida presupuestaria de iva

                if ($factura->tipo_doc == 4){

                    //----------------------------------------
                    //  Ingresar Registro en Pre_Movimientos
                    //----------------------------------------
                    $decripcion = "REVERSO DEL CAUSADO DE CERTIFICACION DE PAGOS DIRECTOS SEGUN FACTURA: " . $factura->num_fac;

                    //-----------------------------------------------------------------
                    //  Realizar el Movimiento  del Reverso presupestario del Causado
                    //-----------------------------------------------------------------
                    $tip_ope   = 60;
                    $sol_tipo  = 'IF';
                    $nota_entr = '';
                    $res = $this->Reverso_Causado($factura,$msj,$ano_sol_doc,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,
                            $unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$row2["sal_cau"],$decripcion,$result_iva,
                            $ano_proceso,$tip_ope,$sol_tipo,$nota_entr,'1','-'); //Dualidad

                    if (!$res)
                        return $res;
                    $fact=$factura->num_fac;
                    $result_pmov = PreMovimiento::where('num_fac',$factura->num_fac )
                                                ->where('num_doc',$nro_doc )
                                                ->where('tip_ope',95 )
                                                ->where('ano_doc',$ano_sol_doc )
                                                ->where('cod_com',$cod_com )
                                                ->select('mto_tra')
                                                ->first();

                    if ($result_pmov) {
                        $monto=$result_pmov->mto_tra;
                        $decripcion = "REVERSO DEL COMPROMISO DE LA PARTIDA SEGUN FACTURA: " . $factura->num_fac;
                        $result = DB::select("SELECT *
                                            FROM movimientopresupuestario('$ano_proceso','$cod_com','AJ','96','$nro_doc',$monto,'','$decripcion',
                                                                        '1','$usu','$ano_sol_doc','','$fact','0.00','$fecha_proceso')");

                        if(!$result)
                        {
                            $msj .= "Error EN FUNCION movimientopresupuestario2";
                            return $result;
                        }

                    }
                }
            }//fin de que no es la partida de iva

            //----------------------------------------------------------------
            //  Proceso para actualizar la estructura de gasto de la factura
            //----------------------------------------------------------------
            $res = $this->Actualizar_Gasto_Factura($factura,$msj,$row2["mto_tra"],$cod_com);

            if (!$res)
                return $res;
        }

        return $res;
    }


    //-----------------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    function Reverso_Causado($factura,&$msj,$ano_sol_orden,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,
                                $cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$monto_Causar,$decripcion,$result_iva,$ano_fiscal,$tip_ope,
                                $sol_tipo,$nota_entr,$tipo,$accion){

        $res = $this->Causar_Partida_Pre_Maestro_Ley($msj,$monto_Causar,$cod_com,$accion,$ano_fiscal);

        if (!$res)
            return $res;

        $cod_com_viejo = '';

        //Si es la partida de IVA
        if ($result_iva[0]["cod_pari"] == $cod_par && $result_iva[0]["cod_geni"] == $cod_gen
            && $result_iva[0]["cod_espi"] == $cod_esp && $result_iva[0]["cod_subi"] == $cod_sub){

            $res = $this->Buscar_Partida_Iva_Inicial_del_Documento($msj,$ano_sol_orden,$cod_com_viejo,$cod_com,$ano_fiscal);

            if (!$res)
                return $res;
        }else{
            $cod_com_viejo = '';
            $centro_actual = $this->Concatenar_Centro_Costo($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad);
            $partida	   = $this->Concatenar_Partida($cod_par,$cod_gen,$cod_esp,$cod_sub);
            $res		   = $this->Buscar_centro_costo_viejo($msj,$centro_actual,$cod_com_viejo,$ano_sol_orden);

            if (!$res)
                return $res;

            $cod_com_viejo = $cod_com_viejo . "." . $partida;
        }

        $monto_causado   = 0;
        $nro_enl_anular  = '';
        $operaciones	 = "('50','91')";
        $sol_tipoeracion = '';
        $tip_operacion	 = '';
        $mto_transaccion = 0.00;
        $res = $this->Buscar_ultimo_Registro_Causado($factura,$msj,$ano_sol_orden,$cod_com_viejo,$cod_com,
                                                        $monto_causado,$nro_enl_anular,$tip_operacion,$sol_tipoeracion,$operaciones,$nota_entr); //,
                                                        //$mto_transaccion);
        //			  Buscar_ultimo_Registro_Causado($db,$conn,&$datosDetalle,$tablasDetalle,&$msj,$ano_sol_orden,$cod_com_buscar,$cod_com,
    //			&$monto_compromiso,&$nro_enl_anular,&$tip_ope,&$sol_tipo,$operaciones,$nota_entr){

        if (!$res)
            return $res;

        //------------------------------------------------------------------
        //             Realizar el Reverso del Ajuste del causado
        //------------------------------------------------------------------
        $status_reg = "2";
        if ($tip_ope == 60){
            $nota_entrega = '';
            $monto_reverso_ajuste = 0;
        }else{
            $nota_entrega = $nota_entr;
            $monto_reverso_ajuste = $monto_Causar;
        }

        //----------------------------------------
        //   Insertar el momento Presupuestario
        //----------------------------------------
        $num_fac = $factura->num_fac;
        $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,
                                            $cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,
                                            $monto_causado,$decripcion,$tip_ope,$sol_tipo,$status_reg,
                                            $ano_sol_orden,$factura->nro_doc,$nota_entrega,
                                            $num_fac,$monto_reverso_ajuste,$ano_fiscal,'1');
        if (!$res)
            return $res;

        //----------------------------------------------------
        // Leer numero de registro que se termina de ingresar
        //----------------------------------------------------
        $nro_enl = '';
        $estado  = '2';
        $res = $this->Busca_Registro_Actual($factura,$msj,$ano_sol_orden,
                                            $cod_com,$nro_enl_anular,$ano_sol_orden,$nro_enl,$estado);
        if (!$res)
            return $res;

        //---------------------------------------------------------------------
        //  Actualizar status en Pre Movimiento del movimiento anterior en 2
        //---------------------------------------------------------------------
        $res = $this->Actualizar_status_Pre_Movimiento($factura,$msj,$nro_enl,$nro_enl_anular);

        if (!$res)
            return $res;

        if ($tipo != '1'){
            //-----------------------------------------------------------------------------------
            // Si viene por orden de compra o Contrato de obra se debe crear un nuevo causado  50
            //------------------------------------------------------------------------------------
            //Se debe crear un Registro 50
            $tip_ope  = 50;
            $sol_tipo = 'NE';
            $monto_reverso_ajuste = 0;
            $concepto_actual = 'Causado sobre Compromiso NE';
            $status_reg = "1";

            //---------------------------------------
            //   Insertar el momento Presupuestario
            //---------------------------------------
            $num_fac 	   = $factura->num_fac;
            $nota_entrega  = $nota_entr;
            $monto_causado = $monto_causado + $monto_Causar;
            $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,
                                                $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$monto_causado,
                                                $concepto_actual,$tip_ope,$sol_tipo,$status_reg,$ano_sol_orden,
                                                $factura->nro_doc,$nota_entrega,$num_fac,$monto_reverso_ajuste,
                                                $ano_fiscal,'1');
            if (!$res)
                return $res;
        }

        return $res;
    }
    //--------------------------------------------------------------------------
    //Funcion que realiza las acciones correspondiente a la accion de Reversar
    //--------------------------------------------------------------------------
    function Datos_reversar($factura,&$msj){

        $x = 1;
        $ncr_sn		   = $factura->ncr_sn;
        $nro_ncr	   = $factura->nro_ncr;
        $por_anticipo  = $factura->por_anticipo;
        $nro_doc	   = $factura->nro_doc;
        $res		   = true;
        $grupo		   ='';
        $icont         = 0;
        $ano_proceso   = $factura->ano_fiscal; //Dualidad
        $fecha_proceso = $this->FechaSistema($ano_proceso,'Ymd' );
        $concepto = '';
        //Buscar las Siglas del Documento
        $res = $this->Retornar_siglas_Documentos($msj,$grupo,$factura->tipo_doc);

        if (!$res)
            return $res;

        //Actualizar el status en la cabecera de la factura
        $sta_fac = '2';
        $usu	 = 'usua_anu';
        $fecha	 = 'fec_anu';
        $res	 = $this->Actualizar_statu_Factura($factura,$msj,$sta_fac,$usu,$fecha);

        if (!$res)
            return $res;

        //Buscar año de generación de Documento
        $ano_sol_doc = $factura->ano_sol;

        //Buscar la Partida de Iva
        $result_iva = '';
        $res = $this->Retornar_Partida_IVA($msj,$result_iva,$ano_proceso); //Dualidad

        if (!$res)
            return $res;

        $por_iva = 0;

        //Query para retornar % de IVA
        $res = $this->Retorna_Porcenta_Iva($factura,$msj,$ano_sol_doc,$por_iva);

        if (!$res)
            return $res;

        //---------------------------------------------------------------------------------
        // Si Existe Nota de Credito se debe buscar las partidas presupuestrias afectadas
        //---------------------------------------------------------------------------------
        if ($ncr_sn == 'S'){
            $result_nc = '';
            $res = $this->Partidas_Nota_credito($factura,$msj,$result_nc);

            if (!$res)
                return $res;
        }

        $nc  = '0';
        $res = $this->Eliminar_comprobante_contable($factura,$msj,$nc);

        if (!$res)
            return $res;

        $nc  = '1';
        $res = $this->Eliminar_comprobante_contable($factura,$msj,$nc);

        if (!$res)
            return $res;


        //----------------------------------------------
        //----------------------------------------------


        $facfactura = Factura::where('ano_pro',$factura->ano_pro)
                             ->where('rif_prov',$factura->rif_prov)
                             ->where('num_fac',$factura->num_fac)
                             ->first();

        $detgastosfactura =$facfactura->cxpdetgastosfactura;

            //return  $detgastosfactura;
        foreach($detgastosfactura as $index => $row2){
            $icont = $icont + 1 ;

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
            $cod_par    = $row2["cod_par"];
            $cod_gen    = $row2["cod_gen"];
            $cod_esp    = $row2["cod_esp"];
            $cod_sub    = $row2["cod_sub"];

            if ($ano_proceso != $ano_sol_doc){
                $res = $this->armar_centro_costo($msj,$tip_cod,$cod_pryacc,
                                                    $cod_obj,$gerencia,$unidad,$cod_com,$row2,$ano_proceso,$ano_sol_doc);

                if (!$res)
                    return $res;
            }else{
                $tip_cod	= $row2["tip_cod"];
                $cod_pryacc = $row2["cod_pryacc"];
                $cod_obj	= $row2["cod_obj"];
                $gerencia	= $row2["gerencia"];
                $unidad		= $row2["unidad"];
                $cod_com	= $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);
            }

            $sal_cau = $row2["sal_cau"];
            $saldo_estructura = $sal_cau;

            if ($result_iva[0]["cod_pari"] == $row2["cod_par"] && $result_iva[0]["cod_geni"] == $row2["cod_gen"]
                && $result_iva[0]["cod_espi"] == $row2["cod_esp"] && $result_iva[0]["cod_subi"] == $row2["cod_sub"]){
                //------------------------------------------------------------------------------------
                //  Verificar si Existe Nota de Credito para disminuir el monto de la partida de iva
                //------------------------------------------------------------------------------------
                if ($ncr_sn == 'S')
                    $valor = $row2["mto_nc"];
                else
                    $valor = 0;

                if ($valor != 0){
                    //Ajustar el Compromiso por concepto de nota de Credito
                    $accion		 = "+";$accion2="-";
                    $decripcion  = "REVERSO DEL AJUSTE DEL COMPROMISO POR CONCEPTO DE NOTA DE CREDITO NRO: " . $factura->nro_ncr . " EN LA FACTURA: " . $factura->num_fac;
                    $des_proceso = 'Por Concepto de Nota de Credito';
                    $res = $this->Reverso_Compromiso($factura,$msj,$ano_sol_doc,$concepto,$tip_cod,$cod_pryacc,$cod_obj,
                                                        $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$valor,$accion,$accion2,$decripcion,
                                                        $result_iva,$ano_proceso,$des_proceso);

                    if (!$res)
                        return $res;
                }

                //------------------------------------------------------------------------------------
                // Si es la partida de iva se debe validar que el porcentaje de la factura sea igual
                // al ingresado en la orden  de compra  o en la certificacion en caso contrario se
                // reversar el ajuste al compromiso por concepto de cambio en la alicuota de iva
                //------------------------------------------------------------------------------------
                $por_iva_fac = $factura->porcentaje_iva;
                $base_imponible			 = $factura->base_imponible;
                $monto_iva_certificacion = round(($base_imponible * ($por_iva / 100)) * 100) / 100;
                $monto_iva_factura		 = round(($base_imponible * ($por_iva_fac / 100)) * 100) / 100;

                if ($por_iva != $por_iva_fac){
                    //------------------------------------------------------------------------
                    //Verificar si es % de la factura es mayor al de la certificacion se
                    //debe disminuir el compromiso y aumentar el disponible en caso contrario
                    //    se debe aumentar el compromiso y disminuir el disponible
                    //------------------------------------------------------------------------
                    $base_imponible			 = $factura->base_imponible;
                    $monto_iva_certificacion = round(($base_imponible * ($por_iva / 100)) * 100) / 100;
                    $monto_iva_factura		 = round(($base_imponible * ($por_iva_fac / 100)) * 100) / 100;

                    if ($por_iva > $por_iva_fac){
                        $monto_comprometer = $monto_iva_certificacion - $monto_iva_factura;
                        $sumar	 = false;
                        $accion	 = '+';
                        $accion2 = '-';
                        $saldo_estructura = $saldo_estructura + $monto_comprometer;
                    }else{
                        $monto_comprometer = $monto_iva_factura - $monto_iva_certificacion;
                        $sumar   = true;
                        $accion  = '-';
                        $accion2 = '+';
                        $saldo_estructura =	$saldo_estructura - $monto_comprometer;
                    }

                    //Ajustar el Compromiso por cambio en la alicuota de IVA
                    $decripcion  = "REVERSO DEL AJUSTE DEL COMPROMISO POR CAMBIO EN LA ALICUOTA DE IVA EN LA FACTURA: " . $factura->num_fac;
                    $des_proceso = 'Por Concepto de Cambio en la Alicuota de IVA';
                    $res = $this->Reverso_Compromiso($factura,$msj,$ano_sol_doc,$concepto,$tip_cod,$cod_pryacc,$cod_obj,
                                                        $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$monto_comprometer,$accion,$accion2,
                                                        $decripcion,$result_iva,$ano_proceso,$des_proceso); //Dualidad
                    if (!$res)
                        return $res;
                }


                //-----------------------------------------------------------------
                // Realizar el Movimiento  del Reverso presupestario del Causado
                //-----------------------------------------------------------------
                $decripcion = "REVERSO DEL CAUSADO DE LA PARTIDA DE IVA SEGUN FACTURA: " . $factura->num_fac;
                $tip_ope    = 60;
                $sol_tipo	= 'IF';
                $nota_entr  = '';
                $res = $this->Reverso_Causado($factura,$msj,$ano_sol_doc,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,
                                                $cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$row2["sal_cau"],$decripcion,$result_iva,$ano_proceso,
                                                $tip_ope,$sol_tipo,$nota_entr,'1','-'); //Dualidad

                if (!$res)
                    return $res;
            }else{//sino es la partida presupuestaria de iva
                //--------------------------------------------------------------------------
                //   Si existe notas de credito:
                //   - Se debe Reversar el ajuste del Compromiso
                //   - Se debe reversar el ajuste del causado de las notas de entregas o
                //     actas de aceptacion de servicios asociadas a la factura
                //--------------------------------------------------------------------------
                if ($ncr_sn == 'S'){
                    $valor = $row2["mto_nc"];

                    if ($valor != 0){
                        //Ajustar el compromiso por concepto de nota de credito
                        $accion 	 = "+";
                        $accion2	 = "-";
                        $decripcion  = "REVERSO DEL AJUSTE DEL COMPROMISO POR CONCEPTO DE NOTA DE CREDITO NRO: " . $factura->nro_ncr . " EN LA FACTURA: " . $factura->num_fac;
                        $des_proceso = 'Por Concepto de NOta de Credito';
                        $res = $this->Reverso_Compromiso($factura,$msj,$ano_sol_doc,$concepto,$tip_cod,$cod_pryacc,
                                                            $cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$valor,$accion,
                                                            $accion2,$decripcion,$result_iva,$ano_proceso,$des_proceso); //Dualidad
                        if (!$res)
                            return $res;

                        //---------------------------------------------------------------------
                        // Si viene por Orden de Compra o contrato se debe ajustar el causado
                        // Si viene por pago Directo se debe Causar el gasto
                        //---------------------------------------------------------------------
                        switch ($factura->tipo_doc){
                            case '1':
                            case '2':
                            case '3':
                            case '5':
                                $result_notas = CxPDetNotaFactura::where('ano_pro',$factura->ano_pro)
                                                                 ->where('rif_prov',$factura->rif_prov)
                                                                 ->where('num_fac',$factura->num_fac)
                                                                 ->select('ano_nota_entrega', 'rif_prov', 'num_fac','grupo', 'nro_ent', 'mto_ord', 'nro_doc')
                                                                 ->get();


                                if ($result_notas){
                                    //Dividir el monto de la nota de credito entre las notas de entregas asociadas a la factura
                                    $total_registro_notas = count($result_notas);
                                    $monto_Modificado_causado = $valor / $total_registro_notas;

                                    for ($i = 0; $i < count($result_notas); $i ++){
                                        $nota_entr = $result_notas[$i]["grupo"] . '-' . $result_notas[$i]["nro_ent"] . '-' . $result_notas[$i]["ano_nota_entrega"];
                                        //------------------------------------------------------------------------
                                        //  Ajustar el causado de las Notas de Entregas asociadas a las facturas
                                        //------------------------------------------------------------------------
                                        $decripcion = "REVERSO DEL AJUSTE DEL CAUSADO POR CONCEPTO DE NOTA DE CREDITO NRO:" . $factura->nro_ncr .
                                                        " EN LA FACTURA: " . $factura->num_fac;
                                        $tip_ope  = 93;
                                        $sol_tipo = 'AJ';
                                        $res = $this->Reverso_Causado($factura,$msj,$ano_sol_doc,$tip_cod,$cod_pryacc,
                                                                        $cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,
                                                                        $monto_Modificado_causado,$decripcion,$result_iva,$ano_proceso,$tip_ope,
                                                                        $sol_tipo,$nota_entr,'2','+'); //Dualidad
                                        if (!$res)
                                            return $res;
                                    }
                                }else{
                                    $res =false;
                                    $msj = "Error Consultado Notas de Entregas asociadas a la Factura.\\nComuniquese con el Administrador del Sistema.";
                                    return $res;
                                }

                                break;
                            case '4':
                                //-------------------------------------------------------------------
                                //    Realizar el Movimiento  del Reverso presupestario del Causado
                                //-------------------------------------------------------------------
                                $decripcion = "REVERSO DEL CAUSADO DE CERTIFICACION DE PAGOS DIRECTOS SEGUN FACTURA: " . $factura->num_fac;
                                $tip_ope	= 60;
                                $sol_tipo	= 'IF';
                                $nota_entr	= '';
                                $res = $this->Reverso_Causado($factura,$msj,$ano_sol_doc,$tip_cod,$cod_pryacc,$cod_obj,
                                                                $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$row2["sal_cau"],
                                                                $decripcion,$result_iva,$ano_proceso,$tip_ope,$sol_tipo,$nota_entr,'1','-'); //Dualidad
                                if (!$res)
                                    return $res;

                                break;
                        }//Fin del switch
                    }else{//if ($valor!=0)
                        if ($factura->tipo_doc == 4){
                            //Existe Notas de Credito pero la partida no se ve afecta por la misma
                            //------------------------------------------
                            //  Ingresar Registro en Pre_Movimientos
                            //------------------------------------------
                            $decripcion = "REVERSO DEL CAUSADO DE CERTIFICACION DE PAGOS DIRECTOS SEGUN FACTURA: " . $factura->num_fac;

                            //----------------------------------------------------------------
                            // Realizar el Movimiento  del Reverso presupestario del Causado
                            //----------------------------------------------------------------
                            $nota_entr = '';
                            $tip_ope   = 60;
                            $sol_tipo  = 'IF';
                            $res = $this->Reverso_Causado($factura,$msj,$ano_sol_doc,$tip_cod,$cod_pryacc,$cod_obj,
                                                            $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$row2["sal_cau"],$decripcion,
                                                            $result_iva,$ano_proceso,$tip_ope,$sol_tipo,$nota_entr,'1','-'); //Dualidad

                            if (!$res)
                                return $res;
                        }
                    }
                }else{//fin del  if ($ncr_sn=='S')
                    if ($factura->tipo_doc == 4){

                        //----------------------------------------
                        //  Ingresar Registro en Pre_Movimientos
                        //----------------------------------------
                        $decripcion = "REVERSO DEL CAUSADO DE CERTIFICACION DE PAGOS DIRECTOS SEGUN FACTURA: " . $factura->num_fac;

                        //-----------------------------------------------------------------
                        //  Realizar el Movimiento  del Reverso presupestario del Causado
                        //-----------------------------------------------------------------
                        $tip_ope   = 60;
                        $sol_tipo  = 'IF';
                        $nota_entr = '';
                        $res = $this->Reverso_Causado($factura,$msj,$ano_sol_doc,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,
                                                        $unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$row2["sal_cau"],$decripcion,$result_iva,
                                                        $ano_proceso,$tip_ope,$sol_tipo,$nota_entr,'1','-'); //Dualidad

                        if (!$res)
                            return $res;
                    }
                }
            }//fin e que no es la partida de iva

            //----------------------------------------------------------------
            //  Proceso para actualizar la estructura de gasto de la factura
            //----------------------------------------------------------------
            $res = $this->Actualizar_Gasto_Factura($factura,$msj,$row2["mto_tra"],$cod_com);

            if (!$res)
                return $res;
        }


        return $res;
    }

    //----------------------------------------------------------------------------------------------------------------------------------------------
    //                        Funcion para ajustar el compromiso ppara cualquier concepto
    //----------------------------------------------------------------------------------------------------------------------------------------------
    function Reverso_Compromiso($factura,&$msj,$ano_sol_orden,$concepto,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,
                                $cod_par,$cod_gen,$cod_esp,$cod_sub,$cod_com,$monto_Comprometer,$accion,$accion2,$decripcion,$result_iva,$ano_fiscal,
                                $des_proceso){
        //Realizar el ajuste en Pre Maestro Ley
        $res = $this->Ajustar_Compromiso_Pre_Maestro_Ley($msj,$monto_Comprometer,$cod_com,$accion,$accion2,$ano_fiscal,$des_proceso);

        if (!$res)
            return $res;

        $cod_com_viejo = '';

        //Si es la partida de IVA
        if ($result_iva[0]["cod_pari"] == $cod_par && $result_iva[0]["cod_geni"] == $cod_gen
            && $result_iva[0]["cod_espi"] == $cod_esp && $result_iva[0]["cod_subi"] == $cod_sub){

            $res = $this->Buscar_Partida_Iva_Inicial_del_Documento($msj,$ano_sol_orden,$cod_com_viejo,$cod_com,$ano_fiscal);

            if (!$res)
                return $res;
        }else{
            $cod_com_viejo = '';
            $centro_actual = $this->Concatenar_Centro_Costo($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad);
            $partida	   = $this->Concatenar_Partida($cod_par,$cod_gen,$cod_esp,$cod_sub);
            $res		   = $this->Buscar_centro_costo_viejo($msj,$centro_actual,$cod_com_viejo,$ano_sol_orden);

            if (!$res)
                return $res;

            $cod_com_viejo = $cod_com_viejo . "." . $partida;
        }

        $monto_compromiso = 0;
        $nro_enl_anular	  = '';
        $tip_ope		  = '';
        $sol_tipo		  = '';
        $operaciones	  = "('90','95')";
        $mto_transaccion  = 0.00;
        $res = $this->Buscar_ultimo_Registro($msj,$ano_sol_orden,$cod_com_viejo,$cod_com,
                                                $monto_compromiso,$nro_enl_anular,$tip_ope,$sol_tipo,$operaciones,$mto_transaccion);
        if (!$res)
            return $res;

        //------------------------------------------------------------------
        //             Realizar el Reverso del Ajuste del compromiso
        //------------------------------------------------------------------
        $status_reg = "2";

        IF ($tip_ope==95)
            $tip_ope	 = 96;
        ELSE
            $tip_ope	 = 92;

        $sol_tipo	 = 'AJ';

        if ($accion == '-'){
            $monto_a_comprometer  = $monto_compromiso - $monto_Comprometer;
            $monto_reverso_ajuste = $monto_Comprometer*(-1) ;
        }else{
            $monto_a_comprometer  = $monto_compromiso + $monto_Comprometer;
            $monto_reverso_ajuste = $monto_Comprometer;
        }

        //----------------------------------------
        //  Insertar el momento Presupuestario
        //----------------------------------------
        $num_fac		  = $factura->num_fac;
        $nota_entrega	  = '';
        $mto_transaccion = $mto_transaccion * (-1);
        $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,
                                            $unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$monto_compromiso,$decripcion,
                                            $tip_ope,$sol_tipo,$status_reg,$ano_sol_orden,$this->getCampo("nro_doc")->valor,
                                            $nota_entrega,$num_fac,$mto_transaccion,$ano_fiscal,'1');
        if (!$res)
            return $res;

        //----------------------------------------------------
        // Leer numero de registro que se termina de ingresar
        //----------------------------------------------------
        $nro_enl = '';
        $estado  = '2';
        $res = $this->Busca_Registro_Actual($factura,$msj,$ano_sol_orden,$cod_com,$nro_enl_anular,$ano_sol_orden,$nro_enl,$estado);

        if (!$res)
            return $res;

        //----------------------------------------------------------------------
        //  Actualizar status en Pre Movimiento del movimiento anterior en 2
        //----------------------------------------------------------------------
        $res = $this->Actualizar_status_Pre_Movimiento($factura,$msj,$nro_enl,$nro_enl_anular);

        if (!$res)
            return $res;

        //---------------------------------------------------------------------------
        //Crear nuevo Registro para ello debo saber si es un registro 10 ,94 0 90
        //si el monto es igual al compromiso original es un registro 10 en caso
        //      contrario se crea un registro 90
        //----------------------------------------------------------------------------
        $monto_compromiso_original = 0;
        $sol_tipo_original = '';
        $operaciones	 = "'10','94','90','95'";
        $concepto_actual = '';
        $tip_ope		 = 0;
        $res = $this->Buscar_Registro_Especifico($factura,$msj,$ano_sol_orden,$cod_com_viejo,$cod_com,
                                                    $monto_compromiso_original,$operaciones,$sol_tipo_original,$concepto_actual,$tip_ope);

        if (!$res)
            return $res;

        if ($monto_compromiso_original == $monto_a_comprometer){
            //Se debe crear un Registro 10 o 94
            $sol_tipo = $sol_tipo_original;
            $monto_reverso_ajuste = 0;
            }else{
            //Se debe crear un registro 90
            $tip_ope  = 90;
            $sol_tipo = 'AJ';
            $concepto_actual = 'AJUSTE DE COMPROMISO POR REVERSO';
            $monto_reverso_ajuste = $monto_a_comprometer;
            }

            $status_reg = "1";

            //----------------------------------------
            //   Insertar el momento Presupuestario
            //----------------------------------------
            if ($tip_ope=='90')
            $num_fac = $this->getCampo("num_fac")->valor;
            else
            $num_fac = '';

        $nota_entrega = '';
        $res = $this->Insertar_Presupuesto($factura,$msj,$tip_cod,$cod_pryacc,$cod_obj,
                                            $gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub,$monto_a_comprometer,
                                            $concepto_actual,$tip_ope,$sol_tipo,$status_reg,$ano_sol_orden,
                                            $this->getCampo("nro_doc")->valor,$nota_entrega,$num_fac,$monto_reverso_ajuste,
                                            $ano_fiscal,'0');
        if (!$res)
            return $res;

        return $res;
    }
    //-------------------------------------------------------------------------------------------------------
    //Funcion que busca el ultimo registro vivo para un compromiso dependiendo la estructura presupuestaria
    //-------------------------------------------------------------------------------------------------------
    function Buscar_Registro_Especifico($factura,&$msj,$ano_sol_orden,$cod_com_buscar,$cod_com,
                                        &$monto_compromiso,$operaciones,&$sol_tipo_original,&$concepto,&$tipo_operacion){
        $res = true;

        $result_monto = PreMovimiento::where('ano_doc', $ano_sol_orden )
                                     ->whereIn('tip_ope',[$operaciones] )
                                     ->where('num_doc',$factura->nro_doc )
                                     ->whereIn('cod_com',[$cod_com_buscar,$cod_com] )
                                     ->orderBy( 'num_reg', 'desc')
                                     ->get();

        if (!empty($result_monto[0]->tip_ope)){
            $monto_compromiso  = $result_monto[0]->mto_tra;
            $sol_tipo_original = $result_monto[0]->sol_tip;
            $concepto		   = $result_monto[0]->concepto;
            $tipo_operacion    = $result_monto[0]->tip_ope;
        }else{
            $res = false;
            $msj = "Error al Buscar Registro Inicial del Compromiso para la partida [" . $cod_com . "]\\nComuniquese con el Administrador del Sistema.";
            return $res;
        }

        return $res;
    }


}
