<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\contratos\Proceso;

use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpDetsolservicio;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpDetgastossolservicio;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\OpCorrsolservicio;
use App\Http\Requests\Administrativo\Meru_Administrativo\Contratos\Proceso\OpSolContratoRequest;
use App\Models\Administrativo\Meru_Administrativo\General\DatosEmpresa;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Factura;
use App\Models\Administrativo\Meru_Administrativo\Compra\EncNotaEntrega;
use Illuminate\Support\Facades\Route;

use App\Traits\MovimientoPresupuestario;
use Illuminate\Http\Request;
use App\Traits\Presupuesto;
use App\Traits\PreMovimientos;
use App\Traits\convertirLetrasMonto;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Traits\ReportFpdf;
use Carbon\Carbon;

class CertificacionContratoController extends Controller
{   use ReportFpdf;
    use MovimientoPresupuestario;
    use PreMovimientos;
    use Presupuesto;
    use convertirLetrasMonto;
    //--------------------------------------------------------------
    //              Funcion que llama al Index
    //-------------------------------------------------------------
    public function index()
    {    $nombreRuta = Route::currentRouteName();

        Route::currentRouteName()=='contratos.proceso.certificacioncontrato.index'?
                                    $nombreRuta='contrato':$nombreRuta='addendum';
        return view('administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.index', [
            'nombreRuta'     => $nombreRuta
        ]);
    }
    //--------------------------------------------------------------
    //              Funcion que llama al Crear
    //-------------------------------------------------------------
    public function crear($nombreRuta)
    {
          $opsolservicio= new OpSolservicio();
          return view('administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.create',
          [
              'opsolservicio'         => $opsolservicio,
              'nombreRuta'            => $nombreRuta
          ]);
    }
    //-------------------------------------------------------------
    //      Determina el proceso por el cual se está accediendo
    //-------------------------------------------------------------
	function ObtenerProceso($xnro_sol){
		$process = '';
		$tipo = substr_count($xnro_sol , '-' );
		if($tipo == 1){
			$process = "Contrato de Obras / Servicios";
		}
		else{
			$process = "Addendum Contrato de Obras / Servicios";
		}
		return $process;
	}
    //-------------------------------------------------------------
    //      Determina el proceso por el cual se está accediendo
    //-------------------------------------------------------------
	function ObtenerRuta($xnro_sol){
		$ruta = '';
		$tipo = substr_count($xnro_sol , '-' );
		if($tipo == 1){
			$ruta = "certificacioncontrato";
		}
		else{
			$ruta = "certificacioncontratoaddendum";
		}
		return $ruta;
	}
    //--------------------------------------------------------------
    //              Funcion que llama al Insertar
    //-------------------------------------------------------------
    public function store(OpSolContratoRequest $request)
    {
        try {
                DB::connection('pgsql')->beginTransaction();
			    $listadoDetalle = json_decode($request->listadoDetalle);

                if (is_null($request->xnro_sol))
                {
                    //-------------------------------------------------------------------
                    //  Obtener el último correlativo de la tabla OpCorrsolservicio
                    //         esto se debe actualizar por cada año fiscal
                    //             El proceso viene por contrato de obra
                    //--------------------------------------------------------------------
                    $num_reg = OpCorrsolservicio::query()->where('ano_pro', $request->ano_pro)->first()->num_contrato?? 1;
                    $xnro_sol ='CO-'.$num_reg;
                    $ult_sol=0;
                    //-------------------------------------------------------------------------------------
                    //            Incrementar o Crear el correlativo si es un nuevo año Fiscal
                    //-------------------------------------------------------------------------------------
                    if ($num_reg ==1) {
                        OpCorrsolservicio::create([
                            'ano_pro'               => $request->ano_pro,
                            'num_contrato'          => $num_reg+1
                            ]);
                    }else{
                        OpCorrsolservicio::where('ano_pro', $request->ano_pro)->increment('num_contrato');

                    }
                }else{
                        //-------------------------------------------------------------------
                        // Incrementa en uno el número correlativo correspondiente al Addendum,
                        // en la solicitud original
                        //--------------------------------------------------------------------
                        OpSolservicio::query()->where('ano_pro', $request->ano_pro)
                                                                       ->where('xnro_sol', strtoupper($request->xnro_sol))
                                                                       ->increment('ult_sol');
                        $num_reg =OpSolservicio::query()->where('ano_pro', $request->ano_pro)
                                                        ->where('xnro_sol', strtoupper($request->xnro_sol))->first()->ult_sol;
                        $xnro_sol =strtoupper($request->xnro_sol).'-'.$num_reg;
                        $ult_sol=-1;
                }
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
                        'sta_sol'             => '0',
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
                        'xnro_sol'            =>  $xnro_sol,
                        'observaciones'       => $request->observaciones,
                        'num_contrato'        => $request->num_contrato,
                        'ult_sol'             => $ult_sol,
                        'tip_contrat'         => $request->tip_contrat

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
                        'grupo'                 => 'CO',
                        'xnro_sol'              => $xnro_sol,
                        'base_excenta'          => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_excenta)))),
                        'op_solservicio_id'     => $opsolservicio->id
                    ]);
                    //--------------------------------------------------------------
                    //              Insert en la Tabla Detalle
                    //-------------------------------------------------------------
                    foreach($listadoDetalle as $detalle){
                        //-------------------------------------------------
                        //-------------------------------------------------
                        $OpDetgastossolserviciox=    OpDetgastossolservicio::create([
                                'ano_pro'                    => $request->ano_pro,
                                'xnro_sol'                   => $xnro_sol,
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
                                'grupo'                      => 'CO',
                                'cod_com'                    => $this->varmarCodcom($detalle->tip_cod,$detalle->cod_pryacc,
                                                                                    $detalle->cod_obj,$detalle->gerencia,$detalle->unidad,
                                                                                    $detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub),
                                'op_solservicio_id'          =>$opsolservicio->id
                            ]);
                    }

                $process = $this->ObtenerProceso($opsolservicio->xnro_sol);
                alert()->success('¡Éxito!', $process.' Registrado Sastifactoriamente con el Numero: '.$opsolservicio->ano_pro."-".$opsolservicio->xnro_sol);
                DB::connection('pgsql')->commit();
                return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($opsolservicio->xnro_sol).'.index');
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
    public function show(OpSolservicio $certificacioncontrato,$valor)
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
        switch ($valor) {
            case "show":
                $ruta='administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.show';
                break;
            case "anular":
                $ruta='administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.anular';
                break;
            case "aprobar":
                $ruta='administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.aprobar';
                break;
            case "reversar":
                $ruta='administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.reversar';
                break;
            case "reverso":
                $ruta='administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.reverso';
                break;
            case "comprometer":
                $ruta='administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.comprometer';
                break;
            case "cierre":
                $ruta='administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.cierre';
                break;
        }
        $this->ObtenerRuta($certificacioncontrato->xnro_sol)=='certificacioncontratoaddendum'? $nombreRuta='addendum':$nombreRuta='contrato';
        return view($ruta,
        [
            'opsolservicio'     => $certificacioncontrato,
            'registrocontrol'   => $registrocontrol,
            'beneficiario'      => $beneficiario,
            'gerencia'          => $gerencia,
            'valor'             => $valor,
            'nombreRuta'        => $nombreRuta,
        ]);

    }
    //--------------------------------------------------------------
    //              Funcion que llama al Edit
    //-------------------------------------------------------------
    public function edit(OpSolservicio $certificacioncontrato)
    {   $this->ObtenerRuta($certificacioncontrato->xnro_sol)=='certificacioncontratoaddendum'? $nombreRuta='addendum':$nombreRuta='contrato';
        return view('administrativo.meru_administrativo.contratos.proceso.certificacion_contrato.edit', compact('certificacioncontrato','nombreRuta'));
    }
    //--------------------------------------------------------------
    //              Funcion que llama a Actualizar Datos
    //-------------------------------------------------------------
    public function update(OpSolContratoRequest $request, OpSolservicio $certificacioncontrato)
    {
        try {
             $process = $this->ObtenerProceso($certificacioncontrato->xnro_sol);
             DB::connection('pgsql')->beginTransaction();
             $listadoDetalle = json_decode($request->listadoDetalle);
              //----------------------------------------------------------------------------------------------
             //        Actualiza los datos principales de la certificacion en la tabla principal
             //----------------------------------------------------------------------------------------------
              $certificacioncontrato->update([
                 'cod_ger'             => $request->cod_ger,
                 'fec_emi'            => $request->fec_emi,
                 'rif_prov'            => $request->rif_prov,
                 'sta_sol'             => $request->sta_sol,
                 'tip_pag'             => $request->tip_pag,
                 'factura'             => $request->factura,
                 'fec_serv'            => $request->fec_serv,
                 'fec_pto'             => $request->fec_pto,
                 'pto_cta'              => $request->pto_cta,
                 'motivo'              => $request->motivo,
                 'usuario'             =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                 'fecha'               => $this->fechaGuardar,
                 'por_anticipo'        => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->por_anticipo)))),
                 'mto_ant'             => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->mto_ant)))),
                 'monto_iva'           => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->monto_iva)))),
                 'por_iva'             => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->por_iva)))),
                 'monto_total'         => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->monto_total)))),
                 'base_exenta'         => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->base_exenta)))),
                 'base_imponible'      => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->base_imponible)))),
                 'monto_neto'          => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->monto_neto)))),
                 'observaciones'       => $request->observaciones,
                 'num_contrato'        => $request->num_contrato,
                 'tip_contrat'         => $request->tip_contrat,
                 'fec_sta'             =>  now()->format('Y-m-d'),
                ]);
             //--------------------------------------------------------------
             //          Borrar -  Insert en la Tabla Detalle
             //-------------------------------------------------------------
             $OpDetsolservicio=OpDetsolservicio::where('op_solservicio_id','=',$certificacioncontrato->id);
             $OpDetsolservicio->delete();
             OpDetsolservicio::create([
                 'ano_pro'               => $request->ano_pro,
                 'nro_sol'               => $certificacioncontrato->nro_sol,
                 'cod_prod'              => $request->codigo,
                 'por_iva'               => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->por_iva_con)))),
                 'cantidad'              => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cantidad)))),
                 'cos_uni'               => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_uni)))),
                 'cos_tot'               => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_tot)))),
                 'grupo'                 => $certificacioncontrato->grupo,
                 'xnro_sol'              => $certificacioncontrato->xnro_sol,
                 'base_excenta'          => floatval(\Str::replace(',', '.', \Str::replace('.','', ($request->cos_excenta)))),
                 'op_solservicio_id'     => $certificacioncontrato->id
             ]);
             //--------------------------------------------------------------
             //           Borrar -     Insert en la Tabla Detalle
             //-------------------------------------------------------------
             $OpDetgastossolservicio=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacioncontrato->id);
             $OpDetgastossolservicio->delete();
             foreach($listadoDetalle as $detalle){
                    //-------------------------------------------------
                    //-------------------------------------------------
                    if($request->provision=='S'){
                        $result_cuenta_contable=$this->ObtenerDatosPartida($detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub);
                       if($result_cuenta_contable->cta_provision ==null  ||
                          $result_cuenta_contable->cod_cta!='4.03.18.01.00'){
                            alert()->error('Error!', 'La la Estructura de Gasto, No tiene asociada Cta. de Provisión .Favor Verifique');
                           return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
                        }
                    }
					//-------------------------------------------------
                    //-------------------------------------------------
                    OpDetgastossolservicio::create([
                         'ano_pro'       => $request->ano_pro,
                         'xnro_sol'      => $certificacioncontrato->xnro_sol,
                         'nro_sol'       => $certificacioncontrato->nro_sol,
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
                         'grupo'         => 'CO',
                         'cod_com'       => $this->varmarCodcom($detalle->tip_cod,$detalle->cod_pryacc,
                                                                 $detalle->cod_obj,$detalle->gerencia,$detalle->unidad,
                                                                 $detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub),
                         'op_solservicio_id' =>$certificacioncontrato->id
                     ]);
            }
            alert()->success('¡Éxito!', $process.' '.$certificacioncontrato->ano_pro."-".$certificacioncontrato->xnro_sol.' Modificada Sastifactoriamente');
            DB::connection('pgsql')->commit();
           return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
      } catch(\Illuminate\Database\QueryException $e){
         DB::connection('pgsql')->rollBack();
         alert()->error('¡Transacción Fallida!', $e->getMessage());
         return redirect()->back()->withInput();
      }

    }

    //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function anular_certificacion(OpSolservicio $certificacioncontrato)
    {
        try {
            $process = $this->ObtenerProceso($certificacioncontrato->xnro_sol);
            if($certificacioncontrato->sta_sol->value!='0'){
                    //----------------------------------------------------------------------------------------------
                    //      Valida que la Certificación este solo ingresada para poder anular
                    //----------------------------------------------------------------------------------------------
                    alert()->error('Error!',  $process.' '.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. '. Con Estado Invalidos:'. $this->estado($certificacioncontrato->sta_sol->value));
                   return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
            }else{
                //----------------------------------------------------------------------------------------------
                //        Actualiza los datos principales de anulación de la certificacion en la tabla principal
                //----------------------------------------------------------------------------------------------
                DB::connection('pgsql')->beginTransaction();
                $certificacioncontrato->update([
                    'usua_anu'             =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                    'fec_anu'              => $this->fechaGuardar,
                    'sta_sol'              => '1'
                ]);

                alert()->success('¡Éxito!', $process.' '.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. '  Eliminada Sastifactoriamente');
                DB::connection('pgsql')->commit();
               return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
            }

     } catch(\Illuminate\Database\QueryException $e){
        DB::connection('pgsql')->rollBack();
        alert()->error('¡Transacción Fallida!', $e->getMessage());
        return redirect()->back()->withInput();
     }

    }
    //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function aprobar_certificacion(OpSolservicio $certificacioncontrato)
    {
        try {
            $process = $this->ObtenerProceso($certificacioncontrato->xnro_sol);
            if($certificacioncontrato->sta_sol->value!='0' && $certificacioncontrato->sta_sol->value!='3'){
                //----------------------------------------------------------------------------------------------
                //      Valida que la Certificación este solo ingresada  o Reversada por el Gerente para poder Aprobar
                //----------------------------------------------------------------------------------------------
                alert()->error('Error!', $process.' '.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. '. Con Estado Invalidos: '.$this->estado($certificacioncontrato->sta_sol->value));
               return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
             }else{
                DB::connection('pgsql')->beginTransaction();
                //----------------------------------------------------------------------------------------------
                //        Actualiza los datos principales de anulación de la certificacion en la tabla principal
                //----------------------------------------------------------------------------------------------
                $certificacioncontrato->update([
                    'usua_apr'             =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                    'fec_apr'              => $this->fechaGuardar,
                    'sta_sol'              => '2'
                ]);
                alert()->success('¡Éxito!',$process .' '.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. '  Aprobada Sastifactoriamente');
                DB::connection('pgsql')->commit();
               return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
        }
     } catch(\Illuminate\Database\QueryException $e){
        DB::connection('pgsql')->rollBack();
        alert()->error('¡Transacción Fallida!', $e->getMessage());
        return redirect()->back()->withInput();
     }

    }
    //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function  reversar_certificacion(OpSolservicio $certificacioncontrato)
    {
        try {
            $process = $this->ObtenerProceso($certificacioncontrato->xnro_sol);
            if($certificacioncontrato->sta_sol->value!='2'){
                //----------------------------------------------------------------------------------------------
                //      Valida que la Certificación este solo aprobada para poder reversar
                //----------------------------------------------------------------------------------------------
                alert()->error('Error!', $process .' '.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. '. Con Estado Invalidos: '.$this->estado($certificacioncontrato->sta_sol->value));
               return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
             }else{
                DB::connection('pgsql')->beginTransaction();
                //----------------------------------------------------------------------------------------------
                //        Actualiza los datos principales de anulación de la certificacion en la tabla principal
                //----------------------------------------------------------------------------------------------
                $certificacioncontrato->update([
                    'usu_reverso_a'        =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                    'fech_reverso_a'       => $this->fechaGuardar,
                    'sta_sol'              => '3'
                ]);
                alert()->success('¡Éxito!', $process .' '.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. 'Reversada Sastifactoriamente');
                DB::connection('pgsql')->commit();
               return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
           }
     } catch(\Illuminate\Database\QueryException $e){
        DB::connection('pgsql')->rollBack();
        alert()->error('¡Transacción Fallida!', $e->getMessage());
        return redirect()->back()->withInput();
     }

    }
    //--------------------------------------------------------------
    //-------------------------------------------------------------
    public function  comprometer_certificacion(OpSolservicio $certificacioncontrato)
    {
        try {
            $process = $this->ObtenerProceso($certificacioncontrato->xnro_sol);
            if($certificacioncontrato->sta_sol->value!='2' && $certificacioncontrato->sta_sol->value!='5' ){
                //----------------------------------------------------------------------------------------------
                //      Valida que la Certificación este solo Aprobada para poder comprometer
                //----------------------------------------------------------------------------------------------
                alert()->error('Error!', $process .' '.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. '. Con Estado Invalidos: '.$this->estado($certificacioncontrato->sta_sol->value));
               return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
            }else{
                        DB::connection('pgsql')->beginTransaction();
                        //-------------------------------------------------------------------------------------------------
                        //  Actualizar el status de la certificacion
                        //-------------------------------------------------------------------------------------------------
                        $certificacioncontrato->update([
                            'usua_comp'            =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                            'fec_comp'             => $this->fechaGuardar,
                            'sta_sol'              => '4'
                        ]);
                        //---------------------------------------------------------------------------------
                        //                        ASIENTO PRESUPUESTARIO
                        //---------------------------------------------------------------------------------
                        $ano_fiscal   = $this->anoPro;
                        $tip_ope   = 10;
                        $sol_tip   = "CO";
                        $num_doc   =  $certificacioncontrato->xnro_sol;
                        $rif_prov   =  $certificacioncontrato->rif_prov;
                        $ano_pro   =  $certificacioncontrato->ano_pro;
                        $concepto  ="COMPROMISO DE ".strtoupper($process);
                        $usuario   = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
                        if($process   == "Contrato de Obras / Servicios"){
                           $tip_op = 10;
                        }else {
                            if ($certificacioncontrato->mod == "0"){
                               $tip_ope= 95;
                            }else {
                               $tip_ope= 96;
                             }

                        }
                        $OpDetgastossolservicio=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacioncontrato->id)->get();
                        foreach($OpDetgastossolservicio as $detalle){
                               $cod_com=$this->generarCentroCosto($detalle->tip_cod, $detalle->cod_pryacc, $detalle->cod_obj,$detalle->gerencia,
                                                                $detalle->unidad,$detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,
                                                                $detalle->cod_sub, $certificacioncontrato->ano_pro);
                                if($cod_com==''){
                                    alert()->error('Error Buscando Centro de Costo');
                                    return redirect()->back()->withInput();
                                }
                                if($detalle->mto_tra!=0){
                                    $movpre = DB::select("SELECT * FROM movimientopresupuestario('$ano_fiscal', '$cod_com', '$sol_tip','$tip_ope',
                                                                                                '$num_doc', '$detalle->mto_tra', '$rif_prov', '$concepto', '0',
                                                                                    '$usuario', '$ano_pro', '', '', '0', '$this->fechaGuardar')");
                                    if(!$movpre){
                                        DB::connection('pgsql')->rollBack();
                                        alert()->error('¡Transacción Fallida!','Ocurrio error al ejecutar Movimiento Presupuestario');
                                        return redirect()->back()->withInput();
                                    }
                                }
                        }
                        $msj = "El ".$process." Nº $certificacioncontrato->ano_pro-$certificacioncontrato->xnro_sol Ha sido COMPROMETIDO Exitosamente!";
                     /*   $reporte = "classesPHP/formas/otrospagos/reportes/rep_Certificacion_Pagos_contrato.Class.php";
                        $reporte .= "?ano_pro={$this->getCampo("ano_pro")->valor}&amp;xnro_sol={$this->getCampo("xnro_sol")->valor}&amp;process={$process}";
                        $reporte .= "&amp;url={$this->urlapp}";*/
                        alert()->success('¡Éxito!', $msj );
                        DB::connection('pgsql')->commit();
                       return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
                }
        }catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }

    }
 //--------------------------------------------------------------
//-------------------------------------------------------------
    public function  reverso_compromiso_certificacion(OpSolservicio $certificacioncontrato)
    {
        try {
            $process = $this->ObtenerProceso($certificacioncontrato->xnro_sol);
            if($certificacioncontrato->sta_sol->value!='4'){
                //----------------------------------------------------------------------------------------------
                //      Valida que la Certificación este solo Aprobada para poder comprometer
                //----------------------------------------------------------------------------------------------
                alert()->error('Error!', $process.''.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. '. Con Estado Invalidos: '.$this->estado($certificacioncontrato->sta_sol->value));
               return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
            }else{
                //------------------------------------------------------------------------------
                //Se debe Validar que no Exista Facturas asociadas a la Contrato
                //En este caso se debe eliminar primero, para luego reversar la Contrato
                //-------------------------------------------------------------------------------
                $factura=Factura::where('ano_pro','=',$certificacioncontrato->ano_pro)
                ->where('nro_doc','=',$certificacioncontrato->xnro_sol)
                ->where('tipo_doc','=','5')
                ->where('rif_prov','=',$certificacioncontrato->rif_prov)->first();
               if(!is_null($factura)){
                    alert()->error('Warning!','Existen Facturas Asociadas al Contrato.Favor Reverse las Facturas.');
                    return redirect()->back()->withInput();
               }
                //------------------------------------------------------------------------------
                //Se debe Validar que no Exista Actas asociadas a la Contrato
                //En este caso se debe eliminar primero, para luego reversar la Contrato
                //-------------------------------------------------------------------------------
                $com_encnotaentrega=EncNotaEntrega::where('ano_ord_com','=',$certificacioncontrato->ano_pro)
                                                    ->where('xnro_ord','=',$certificacioncontrato->xnro_sol)
                                                    ->whereNotIn('sta_ent',['5','8'])
                                                    ->where('fk_rif_con','=',$certificacioncontrato->rif_prov)->first();
                if(!is_null($com_encnotaentrega)){
                    alert()->error('Warning!','Existen Actas Asociadas al Contrato.Debe Reversarlas o Anularlas para poder Reversar el Compromiso del Contrato.');
                    return redirect()->back()->withInput();
                }
                DB::connection('pgsql')->beginTransaction();
                //-------------------------------------------------------------------------------------------------
                //  Actualizar el status de la certificacion
                //-------------------------------------------------------------------------------------------------
                $certificacioncontrato->update([
                    'usuario_reverso_c'            =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                    'fech_reverso_c'             =>'1900-01-01',
                    'ano_sol_pago'             =>  '1900',
                    'nro_sol_pago'             => '',
                    'sta_sol'              => '5'
                ]);
                //---------------------------------------------------------------------------------
                //                        ASIENTO PRESUPUESTARIO
                //---------------------------------------------------------------------------------
                $ano_fiscal   = $this->anoPro;
                $tip_ope      = 20;
                $sol_tip      = "CO";
                $num_doc      =  $certificacioncontrato->xnro_sol;
                $rif_prov     =  $certificacioncontrato->rif_prov;
                $ano_pro      =  $certificacioncontrato->ano_pro;
                $concepto     =  "REVERSO COMPROMISO DE ".strtoupper($process);
                $usuario      = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
                if($process   == "Contrato de Obras / Servicios"){
                    $tip_op = 20;
                }else {
                    if ($certificacioncontrato->mod == "0"){
                        $tip_ope= 96;
                    }else {
                        $tip_ope= 95;
                        }

                }
                $OpDetgastossolservicio=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacioncontrato->id)->get();
                foreach($OpDetgastossolservicio as $detalle){
                    $cod_com=$this->generarCentroCosto($detalle->tip_cod, $detalle->cod_pryacc, $detalle->cod_obj,$detalle->gerencia,
                                                        $detalle->unidad,$detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,
                                                        $detalle->cod_sub, $certificacioncontrato->ano_pro);
                    if($detalle->mto_tra!=0){
                        $movpre = DB::select("SELECT * FROM movimientopresupuestario('$ano_fiscal', '$cod_com', '$sol_tip','$tip_ope',
                                                                                    '$num_doc', '$detalle->mto_tra', '$rif_prov', '$concepto', '0',
                                                                                    '$usuario', '$ano_pro', '', '', '0', '$this->fechaGuardar')");
                        if(!$movpre){
                        DB::connection('pgsql')->rollBack();
                        alert()->error('¡Transacción Fallida!','Ocurrio error al ejecutar Movimiento Presupuestario');
                        return redirect()->back()->withInput();
                         }
                    }

                }
                alert()->success('¡Éxito!',  $process .' '.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. 'Reversada Sastifactoriamente');
                DB::connection('pgsql')->commit();
               return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
            } //if($certificacioncontrato->sta_sol->value!='4')
        } catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }

    }
     //--------------------------------------------------------------
//-------------------------------------------------------------
public function  cierre_compromiso_certificacion(OpSolservicio $certificacioncontrato)
{
    try {
        $process = $this->ObtenerProceso($certificacioncontrato->xnro_sol);
        if($certificacioncontrato->sta_sol->value!='4'){
            //----------------------------------------------------------------------------------------------
            //      Valida que la Certificación no este comprometida
            //----------------------------------------------------------------------------------------------
            alert()->error('Error!', $process.''.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. '. Con Estado Invalidos: '.$this->estado($certificacioncontrato->sta_sol->value));
           return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
        }else{

            DB::connection('pgsql')->beginTransaction();
            //-------------------------------------------------------------------------------------------------
            //  Actualizar el status de la certificacion
            //-------------------------------------------------------------------------------------------------
            $certificacioncontrato->update([
                'usuario_reverso_c'     =>  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                'fech_reverso_c'        => $this->fechaGuardar,
                'cau_anu'				=> $certificacioncontrato->cau_anu,
				'fec_sta'				=> $this->fechaGuardar,
                'sta_sol'               => 'C'
            ]);

            //---------------------------------------------------------------------------------
            //                        ASIENTO PRESUPUESTARIO
            //---------------------------------------------------------------------------------
            $ano_fiscal   = $this->anoPro;
            $tip_ope      = 96;
            $sol_tip      = "CO";
            $num_doc      =  $certificacioncontrato->xnro_sol;
            $rif_prov     =  $certificacioncontrato->rif_prov;
            $ano_pro      =  $certificacioncontrato->ano_pro;
            $concepto     =  "AJUSTE DEL COMPROMISO POR CONCEPTO DE CIERRE DE CONTRATO";
            $usuario      = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);

            //$OpDetgastossolservicio=OpDetgastossolservicio::where('op_solservicio_id','=',$certificacioncontrato->id)->get();
            foreach($certificacioncontrato->opdetgastossolservicio as $detalle){
                $monto_causado = 0;
                $cod_com_viejo    = MaestroLey::generarCodCom($detalle->tip_cod, $detalle->cod_pryacc, $detalle->cod_obj,$detalle->gerencia,
                                                        $detalle->unidad,$detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,
                                                        $detalle->cod_sub);
                $cod_com =  $cod_com_viejo;
                if ($this->anoPro!=$certificacioncontrato->ano_pro){
                     $cod_com=$this->generarCentroCosto($detalle->tip_cod, $detalle->cod_pryacc, $detalle->cod_obj,$detalle->gerencia,
                                                         $detalle->unidad,$detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,
                                                        $detalle->cod_sub, $certificacioncontrato->ano_pro);
                    //Ocurrio un error armando el codcom
                    if(empty($cod_com)){
                        alert()->error('¡Transacción Fallida!','Ocurrio error al Generar centro de Costo');

                        DB::connection('pgsql')->rollBack();
                       return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
                    }
                }
                $causado = $this->VerificarCausado($certificacioncontrato->ano_pro,$certificacioncontrato->xnro_sol,$cod_com,$cod_com_viejo,$detalle->cod_par,$detalle->cod_gen,$detalle->cod_esp,$detalle->cod_sub);
                $monto_causado += $causado;
                $diferencia = $detalle->mto_tra - $causado;
                if ($diferencia>0){
                    if($detalle->mto_tra!=0){
                        $movpre = DB::select("SELECT * FROM movimientopresupuestario('$ano_fiscal', '$cod_com', '$sol_tip','$tip_ope',
                                                                                '$num_doc', '$detalle->mto_tra', '$rif_prov', '$concepto', '0',
                                                                                '$usuario', '$ano_pro', '', '', '0', '$this->fechaGuardar')");
                        if(!$movpre){
                            DB::connection('pgsql')->rollBack();
                            alert()->error('¡Transacción Fallida!','Ocurrio error al ejecutar Movimiento Presupuestario');
                            return redirect()->back()->withInput();
                        }
                    }
                }
                $detalle->update(['saldo'=> 0 ]);
            }
            alert()->success('¡Éxito!',  $process .' '.$certificacioncontrato->ano_pro."-". $certificacioncontrato->xnro_sol. 'Cerrada Sastifactoriamente');
            DB::connection('pgsql')->commit();
           return redirect()->route('contratos.proceso.'.$this->ObtenerRuta($certificacioncontrato->xnro_sol).'.index');
        }
    } catch(\Illuminate\Database\QueryException $e){
        DB::connection('pgsql')->rollBack();
        alert()->error('¡Transacción Fallida!', $e->getMessage());
        return redirect()->back()->withInput();
    }

}
//----------------------------------------------------------------------------------------------
// Funcion que calcula el monto causada de una orden de compra para poder proceder a cerrarla
//------------------------------------------------------------------------------------------------
public function VerificarCausado($ano_pro,$xnro_ord,$cod_com,$cod_com_viejo,$cod_par,$cod_gen,$cod_esp,$cod_sub){
		$causado = 0;

		$result = DB::select("SELECT sum(a.monto) as monto,
						a.tip_cod, a.cod_pryacc, a.cod_obj, a.gerencia, a.unidad,
						a.cod_par, a.cod_gen, a.cod_esp, a.cod_sub, a.cod_com
					FROM
						(SELECT
							sum(a.mto_cau) as monto,
							a.tip_cod, a.cod_pryacc, a.cod_obj, a.gerencia, a.unidad,
							a.cod_par, a.cod_gen, a.cod_esp, a.cod_sub, a.cod_com
						FROM com_detgastosnotaentrega a
							INNER JOIN com_encnotaentrega B ON b.fk_ano_pro=a.ano_pro and b.grupo=a.grupo and b.nro_ent=a.nro_ent
						WHERE b.ano_ord_com=$ano_pro and b.xnro_ord='$xnro_ord' and b.sta_ent not in ('0','5','8')
							and (a.cod_com='$cod_com' or a.cod_com='$cod_com_viejo')
						GROUP by 2,3,4,5,6,7,8,9,10,11
						UNION
						SELECT
							sum(a.monto) as monto,
							a.tip_cod, a.cod_pryacc, a.cod_obj, a.gerencia, a.unidad,
							a.cod_par, a.cod_gen, a.cod_esp, a.cod_sub, a.cod_com
						FROM cxp_detgastossolpago_prov a
							INNER JOIN cxp_solpagoanticipoprov b on b.ano_form=a.ano_pro and b.num_anticipo=a.num_anticipo
							INNER JOIN op_solservicio c on c.ano_pro=b.ano_sol and c.xnro_sol=b.xnum_orden
						WHERE b.ano_sol=$ano_pro and b.xnum_orden='$xnro_ord' and c.ant_old='1' and
							b.status in ('1','2','6')
							and (a.cod_com='$cod_com' or a.cod_com='$cod_com_viejo')
						GROUP by 2,3,4,5,6,7,8,9,10,11) a
					GROUP by 2,3,4,5,6,7,8,9,10,11");
		$total=0;
		for ($i=0;$i<count($result);$i++){
			$row = $result[$i]; #Obtener Fila
			$total = $total + $row->monto;
		}
		if ($total>0){
			$causado = $total;
		}
		return $causado;
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
            case "C":
                $descrip_estado="Cerrada Presupuestariamente";
                break;
        }
        return $descrip_estado;
    }


public function print_certificacionobras(Request $request)
{
        //-------------------------------------------------------------------------------------------
        //                       Encabezado de La Certificacion de Pagos Directos
        //-------------------------------------------------------------------------------------------

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
                        INNER JOIN op_conceptos_contrato ON(op_conceptos_contrato.cod_con=op_detsolservicio.cod_prod)
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
                        ->first();
        if(is_null($detalle_aprobar)){$nombre_aprobar='';}else{$nombre_aprobar=$detalle_aprobar->nombre;}
        //-------------------------------------------------------------
        // Nombre de la persona que Compromete la soilcitud
        //-------------------------------------------------------------
        $detalle_compromete = OpSolservicio::query()
                        ->join('usuarios', 'op_solservicio.usua_comp', '=', 'usuarios.usuario')
                        ->where('ano_pro',$request->ano_pro)
                        ->where('xnro_sol',$request->xnro_sol)
                        ->select('usuarios.nombre')
                        ->first();
        if(is_null($detalle_compromete)){$nombre_comprometer='';}else{$nombre_comprometer=$detalle_compromete->nombre;}
       //--------------------------------------------------------------------------------------------
        //                                Datos de la Empresa
        //--------------------------------------------------------------------------------------------
        $datos_empresa = DatosEmpresa::query()
                                     ->where('cod_empresa','01')
                                     ->get();

        if ($cont_enc > 0 &&  $cont_det >0)
        {

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
            $this->ObtenerRuta($request->xnro_sol)=='certificacioncontratoaddendum'?$titulo='ADDENDUM DE OBRAS / SERVICIOS':$titulo='CONTRATO DE OBRAS / SERVICIOS';

            $pdf->SetFont('Arial','B',15);
            $pdf->SetY(15);
            $pdf->SetX(80);
            $pdf->Cell(85,4,$titulo,0,0,'C',0);

            // control y vigencia de registros
            $pdf->SetFont('Arial','B',6);
            $pdf->SetY(10);
            $pdf->SetX(198);
            $pdf->Cell(60,4,'Codigo: F-UG-011',0,0,'R',0);

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
                $pdf->SetX(148);
                $pdf->Cell(50,5,utf8_decode('Número MERU'),1,0,'C',1);
                $pdf->SetY($Y_Fields_Name_position);
                $pdf->SetX(198);
                $pdf->Cell(50,5,utf8_decode('Número Contrato'),1,0,'C',1);
                $pdf->SetX(248);
                $pdf->Cell(30,5,'Fecha',1,0,'C',1);
                $pdf->Ln();



                $pdf->SetFillColor(255,255,255);
                $pdf->SetY($Y_Fields_Name_position + 5);
                $pdf->SetX(148);
                $pdf->Cell(50,5,$datos_sol[0]->xnro_sol,1,0,'C',1);
                $pdf->SetY($Y_Fields_Name_position + 5);
                $pdf->SetX(198);
                $pdf->Cell(50,5,$datos_sol[0]->num_contrato,1,0,'C',1);
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

                $pdf->Cell(90,7,'Nombre: '.utf8_decode($nombre_aprobar),1,0,'L',1);
                $pdf->SetX(182);
                $pdf->Cell(96,7,'Nombre: '.utf8_decode($nombre_comprometer),1,0,'L',1);
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
    public function print_certificacion_contrato($accion)
    {


       if ($accion=='addendum'){
                $query = OpSolservicio::query()
                ->select(
                    DB::raw("ano_pro"),
                    DB::raw("xnro_sol"),
                    DB::raw("des_ger"),
                    DB::raw("nom_prov"),
                    DB::raw("monto_total"),
                    DB::raw( "fec_emi"))
                    ->where('grupo','=','CO')
                    ->where('ult_sol','=','-1')
                    ->join('gerencias AS g', 'g.cod_ger', '=', 'op_solservicio.cod_ger')
                    ->join('proveedores AS p', 'p.rif_prov', '=', 'op_solservicio.rif_prov')
                    ->orderby('ano_pro','desc')->orderby('xnro_sol','desc')->get();
       }else{
                $query =OpSolservicio::query()
                                    ->select(
                                        DB::raw("ano_pro"),
                                        DB::raw("xnro_sol"),
                                        DB::raw("des_ger"),
                                        DB::raw("nom_prov"),
                                        DB::raw("monto_total"),
                                        DB::raw( "fec_emi"))
                                        ->where('grupo','=','CO')
                                        ->join('gerencias AS g', 'g.cod_ger', '=', 'op_solservicio.cod_ger')
                                        ->join('proveedores AS p', 'p.rif_prov', '=', 'op_solservicio.rif_prov')
                                        ->orderby('ano_pro','desc')->orderby('xnro_sol','desc')->get();
        }
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
        $data['nombre_documento']			= 'listado_certificacioncontrato.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = $query;
        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Certificación'));
        $this->pintar_listado_pdf($pdf,$data);
        exit;
    }

}


