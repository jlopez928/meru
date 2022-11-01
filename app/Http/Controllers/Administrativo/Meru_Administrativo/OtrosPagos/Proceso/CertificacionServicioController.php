<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\OtrosPagos\Proceso;
use App\Exports\FromQueryExport;

use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\ComprobanteOpen;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Compras\ComprobanteOpenDet;
use App\Models\Administrativo\Meru_Administrativo\Compras\CorrelativoComprobante;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpDetsolservicio;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpDetgastossolservicio;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\PlanSolpago;
use App\Models\Administrativo\Meru_Administrativo\TesCorrelativo;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\PreMovimiento;
use App\Models\Administrativo\Meru_Administrativo\Corrsolpago;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPCabeceraFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Factura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPGastoFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxpDetGastosSolpago;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxpDetContableSolpago;
Use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPSolPagoAnticipoProv;
use App\Http\Requests\Administrativo\Meru_Administrativo\OtrosPagos\Proceso\OpSolServicioRequest;
use App\Models\Administrativo\Meru_Administrativo\General\DatosEmpresa;
use App\Traits\MovimientoPresupuestario;
use Illuminate\Http\Request;
use App\Traits\Presupuesto;
use App\Traits\PreMovimientos;
use App\Traits\convertirLetrasMonto;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Traits\ReportFpdf;
use Carbon\Carbon;

class CertificacionServicioController extends Controller
{   use ReportFpdf;
    use MovimientoPresupuestario;
    use PreMovimientos;
    use Presupuesto;
    use convertirLetrasMonto;
    //--------------------------------------------------------------
    //              Funcion que llama al Index
    //-------------------------------------------------------------
    public function index()
    { return view('administrativo.meru_administrativo.otrospagos.proceso.certificacion_servicio.index');
    }
    //--------------------------------------------------------------
    //              Funcion que llama al Crear
    //-------------------------------------------------------------
    public function crear($accion)
    {
          $opsolservicio= new OpSolservicio();
          return view('administrativo.meru_administrativo.otrospagos.proceso.certificacion_servicio.create',
          [
              'opsolservicio'     => $opsolservicio,
              'accion'            => $accion
          ]);
    }
    //--------------------------------------------------------------
    //              Funcion que llama al Insertar
    //-------------------------------------------------------------
    public function store(OpSolServicioRequest $request)
    {
        try {
                DB::connection('pgsql')->beginTransaction();
			    $listadoDetalle = json_decode($request->listadoDetalle);
               ///----------------------------------------------------------------------------------------------
                // Actualizar el proveedor para que no pueda ser eliminado desde la pantalla de proveedores
                //                ya que tiene una certificacion asociada
                //----------------------------------------------------------------------------------------------
                Proveedor::where('rif_prov',$request->rif_prov)
                ->update(['contrato' => 1 ]);
                //-------------------------------------------------------------------
                //  Obtener el último correlativo de la tabla tes_corrtransferencias
                //         esto se debe actualizar por cada año fiscal
                //--------------------------------------------------------------------
                $num_reg = TesCorrelativo::query()->where('ano_pro', $request->ano_pro)->where('flujo', 'PD')->first()->correlativo?? 1;
                //----------------------------------------------------------------------------------------------
                //           Insertar los datos principales de la certificacion en la tabla principal
                // Toda Certificacion debe ser ingresada para el año fiscal por sistema no puedo ingresar
                // solictudes de año anteriores
                //----------------------------------------------------------------------------------------------



                $opsolservicio=OpSolservicio::create([
                    'ano_pro'             => $request->ano_pro,
                    'nro_sol'             => $num_reg,
                    'fec_emi'             => $request->fec_emi,
                    'cod_ger'             => $request->cod_ger,
                    'rif_prov'            => $request->rif_prov,
                    'fec_serv'            => $request->fec_serv,
                    'lugar_serv'          => $request->lugar_serv,
                    'tip_pag'             => $request->tip_pag,
                    'factura'             => $request->factura,
                    'fec_pto'             => $request->fec_pto,
                    'motivo'              => $request->motivo,
                    'sta_sol'             => $request->sta_sol,
                    'fec_sta'             =>  now()->format('Y-m-d'),
                    'usuario'             =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                    'fecha'               => $this->fechaGuardar,
                    'por_anticipo'        => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->por_anticipo)))) ,
                    'mto_ant'             => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->mto_ant)))) ,
                    'monto_iva'           => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->monto_iva)))),
                    'por_iva'             => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->por_iva)))),
                    'monto_neto'          => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->monto_neto)))),
                    'monto_total'         => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->monto_total)))),
                    'base_exenta'         => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->base_exenta)))),
                    'base_imponible'      => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->base_imponible)))),
                    'grupo'               => $request->grupo,
                    'xnro_sol'            => 'PD-'.$num_reg,
                    'observaciones'       => $request->observaciones,
                    'tiempo_entrega '     => $request->tiempo_entrega,
                    'certificados'        => $request->certificados,
                    'lugar_entrega'       => $request->lugar_entrega,
                    'forma_pago'          => $request->forma_pago,
                    'flete'               => $request->flete,
                    'num_contrato'        => $request->num_contrato,
                    'provision'           => $request->provision,
                    'tip_contrat'         => $request->tip_contrat,
                    'deposito_garantia'   => $request->deposito_garantia
                ]);
                //--------------------------------------------------------------
                //              Insert en la Tabla Detalle
                //-------------------------------------------------------------
                OpDetsolservicio::create([
                    'ano_pro'               => $request->ano_pro,
                    'nro_sol'               => $num_reg,
                    'cod_prod'              => $request->codigo,
                    'por_iva'               => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->por_iva_con)))),
                    'cantidad'              => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cantidad)))),
                    'cos_uni'               => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_uni)))),
                    'cos_tot'               => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_tot)))),
                    'grupo'                 => 'PD',
                    'xnro_sol'              => 'PD-'.$num_reg,
                    'base_excenta'          => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_excenta)))),
                    'op_solservicio_id'     => $opsolservicio->id
                ]);
                //--------------------------------------------------------------
                //              Insert en la Tabla Detalle
                //-------------------------------------------------------------
                foreach($listadoDetalle as $detalle){
                    //-------------------------------------------------
                    //-------------------------------------------------
                    if($request->provision=='S'){
                        $result_cuenta_contable=$this->ObtenerDatosPartida($detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub);
                       if($result_cuenta_contable->cta_provision ==null  ||
                          $result_cuenta_contable->cod_cta!='4.03.18.01.00'){
                            alert()->error('Error!', 'La la Estructura de Gasto, No tiene asociada Cta. de Provisión .Favor Verifique');
                            return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                        }
                    }
					//-------------------------------------------------
                    //-------------------------------------------------
                    $OpDetgastossolserviciox=    OpDetgastossolservicio::create([
                            'ano_pro'                    => $request->ano_pro,
                            'xnro_sol'                   => 'PD-'.$num_reg,
                            'nro_sol'                    => $num_reg,
                            'gasto'                      => $detalle->gasto,
                            'tip_cod'                    => $detalle->tip_cod,
                            'cod_pryacc'                 => $detalle->cod_pryacc,
                            'cod_obj'                    => $detalle->cod_obj,
                            'gerencia'                   => $detalle->gerencia,
                            'unidad'                     => $detalle->unidad,
                            'cod_par'                    => $detalle->cod_par,
                            'cod_gen'                    => $detalle->cod_gen,
                            'cod_esp'                    => $detalle->cod_esp,
                            'cod_sub'                    => $detalle->cod_sub,
                            'descrip'                    => $detalle->descrip,
                            'mto_tra'                    => floatval(\Str::replace(',', '.', \Str::replace('.','', ($detalle->mto_tra)))),
                            'cod_cta'                    => $detalle->cod_cta,
                            'partida_presupuestaria_id'  =>$this->ObtenerIdpartida($detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub) ,
                            'grupo'                      => 'PD',
                            'cod_com'                    => $this->varmarCodcom($detalle->tip_cod,$detalle->cod_pryacc,
                                                                                $detalle->cod_obj,$detalle->gerencia,$detalle->unidad,
                                                                                $detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub),
                            'op_solservicio_id'          =>$opsolservicio->id
                        ]);
                }
                //-------------------------------------------------------------------------------------
                //            Incrementar o Crear el correlativo si es un nuevo año Fiscal
                //-------------------------------------------------------------------------------------
                if ($num_reg ==1) {
                    TesCorrelativo::create([
                        'ano_pro'               => $request->ano_pro,
                        'correlativo'           => $num_reg+1,
                        'flujo'                 => 'PD'  ]);
                    }else{
                        TesCorrelativo::where('flujo', 'PD')->where('ano_pro', $request->ano_pro)->increment('correlativo');

                        }
                alert()->success('¡Éxito!', 'Certificación Registrada Sastifactoriamente con el Numero: '.$opsolservicio->ano_pro."-".$opsolservicio->xnro_sol);
                DB::connection('pgsql')->commit();
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
         } catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
         }
    }
     //--------------------------------------------------------------
    //        Funcion que llama al Show y dependienco la accion
    //                     llama a las diferenes vista
    //-------------------------------------------------------------
    public function show(OpSolservicio $certificacionservicio,$valor)
    {
       $registrocontrol=  RegistroControl::query()
                        ->select('ano_pro','ano_pro')
                        ->orderBy('ano_pro', 'desc')
                        ->get();

        $beneficiario = Beneficiario::query()
                        ->select('rif_ben','nom_ben')
                        ->orderBy('nom_ben', 'asc')
                        ->get();
        $gerencia  =  Gerencia::query()
                    ->select('cod_ger','des_ger')
                    ->orderBy('des_ger', 'asc')
                    ->get();
        $activardeposito=false;
        switch ($valor) {
            case "show":
                $ruta='administrativo.meru_administrativo.otrospagos.proceso.certificacion_servicio.show';
                break;
            case "anular":
                $ruta='administrativo.meru_administrativo.otrospagos.proceso.certificacion_servicio.anular';
                break;
            case "aprobar":
                $ruta='administrativo.meru_administrativo.otrospagos.proceso.certificacion_servicio.aprobar';
                break;
            case "reversar":
                $ruta='administrativo.meru_administrativo.otrospagos.proceso.certificacion_servicio.reversar';
                break;
            case "reverso":
                $ruta='administrativo.meru_administrativo.otrospagos.proceso.certificacion_servicio.reverso';
                break;
            case "comprometer":
                $ruta='administrativo.meru_administrativo.otrospagos.proceso.certificacion_servicio.comprometer';
                // Si existe una partidaque comienza por 4
                // se debe habilitar la grilla paraseleccionar las estructuras de gastos (Ojo pendiente)
                // y el combo de Deposito en Garantia
                $array=$certificacionservicio->opdetgastossolservicio->toArray();
                foreach ( $array as $item) {
                    if( $item['cod_par']=='4'|| ( $item['cod_par'] == 3 &&  $item['cod_gen'] == 1 &&
                                                  $item['cod_esp'] == 1 &&  $item['cod_sub'] == 0))
                    {
                    $activardeposito= true;
                    }
                }
                break;
        }
        return view($ruta,
        [
            'opsolservicio'     => $certificacionservicio,
            'registrocontrol'   => $registrocontrol,
            'beneficiario'      => $beneficiario,
            'gerencia'          => $gerencia,
            'valor'             => $valor,
            'activardeposito'   => $activardeposito
        ]);

    }
    //--------------------------------------------------------------
    //              Funcion que llama al Edit
    //-------------------------------------------------------------
    public function edit(OpSolservicio $certificacionservicio)
    {
        return view('administrativo.meru_administrativo.otrospagos.proceso.certificacion_servicio.edit', compact('certificacionservicio'));
    }
    //--------------------------------------------------------------
    //              Funcion que llama a Actualizar Datos
    //-------------------------------------------------------------
    public function update(OpSolServicioRequest $request, OpSolservicio $certificacionservicio)
    {
        try {

             DB::connection('pgsql')->beginTransaction();
             $listadoDetalle = json_decode($request->listadoDetalle);
              //----------------------------------------------------------------------------------------------
             //        Actualiza los datos principales de la certificacion en la tabla principal
             //----------------------------------------------------------------------------------------------
              $certificacionservicio->update([
                 'cod_ger'             => $request->cod_ger,
                 'rif_prov'            => $request->rif_prov,
                 'fec_serv'            => $request->fec_serv,
                 'lugar_serv'          => $request->lugar_serv,
                 'tip_pag'             => $request->tip_pag,
                 'factura'             => $request->factura,
                 'fec_pto'             => $request->fec_pto,
                 'motivo'              => $request->motivo,
                 'sta_sol'             => $request->sta_sol,
                 'fec_sta'             =>  now()->format('Y-m-d'),
                 'usuario'             =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                 'fecha'               => $this->fechaGuardar,
                 'por_anticipo'        => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->por_anticipo)))),
                 'mto_ant'             => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->mto_ant)))),
                 'monto_iva'           => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->monto_iva)))),
                 'por_iva'             => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->por_iva)))),
                 'monto_neto'          => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->monto_neto)))),
                 'monto_total'         => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->monto_total)))),
                 'base_exenta'         => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->base_exenta)))),
                 'base_imponible'      => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->base_imponible)))),
                 'observaciones'       => $request->observaciones,
                 'tiempo_entrega '     => $request->tiempo_entrega,
                 'certificados'        => $request->certificados,
                 'lugar_entrega'       => $request->lugar_entrega,
                 'forma_pago'          => $request->forma_pago,
                 'flete'               => $request->flete,
                 'num_contrato'        => $request->num_contrato,
                 'provision'           => $request->provision,
                 'tip_contrat'         => $request->tip_contrat,
                 'deposito_garantia'   => $request->deposito_garantia
             ]);
             //--------------------------------------------------------------
             //          Borrar -  Insert en la Tabla Detalle
             //-------------------------------------------------------------
             $OpDetsolservicio=OpDetsolservicio::where('op_solservicio_id','=',$certificacionservicio->id);
             $OpDetsolservicio->delete();
             OpDetsolservicio::create([
                 'ano_pro'               => $request->ano_pro,
                 'nro_sol'               => $certificacionservicio->nro_sol,
                 'cod_prod'              => $request->codigo,
                 'por_iva'               => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->por_iva_con)))),
                 'cantidad'              => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cantidad)))),
                 'cos_uni'               => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_uni)))),
                 'cos_tot'               => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_tot)))),
                 'grupo'                 => $certificacionservicio->grupo,
                 'xnro_sol'              => $certificacionservicio->xnro_sol,
                 'base_excenta'          => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_excenta)))),
                 'op_solservicio_id'     => $certificacionservicio->id
             ]);
             //--------------------------------------------------------------
             //           Borrar -     Insert en la Tabla Detalle
             //-------------------------------------------------------------
             $OpDetgastossolservicio=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacionservicio->id);
             $OpDetgastossolservicio->delete();
             foreach($listadoDetalle as $detalle){
                    //-------------------------------------------------
                    //-------------------------------------------------
                    if($request->provision=='S'){
                        $result_cuenta_contable=$this->ObtenerDatosPartida($detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub);
                       if($result_cuenta_contable->cta_provision ==null  ||
                          $result_cuenta_contable->cod_cta!='4.03.18.01.00'){
                            alert()->error('Error!', 'La la Estructura de Gasto, No tiene asociada Cta. de Provisión .Favor Verifique');
                            return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                        }
                    }
					//-------------------------------------------------
                    //-------------------------------------------------
                    OpDetgastossolservicio::create([
                         'ano_pro'       => $request->ano_pro,
                         'xnro_sol'      => $certificacionservicio->xnro_sol,
                         'nro_sol'       => $certificacionservicio->nro_sol,
                         'gasto'         => $detalle->gasto,
                         'tip_cod'       => $detalle->tip_cod,
                         'cod_pryacc'    => $detalle->cod_pryacc,
                         'cod_obj'       => $detalle->cod_obj,
                         'gerencia'      => $detalle->gerencia,
                         'unidad'        => $detalle->unidad,
                         'cod_par'       => $detalle->cod_par,
                         'cod_gen'       => $detalle->cod_gen,
                         'cod_esp'       => $detalle->cod_esp,
                         'cod_sub'       => $detalle->cod_sub,
                         'descrip'       => $detalle->descrip,
                         'mto_tra'       => floatval(\Str::replace(',', '.', \Str::replace('.','', ($detalle->mto_tra)))),
                         'cod_cta'       => $detalle->cod_cta,
                         'partida_presupuestaria_id'  =>$this->ObtenerIdpartida($detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub),
                         'grupo'         => 'PD',
                         'cod_com'       => $this->varmarCodcom($detalle->tip_cod,$detalle->cod_pryacc,
                                                                 $detalle->cod_obj,$detalle->gerencia,$detalle->unidad,
                                                                 $detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub),
                         'op_solservicio_id' =>$certificacionservicio->id
                     ]);
            }
            alert()->success('¡Éxito!', 'Certificación: '.$certificacionservicio->ano_pro."-".$certificacionservicio->xnro_sol.' Modificada Sastifactoriamente');
            DB::connection('pgsql')->commit();
            return redirect()->route('otrospagos.proceso.certificacionservicio.index');
      } catch(\Illuminate\Database\QueryException $e){
         DB::connection('pgsql')->rollBack();
         alert()->error('¡Transacción Fallida!', $e->getMessage());
         return redirect()->back()->withInput();
      }

    }

    //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function anular_anticipo(OpSolservicio $certificacionservicio)
    {
     try {
            //---------------------------------------------------------------
            //Valida que la certificacion exista y tenga % anticipo asociado
            //---------------------------------------------------------------
            if($certificacionservicio->por_anticipo==0){
                alert()->error('Error!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol.' NO Posee Anticipo.Favor Verifique');
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
            }
            //-----------------------------------------
            //Valida que no poseea facturas ya causadas
            //-------------------------------------------
             $factura=Factura::where('rif_prov', $certificacionservicio->rif_prov)
                            ->where('ano_sol', $certificacionservicio->ano_pro)
                            ->where('nro_doc', $certificacionservicio->xnro_sol)
                            ->where('tipo_doc','4')
                            ->whereNotIn('sta_fac', ['0', '2'])->get();
            if(!$factura->isEmpty()){
                    alert()->error('Error!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol.' ya tiene Factura Causada en Sistema.Favor Verifique');
                    return redirect()->route('otrospagos.proceso.certificacionservicio.index');
            }
            //-------------------------------------------------------------------------------------
            //Validar que no exista anticipo asignado, porque se debe eliminar primero el anticipo
            //-------------------------------------------------------------------------------------
            $pagoanticipo=CxPSolPagoAnticipoProv::where('status', '!=','5')
            ->where('ano_sol', $certificacionservicio->ano_pro)
            ->where('xnum_orden', $certificacionservicio->xnro_sol)->get();
            if(!$pagoanticipo->isEmpty()){
                alert()->error('Error!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol.' tiene Anticipo Asociadas.Favor Verifique');
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');

            }
            //----------------------------------------------------------------------------------------------
            // Si pasa todas las validadciones se actualizan los datos asociados
            //         Actualiza el % de anticipo de la certificacion
            //----------------------------------------------------------------------------------------------
             DB::connection('pgsql')->beginTransaction();
                $certificacionservicio->update([
                    'por_anticipo'         => 0,
                    'mto_ant'              => 0,

                ]);
                //--------------------------------------------------------------------------
                // Modificar el % de anticipo  de las facturas asociadas a la certificación
                //--------------------------------------------------------------------------
                Factura::where('rif_prov', $certificacionservicio->rif_prov)
                        ->where('ano_sol', $certificacionservicio->ano_pro)
                        ->where('nro_doc', $certificacionservicio->xnro_sol)
                        ->update(['por_anticipo' => '0','mto_anticipo' => '0','mto_amortizacion' => '0']);
                alert()->success('¡Éxito!', 'Anticipo Anulado Sastifactoriamente');
            DB::connection('pgsql')->commit();
            return redirect()->route('otrospagos.proceso.certificacionservicio.index');
        } catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }

    }
    //--------------------------------------------------------------
      //-------------------------------------------------------------
    public function anular_certificacion(OpSolservicio $certificacionservicio)
    {
        try {
            if($certificacionservicio->sta_sol->value!='0'){
                    //----------------------------------------------------------------------------------------------
                    //      Valida que la Certificación este solo ingresada para poder anular
                    //----------------------------------------------------------------------------------------------
                    alert()->error('Error!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol. '. Con Estado Invalidos:'. $this->estado($certificacionservicio->sta_sol->value));
                    return redirect()->route('otrospagos.proceso.certificacionservicio.index');
            }else{
                //----------------------------------------------------------------------------------------------
                //        Actualiza los datos principales de anulación de la certificacion en la tabla principal
                //----------------------------------------------------------------------------------------------
                DB::connection('pgsql')->beginTransaction();
                $certificacionservicio->update([
                    'usua_anu'             =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                    'fec_anu'              => $this->fechaGuardar,
                    'sta_sol'              => '1'
                ]);
                alert()->success('¡Éxito!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol. '  Eliminada Sastifactoriamente');
                DB::connection('pgsql')->commit();
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
            }

     } catch(\Illuminate\Database\QueryException $e){
        DB::connection('pgsql')->rollBack();
        alert()->error('¡Transacción Fallida!', $e->getMessage());
        return redirect()->back()->withInput();
     }

    }
    //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function aprobar_certificacion(OpSolservicio $certificacionservicio)
    {
        try {

            if($certificacionservicio->sta_sol->value!='0'  && $certificacionservicio->sta_sol->value!='3'){
                //----------------------------------------------------------------------------------------------
                //      Valida que la Certificación este solo ingresada para poder anular
                //----------------------------------------------------------------------------------------------
                alert()->error('Error!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol. '. Con Estado Invalidos: '.$this->estado($certificacionservicio->sta_sol->value));
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
             }else{
                DB::connection('pgsql')->beginTransaction();
                //----------------------------------------------------------------------------------------------
                //        Actualiza los datos principales de anulación de la certificacion en la tabla principal
                //----------------------------------------------------------------------------------------------
                $certificacionservicio->update([
                    'usua_apr'             =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                    'fec_apr'              => $this->fechaGuardar,
                    'sta_sol'              => '2'
                ]);
                alert()->success('¡Éxito!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol. '  Aprobada Sastifactoriamente');
                DB::connection('pgsql')->commit();
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
        }
     } catch(\Illuminate\Database\QueryException $e){
        DB::connection('pgsql')->rollBack();
        alert()->error('¡Transacción Fallida!', $e->getMessage());
        return redirect()->back()->withInput();
     }

    }
    //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function  reversar_certificacion(OpSolservicio $certificacionservicio)
    {
        try {
            if($certificacionservicio->sta_sol->value!='2'){
                //----------------------------------------------------------------------------------------------
                //      Valida que la Certificación este solo aprobada para poder reversar
                //----------------------------------------------------------------------------------------------
                alert()->error('Error!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol. '. Con Estado Invalidos: '.$this->estado($certificacionservicio->sta_sol->value));
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
             }else{
                DB::connection('pgsql')->beginTransaction();
                //----------------------------------------------------------------------------------------------
                //        Actualiza los datos principales de anulación de la certificacion en la tabla principal
                //----------------------------------------------------------------------------------------------
                $certificacionservicio->update([
                    'usu_reverso_a'        =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                    'fech_reverso_a'       => $this->fechaGuardar,
                    'sta_sol'              => '3'
                ]);
                alert()->success('¡Éxito!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol. 'Reversada Sastifactoriamente');
                DB::connection('pgsql')->commit();
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
           }
     } catch(\Illuminate\Database\QueryException $e){
        DB::connection('pgsql')->rollBack();
        alert()->error('¡Transacción Fallida!', $e->getMessage());
        return redirect()->back()->withInput();
     }

    }
    //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function  comprometer_certificacion(OpSolservicio $certificacionservicio)
    {
        try {

            if($certificacionservicio->sta_sol->value!='2'   && $certificacionservicio->sta_sol->value!='5' ){
                //----------------------------------------------------------------------------------------------
                //      Valida que la Certificación este solo Aprobada para poder comprometer
                //----------------------------------------------------------------------------------------------
                alert()->error('Error!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol. '. Con Estado Invalidos: '.$this->estado($certificacionservicio->sta_sol->value));
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
            }else{
                        DB::connection('pgsql')->beginTransaction();
                        //-------------------------------------------------------------------------------------------------
                        //  Actualizar el status de la certificacion
                        //-------------------------------------------------------------------------------------------------
                        $certificacionservicio->update([
                            'usua_comp'            =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                            'fec_comp'             => $this->fechaGuardar,
                            'deposito_garantia'    => $certificacionservicio->deposito_garantia,
                            'sta_sol'              => '4'
                        ]);

                        //----------------------------------------------------------------------------
                        // Si la solicitud de Certificacion no posee factura se debe crear la
                        // solicitud de pago ingresando directamente en la tabla plan_solpago
                        // y buscando su correlativo en cxp_corrsolpago
                        //----------------------------------------------------------------------------
                        if($certificacionservicio->factura =='N'){
                            //----------------------------------------------------------------------------
                            //             BUSCAR EL CORRELATIVO DE LA SOLICITUD DE PAGOS
                            //----------------------------------------------------------------------------
                            $num_reg = Corrsolpago::query()->where('ano_pro',$this->anoPro)->where('flujo', 'PD')->first()->sol_pag?? 1;
                            //----------------------------------------------------------------------------
                            //                         Creación del Solicitud Pago
                            //-----------------------------------------------------------------------------
                            PlanSolpago::create([
                                'ano_pro'      => $this->anoPro,
                                'nro_sol'      =>'PD-'.$num_reg,
                                'fecha_sol'    => $this->fechaGuardar,
                                'cesion'       => 'N',
                                'concepto'     => 'CERTIFICACION DE PAGOS DIRECTO: '.$certificacionservicio->nro_sol." Año Solicitud: ".$certificacionservicio->ano_pro,
                                'nro_origen'   => $certificacionservicio->xnro_sol,
                                'nro_doc'      => 'N/A',
                                'mto_neto'     => $certificacionservicio->monto_neto,
                                'total_pagar'  => $certificacionservicio->monto_total,
                                'tabla_origen' => 'op_solservicio',
                                'sta_reg'      => '0',
                                'usuario'      =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                                'rif_ben'      => $certificacionservicio->rif_prov,
                                'ano_solicitud'=> $this->anoPro,
                                'ano_origen'   => $certificacionservicio->ano_pro,
                            ]);
                            //-----------------------------------------------------------------------------------------------
                            // Guarda Datos de LA Solicitud de Pago en la Certificacion de Servicios
                            //-----------------------------------------------------------------------------------------------
                            $certificacionservicio->update([
                                'ano_sol_pago'             =>  $this->anoPro,
                                'nro_sol_pago'             => 'PD-'.$num_reg
                            ]);
                            //-------------------------------------------------------------------------------------
                            //            Incrementar o Crear el correlativo si es un nuevo año Fiscal
                            //-------------------------------------------------------------------------------------
                            if ($num_reg ==1) {
                                Corrsolpago::create([
                                    'ano_pro'   =>  $this->anoPro,
                                    'sol_pag'   => $num_reg+1,
                                    'flujo'     => 'PD'  ]);
                             }else{
                                    Corrsolpago::where('flujo', 'PD')->where('ano_pro',  $this->anoPro,)->increment('sol_pag');
                             }
                        }else
                        {
                            //------------------------------------------------------------------------------------
                            //                          El proceso viene con Factura
                            //                  Insertando Información de las facturas (Si tiene)
                            //                 en la tablas de la factura	cxp_cabecera_facturas
                            //------------------------------------------------------------------------------------
                            $cabecerafactura=CxPCabeceraFactura::query()
                                            ->where('ano_doc',$certificacionservicio->ano_pro)
                                            ->where('nro_doc',$certificacionservicio->xnro_sol)->first();
                            //Valida que exista una factura registrada para actualizar los datos
                            //Esto aplica para los casos que se registro la factura y despues ha sido reversadas desde la certificación

                            if(!is_null($cabecerafactura)){
                                if ($cabecerafactura->statu_proceso =='2'){
                                    $cabecerafactura->update([
                                        'fondo'            => $certificacionservicio->fondo,
                                        'base_imponible'   => $certificacionservicio->base_imponible,
                                        'rif_prov'         => $certificacionservicio->rif_prov,
                                        'base_excenta'     => $certificacionservicio->base_exenta,
                                        'porcentaje_iva'   => $certificacionservicio->por_iva,
                                        'mto_nto'          => $certificacionservicio->monto_neto,
                                        'mto_iva'          => $certificacionservicio->monto_iva,
                                        'mto_tot'          => $certificacionservicio->monto_total,
                                        'por_anticipo'     => $certificacionservicio->por_anticipo,
                                        'monto_anticipo'   => $certificacionservicio->mto_ant,
                                        'statu_proceso'    =>'1',
                                        'tipo_pago'        => $certificacionservicio->tip_pag,
                                        'ano_pro'          => $this->anoPro,
                                        'deposito_garantia'=> $certificacionservicio->deposito_garantia
                                    ]);
                                }
                            }else{
                                    // Si no exist factura se ingresa un nuevo registro
                                    CxPCabeceraFactura::create([
                                        'ano_pro'          => $this->anoPro,
                                        'rif_prov'         => $certificacionservicio->rif_prov,
                                        'tipo_doc'         => '4',
                                        'nro_doc'          => $certificacionservicio->xnro_sol,
                                        'ano_doc'          => $certificacionservicio->ano_pro,
                                        'doc_asociado'     => $certificacionservicio->xnro_sol,
                                        'ano_doc_asociado' => $certificacionservicio->ano_pro,
                                        'ano_doc_asociado' => $certificacionservicio->ano_pro,
                                        'tipo_pago'        => $certificacionservicio->tip_pag,
                                        'fondo'            => $certificacionservicio->fondo,
                                        'base_imponible'   => $certificacionservicio->base_imponible,
                                        'base_excenta'     => $certificacionservicio->base_exenta,
                                        'porcentaje_iva'   => $certificacionservicio->por_iva,
                                        'mto_nto'          => $certificacionservicio->monto_neto,
                                        'mto_iva'          => $certificacionservicio->monto_iva,
                                        'mto_tot'          => $certificacionservicio->monto_total,
                                        'por_anticipo'     => $certificacionservicio->por_anticipo,
                                        'monto_anticipo'   => $certificacionservicio->mto_ant,
                                        'usuario'          => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                                        'fecha'            => $this->fechaGuardar,
                                        'fec_sta'          => $this->fechaGuardar,
                                        'monto_neto_doc'   => $certificacionservicio->monto_neto,
                                        'statu_proceso'    =>'1',
                                        'deposito_garantia'=> $certificacionservicio->deposito_garantia
                                    ]);
                                }
                                //----------------------------------------------------------------------------
                                // Si la solicitud de Certificacion es de provision
                                // se crea asiento contable del compromiso
                                // en esta seccion la cabecera del comprobante
                                //----------------------------------------------------------------------------
                                if($certificacionservicio->provision=='S'){
                                    //--------------------------------------------------------------------
                                    //  Obtener el último correlativo de la tabla comprobantesopen
                                    //         esto se debe actualizar por cada año fiscal
                                    //--------------------------------------------------------------------
                                    $nro = CorrelativoComprobante::query()->where('ano_pro', $certificacionservicio->ano_pro)->first()->corr_compro?? 1;
                                    Comprobanteopen::create([
                                        'nro_com'    => $nro,
                                        'num_mes'    => date('m', strtotime($this->fechaGuardar)),
                                        'tip_com'    => 'GA' ,
                                        'fec_com'    => $this->fechaGuardar,
                                        'fec_pos'    => $this->fechaGuardar,
                                        'usuario'    => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                                        'nro_sol'    => $certificacionservicio->xnro_sol,
                                        'tip_mov'    => '1',
                                        'ano_pro'    => $certificacionservicio->ano_pro,
                                        'origen'     => 'PD',
                                        'ano_sol'    => $certificacionservicio->ano_pro,
                                        'status'     => '0'
                                    ]);
                                    //-------------------------------------------------------------------------------------
                                    //            Incrementar o Crear el correlativo si es un nuevo año Fiscal
                                    //-------------------------------------------------------------------------------------
                                    if ($nro ==1) {
                                        CorrelativoComprobante::create([
                                            'ano_pro'               => $certificacionservicio->ano_pro,
                                            'correlativo'           => $nro+1]);
                                    }else{
                                        CorrelativoComprobante::where('ano_pro', $certificacionservicio->ano_pro)->increment('corr_compro');
                                    }


                               }// if($certificacionservicio->provision=='S')
                            }//Fin del else if($certificacionservicio->factura =='N')

                        //---------------------------------------------------------------------------------
                        //                        ASIENTO PRESUPUESTARIO
                        //---------------------------------------------------------------------------------
                        $datos["ano_pro"] = $this->anoPro;
                        $datos["tip_ope"] =10;// Compromiso|
                        $datos["sol_tip"] = "PD";
                        $datos["num_doc"] = $certificacionservicio->xnro_sol;
                        $datos["fec_pos"] = now()->format('Y-m-d');
                        $datos["fec_tra"] = $certificacionservicio->fec_emi->format('Y-m-d');
                        $datos["cod_ger"] = $certificacionservicio->cod_ger;
                        $datos["nom_elab"] = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
                        $datos["concepto"] = "COMPROMISO DE CERTIFICACIÓN DE PAGOS DIRECTOS";
                        $datos["nro_enl"] = NULL;
                        $datos["ord_pag"] = NULL;
                        $datos["fec_pag"] = NULL;
                        $datos["sta_reg"] = '1';
                        $datos["fec_sta"] = $this->fechaGuardar;
                        $datos["hor_sta"] = date('h:i:s');
                        $OpDetgastossolservicio=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacionservicio->id)->get();
                        $mto_cr=0;
                        $x=1;
                        foreach($OpDetgastossolservicio as $detalle){
                            //-----------------------------------------------------------------
                            /** Validar que no se puedan comprometa las partidas que esten en 0 **/
                            //------------------------------------------------------------------
                            $cod_comV ='';
                            if (!empty($detalle->mto_tra) && $detalle->mto_tra != "0,00"  && $detalle->mto_tra != "0.00"){
                                $cod_comV    = MaestroLey::generarCodCom($detalle->tip_cod, $detalle->cod_pryacc, $detalle->cod_obj,$detalle->gerencia,
                                                                        $detalle->unidad,$detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,
                                                                        $detalle->cod_sub);
                                $cod_com=$cod_comV;
                                if ($this->anoPro!=$certificacionservicio->ano_pro){
                                    $cod_com=$this->generarCentroCosto($detalle->tip_cod, $detalle->cod_pryacc, $detalle->cod_obj,$detalle->gerencia,
                                                                        $detalle->unidad,$detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,
                                                                        $detalle->cod_sub, $certificacionservicio->ano_pro);
                                    //Ocurrio un error armando el codcom
                                    if(empty($cod_com)){
                                        DB::connection('pgsql')->rollBack();
                                        return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                    }else{
                                        //Asignar el centro de costo y partida al arreglo
                                        $estructura=[];
                                        $estructura["cod_com"]=$this->obtener_cod_com($cod_com);
                                        $datos["tip_cod"]    = $estructura["tip_cod"];
                                        $datos["cod_pryacc"] = $estructura["cod_pryacc"];
                                        $datos["cod_obj"]    = $estructura["cod_obj"];
                                        $datos["unidad"]     = $estructura["unidad"];
                                        $datos["cod_par"]    = $estructura["cod_par"];
                                        $datos["cod_gen"]    = $estructura["cod_gen"];
                                        $datos["cod_esp"]    = $estructura["cod_esp"];
                                        $datos["cod_sub"]    = $estructura["cod_sub"];
                                    }

                                }else{
                                    $datos["tip_cod"]    = $detalle["tip_cod"];
                                    $datos["cod_pryacc"] = $detalle["cod_pryacc"];
                                    $datos["cod_obj"]    = $detalle["cod_obj"];
                                    $datos["unidad"]     = $detalle["unidad"];
                                    $datos["gerencia"]   = $detalle["gerencia"];
                                    $datos["cod_par"]    = $detalle["cod_par"];
                                    $datos["cod_gen"]    = $detalle["cod_gen"];
                                    $datos["cod_esp"]    = $detalle["cod_esp"];
                                    $datos["cod_sub"]    = $detalle["cod_sub"];
                                }
                                $datos["mto_tra"]    = $detalle->mto_tra;
                                $datos["ano_doc"]    = $certificacionservicio->ano_pro;
                                $datos["cod_com"]    = $cod_com;
                                if($cod_comV!=$cod_com){
                                    //-----------------------------------------------------------------
                                    //             Elimina la  Estructura de Gasto Vieja
                                    //------------------------------------------------------------------
                                    $OpDetgastossolservicio_borrar=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacionservicio->id)
                                                                                ->where('cod_com', $cod_comV) ;
                                    $OpDetgastossolservicio_borrar->delete();
                                    OpDetgastossolservicio::create([
                                        'ano_pro'       => $certificacionservicio->ano_pro,
                                        'xnro_sol'      => $certificacionservicio->xnro_sol,
                                        'nro_sol'       => $certificacionservicio->nro_sol,
                                        'gasto'         => $detalle->gasto,
                                        'tip_cod'       => $datos["tip_cod"],
                                        'cod_pryacc'    => $datos["cod_pryacc"],
                                        'cod_obj'       => $datos["cod_obj"],
                                        'gerencia'      => $datos["gerencia"],
                                        'unidad'        => $datos["unidad"],
                                        'cod_par'       => $datos["cod_par"],
                                        'cod_gen'       => $datos["cod_gen"],
                                        'cod_esp'       => $datos["cod_esp"],
                                        'cod_sub'       => $datos["cod_sub"],
                                        'descrip'       => $detalle->des_con,
                                        'mto_tra'       => $detalle->mto_tra,
                                        'cod_cta'       => $detalle->cod_cta,
                                        'grupo'         => 'PD',
                                        'cod_com'       => $cod_com,
                                        'op_solservicio_id' =>$certificacionservicio->id
                                    ]);

                                }//fin de  If($cod_comV!=$cod_com)
                                // 	Si es un deposito en garantia siempre debe ir por la cuenta de activo a pesar que presupuesto
                                // la deje marcada como gasto
                                if($detalle->gasto=='1'){
                                    $certificacionservicio->deposito_garantia=='S'?$gastos=0:$gastos=1;
                                }else{
                                    $gastos=0;
                                }
                                // Si la partida va por activo se debe modificar el gasto de la certificacion
                                if($detalle->gasto!='1'){
                                    $detalle->update(['gasto'=>$gastos]);
                                }
                                //----------------------------------------------------------------
                                //Si la certificacion  tiene factura solo se compromete
                                // en caso contrario se compromete y causa
                                //-----------------------------------------------------------------
                                if($certificacionservicio->factura =='S'){
                                    //-----------------------------------------------------------------
                                    //              Insertar Gastos de la Factura
                                    //-----------------------------------------------------------------
                                    CxPGastoFactura::create([
                                                            'ano_pro'          => $this->anoPro,
                                                            'rif_prov'         => $certificacionservicio->rif_prov,
                                                            'ano_doc_asociado' => $certificacionservicio->ano_pro,
                                                            'doc_asociado'     => $certificacionservicio->xnro_sol,
                                                            'tip_cod'          => $datos["tip_cod"],
                                                            'cod_pryacc'       => $datos["cod_pryacc"],
                                                            'cod_obj'          => $datos["cod_obj"],
                                                            'gerencia'         => $datos["gerencia"],
                                                            'unidad'           => $datos["unidad"],
                                                            'cod_par'          => $datos["cod_par"],
                                                            'cod_gen'          => $datos["cod_gen"],
                                                            'cod_esp'          => $datos["cod_esp"],
                                                            'cod_sub'          => $datos["cod_sub"],
                                                            'cod_com'          => $cod_com,
                                                            'gasto'            => $detalle->gasto,
                                                            'mto_tra'          => $detalle->mto_tra,
                                                            'causar'           => 0,
                                                            ]);

                                    // ----------------------------------------------
                                    // Validad Disponibilidad Presupuestaria
                                    //------------------------------------------------
                                    $res= $this->Validar_Monto_a_Procesar($this->anoPro,$detalle->mto_tra,$cod_com,'mto_dis');
                                    if(!$res){
                                        DB::connection('pgsql')->rollBack();
                                        return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                    }
                                    $res=  $this->ActualizarPreMaestro($this->anoPro,$cod_com,'10',$detalle->mto_tra);
                                    if(!$res){
                                        DB::connection('pgsql')->rollBack();
                                        return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                    }
                                     //-------------------------------------------------------------------------------
                                    //          armar comprobante contable de provisiones
                                   //--------------------------------------------------------------------------------
                                   if($certificacionservicio->provision=='S'){
                                        $result_cuenta_contable=$this->ObtenerDatosPartida($detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub);
                                        if($result_cuenta_contable->cta_gasto ==null){
                                            alert()->error('Error!', 'La Partida'.$cod_com.'NO tiene asociada  para Realizar el Asiento Contable.');
                                            return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                       }else{
                                            $mto_cr = $mto_cr + $detalle->mto_tra;
                                              ComprobanteOpenDet::create([
                                                    'nro_com'   => $nro,
                                                    'con_com'   => $x,
                                                    'cod_cta'   => $result_cuenta_contable->cta_gasto,
                                                    'cod_aux'   => $certificacionservicio->rif_prov,
                                                    'tip_doc'   => '41',
                                                    'nro_doc'   => $certificacionservicio->xnro_sol,
                                                    'fec_doc'   => $certificacionservicio->fec_emi,
                                                    'con_doc'   => 'ASIENTO DE CERTIFICACION DE PROVISION',
                                                    'tip_mto'   => 'DB',
                                                    'mto_doc'   => $detalle->mto_tra,
                                                    'ano_pro'   => $this->anoPro,
                                                    ]);
                                                if ($x==1){
                                                    if(trim($result_cuenta_contable->cta_provision)==''){
                                                        alert()->error('Error!', 'Error  La Partida no tiene Asociada la Cta. de Provisiones.Comuniquese con su Administrador de sistema');
                                                        return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                                    }
                                                }
                                                $x=$x+1;
                                        }
                                    }// if($certificacionservicio->provision=='S')
                             //	-----------------------------------------------------

                                 }else{
                                    //-------------------------------------------------
                                    // Validar que el causado no supere el compromiso
                                    //--------------------------------------------------
                                    $datos["concepto"] = "CAUSADO DIRECTO DE CERTIFICACION DE PAGOS DIRECTOS";
								    $datos["tip_ope"] = 30;//Causado Directo
                                    $res=$this->Validar_Causado($this->anoPro,$detalle->mto_tra,$cod_com);
                                    if(!$res){
                                        DB::connection('pgsql')->rollBack();
                                        return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                    }
                                    $res= $this->Validar_Monto_a_Procesar($this->anoPro,$detalle->mto_tra,$cod_com,'mto_dis');
                                    if(!$res){
                                        DB::connection('pgsql')->rollBack();
                                        return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                    }
                                    $this->ActualizarPreMaestro($this->anoPro,$cod_com,'30',$detalle->mto_tra);
                                    if(!$res){
                                        DB::connection('pgsql')->rollBack();
                                        return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                    }
                                 }
                                //----------------------------------------------------
                                // Valida que otro proceso no se ejecuto mientras se
                                //   esta actualizando la partida presupuestaria
                                //---------------------------------------------------

                                $res=$this->insert_cod($this->anoPro,$cod_com);
                                if(!$res){
                                    DB::connection('pgsql')->rollBack();
                                    return redirect()->route('otrospagos.proceso.certificacionservicio.index');

                                }

                                //----------------------------------------------
                                //Insert Generico que inserta en Pre_Movimiento
                                //----------------------------------------------
                                $res = $this->insert_preMovimientos($datos);
                                if(!$res){
                                    DB::connection('pgsql')->rollBack();
                                    return redirect()->route('otrospagos.proceso.certificacionservicio.index');

                                }
                                //-----------------------------------------------------------------
                                // libera la partida para que pueda ser utilizada por otro proceso
                                //------------------------------------------------------------------
                                $res =$this->delete_cod($this->anoPro,$cod_com);
                                if(!$res){
                                    DB::connection('pgsql')->rollBack();
                                    return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                }
                                if($certificacionservicio->factura =='N'){
                                    CxpDetGastosSolpago::create([
                                        'ano_pro'=> $this->anoPro,
                                        'ord_pag'=> 'PD-'.$num_reg,
                                        'tip_cod'          => $datos["tip_cod"],
                                        'cod_pryacc'       => $datos["cod_pryacc"],
                                        'cod_obj'          => $datos["cod_obj"],
                                        'gerencia'         => $datos["gerencia"],
                                        'unidad'           => $datos["unidad"],
                                        'cod_par'          => $datos["cod_par"],
                                        'cod_gen'          => $datos["cod_gen"],
                                        'cod_esp'          => $datos["cod_esp"],
                                        'cod_sub'          => $datos["cod_sub"],
                                        'cod_com'          => $cod_com,
                                        'mto_tra'          =>$detalle->mto_tra,
                                        'mto_sdo'          =>$detalle->mto_tra,
                                        'status_presu'     =>'2'
                                        ]);
                                        //------------------------------------------------------------------
                                        //              CREAR EL ASIENTO CONTABLE
                                        //------------------------------------------------------------------
                                        if( $detalle->gasto=='1'){
                                            // Si es un deposito en garantia siempre debe ir por la cuenta de activo a pesar que presupuesto
                                            // la deje marcada como gasto
                                            if($certificacionservicio->deposito_garantia=='S'){
                                                        $cuenta="cta_activo";
                                                        $descrip_cuenta="Cuenta de Activo";
                                            }else{
                                                        $cuenta="cta_gasto";
                                                        $descrip_cuenta="Cuenta de Gasto";
                                            }

                                        }else{
                                            $cuenta="cta_activo";
                                            $descrip_cuenta="Cuenta de Activo";
                                        }
                                        //---------------------------------------------------------------------------
                                        //          CREAR EL ASIENTO CONTABLE EN LAS TABLAS DE LA SOLICITUD
                                        //              DE PAGO YA QUE LA CERTIFICACION NO POSEE FACTURA
                                        //-----------------------------------------------------------------------------
                                        if(trim($detalle->cod_cta)!=''){
                                            CxpDetContableSolpago::create([
                                                'ano_pro'    => $this->anoPro,
                                                'ord_pag'    => 'PD-'.$num_reg,
                                                'nro_ren'    => '1',
                                                'cod_cta'    => $detalle->cod_cta,
                                                'tipo'       => 'DB',
                                                'monto'      => $detalle->mto_tra,
                                                ]);
                                         }else{
                                            alert()->error( "Error Validando Patida Presupuestaria [".$cod_com."].\\n Comuniquese con su Administrados de Sistema");
                                            DB::connection('pgsql')->rollBack();
                                            return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                         }
                                }


                             }//Fin de validar que el mto_tra de la partida sea diferente de 0
                             else{
                               //------------------------------------------------------------------
                                // Eliminar las estructuras de gastos cuyo monto es 0
                                //-----------------------------------------------------------------
                                $OpDetgastossolservicio_borrar=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacionservicio->id)
                                ->where('cod_com', $cod_comV) ;
                                 $OpDetgastossolservicio_borrar->delete();
                             }
                        }//  foreach($OpDetgastossolservicio as $detalle)
                        //-------------------------------------------------------------------------------
                        //          armar comprobante contable de provisiones el CR
                        //--------------------------------------------------------------------------------
                        if($certificacionservicio->provision=='S'){
                                ComprobanteOpenDet::create([
                                    'nro_com'   => $nro,
                                    'con_com'   => $x,
                                    'cod_cta'   => $result_cuenta_contable->cta_provision,
                                    'cod_aux'   => $certificacionservicio->rif_prov,
                                    'tip_doc'   => '41',
                                    'nro_doc'   => $certificacionservicio->xnro_sol,
                                    'fec_doc'   => $certificacionservicio->fec_emi,
                                    'con_doc'   => 'ASIENTO DE CERTIFICACION DE PROVISION',
                                    'tip_mto'   => 'CR',
                                    'mto_doc'   => $mto_cr,
                                    'ano_pro'   => $this->anoPro,
                                    ]);
                        }// if($certificacionservicio->provision=='S')
                        $certificacionservicio->sta_sol->value =='N'?
                        $msj = "Estructuras de gastos han sido Comprometida y Causada Exitosamente ":
                        $msj = "Estructuras de gastos han sido Comprometida Exitosamente ";
                        alert()->success('¡Éxito!', $msj );
                        DB::connection('pgsql')->commit();
                        return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                }
        }catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }

    }



 //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function  reverso_compromiso_certificacion(OpSolservicio $certificacionservicio)
    {
        try {
            if($certificacionservicio->sta_sol->value!='4'){
                //----------------------------------------------------------------------------------------------
                //      Valida que la Certificación este solo Aprobada para poder comprometer
                //----------------------------------------------------------------------------------------------
                alert()->error('Error!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol. '. Con Estado Invalidos: '.$this->estado($certificacionservicio->sta_sol->value));
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
            }else{
                        //----------------------------------------------------------------------------------------------
                        //Validar que no exista anticipo asignado, porque se debe eliminar primero el anticipo
                        //----------------------------------------------------------------------------------------------
                        if($certificacionservicio->por_anticipo !=0.00){
                            $pagoanticipo=CxPSolPagoAnticipoProv::where('status', '!=','5')
                            ->where('ano_sol', $certificacionservicio->ano_pro)
                            ->where('xnum_orden', $certificacionservicio->xnro_sol)->get();
                            if(!$pagoanticipo->isEmpty()){
                                alert()->error('Error!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol.' tiene Anticipo Asociadas.Favor Verifique');
                                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                            }
                        }
                        DB::connection('pgsql')->beginTransaction();
                        //-------------------------------------------------------------------------------------------------
                        //  Actualizar el status de la certificacion
                        //-------------------------------------------------------------------------------------------------
                        $certificacionservicio->update([
                            'usuario_reverso_c'            =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                            'fech_reverso_c'             =>'1900-01-01',
                            'ano_sol_pago'             =>  '1900',
                            'nro_sol_pago'             => '',
                            'sta_sol'              => '5'
                        ]);
                        if($certificacionservicio->factura =='N'){
                                $operacion=30; //Causado Directo
                            	//-------------------------------------------------------------------------
                                //                Cambia el status de la solicitud d pago a Reversada
                                //-------------------------------------------------------------------------
                                PlanSolpago::where('ano_pro', $certificacionservicio->ano_pro )
                                            ->where('nro_origen',   $certificacionservicio->xnro_sol)
                                            ->where('tabla_origen', 'op_solservicio')
                                            ->where('sta_reg', '0')
                                            ->update(['sta_reg' => '4']);
                                //-----------------------------------------------------------------------------------------------
                                // Limpia Datos de LA Solicitud de Pago en la Certificacion de Servicios
                                //-----------------------------------------------------------------------------------------------
                                $certificacionservicio->update([
                                    'ano_sol_pago'             =>  '',
                                    'nro_sol_pago'             => ''
                                ]);

                        }
                        else{
                            $operacion=10;// Compromiso
                         }
                        $datos["ano_pro"] = $this->anoPro;
                        $datos["sol_tip"] = "PD";
                        $datos["num_doc"] = $certificacionservicio->xnro_sol;
                        $datos["fec_pos"] = now()->format('Y-m-d');
                        $datos["nom_elab"] =  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
                        $datos["cod_ger"] = $certificacionservicio->cod_ger;
                        $datos["nro_enl"] = NULL;
                        $datos["ord_pag"] = NULL;
                        $datos["fec_pag"] = NULL;
                        $datos["sta_reg"] = '2';
                        $datos["fec_tra"] = $this->anoPro;
                        $datos["fec_sta"] = $this->anoPro;
                        $datos["hor_sta"] = now()->format('Y-m-d');

                        $OpDetgastossolservicio=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacionservicio->id);
                        foreach($OpDetgastossolservicio as $detalle){
                             //-----------------------------------------------------------------
                            /** Validar que no se puedan comprometa las partidas que esten en 0 **/
                            //------------------------------------------------------------------
                            $cod_comV ='';
                            if (!empty($detalle->mto_tra) && $detalle->mto_tra != "0,00"  && $detalle->mto_tra != "0.00"){
                                $cod_comV    = MaestroLey::generarCodCom($detalle->tip_cod, $detalle->cod_pryacc, $detalle->cod_obj,$detalle->gerencia,
                                                                        $detalle->unidad,$detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,
                                                                        $detalle->cod_sub);
                                $cod_com=$cod_comV;
                                if ($this->anoPro!=$certificacionservicio->ano_pro){
                                    $cod_com=$this->generarCentroCosto($detalle->tip_cod, $detalle->cod_pryacc, $detalle->cod_obj,$detalle->gerencia,
                                                                        $detalle->unidad,$detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,
                                                                        $detalle->cod_sub, $certificacionservicio->ano_pro);
                                    //Ocurrio un error armando el codcom
                                    if(empty($cod_com)){
                                        DB::connection('pgsql')->rollBack();
                                        return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                    }else{
                                        //Asignar el centro de costo y partida al arreglo
                                        $estructura=[];
                                        $estructura=$this->obtener_cod_com($cod_com);
                                        $datos["tip_cod"]    = $estructura["tip_cod"];
                                        $datos["cod_pryacc"] = $estructura["cod_pryacc"];
                                        $datos["cod_obj"]    = $estructura["cod_obj"];
                                        $datos["unidad"]     = $estructura["unidad"];
                                        $datos["cod_par"]    = $estructura["cod_par"];
                                        $datos["cod_gen"]    = $estructura["cod_gen"];
                                        $datos["cod_esp"]    = $estructura["cod_esp"];
                                        $datos["cod_sub"]    = $estructura["cod_sub"];
                                        $datos["mto_tra"]    = $detalle->mto_tra;
                                        $datos["ano_doc"]    = $certificacionservicio->ano_pro;
                                    }

                                }
                                if($cod_comV!=$cod_com){
                                    //-----------------------------------------------------------------
                                    //             Elimina la  Estructura de Gasto Vieja
                                    //------------------------------------------------------------------
                                    $OpDetgastossolservicio_borrar=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacionservicio->id)
                                                                                ->where('cod_com', $cod_comV) ;
                                    $OpDetgastossolservicio_borrar->delete();
                                    OpDetgastossolservicio::create([
                                        'ano_pro'       => $certificacionservicio->ano_pro,
                                        'xnro_sol'      => $certificacionservicio->xnro_sol,
                                        'nro_sol'       => $certificacionservicio->nro_sol,
                                        'gasto'         => $detalle->gasto,
                                        'tip_cod'       => $datos["tip_cod"],
                                        'cod_pryacc'    => $datos["cod_pryacc"],
                                        'cod_obj'       => $datos["cod_obj"],
                                        'gerencia'      => $datos["gerencia"],
                                        'unidad'        => $datos["unidad"],
                                        'cod_par'       => $datos["cod_par"],
                                        'cod_gen'       => $datos["cod_gen"],
                                        'cod_esp'       => $datos["cod_esp"],
                                        'cod_sub'       => $datos["cod_sub"],
                                        'descrip'       => $detalle->des_con,
                                        'mto_tra'       => $detalle->mto_tra,
                                        'cod_cta'       => $detalle->cod_cta,
                                        'grupo'         => 'PD',
                                        'cod_com'       => $cod_com,
                                        'op_solservicio_id' =>$certificacionservicio->id
                                    ]);

                                }//fin de  If($cod_comV!=$cod_com)
                                $datos["cod_com"]    = $cod_com;
                                if( $this->getCampo("factura")->valor=='S'){
                                    $datos["tip_ope"] = 20;//Reverso de  Compromiso|A|
                                    $datos["concepto"] = "REVERSO DE COMPROMISO DE CERTIFICACIÓN DE PAGOS DIRECTOS";
                                    $this->ActualizarPreMaestro($this->anoPro,$cod_com,'20',$detalle->mto_tra);
                                }else{
                                    $datos["concepto"] = "REVERSO DE CAUSADO DIRECTO DE CERTIFICACIÓN DE PAGOS DIRECTOS";
                                    $datos["tip_ope"] =40;//Causado Directo
                                    $this->ActualizarPreMaestro($this->anoPro,$cod_com,'40',$detalle->mto_tra);
                                }
                                 //----------------------------------------------------
                                // Valida que otro proceso no se ejecuto mientras se
                                //   esta actualizando la partida presupuestaria
                                //----------------------------------------------------
                                $res=$this->insert_cod($this->anoPro,$cod_com);
                                if(!$res){
                                    DB::connection('pgsql')->rollBack();
                                    return redirect()->route('otrospagos.proceso.certificacionservicio.index');

                                }

                                //----------------------------------------------
                                //Insert Generico que inserta en Pre_Movimiento
                                // se encuentra en utilsPHP/sacoGen.Script.php
                                //----------------------------------------------
                                $res = $this->insert_preMovimientos($datos);
                                if(!$res){
                                    DB::connection('pgsql')->rollBack();
                                    return redirect()->route('otrospagos.proceso.certificacionservicio.index');

                                }

                                //-----------------------------------------------------------------
                                // libera la partida para que pueda ser utilizada por otro proceso
                                //------------------------------------------------------------------
                                $res =$this->delete_cod($this->anoPro,$cod_com);
                                if(!$res){
                                    DB::connection('pgsql')->rollBack();
                                    return redirect()->route('otrospagos.proceso.certificacionservicio.index');
                                }
                                 //---------------------------------------------------------------
								 //  Buscar el Numero de enlace del registro que se creo como
								 //    Reverso del Compromiso o del causado directo
								 //---------------------------------------------------------------
                                 $num_reg =PreMovimiento::query()->where('ano_pro',$this->anoPro)
                                                           ->where('sol_tip', 'PD')
                                                          ->where('num_doc', $certificacionservicio->xnro_sol)
                                                          ->where('tip_ope',  $datos["tip_ope"])
                                                          ->where('sta_reg', '2')
                                                          ->where('cod_com', $cod_com)
                                                          ->where('ano_doc',  $certificacionservicio->ano_pro)
                                                          ->first()->num_reg;
                                //-----------------------------------------------------------------
                                //Cambiar el satus al primer registro del movimiento presupuestario
                                //-------------------------------------------------------------------
                                PreMovimiento::where('sol_tip', 'PD')
                                                ->where('num_doc', $certificacionservicio->xnro_sol)
                                                ->where('tip_ope',  $operacion)
                                                ->where('sta_reg', '1')
                                                ->where('cod_com', $cod_comV)
                                                ->where('ano_doc',  $certificacionservicio->ano_pro)
                                ->update(['nro_enl' => $num_reg, 'sta_reg' => '2']);

                                //-----------------------------------------------------------------
                                //           Actualizar Status de Cabecera Factura
                                //-------------------------------------------------------------------
                                CxPCabeceraFactura::where('rif_prov',  $certificacionservicio->rif_prov)
                                                ->where('doc_asociado', $certificacionservicio->xnro_sol)
                                                ->where('ano_doc_asociado',  $certificacionservicio->ano_pro)
                                ->update(['sta_reg' => '2']);
                                $cxp_gasto_factura= CxPGastoFactura::where('rif_prov',  $certificacionservicio->rif_prov)
                                                                        ->where('doc_asociado', $certificacionservicio->xnro_sol)
                                                                        ->where('ano_doc_asociado',  $certificacionservicio->ano_pro);
                                $cxp_gasto_factura->delete();
                        }//if (!empty($detalle->mto_tra) && $detalle->mto_tra != "0,00"  && $detalle->mto_tra != "0.00")
                }//foreach($OpDetgastossolservicio as $detalle)
                //-----------------------------------------
                // Asiento de Reverso de las Provisiones
                //------------------------------------------
                if($certificacionservicio->provision=='S'){
                    //--------------------------------------------------------------------
                    //  Obtener el último correlativo de la tabla comprobantesopen
                    //         esto se debe actualizar por cada año fiscal
                    //--------------------------------------------------------------------
                    $nro = CorrelativoComprobante::query()->where('ano_pro', $certificacionservicio->ano_pro)->first()->corr_compro?? 1;
                    //Crea el asiento de reveso
                    Comprobanteopen::create([
                        'nro_com'    => $nro,
                        'num_mes'    => date('m', strtotime($this->fechaGuardar)),
                        'tip_com'    => 'GA',
                        'fec_com'    => $this->fechaGuardar,
                        'fec_pos'    => $this->fechaGuardar,
                        'usuario'    => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                        'nro_sol'    => $certificacionservicio->xnro_sol,
                        'tip_mov'    => '1',
                        'ano_pro'    => $certificacionservicio->ano_pro,
                        'origen'     => 'PD',
                        'ano_sol'    => $certificacionservicio->ano_pro,
                        'status'     => '3'
                    ]);
                    //-------------------------------------------------------------------------------------
                    //            Incrementar o Crear el correlativo si es un nuevo año Fiscal
                    //-------------------------------------------------------------------------------------
                    if ($nro ==1) {
                        CorrelativoComprobante::create([
                            'ano_pro'               => $certificacionservicio->ano_pro,
                            'correlativo'           => $nro+1]);
                    }else{
                        CorrelativoComprobante::where('ano_pro', $certificacionservicio->ano_pro)->increment('corr_compro');
                    }
                    $comprobantereverso=ComprobanteOpenDet::query()->where('ano_sol', $certificacionservicio->ano_pro)
                                                                ->where('nro_sol', $certificacionservicio->xnro_sol)->get();
                    for ($x = 1 ;$x <=  $comprobantereverso->count(); $x++)  {
                        $comprobantereverso->tip_mto=='CR'?$t_mto = 'DB': $t_mto = 'CR';
                        ComprobanteOpenDet::create([
                        'nro_com'   => $nro,
                        'con_com'   => $x,
                        'cod_cta'   => $comprobantereverso->cod_cta,
                        'cod_aux'   => $$comprobantereverso->cod_aux,
                        'tip_doc'   => $comprobantereverso->tip_doc,
                        'nro_doc'   => $comprobantereverso->nro_doc,
                        'fec_doc'   => $comprobantereverso->fec_doc,
                        'con_doc'   => 'ASIENTO DE CERTIFICACION DE PROVISION',
                        'tip_mto'   => $t_mto,
                        'mto_doc'   => $comprobantereverso->mto_doc,
                        'ano_pro'   => $comprobantereverso->ano_pro,
                        ]);

                        }
                }// if($certificacionservicio->provision=='S')
                alert()->success('¡Éxito!', 'Certificación: '.$certificacionservicio->ano_pro."-". $certificacionservicio->xnro_sol. 'Reversada Sastifactoriamente');
                DB::connection('pgsql')->commit();
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
            } //if($certificacionservicio->sta_sol->value!='4')
        } catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }

    }
    //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function  estado($valor)
    {
        switch ($valor) {
            case "0":
                $descrip_estado = "Ingresada en Sistema";;
                break;
            case "1":
                $descrip_estado = "Anulada";;
                break;
            case "2":
                $descrip_estado = "Aprobada por Gerente de la Unidad Solicitante";;
                break;
            case "3":
                $descrip_estado = "Reversada por Gerente de la Unidad Solicitante";;
                break;
            case "4":
                $descrip_estado = "Comprometida Presupuestariamente";;
                break;
            case "5":
                $descrip_estado = "Reversada Presupuestariamente";;
                break;
            case "6":
                $descrip_estado = "Con Orden Impresa";;
                break;
        }
        return $descrip_estado;
    }

    public function print_certificacion_solicitud(Request $request)
{
    $PUNTODECIMAL=',';
            /*********************************************************************************************
             * 					Datos de la cabecera de la Solicitud de Pago
             ********************************************************************************************/

           $encab = PlanSolPago::with('beneficiario:rif_ben,nom_ben')
                               ->selectRaw("ano_pro, nro_sol, to_char(fecha_sol,'dd/mm/yyyy') as fecha, cesion,rif_ben, concepto, nro_origen,
                                            nro_doc, mto_neto, total_ded, total_pagar, cod_ger, cod_uni")
                               ->where('ano_origen',$request->ano_pro)
                               ->where('nro_origen',$request->xnro_sol)
                               ->get();


            $cont_enc = count($encab);

            // /*********************************************************************************************
            //  * 					Datos de los Asientos Presupuestarios
            //  ********************************************************************************************/

            $movPresupuestario = CxPDetGastosSolPago::query()
                                                    ->selectRaw("tip_cod,cod_pryacc,cod_obj,gerencia,unidad,cod_par,cod_gen,cod_esp,cod_sub,SUM(mto_tra) as mto_tra")
                                                    ->where('ano_pro',$request->ano_pro)
                                                    ->where('ord_pag',$request->xnro_sol)
                                                    ->groupBy('tip_cod', 'cod_pryacc', 'cod_obj', 'gerencia','unidad', 'cod_par', 'cod_gen', 'cod_esp', 'cod_sub','cod_com')
                                                    ->get();


            $cont_pres = count($movPresupuestario);
            // /*********************************************************************************************
            //  * 					Datos de los Asientos Contable
            //  ********************************************************************************************/



            $comprobanteContable = DB::select("SELECT  a.cod_cta,plancontable.nom_cta as nom_cta,tipo, monto
                            FROM cxp_detcontablesolpago as a
                            INNER JOIN plancontable ON (plancontable.cod_cta = a.cod_cta AND plancontable.ano_cta = a.ano_pro)
                            WHERE a.ord_pag= '$request->xnro_sol' AND	a.ano_pro = $request->ano_pro
                            ORDER BY a.tipo desc");
            // $comprobanteContable = $db->execSelect($conn,$query_asiento);

            // dd($c);
            $pdf = new Fpdf('p','mm','letter','true');
            $pdf->SetLeftMargin(5);
            $pdf->SetRightMargin(5);
            $pdf->AddPage("P");


            $cont_det = count($comprobanteContable);
           /**********************************************************************************************/
            $concepto = "PAGO POR CONCEPTO DE CERTIFICACION DE PAGO DIRECTO";
            /*******************************Datos del Detalle*********************************************/
            if ($cont_enc > 0 &&  $cont_pres >0 && $cont_det>0)
            {
                $ano_pro = $encab[0]->ano_pro;
                $pdf->Image('img/hidrobolivar.jpg',10,10,40,13,'JPG');
                $pdf->Image('img/logo_superior_derecho.png',198,4,10,10,'PNG');
                $pdf->SetFont('Arial','B',13);
                $pdf->SetY(15);
                $pdf->SetX(60);
                $pdf->Cell(85,4,'SOLICITUD DE PAGO',0,0,'C',0);
                $pdf->Header();
                $Y_Fields_Name_position = 15;
                $Posicion_Y = 85;
                $Y_Position = 157;
                $pdf->SetFillColor(235,235,235);
                $pdf->SetFont('Arial','B',9);
                $pdf->SetY(15);
                $pdf->SetX(165);
                $pdf->Cell(20,5,'Numero:',1,0,'L',1);
                $pdf->SetY(20);
                $pdf->SetX(165);
                $pdf->Cell(20,5,'Fecha:',1,0,'L',1);
                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',8);
                $pdf->SetY(15);
                $pdf->SetX(180);
                $pdf->Cell(30,5,$encab[0]->nro_sol,1,0,'L',1);
                $pdf->SetY(20);
                $pdf->SetX(180);
                $pdf->Cell(30,5,$encab[0]->fecha,1,0,'L',1);
                $pdf->Header();
                //linea 1
                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',10);
                $pdf->SetY($Y_Fields_Name_position + 10);
                $pdf->SetX(10);
                $pdf->Cell(200,5,'INFORMACION GENERAL DEL PAGO',1,0,'C',1);
                $pdf->Ln();

                $pdf->SetFillColor(235,235,235);
                $pdf->SetFont('Arial','B',9);
                $pdf->SetY($Y_Fields_Name_position + 15);
                $pdf->SetX(10);
                $pdf->Cell(115,5,'BENEFICIARIO',1,0,'C',1);
                $pdf->SetX(125);
                $pdf->Cell(45,5,'CESIONARIO DE CREDITO',1,0,'C',1);
                $pdf->SetX(170);
                $pdf->Cell(40,5,'RIF DEL BENEFICIARIO',1,0,'C',1);
                $pdf->Ln();
                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',8);
                $pdf->SetY($Y_Fields_Name_position + 20);
                $pdf->SetX(10);
                $pdf->Cell(115,5,utf8_decode($encab[0]->beneficiario->nom_ben),1,0,'L',1);
                $pdf->SetX(125);
                $pdf->Cell(45,5,'N',1,0,'C',1);
                $pdf->SetX(170);
                $pdf->Cell(40,5,utf8_decode($encab[0]->rif_ben),1,0,'L',1);
                $pdf->Ln();



                $pdf->SetFillColor(235,235,235);
                $pdf->SetFont('Arial','B',9);

                $pdf->SetY($Y_Fields_Name_position + 25);
                $pdf->SetX(10);
                $pdf->Cell(200,5,'CONCEPTO:',1,0,'L',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',8);
                $pdf->SetY($Y_Fields_Name_position + 30);
                $pdf->SetX(10);
                $pdf->MultiCell(200,5,$concepto,1,'L',0);
                $pdf->Ln();


                $pdf->SetY($Y_Fields_Name_position + 50);
                $pdf->SetFont('Arial','B',9);
                $pdf->SetFillColor(235,235,235);

                $pdf->SetX(10);
                $pdf->Cell(35,10,'CERTIFICACION',1,0,'C',1);
                $pdf->SetX(45);
                $pdf->Cell(35,10,'NRO DE VALUACION',1,0,'C',1);
                $pdf->SetX(80);
                $pdf->Cell(30,10,'NRO DE FACTURA',1,0,'C',1);
                $pdf->SetY(65);
                $pdf->SetX(110);
                $pdf->Cell(100,5,'MONTOS (BsF)',1,0,'C',1);
                $pdf->SetY(70);
                $pdf->SetX(110);
                $pdf->Cell(30,5,'NETO',1,0,'C',1);
                $pdf->SetX(140);
                $pdf->Cell(30,5,'DEDUCCIONES',1,0,'C',1);
                $pdf->SetX(170);
                $pdf->Cell(40,5,'TOTAL A PAGAR',1,0,'C',1);
                $pdf->Ln();


                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',8);

                $pdf->SetY($Y_Fields_Name_position + 60);
                $pdf->SetX(10);
                $pdf->Cell(35,5,$request->xnro_sol,1,0,'L',1);
                $pdf->SetX(45);
                $pdf->Cell(35,5,'',1,0,'C',1);
                $pdf->SetX(80);
                $pdf->Cell(30,5,'',1,0,'L',1);
                $pdf->SetX(110);
                $pdf->Cell(30,5,$this->formatNumber($encab[0]->mto_neto,2,",",$PUNTODECIMAL,"("),1,0,'R',1);
                $pdf->SetX(140);
                $pdf->Cell(30,5,'0',1,0,'R',1);
                $pdf->SetX(170);
                $pdf->Cell(40,5,$this->formatNumber($encab[0]->total_pagar,2,",",$PUNTODECIMAL,"("),1,0,'R',1);
                $pdf->Ln();

                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',10);
                $pdf->SetY($Y_Fields_Name_position + 70);
                $pdf->SetX(10);
                $pdf->Cell(200,5,'CONTROL PRESUPUESTARIO',1,0,'C',1);
                $pdf->Ln();

                $pdf->SetY($Y_Fields_Name_position + 75);
                $pdf->SetFont('Arial','B',9);
                $pdf->SetFillColor(235,235,235);

                $pdf->SetX(10);
                $pdf->Cell(10,5,'TP',1,0,'C',1);
                $pdf->SetX(20);
                $pdf->Cell(10,5,'P/A',1,0,'C',1);
                $pdf->SetX(30);
                $pdf->Cell(12,5,'OBJ',1,0,'C',1);
                $pdf->SetX(42);
                $pdf->Cell(12,5,'GCIA',1,0,'C',1);
                $pdf->SetX(54);
                $pdf->Cell(12,5,'UNI',1,0,'C',1);
                $pdf->SetX(66);
                $pdf->Cell(10,5,'PA',1,0,'C',1);
                $pdf->SetX(76);
                $pdf->Cell(10,5,'GN',1,0,'C',1);
                $pdf->SetX(86);
                $pdf->Cell(12,5,'ESP',1,0,'C',1);
                $pdf->SetX(98);
                $pdf->Cell(12,5,'SUB',1,0,'C',1);
                $pdf->SetX(110);
                $pdf->Cell(40,5,'MTO COMPROMETIDO',1,0,'C',1);
                $pdf->SetX(150);
                $pdf->Cell(30,5,'MTO CAUSADO',1,0,'C',1);
                $pdf->SetX(180);
                $pdf->Cell(30,5,'MTO A PAGAR',1,0,'C',1);
                $pdf->Ln();
                $Y_Pie = 95;
                $pdf->SetFont('Arial','',8);
                $pdf->SetFillColor(255,255,255);

                for ($i=0; $i<12;$i++)
                    {
                        $Y_Pie = 95 + 5;
                        $pdf->SetX(10);
                        if ($i < $cont_pres)
                        {
                            $pdf->Cell(10,5,$movPresupuestario[$i]->tip_cod,1,0,'C',1);
                            $pdf->SetX(20);
                            $pdf->Cell(10,5,$movPresupuestario[$i]->cod_pryacc,1,0,'C',1);
                            $pdf->SetX(30);
                            $pdf->Cell(12,5,$movPresupuestario[$i]->cod_obj,1,0,'C',1);
                            $pdf->SetX(42);
                            $pdf->Cell(12,5,$movPresupuestario[$i]->gerencia,1,0,'C',1);
                            $pdf->SetX(54);
                            $pdf->Cell(12,5,$movPresupuestario[$i]->unidad,1,0,'C',1);
                            $pdf->SetX(66);
                            $pdf->Cell(10,5,$movPresupuestario[$i]->cod_par,1,0,'C',1);
                            $pdf->SetX(76);
                            $pdf->Cell(10,5,$movPresupuestario[$i]->cod_gen,1,0,'C',1);
                            $pdf->SetX(86);
                            $pdf->Cell(12,5,$movPresupuestario[$i]->cod_esp,1,0,'C',1);
                            $pdf->SetX(98);
                            $pdf->Cell(12,5,$movPresupuestario[$i]->cod_sub,1,0,'C',1);
                            $pdf->SetX(110);
                            $pdf->Cell(40,5,$this->formatNumber($movPresupuestario[$i]->mto_tra,2,",",$PUNTODECIMAL,"("),1,0,'R',1);
                            $pdf->SetX(150);
                            $pdf->Cell(30,5,$this->formatNumber($movPresupuestario[$i]->mto_tra,2,",",$PUNTODECIMAL,"("),1,0,'R',1);
                            $pdf->SetX(180);
                            $pdf->Cell(30,5,$this->formatNumber($movPresupuestario[$i]->mto_tra,2,",",$PUNTODECIMAL,"("),1,0,'R',1);
                            $pdf->Ln();
                        }
                        else
                        {
                            $pdf->Cell(10,5,'',1,0,'C',1);
                            $pdf->SetX(20);
                            $pdf->Cell(10,5,'',1,0,'C',1);
                            $pdf->SetX(30);
                            $pdf->Cell(12,5,'',1,0,'C',1);
                            $pdf->SetX(42);
                            $pdf->Cell(12,5,'',1,0,'C',1);
                            $pdf->SetX(54);
                            $pdf->Cell(12,5,'',1,0,'C',1);
                            $pdf->SetX(66);
                            $pdf->Cell(10,5,'',1,0,'C',1);
                            $pdf->SetX(76);
                            $pdf->Cell(10,5,'',1,0,'C',1);
                            $pdf->SetX(86);
                            $pdf->Cell(12,5,'',1,0,'C',1);
                            $pdf->SetX(98);
                            $pdf->Cell(12,5,'',1,0,'C',1);
                            $pdf->SetX(110);
                            $pdf->Cell(40,5,'',1,0,'C',1);
                            $pdf->SetX(150);
                            $pdf->Cell(30,5,'',1,0,'C',1);
                            $pdf->SetX(180);
                            $pdf->Cell(30,5,'',1,0,'C',1);
                            $pdf->Ln();
                        }

                    }


                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',10);
                $pdf->SetY($Y_Fields_Name_position + 145);
                $pdf->SetX(10);
                $pdf->Cell(200,5,'CONTABILIDAD',1,0,'C',1);
                $pdf->Ln();



                $pdf->SetY($Y_Fields_Name_position + 150);
                $pdf->SetFont('Arial','B',9);
                $pdf->SetFillColor(235,235,235);

                $pdf->SetX(10);
                $pdf->Cell(35,5,'CODIGO CONTABLE',1,0,'C',1);
                $pdf->SetX(45);
                $pdf->Cell(135,5,'DESCRIPCION',1,0,'C',1);
                $pdf->SetX(180);
                $pdf->Cell(30,5,'TOTAL A PAGAR',1,0,'C',1);
                $pdf->Ln();
                $pdf->SetFont('Arial','',8);
                $pdf->SetFillColor(255,255,255);
                $j=0;
                $cadena	= strlen($comprobanteContable[$j]->nom_cta);
                $ancho_fila=5;
                $num_filas = 12;
                for ($j=0; $j<$num_filas;$j++)
                {
                    if ($j < $cont_det)
                    {
                        if (strlen($comprobanteContable[$j]->nom_cta) > 75)
                        {
                            $ancho_fila =	5 * 2;
                            $num_filas-- ;
                        }
                        else
                        {
                            $ancho_fila=5;
                        }
                    if ($j == 0)
                        {
                            $Y_Pie = 150 + 5;
                            $pdf->SetY($Y_Fields_Name_position + $Y_Pie);
                            $pdf->SetX(10);
                            $pdf->Cell(35,$ancho_fila,utf8_decode($comprobanteContable[$j]->cod_cta),1,0,'L',0);
                            $pdf->SetX(45);
                            $pdf->MultiCell(135,5,utf8_decode($comprobanteContable[$j]->nom_cta),1,'L',0);
                            $pdf->SetX(180);
                            if ($comprobanteContable[$j]->tipo == 'DB')
                            {
                                $pdf->SetY($Y_Fields_Name_position + $Y_Pie );
                                $pdf->SetX(180);
                                $pdf->Cell(30,$ancho_fila,$this->formatNumber($comprobanteContable[$j]->monto,2,",",$PUNTODECIMAL,"("),1,0,'R',0);
                            }
                            else
                            {
                                $pdf->SetY($Y_Fields_Name_position + $Y_Pie );
                                $pdf->SetX(180);
                                $pdf->Cell(30,$ancho_fila,'('.$this->formatNumber($comprobanteContable[$j]->monto,2,",",$PUNTODECIMAL,"(").')',1,0,'R',0);
                            }
                            $ultimo_ancho = $ancho_fila;
                        }
                        if ($j != 0)
                        {
                            $Y_Pie = $Y_Pie + $ultimo_ancho;
                            $pdf->SetY($Y_Fields_Name_position + $Y_Pie);
                            $pdf->SetX(10);
                            $pdf->Cell(35,$ancho_fila,utf8_decode($comprobanteContable[$j]->cod_cta),1,0,'L',0);
                            $pdf->SetX(45);
                            $pdf->MultiCell(135,5,utf8_decode($comprobanteContable[$j]->nom_cta),1,'L',0);
                            $pdf->SetX(180);

                            if ($comprobanteContable[$j]->tipo == 'DB')
                            {
                                $pdf->SetY($Y_Fields_Name_position + $Y_Pie );
                                $pdf->SetX(180);
                                $pdf->Cell(30,$ancho_fila,$this->formatNumber($comprobanteContable[$j]->monto,2,",",$PUNTODECIMAL,"("),1,0,'R',0);
                            }
                            else
                            {
                                $pdf->SetY($Y_Fields_Name_position + $Y_Pie );
                                $pdf->SetX(180);
                                $pdf->Cell(30,$ancho_fila,'('.$this->formatNumber($comprobanteContable[$j]->monto,2,",",$PUNTODECIMAL,"(").')',1,0,'R',0);
                            }
                            $ultimo_ancho = $ancho_fila;
                        }

                        $pdf->Ln();
                    }
                    else
                    {
                        $pdf->SetX(10);
                        $pdf->Cell(35,5,'',1,0,'C',1);
                        $pdf->SetX(45);
                        $pdf->Cell(135,5,'',1,0,'C',1);
                        $pdf->SetX(180);
                        $pdf->Cell(30,5,'',1,0,'C',1);
                        $pdf->Ln();
                    }

                }


                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',10);
                $pdf->SetY($Y_Fields_Name_position + 220);
                $pdf->SetX(10);
                $pdf->Cell(200,5,'CONFORMACION',1,0,'C',1);
                $pdf->Ln();

                $Y_Pie = 240;
                //firmas y aprobaciones
                $pdf->SetFillColor(235,235,235);
                $pdf->SetFont('Arial','B',9);

                $pdf->SetY($Y_Pie);
                $pdf->SetX(10);
                $pdf->Cell(40,5,'UNIDAD TRAMITADORA',1,0,'C',1);
                $pdf->SetX(50);
                $pdf->Cell(60,5,'REGISTRO Y CONTROL DEL GASTO',1,0,'C',1);
                $pdf->SetX(110);
                $pdf->Cell(40,5,'CONTABILIDAD',1,0,'C',1);
                $pdf->SetX(150);
                $pdf->Cell(60,5,'ADMINISTRACION Y FINANZAS',1,0,'C',1);
                $pdf->Ln();
                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',8);
                $Y_Pie = 245;
                $pdf->SetY($Y_Pie);
                $pdf->SetX(10);
                $pdf->Cell(40,13,'',1,0,'C',1);
                $pdf->SetX(50);
                $pdf->Cell(60,13,'',1,0,'C',1);
                $pdf->SetX(110);
                $pdf->Cell(40,13,'',1,0,'C',1);
                $pdf->SetX(150);
                $pdf->Cell(60,13,'',1,0,'C',1);
                $pdf->Ln();

                header("Content-type: application/pdf");
                $pdf->Output();
                exit();
            }
             else
            {
                if($cont_enc==0){
                    alert()->warning('No Existe Datos en la Cabecera de la Solicitud de Pago');
                }else{
                    if($cont_pres ==0){
                        alert()->warning('No Existe Datos del Gasto en la Solicitud de Pago');
                    }else{
                        alert()->warning('No Existe Datos Contables de la Solicitud de Pago');
                    }
                }
                return redirect()->route('otrospagos.proceso.certificacionservicio.index');
            }
}
public function print_certificacion(Request $request)
{
        //-------------------------------------------------------------------------------------------
        //                       Encabezado de La Certificacion de Pagos Directos
        //-------------------------------------------------------------------------------------------
       //
        $datos_sol = OpSolservicio::with('gerencias:cod_ger,des_ger','beneficiario:rif_ben,nom_ben,direccion,telf')
                                   ->where('ano_pro',$request->ano_pro)
                                   ->where('xnro_sol',$request->xnro_sol)
                                   ->selectRaw("to_char(fec_emi,'dd-mm-yyyy') as fec_emi,nro_sol,cod_ger,rif_prov,
                                                pto_cta,TO_CHAR(fec_pto,'dd/mm/yyyy') as fec_pto,
                                                monto_iva,monto_neto,monto_total,observaciones,tiempo_entrega,certificados,lugar_entrega,
                                                forma_pago,flete,motivo,sta_sol,xnro_sol,por_iva,provision")
                                   ->get();

        $cont_enc=count($datos_sol);

        //-------------------------------------------------------------------------------------------
        //                       Detalle de La Certificacion de Pagos Directos
        //-------------------------------------------------------------------------------------------
        $detalle_sol =  DB::Select("SELECT DISTINCT des_con,cantidad,cos_uni,cos_tot,sal_cau,
                    to_char(op_detgastossolservicio.cod_par,'FM00') as cod_partida,
                        to_char(op_detgastossolservicio.cod_gen,'FM00') as cod_generica,
                        to_char(op_detgastossolservicio.cod_esp,'FM00') as cod_especifica,
                        to_char(op_detgastossolservicio.cod_sub,'FM00') as cod_sub
                        FROM op_solservicio
                        INNER JOIN op_detsolservicio ON(op_solservicio.ano_pro=op_detsolservicio.ano_pro and
                                                        op_solservicio.xnro_sol=op_detsolservicio.xnro_sol and
                                                        op_solservicio.nro_sol=op_detsolservicio.nro_sol)
                        INNER JOIN op_conceptos ON(op_conceptos.cod_con=op_detsolservicio.cod_prod)
                        INNER JOIN op_detgastossolservicio ON(op_solservicio.ano_pro=op_detgastossolservicio.ano_pro and
                                                                op_solservicio.xnro_sol=op_detgastossolservicio.xnro_sol and
                                                                op_solservicio.nro_sol=op_detgastossolservicio.nro_sol)
                        WHERE op_solservicio.ano_pro = " . $request->ano_pro. "
                        AND op_solservicio.xnro_sol = '" . $request->xnro_sol. "'
                        AND to_char(op_detgastossolservicio.cod_par, '99') || to_char(op_detgastossolservicio.cod_gen, '99') ||
                        to_char(op_detgastossolservicio.cod_esp, '99') || to_char(op_detgastossolservicio.cod_sub, '99') NOT IN
                        (SELECT to_char(cod_pari, '99') || to_char(cod_geni, '99') || to_char(cod_espi, '99') || to_char(cod_subi, '99')
                        FROM registrocontrol WHERE ano_pro = ". $request->ano_pro.")
                        ");


        $cont_det=count($detalle_sol);

        //-------------------------------------------------------------
        // Nombre de la persona que creo la soilcitud
        //-------------------------------------------------------------
        $detalle_crear =OpSolservicio::query()
                     ->join('usuarios', 'op_solservicio.usuario', '=', 'usuarios.usuario')
                     ->where('ano_pro',$request->ano_pro)
                     ->where('xnro_sol',$request->xnro_sol)
                     ->select('usuarios.nombre')
                     ->get();

        //-------------------------------------------------------------
        // Nombre de la persona que Aprobo la soilcitud
        //-------------------------------------------------------------
        $detalle_aprobar = OpSolservicio::query()
                        ->join('usuarios', 'op_solservicio.usua_apr', '=', 'usuarios.usuario')
                        ->where('ano_pro',$request->ano_pro)
                        ->where('xnro_sol',$request->xnro_sol)
                        ->select('usuarios.nombre')
                        ->get();

        //-------------------------------------------------------------
        // Nombre de la persona que Compromete la soilcitud
        //-------------------------------------------------------------
        $detalle_compromete = OpSolservicio::query()
                        ->join('usuarios', 'op_solservicio.usua_comp', '=', 'usuarios.usuario')
                        ->where('ano_pro',$request->ano_pro)
                        ->where('xnro_sol',$request->xnro_sol)
                        ->select('usuarios.nombre')
                        ->get();

        //--------------------------------------------------------------------------------------------
        //                                Datos de la Empresa
        //--------------------------------------------------------------------------------------------
        $datos_empresa = DatosEmpresa::query()
                                     ->where('cod_empresa','01')
                                     ->get();

        if ($cont_enc > 0 &&  $cont_det >0)
        {
            // dd($c);
            $pdf = new Fpdf('p','mm','letter','true');
            $pdf->SetLeftMargin(5);
            $pdf->SetRightMargin(5);
            $pdf->AddPage("1");


            //Pintar encabezado
            $pdf->Image('img/logo_superior_izquierdo.png',10,13,30,12);
            $pdf->Image('img/logo_superior_derecho.png', 260,9,15,15,'PNG');
            //  $pdf->Image('images/logo_superior_centro.png', 80,8,60,8,'PNG');
            /*Imagenes pie*/
            $pdf->Image('img/logo_inferior_izquierdo.png', 5,190,15,15,'PNG');
            $pdf->Image('img/logo_inferior_centro.png',130,186,30,8,'PNG');
            $pdf->Image('img/logo_inferior_derecho.png', 260,190,10,15,'PNG');

            if ($datos_sol[0]->provision=='S'){
                $titulo='CERTIFICACION DE PAGO DIRECTO (PROVISION)';
            }else{
                $titulo='CERTIFICACION DE PAGO DIRECTO';
            }

            $pdf->SetFont('Arial','B',15);
            $pdf->SetY(25);
            $pdf->SetX(80);
            $pdf->Cell(85,4,$titulo,0,0,'C',0);

            // control y vigencia de registros
            $pdf->SetFont('Arial','B',6);
            $pdf->SetY(10);
            $pdf->SetX(198);
            $pdf->Cell(60,4,'Codigo: F-AF-033',0,0,'R',0);

            $pdf->SetFont('Arial','B',6);
            $pdf->SetY(12);
            $pdf->SetX(198);
            $pdf->Cell(60,4,'Vigencia: 24/01/2018',0,0,'R',0);

            $pdf->SetFont('Arial','B',6);
            $pdf->SetY(14);
            $pdf->SetX(198);
            $pdf->Cell(60,4,'Revision: 2',0,0,'R',0);

            $pdf->Header();

            //Fields Name position
            $Y_Fields_Name_position = 25;
            //Table position, under Fields Name
            $Posicion_Y = 85;
            $Y_Position = 157;

            /***********************************************************/
            /*******************Encabezado de la O/C********************/
            /***********************************************************/
                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',9);

                $pdf->SetY($Y_Fields_Name_position);
                $pdf->SetX(198);
                $pdf->Cell(50,5,utf8_decode('Número Certificación'),1,0,'C',1);
                $pdf->SetX(248);
                $pdf->Cell(30,5,'Fecha',1,0,'C',1);
                $pdf->Ln();

                $pdf->SetFillColor(255,255,255);

                $pdf->SetY($Y_Fields_Name_position + 5);
                $pdf->SetX(198);
                $pdf->Cell(50,5,$datos_sol[0]->xnro_sol,1,0,'C',1);
                $pdf->SetX(248);
                $fecha_emi=Carbon::parse($datos_sol[0]->fec_emi)->format('d/m/Y');
                $pdf->Cell(30,5,$fecha_emi,1,0,'C',1);

                //linea 1
                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',9);

                $pdf->SetY($Y_Fields_Name_position + 10);
                $pdf->SetX(2);
                $pdf->Cell(196,5,'Gerencia Solicitante',1,0,'C',1);
                $pdf->SetX(198);
                $pdf->Cell(80,5,'Status de Certificacion',1,0,'C',1);
                $pdf->Ln();
                $estado = '';

                switch ($datos_sol[0]->sta_sol->value)
                      {
                        case '0':
                         $estado='Ingresada en Sistema';
                         break;
                        case '1':
                         $estado='Anulada';
                         break;
                        case '2':
                         $estado='Aprobada por Gerente de la Unidad Solicitante';
                         break;
                         case '3':
                         $estado='Reversada por Gerente de la Unidad Solicitante';
                         break;
                        case '4':
                         $estado='Comprometida Presupuestariamente';
                         break;
                        case '5':
                         $estado='Reversada Presupuestariamente';
                         break;
                        case '6':
                         $estado='Con Orden Impresa';
                         break;
                }
                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',8);
                $pdf->SetY($Y_Fields_Name_position + 15);
                $pdf->SetX(2);
                $pdf->Cell(196,5,utf8_decode($datos_sol[0]->gerencias->des_ger),1,0,'C',1);
                $pdf->SetX(198);
                $pdf->Cell(80,5,$estado,1,0,'C',1);
                $pdf->Ln();
                /***********************************************************/
                //linea 2
                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',9);

                $pdf->SetY($Y_Fields_Name_position + 20);
                $pdf->SetX(2);
                $pdf->Cell(276,5,'Datos del Proveedor.',1,0,'C',1);
                $pdf->Ln();

                $pdf->SetFillColor(235,235,235);

                $pdf->SetY($Y_Fields_Name_position + 25);
                $pdf->SetX(2);
                $pdf->Cell(25,5,'Nombre',1,0,'C',1);
                $pdf->SetX(172);
                $pdf->Cell(26,5,'Telefono',1,0,'C',1);
                $pdf->SetX(228);
                $pdf->Cell(20,5,'Rif',1,0,'C',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',8);
                $pdf->SetX(27);
                $pdf->Cell(150,5,utf8_decode($datos_sol[0]->beneficiario->nom_ben),1,0,'C',1);
                $pdf->SetX(198);
                $pdf->Cell(30,5,utf8_decode($datos_sol[0]->beneficiario->telf),1,0,'L',1);
                $pdf->SetX(248);
                $pdf->Cell(30,5,utf8_decode($datos_sol[0]->beneficiario->rif_ben),1,0,'L',1);
                $pdf->Ln();

                /***********************************************************/
                //linea 3
                $pdf->SetFillColor(235,235,235);
                $pdf->SetFont('Arial','B',9);

                $pdf->SetY($Y_Fields_Name_position + 30);
                $pdf->SetX(2);
                $pdf->Cell(25,5,'Direccion',1,0,'C',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',8);
                $pdf->SetX(27);
                $pdf->Cell(251,5,utf8_decode($datos_sol[0]->beneficiario->direccion),1,0,'L',1);
                $pdf->Ln();
                /***********************************************************/
                //linea 4 - detalle de la O/C
                $pdf->SetY($Y_Fields_Name_position + 35);
                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',9);

                $pdf->SetX(2);
                $pdf->Cell(276,5,'DESCRIPCION DE LA COMPRA',1,0,'C',1);
                $pdf->Ln();

                $pdf->SetFillColor(235,235,235);

                $pdf->SetX(2);
                $pdf->Cell(8,5,'Item',1,0,'C',1);
                $pdf->SetX(10);
                $pdf->Cell(25,5,'Partida Pres.',1,0,'C',1);
                $pdf->SetX(35);
                $pdf->Cell(220,5,'Descripcion',1,0,'C',1);
                $pdf->SetX(250);
                $pdf->Cell(28,5,'Precio Total Bs.F',1,0,'C',1);
                $pdf->Ln();

                /***********************************************************/
                /*******************Detalle de la O/C***********************/
                /***********************************************************/
                 $cont_det=count($detalle_sol);
                for($i=0; $i<$cont_det; $i++){
                    $column_est	 = $detalle_sol[$i]->cod_partida."-".$detalle_sol[$i]->cod_generica."-".$detalle_sol[$i]->cod_especifica."-".$detalle_sol[$i]->cod_sub;
                    if ($i==22){
                        $pdf->AddPage("1");
                        $Posicion_Y =-195;
                        $X_inicio=5;
                    }
                    $pdf->SetFillColor(255,255,255);
                    $pdf->SetFont('Arial','',8);

                    $pdf->SetY($Posicion_Y - 15);
                    $pdf->SetX(2);
                    $pdf->SetAligns(array('C','C','L','C')); // Se establece la alineación de las columnas
                    $pdf->SetWidths(array(8,25,215,28));
                    $pdf->Row(array(trim($i+1),trim($column_est),trim($detalle_sol[$i]->des_con),trim(number_format($detalle_sol[$i]->sal_cau, 2, ',', '.'))),5);

                    $Posicion_Y = $Posicion_Y + 5;

                    $pdf->Ln();
                };
            //}
                $Y_Pie = 115;
                //montos
                $pdf->SetFillColor(235,235,235);
                $pdf->SetFont('Arial','B',9);

                $X_Pie = 201;
                $X1_Pie = 240;
                $pdf->SetY($Y_Pie);
                $pdf->SetX($X_Pie+12);
                $pdf->Cell(35,5,'Sub Total',1,0,'C',1);
                $pdf->SetY($Y_Pie + 5);
                $pdf->SetX($X_Pie+12);
                $pdf->Cell(35,5,'Total IVA  '. number_format($datos_sol[0]->por_iva, 2, ',', '.') . ' %',1,0,'C',1);
                $pdf->SetY($Y_Pie + 10);
                $pdf->SetX($X_Pie+12);
                $pdf->Cell(35,5,'MONTO EN Bs.F',1,0,'C',1);
                $pdf->SetY($Y_Pie + 5);
                $pdf->SetX(2);
                $pdf->Cell(60,10,'MONTO EN LETRAS:',1,0,'C',1);

                /***IMPRESION DE MONTO EN LETRAS SACOFIGO***/
                $letras=$this->convertirLetrasMonto($datos_sol[0]->monto_total,".",true);

                $pdf->SetFillColor(255,255,255);

                $X1_Pie = 230;
                $pdf->SetY($Y_Pie);
                $pdf->SetX($X1_Pie+18);
                $pdf->Cell(30,5,number_format($datos_sol[0]->monto_neto, 2, ',', '.'),1,0,'C',1);
                $pdf->SetY($Y_Pie + 5);
                $pdf->SetX($X1_Pie+18);
                $pdf->Cell(30,5,number_format($datos_sol[0]->monto_iva, 2, ',', '.'),1,0,'C',1);
                $pdf->SetY($Y_Pie + 10);
                $pdf->SetX($X1_Pie+18);
                $pdf->Cell(30,5,number_format($datos_sol[0]->monto_total, 2, ',', '.'),1,0,'C',1);
                $pdf->SetY($Y_Pie + 5);
                $pdf->SetX(62);
                $pdf->Cell(151,10,'',1,0,'C',1);
                $pdf->SetY($Y_Pie + 6);
                $pdf->SetX(63);
                $pdf->MultiCell(149,4,'Bolivares Fuertes:  '.$letras,0,'J',1);

                /***********************************************************/
                /***********************************************************/
                $pdf->SetY(133);
                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',9);

                $pdf->SetX(2);
                $pdf->Cell(276,5,'MOTIVO',1,0,'C',1);
                $pdf->Ln();
                /***********************************************************/
                /************************Datos de Consulta******************/
                /***********************************************************/

                $pdf->SetFillColor(255,255,255);
                $pdf->SetY(138);
                $pdf->SetX(2);
                $pdf->SetAligns(array('L')); // Se establece la alineación de las columnas
                $pdf->SetWidths(array(276));
                $pdf->Row(array(trim($datos_sol[0]->motivo)),5);
                $pdf->Ln();

                $Y_Pie = 155;
                //firmas y aprobaciones
                $pdf->SetFillColor(205,205,205);
                $pdf->SetFont('Arial','B',8);
                $pdf->SetY($Y_Pie);
                $pdf->SetX(2);
                $pdf->Cell(90,4,'Elaborado por:',1,0,'C',1);
                $pdf->SetX(92);
                $pdf->Cell(90,4,'Aprobado por:',1,0,'C',1);
                $pdf->SetX(182);
                $pdf->Cell(96,4,'Comprometido por:',1,0,'C',1);
                $pdf->Ln();


                $pdf->SetY($Y_Pie + 4);
                $pdf->SetFillColor(255,255,255);
                $pdf->SetX(2);
                $pdf->Cell(90,20,'',1,0,'C',1);
                $pdf->SetX(92);
                $pdf->Cell(90,20,'',1,0,'C',1);
                $pdf->SetX(182);
                $pdf->Cell(96,20,'',1,0,'C',1);

                $pdf->SetY($Y_Pie + 24);
                $pdf->SetX(2);
                $pdf->Cell(90,7,'Nombre: '.utf8_decode($detalle_crear[0]->nombre),1,0,'L',1);
                $pdf->SetX(2);
                $pdf->SetX(92);
                $pdf->Cell(90,7,'Nombre: '.utf8_decode($detalle_aprobar[0]->nombre),1,0,'L',1);
                $pdf->SetX(182);
                $pdf->Cell(96,7,'Nombre: '.utf8_decode($detalle_compromete[0]->nombre),1,0,'L',1);
                //******************************************************************/
                /*******************Direccion Hidrobolivar**************************/
                //******************************************************************/
                 $pdf->SetFillColor(235,235,235);
                $pdf->SetFont('Arial','I',7);
                $pdf->SetY(193.8);
                $pdf->SetX(8);
                $direccion = $datos_empresa[0]->direccion."-".$datos_empresa[0]->telefono."-".$datos_empresa[0]->fax."-".$datos_empresa[0]->rif."-".$datos_empresa[0]->nit;
                $pdf->Cell(270,2,'El Logotipo de Certificacion esta relacionado con los Procesos de Captacion, Tratamiento y Almacenamiento en los Ac. Industrial, Pto. Ordaz y Macagua- San Felix de la Empresa HIDROBOLIVAR, C.A',0,0,'C',0);


                header("Content-type: application/pdf");
                $pdf->Output();
                exit();
            }
            else
            {
                if ($cont_enc > 0){
                     $html='<script language="javascript">alert("No se encontraron Datos para IMPRIMIR (Verifique Certificación). "); window.close(); </script>';
                    echo $html;
                }else{
                    $html='<script language="javascript">alert("No se encontraron Datos para IMPRIMIR (Verifique Detalle de Certificació)"); window.close(); </script>';
                    echo $html;
                }


            }

        }
    //-----------------------------------------------------------------------
    //    Funcion que lista todas las certificaciones Ingresadas en Sistema
    //-----------------------------------------------------------------------
    public function print_certificacion_servicios()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE CERTIFICACION DE SERVICIOS';
        $data['alineacion_columnas']		= array('C','C','C','C','C','C');//C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','20','30','50','50','30');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Año Proceso'),utf8_decode('Nro Solicitud'),utf8_decode('Fecha'),utf8_decode('Gerencia'),utf8_decode('Proveedor'),utf8_decode('Monto'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('ano_pro', 'xnro_sol','fec_emi',utf8_decode('des_ger'),utf8_decode('nom_prov'),'monto_total');
        $data['nombre_documento']			= 'listado_certificacionservicio.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = OpSolservicio::query()
                                                        ->select(
                                                            DB::raw("ano_pro"),
                                                            DB::raw("xnro_sol"),
                                                            DB::raw("des_ger"),
                                                            DB::raw("nom_prov"),
                                                            DB::raw("monto_total"),
                                                            DB::raw("fec_emi"))
                                                            ->where('grupo','=','PD')
                                                            ->join('gerencias AS g', 'g.cod_ger', '=', 'op_solservicio.cod_ger')
                                                            ->join('proveedores AS p', 'p.rif_prov', '=', 'op_solservicio.rif_prov')
                                                            ->orderby('ano_pro','desc')->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Certificación'));
        $this->pintar_listado_pdf($pdf,$data);
        exit;
    }

    //-----------------------------------------------------------------------
    //    Funcion que llama pantalla de parametros para reporte de certificación/contratos
    //-----------------------------------------------------------------------
    public function show_certificacion_contrato()
    {
        $gerencias =  Gerencia::query()
                              ->select('cod_ger','des_ger')
                              ->orderBy('des_ger', 'asc')
                              ->get();
        //return($gerencia);
        $proveedores = Proveedor::where('cod_edo','1')
                                    ->select('rif_prov','nom_prov')
                                    ->orderBy('nom_prov')
                                     ->get();
         //return($proveedor);
        $estados = DB::select("select '0' as valor, 'Ingresada en Sistema' descripcion
                              union
                              select '1' ,'Anulada'
                              union
                              select '2' ,'Aprobada por Gerente de la Unidad Solicitante'
                              union
                              select '3' ,'Reversada por Gerente de la Unidad Solicitante'
                              union
                              select '4' ,'Comprometida Presupuestariamente'
                              union
                              select '5' ,'Reversada Presupuestariamente'
                              union
                              select 	'6' ,'Con Orden Impresa'
                              order by 1");
        //return $estado;
        $anos = OpSolservicio::query()
                            ->select(DB::Raw("distinct ano_pro" ))
                            ->get();
        //return $ano;
        return view('administrativo.meru_administrativo.otrospagos.reporte.certificacion_contrato.show', compact('gerencias','proveedores','estados','anos'));
    }

    //-----------------------------------------------------------------------
    //    Funcion para generar reporte de certificación/contratos
    //-----------------------------------------------------------------------
    public function print_certificacion_contrato(Request $request)
    {

        if ($request->inicio != null || $request->fin != null){
            if ($request->inicio >  $request->fin){
                $msj = 'La fecha de inicio debe ser menor o igual a la fecha final.\\n Por Favor Intente de Nuevo.';
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                return redirect()->back()->withInput();
            }
        }
        if ($request->tipo==null){
            $msj = 'Debe seleccionar el tipo de salida.\\n Por Favor Intente de Nuevo.';
            alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
            return redirect()->back()->withInput();
        }

        $pdf = new Fpdf('L','mm','legal','true');
        // $pdf->titulo = $titulo;
        $pdf->SetLeftMargin(5);
        $pdf->SetRightMargin(2);
        $pdf->AddPage("L");

        if ($request->grupo!=''){
            if ($request->grupo=='PD') {
                $titulo = "LISTADO DE CERTIFICACIONES";
            }
            if ($request->grupo=='CO') {
                $titulo = "LISTADO DE CONTRATOS";
            }
        }else{
            $titulo = "LISTADO DE CERTIFICACIONES Y CONTRATOS";
         }



        $data = OpSolservicio::where('cod_ger', $request->gerencia);

        if  ($request->grupo != null){
            $data = $data->where('grupo', $request->grupo);
        }
        if ($request->estado != null){
            $data = $data->where('sta_sol',  $request->estado);
        }
        if ($request->beneficiario != null){
            $data = $data->where('rif_prov',  $request->beneficiario);
        }
        if ($request->provision != null){
            $data = $data->where('provision',  $request->provision);
        }
        if ($request->provision != null){
            $data = $data->where('provision',  $request->provision);
        }
        if ($request->ano_ord_com != null){
            $data = $data->where('ano_pro',  $request->ano_ord_com);
        }
        if ($request->inicio !='' && $request->fin !=''){
            $data = $data->whereBetween('fecha',[$request->inicio, $request->fin]);
        }

        $data =$data->get();

        $detalle_proveedor =  $data;
        //return $detalle_proveedor;
        $num_regis_det_prov = count($detalle_proveedor);//Total de Registros que arrojo la consulta



		$pdf->SetFont('Arial','B',12);
		$pdf->SetY(10);
		$pdf->Cell(0,5,$titulo,0,1,'C',0);
		if ($request->inicio != ''){
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(0,5,'DESDE EL '.\Carbon\Carbon::parse($request->inicio)->format('d/m/Y').' HASTA EL '.\Carbon\Carbon::parse($request->fin)->format('d/m/Y'),0,0,'C',0);
		}

		$pdf->Image('img/hidrobolivar.jpg',5,5,45,15,'JPG');
		$Y=5;
		$x=320;
		$pdf->SetFont('Arial','B',9);
		$pdf->SetXY($x,$Y);
		$pdf->Cell(5,5,"Fecha: ",0,0,'R');
		$pdf->Cell(21,5,date("d/m/Y"),0,0,'L');
		$Y+=4;
		$pdf->SetXY($x,$Y);
		$pdf->Cell(5,5," Hora: ",0,0,'R');
		$pdf->Cell(21,5,date("H:i:s"),0,0,'L');
		$Y+=4;
		$pdf->SetXY($x,$Y);
		$pdf->Cell(5,5,utf8_decode("Página: "),0,0,'R');
        $pdf->AliasNbPages();
		$pdf->Cell(21,5,$pdf->PageNo().' de '.'{nb}',0,0,'L');
		$pdf->Ln(5);$pdf->Ln(5);$pdf->Ln(5);

		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(235,235,235);
		$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
		$pdf->SetWidths(array(20,65,60,20,60,35,18,27,35));
		$pdf->Row(array("NUMERO", "CONCEPTO", "GERENCIA", "RIF", "PROVEEDOR", "OBSERVACION", "FECHA", "MONTO", "ESTRUCT. GASTOS"),5);
		$pdf->SetAligns(array('C','L','L','L','L','C','C','C','C'));




        if ($num_regis_det_prov >0)
        {
            for($i=0; $i<$num_regis_det_prov; $i++){

                $datos2 = OpDetgastossolservicio::query()
                                                ->select('cod_com')
                                                ->where('xnro_sol',$detalle_proveedor[$i]->xnro_sol)
                                                ->where('ano_pro',$detalle_proveedor[$i]->ano_pro)
                                                ->get();


                $cont_det = count($datos2);

                $estructgast="";

                for($q=0; $q<$cont_det; $q++)
                {
                    $estructgast .= $datos2[$q]->cod_com;
                    if ($q<$cont_det)
                        $estructgast .="\r\n";
                }

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',7);
                $pdf->SetAligns(array('C','L','L','L','L','C','C','C','C'));
                $pdf->SetWidths(array(20,65,60,20,60,35,18,27,35));

                if ( $detalle_proveedor[$i]->provision == 'S' )
                    $num =$detalle_proveedor[$i]->xnro_sol."-".$detalle_proveedor[$i]->ano_pro." (Provision)";
                else
                    $num =$detalle_proveedor[$i]->xnro_sol."-".$detalle_proveedor[$i]->ano_pro;

                $pdf->Row(array(
                                $num,
                                utf8_decode($detalle_proveedor[$i]->motivo).' - Estatus: '.utf8_decode($detalle_proveedor[$i]->getEstSol($detalle_proveedor[$i]->sta_sol->value)),
                                utf8_decode($detalle_proveedor[$i]->gerencias->des_ger).' - '.$detalle_proveedor[$i]->usuario,
                                $detalle_proveedor[$i]->rif_prov,
                                utf8_decode($detalle_proveedor[$i]->beneficiario->nom_ben),
                                utf8_decode($detalle_proveedor[$i]->observaciones),
                                \Carbon\Carbon::parse($detalle_proveedor[$i]->fecha)->format('d/m/Y'),
                                number_format($detalle_proveedor[$i]->monto_total, 2, ',', '.'),
                                $estructgast
                            ),5);
                $pdf->SetAligns(array('C','L','L','L','L','C','C','C','C'));
            };

            $pdf->Ln(5);
            $pdf->Cell(0,5,'TOTAL DE REGISTROS: '.$num_regis_det_prov,0,0,'L',0);

            header("Content-type: application/pdf");
            $pdf->Output();
            exit();
        }
    }
    //-----------------------------------------------------------------------
    //    Funcion que llama pantalla de parametros para reporte de guarderias
    //-----------------------------------------------------------------------
    public function show_listado_guarderia()
    {

       return view('administrativo.meru_administrativo.otrospagos.reporte.listado_guarderia.show');
    }

    //-----------------------------------------------------------------------
    //    Funcion para generar reporte de certificación/contratos
    //-----------------------------------------------------------------------
    public function print_listado_guarderia(Request $request)
    {
        //-----------------------------------------------------------------------
        //   Se debe incluir el correlativo
        //-----------------------------------------------------------------------
        if ($request->correlativo == null){
                $msj = 'Debe ingresar el correlativo correspondiente. Por Favor Intente de Nuevo.';
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                return redirect()->back()->withInput();
        }

        //-----------------------------------------------------------------------
        //     Se valida rango de fecha valido
        //-----------------------------------------------------------------------
        if ($request->inicio != null || $request->fin != null){
            if ($request->inicio >  $request->fin){
                $msj = 'La fecha de inicio debe ser menor o igual a la fecha final. Por Favor Intente de Nuevo.';
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                return redirect()->back()->withInput();
            }
        }

        //-----------------------------------------------------------------------
        //     Se garantiza que selecciones el tipo de salida del reporte
        //-----------------------------------------------------------------------
        if ($request->tipo==null){
            $msj = 'Debe seleccionar el tipo de salida. Por Favor Intente de Nuevo.';
            alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
            return redirect()->back()->withInput();
        }

        //-----------------------------------------------------------------------
        //     Datos del Gerente de RRHH
        //-----------------------------------------------------------------------
        $topGER =  Gerencia::query()
                           ->selectRaw("LOWER(nom_jefe) AS nom_jefe")
                           ->where('cod_ger','4')
                           ->get();

        //-----------------------------------------------------------------------
        //    Datos del Gerente de Administración
        //-----------------------------------------------------------------------
        $topGERA =  Gerencia::query()
                           ->selectRaw("LOWER(nom_jefe) AS nom_jefe")
                           ->where('cod_ger','10')
                           ->get();

        //-----------------------------------------------------------------------
        //    Listado de Certificaciones
        //-----------------------------------------------------------------------
        $res_cert = OpSolservicio::query()
                                 ->where('op_solservicio.cod_ger','4')
                                 ->where('op_solservicio.sta_sol','2')
                                 ->whereBetween('fec_apr',[$request->inicio, $request->fin])
                                  ->leftJoin('op_detsolservicio AS b', function ($join) {
                                        $join->on('b.ano_pro', '=', 'op_solservicio.ano_pro')
                                             ->on('b.xnro_sol', '=', 'op_solservicio.xnro_sol');
                                  })
                                  ->where('cod_prod', '=', '18')
                                 ->leftJoin('tes_beneficiarios  AS c', 'c.rif_ben', '=', 'op_solservicio.rif_prov')
                                 ->selectRaw("UPPER(op_solservicio.motivo) AS motivo")// UPPER(op_solservicio.observaciones) AS factura, op_solservicio.rif_prov AS rif, c.nom_ben AS proveedor,
                                              //op_solservicio.ano_pro||' / '||op_solservicio.xnro_sol AS certificacion, op_solservicio.monto_total AS monto, TO_CHAR(op_solservicio.fec_apr, 'DD/MM/YYYY') AS fec_apr, b.cod_prod")
                                ->orderby('op_solservicio.ano_pro','desc');
                                //->get();

        //-----------------------------------------------------------------------
        //    Listado de Certificaciones
        //-----------------------------------------------------------------------
        if ($request->tipo=='EXCEL'){
            $this->reporteexcel($res_cert,$topGERA,$topGER, $request);
        }else{
            $this->reportepdf($res_cert,$topGERA,$topGER, $request);
        }
    }


    //-----------------------------------------------------------------------
    //    Funcion para generar reporte de certificación/contratos
    //-----------------------------------------------------------------------
    public function reporteexcel($res_cert, $topGERA, $topGER,$request)
    {
        $titulo    = 'PRESUPUESTO: ' ;
        $subtitulo = 'PERIODO: ' ;
        $archivo   = 'Diferencias_Prespuestarias';


     $query_cert = DB::table('pre_cierremensual AS b')
     ->select(
         'b.cod_com')
        ->where([
        ['b.ano_pro', '=','2016'],
        ['b.mes_pro', '=', '11']
    ]);
    $query_cert->orderByRaw('1');
            $data = [
                'query'      => $query_cert,
                'titulo'     => [$titulo, $subtitulo],
                'ancho'      => [30],
                'alineacion' => ['C'],
                'formatos'   => ['T'],
                'columnas'   => ['ESTRUCTURA']
            ];

            return (new FromQueryExport($data))->download($archivo . '.xlsx');
    }



    //-----------------------------------------------------------------------
    //    Funcion para generar reporte de certificación/contratos
    //-----------------------------------------------------------------------
    public function reportepdf($res_cert, $topGERA, $topGER,$request)
    {
        $corr =$request->correlativo;
        $fecha = date('d/m/Y');
        $cont_cert = count($res_cert);

        $pdf = new Fpdf('P','mm','letter','true');
        $pdf->SetLeftMargin(5);
        $pdf->SetRightMargin(5);
        $pdf->AddPage("P");

        //-----------------------------------------------------------------------------------------------
        //   Deben existir Certificaciones de Guarderías Aprobadas 	 * en el rango de fecha solicitado.
        //-------------------------------------------------------------------------------------------------
		if ($res_cert){
			$pdf->SetFont('Arial', 'B', 9);
			$pdf->SetY(10);
			$pdf->SetX(20);
			// $pdf->Image('img/hidrobolivar.png', 10, 15, 40, 13, 'PNG');
            $pdf->Image('img/hidrobolivar.jpg',10,15,40,13,'JPG');
			$pdf->Image('img/fondonorma.png', 180, 15, 15, 13, 'PNG');
			$pdf->SetY(30);
			$pdf->Ln(7);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetFont('Arial', 'B', 8);
			$pdf->SetX(165);
			$pdf->Cell(30, 5, $corr, 0, 0, 'R', 1);
			$pdf->Ln();
			$pdf->SetFillColor(205, 205, 205);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->SetX(10);
			$pdf->Cell(200, 5, utf8_decode('COMUNICACIÓN'), 0, 0, 'C', 1);
			$pdf->Ln();
            $pdf->SetX(10);
			$pdf->Cell(25, 9, 'Para:  ', 0, 0, 'L', 1);
			$pdf->SetX(36);
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->Cell(50,5,ucwords(utf8_decode($topGERA[0]->nom_jefe)),0,0,'L',0);
			$pdf->Ln();
			$pdf->SetX(36);
			$pdf->Cell(50, 4, utf8_decode('Gerente (E) de Administración y Finanzas'), 0, 0, 'L', 0);
			$pdf->Ln(4);
            $pdf->SetX(10);
			$pdf->SetFillColor(205, 205, 205);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(25, 9,'De:  ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetX(36);
			$pdf->Cell(50, 5, ucwords(utf8_decode($topGER[0]->nom_jefe)), 0, 0, 'L', 0);
			$pdf->Ln();
			$pdf->SetX(36);
			$pdf->Cell(50, 4, utf8_decode('Gerente de Recursos Humanos'), 0, 0, 'L', 0);
			$pdf->Ln(4);
            $pdf->SetX(10);
			$pdf->SetFillColor(205, 205, 205);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(25, 5, 'Fecha:  ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial', '', 10);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetX(36);
			$pdf->Cell(50, 5, $fecha, 0, 0, 'L', 0);
			$pdf->Ln(5);
            $pdf->SetX(10);
			$pdf->SetFillColor(205, 205, 205);
			$pdf->SetFont('Arial', 'B', 10);
			$pdf->Cell(25, 5, 'Asunto:  ', 0, 0, 'L', 1);
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetX(36);
			$pdf->Cell(50, 5, utf8_decode('Pago de Guardería'), 0, 0, 'L', 0);
			$pdf->Ln(10);
			$pdf->SetX(10);


        //-----------------------------------------------------------------------------------------------
        //  Cuerpo de Reporte
        //-------------------------------------------------------------------------------------------------
			$pdf->MultiCell(187, 5, utf8_decode('Reciba un cordial saludo, la presente es con la finalidad de solicitar ' .
												' la emisión de cheque a las guarderías  que se mencionan a continuación:'));
			$pdf->Ln(5);
			$pdf->SetX(10);
            $pdf->SetFillColor(205, 205, 205, 205, 205, 205, 205);
            $pdf->SetFillColor(120, 120, 120);
			$pdf->SetFont('Arial', 'B', 6);
			$pdf->SetAligns(array('C', 'C', 'C', 'C', 'C', 'C', 'C'));
			$pdf->SetWidths(array(70, 20, 20, 30, 20, 20, 20));
			$pdf->Row(array( utf8_decode('DESCRIPCIÓN'), 'FACTURA', 'RIF', 'PROVEEDOR', 'NRO. CERT', 'MONTO',  utf8_decode('FECHA APROBACIÓN')), 5);
			$total = 0;

			/** Detalle de Facturas Registradas **/
			for ($i = 0; $i < $cont_cert; $i++)	{
				$pdf->SetX(10);
				$pdf->SetFont('Arial', '', 6);
				$pdf->SetFillColor(255, 255, 255, 255, 255, 255, 255);
				$pdf->SetAligns(array('J', 'C', 'C', 'J', 'C', 'R', 'C'));
				$pdf->SetWidths(array(70, 20, 20, 30, 20, 20, 20));
				$pdf->Row(array($res_cert[$i]->motivo,
								$res_cert[$i]->factura,
								$res_cert[$i]->rif,
								$res_cert[$i]->proveedor,
								$res_cert[$i]->certificacion,
					            number_format($res_cert[$i]->monto, 2, ',', '.'),
					            $res_cert[$i]->fec_apr), 5);

				$total += $res_cert[$i]->monto;
			}

		    $pdf->SetFont('Arial','B',8);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->SetX(10);
			$pdf->Cell(160, 5 ,'Total en Bs.', 1, 0, 'R', 0);
			$pdf->Cell(20, 5, number_format($total, 2, ',', '.'), 1, 0, 'R', 0);

	        $pdf->Ln(5);
            $pdf->SetX(10);
	        $pdf->SetFont('Arial', '', 10);
	        $pdf->SetFillColor(255, 255, 255);

			$pdf->Cell(50, 5, 'Sin otro particular a que hacer referencia, se despide.', 0, 0, 'L', 0);
			$pdf->Ln(20);
			$pdf->SetX(85);
			$pdf->Cell(50, 5, ucwords(utf8_decode($topGER[0]->nom_jefe)), 0, 0, 'C', 0);
			$pdf->Ln();
			$pdf->SetX(85);
			$pdf->Cell(50, 5, utf8_decode('Gerente de Recursos Humanos'), 0, 0, 'C', 0);

			// header('Content-type: application/pdf');
			// $pdf->Output('Listado_Guarderias.pdf', 'D');

            header("Content-type: application/pdf");
            $pdf->Output();

			exit();
        }
    }
}

