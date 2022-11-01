<?php

    namespace App\Traits;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPCabeceraFactura;
    use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Factura;

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
        // fecha_cau = fecha.substring(0, 4);
        // ano_fiscal = Utils.ano_pro;
        switch ($accion) {
            // Nuevo
            case "N":
                if ($valor == '0') {
                    return true;
                } else {
                    $estado = $this->descrip_statu($valor);
                    alert()->error('Factura con '. $estado. ' Por favor verifique');
                    return redirect()->back()->withInput();
                }
                // Reversar
            case "R":
                {
                    if ($valor == '1') {
                        if ($this->ano_fiscal != $this->fecha_cau) {
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
                            $estado = $this->descripcion_statu_factura(valor);
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
                            $estado = $this->descripcion_statu_factura(valor);
                            alert()->error('Factura con Status Invalido.('.$estado.'). Por favor verifique.');
                            return redirect()->back()->withInput();
                        }
                    }
                }
        }
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
        //$rec_val = $('#recibo').val();

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
                                            ->whereNull('pago_manual')
                                            ->selectRaw("rif_prov, pago_manual, tipo_pago,'S' AS factura,por_anticipo, monto_anticipo ,
                                                        SUM(monto_amortizacion) AS monto_amortizacion ,SUM(mto_iva) AS mto_iva, porcentaje_iva,
                                                        SUM(mto_nto) AS mto_nto, SUM(mto_tot) AS mto_tot,SUM(base_imponible) AS base_imponible,
                                                        SUM(base_excenta) AS base_excenta, fondo,cuenta_contable, tipo_doc,nro_doc,ano_doc,
                                                        monto_neto_doc,'N' AS deposito_garantia,'N' AS provision, rec_val AS recibo")
                                            ->groupBy('rif_prov','pago_manual','tipo_pago','factura','por_anticipo','monto_anticipo','porcentaje_iva',
                                                    'fondo','cuenta_contable','tipo_doc','nro_doc','ano_doc','monto_neto_doc','deposito_garantia')
                                            ->get();
                break;
            case "4":
                $queryopsol = CxPCabeceraFactura::query()
                                                ->where('tipo_doc',$tipo_doc)
                                                ->where('ano_doc',$ano_sol)
                                                ->where('nro_doc',$nro_doc)
                                                ->whereIn('statu_proceso',['1','4','3'])
                                                ->whereNull('pago_manual')
                                                ->selectRaw("rif_prov, pago_manual, tipo_pago,'S' AS factura,por_anticipo, monto_anticipo ,
                                                             SUM(monto_amortizacion) AS monto_amortizacion ,SUM(mto_iva) AS mto_iva, porcentaje_iva,
                                                             SUM(mto_nto) AS mto_nto, SUM(mto_tot) AS mto_tot,SUM(base_imponible) AS base_imponible,
                                                             SUM(base_excenta) AS base_excenta, fondo,cuenta_contable, tipo_doc,nro_doc,ano_doc,
                                                             monto_neto_doc, deposito_garantia, provision, rec_val AS recibo")
                                                ->groupBy('rif_prov','pago_manual','tipo_pago','factura','por_anticipo','monto_anticipo','porcentaje_iva',
                                                'fondo','cuenta_contable','tipo_doc','nro_doc','ano_doc','monto_neto_doc','deposito_garantia','provision')
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
                                            ->selectRaw("CASE WHEN SUM(mto_nto) == null THEN 0 ELSE SUM(mto_nto) END AS sum, provisionada")
                                            ->groupBy('2')
                                            ->get();
                        foreach($queryProv as $sq_datos_montos){

                            if ($sq_datos_montos->sum > 0) {
                                $Monto_Recepcionado = $sq_datos_montos->sum ;
                                $provisionado = $sq_datos_montos->provisionada;
                                if ($Monto_Recepcionado >= $Monto_Orden) {
                                    alert()->error('Ya ha sido Recepcionado el Monto Total de la'.$descrip_doc. '   Por favor verifique. ');
                                    return redirect()->back()->withInput();
                                } else {
                                    // Si se define que es pago total y ya esxiste una
                                    // factura ingresada en sistema no debe permitir
                                    // ingresar otra factura
                                    if ($tipo_doc == '4' &&  $sq_datos_opsol->factura == 'T') {
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
                                    //---------------------------------------------------
                                    if ($existen_facturas_ingresadas) {
                                        $query_Montos =Factura::where('rif_prov',$rif_proveedor)
                                                            ->where('nro_doc', $nro_doc)
                                                            ->where('recibo',$recib)
                                                            ->where('ano_sol', $ano_sol)
                                                            ->selectRaw("CASE WHEN SUM(base_imponible) == NULL THEN 0 ELSE SUM(base_imponible) END AS base_imponible,
                                                                        CASE WHEN SUM(base_excenta) == NULL THEN 0 ELSE SUM(base_excenta) END AS base_excenta,
                                                                        CASE WHEN SUM(mto_nto) == NULL THEN 0 ELSE SUM(mto_nto) END AS mto_nto,
                                                                        CASE WHEN SUM(mto_iva) == NULL THEN 0 ELSE SUM(mto_iva) END AS mto_iva,
                                                                        CASE WHEN SUM(mto_fac) == NULL THEN 0 ELSE SUM(mto_fac) END AS mto_fac")
                                                            ->first();

                                            if ($query_Montos != null) {
                                                // ----------------------------------------------------
                                                // Asignar nuevamente los montos
                                                // ----------------------------------------------------
                                                $sq_datos_opsol_original[0] = $this->redondear($sq_datos_opsol->base_imponible - $query_Montos[0]->base_imponible, 2); // Base
                                                // Imponible
                                                $sq_datos_opsol_original[1] = $this->redondear($sq_datos_opsol->base_excenta - $query_Montos[0]->base_excenta, 2); // Base
                                                // Exenta
                                                $sq_datos_opsol_original[2] = $this->redondear($sq_datos_opsol->mto_nto - $query_Montos[0]->mto_nto, 2); // Monto
                                                // Neto
                                                $sq_datos_opsol_original[3] = $this->redondear($sq_datos_opsol->mto_iva - $query_Montos[0]->mto_iva, 2); // Monto
                                                // Iva
                                                $sq_datos_opsol_original[4] = $this->redondear($sq_datos_opsol->mto_tot - $query_Montos[0]->mto_fac, 2); // Monto
                                                // Iva
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
}
