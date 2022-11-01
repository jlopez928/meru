<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso;

use App\Traits\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Proceso\SolicitudUnidadRequest;
use App\Models\Administrativo\Meru_Administrativo\Compras\Bien;
use App\Models\Administrativo\Meru_Administrativo\General\Unidad;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;
use App\Models\Administrativo\Meru_Administrativo\Compras\Comprador;
use App\Models\Administrativo\Meru_Administrativo\Compras\TipoCompra;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetOfertaPro;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetSolicitud;
use App\Models\Administrativo\Meru_Administrativo\Compras\ServicioBien;
use App\Models\Administrativo\Meru_Administrativo\Compras\CausaAnulacion;
use App\Models\Administrativo\Meru_Administrativo\Compras\CorrSolCompras;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetOrdenCompra;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetSolicitudDet;
use App\Models\Administrativo\Meru_Administrativo\Compras\SolicitudUnidad;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetSolCotizacion;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_administrativo\Configuracion\UnidadTributaria;

class SolicitudUnidadController extends Controller
{
    use Presupuesto;

    public function index()
    {
        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.index');
    }

    public function create(Request $request)
    {

        $opcion = $request->opcion;
        $solicitudUnidad = new SolicitudUnidad();
        $accion = 'nuevo';
        $years = RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
        $ramos = Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
        $gerencias = Gerencia::query()->where('status', 1)->where('aplica_pre', $opcion)->orderBy('des_ger')->pluck('des_ger','cod_ger');
        $compradores = Comprador::with('usuariot:usuario,nombre')->where('sta_reg', 1)->get(['cod_com','usu_com'])->sortBy('usuariot.nombre')->pluck('usuariot.nombre','cod_com');
        $tipoDeCompras = TipoCompra::query()->whereNot('cod_tipocompra', '0')->orderBy('des_tipocompra')->pluck('des_tipocompra','cod_tipocompra');
        $causaAnulacion = CausaAnulacion::query()->orderBy('des_cau')->pluck('des_cau','cod_cau');

        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.create', compact('solicitudUnidad','years','ramos','gerencias','compradores','tipoDeCompras','opcion','accion','causaAnulacion'));
    }

    public function crear_solicitud(Request $request)
    {
        $request->validate([
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
        ]);

        try {
             // Buscar el CorrelativoComprobante
            $corr_nro_req = CorrSolCompras::getCorrSolCompras($this->anoPro, $request->grupo);

            $nro_req = $corr_nro_req == 0 ? 1 : $corr_nro_req;

            if(is_null($request->detalle_productos))
            {
                alert()->html('',   "Debe agregar al menos un Bien/Material/Servicio en el detalle de la Solicitud<br>".
                                    "Por Favor Verifique",'warning')
                                    ->showConfirmButton('Ok', '#3085d6');

                return redirect()->back()->withInput();
            }

            if(is_null($request->vehiculos) && ($request->grupo == "SV"))
            {
                alert()->html('',   "Debe agregar al menos un Bien al que se le aplique el servicio especificado en la Solicitud<br>".
                                    "Por Favor Verifique",'warning')
                                    ->showConfirmButton('Ok', '#3085d6');

                return redirect()->back()->withInput();
            }

            DB::connection('pgsql')->transaction(function () use($request, $nro_req){

                // Se Busca la ultima unidad tributaria
                $unidadTributaria = UnidadTributaria::getUltimaUnidadTributaria();

                $total_bs_ut = round($request->monto_tot / $unidadTributaria->bs_ut, 2);

                $contratante = SolicitudUnidad::obtenerUnidadContratante($request->grupo, $total_bs_ut);

                $productos  = json_decode($request->productos);
                $detalles   = json_decode($request->detalle_productos);
                $vehiculos  = json_decode($request->vehiculos);

                SolicitudUnidad::create([
                    'ano_pro'      =>   $this->anoPro,
                    'grupo'        =>   $request->grupo,
                    'nro_req'      =>   $nro_req,
                    'fk_cod_ger'   =>   $request->fk_cod_ger,
                    'fec_emi'      =>   $this->fechaGuardar,
                    'cla_sol'      =>   $request->cla_sol,
                    'pri_sol'      =>   $request->pri_sol,
                    'jus_sol'      =>   $request->jus_sol,
                    'sta_sol'      =>   0,
                    'fec_sta'      =>   $this->fechaGuardar,
                    'usuario'      =>   auth()->user()->id,
                    'monto_tot'    =>   $request->monto_tot,
                    'donacion'     =>   $request->donacion,
                    'gru_ram'      =>   $request->gru_ram,
                    'cod_uni'      =>   $request->cod_uni,
                    'anexos'       =>   $request->anexos,
                    'aplica_pre'   =>   $request->aplica_pre,
                    'contratante'  =>   $contratante
                ]);

                foreach ($productos as $producto) {

                    $cod_com = DetSolicitud::getCodCom($request->tip_cod, $request->cod_pryacc, $request->cod_obj, $request->gerencia, $request->unidad, $producto->cod_par, $producto->cod_gen, $producto->cod_esp, $producto->cod_sub);

                    DetSolicitud::create([
                        'nro_req'     => $nro_req,
                        'grupo'       => $request->grupo,
                        'ano_pro'     => $this->anoPro,
                        'nro_ren'     => $producto->nro_ren,
                        'fk_cod_mat'  => $producto->fk_cod_mat,
                        'des_bien'    => $producto->des_bien,
                        'fk_cod_uni'  => $producto->fk_cod_uni,
                        'des_uni_med' => $producto->des_uni_med,
                        'cantidad'    => $producto->cantidad,
                        'sal_can'     => $producto->cantidad,
                        'tip_cod'     => $request->tip_cod,
                        'cod_pryacc'  => $request->cod_pryacc,
                        'cod_obj'     => $request->cod_obj,
                        'gerencia'    => $request->gerencia,
                        'unidad'      => $request->unidad,
                        'cod_par'     => $producto->cod_par,
                        'cod_gen'     => $producto->cod_gen,
                        'cod_esp'     => $producto->cod_esp,
                        'cod_sub'     => $producto->cod_sub,
                        'pre_ref'     => $producto->pre_ref,
                        'tot_ref'     => $producto->tot_ref,
                        'sta_reg'     => '0',
                        'cod_com'     => $cod_com,
                        'cant_sal'    => $producto->cantidad
                    ]);
                }

                foreach ($detalles as $detalle) {
                    DetSolicitudDet::create([
                        'ano_pro'     => $this->anoPro,
                        'grupo'       => $request->grupo,
                        'nro_req'     => $nro_req,
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
                        'sta_reg'     => '0'
                    ]);
                }

                if ($request->grupo=='SV')
                {
                    foreach ($vehiculos as $vehiculo) {
                        ServicioBien::create([
                            'nro_req'     => $nro_req,
                            'grupo'       => $request->grupo,
                            'ano_pro'     => $this->anoPro,
                            'cod_corr'    => $vehiculo->cod_corr,
                        ]);
                    }
                }

                // Actualizar el correlativo
                CorrSolCompras::incCorrSolCompras($this->anoPro, $request->grupo, $nro_req == 1 ? 2 : 1);
            });

            alert()->html('',   "SOLICITUD CREADA:<br>".
                                "* <strong>Número: </strong>".$nro_req."<br>".
                                "* <strong>Grupo: </strong>".$request->grupo."<br>".
                                "* <strong>Año: </strong>".$this->anoPro,'success')
                ->showConfirmButton('Ok', '#3085d6');

            return to_route('compras.proceso.solicitud_unidad.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function edit($ano_pro, $grupo, $nro_req)
    {
        $solicitudUnidad = SolicitudUnidad::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();

        if(($solicitudUnidad->sta_sol != '0') && ($solicitudUnidad->sta_sol != '2'))
        {
            $accion = 'editar_anexos';
        }else{
            $accion = 'editar';
        }

        $years = RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
        $ramos = Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
        $gerencias = Gerencia::query()->where('status', 1)->orderBy('des_ger')->pluck('des_ger','cod_ger');
        $compradores = Comprador::with('usuariot:usuario,nombre')->where('sta_reg', 1)->get(['cod_com','usu_com'])->sortBy('usuariot.nombre')->pluck('usuariot.nombre','cod_com');
        $tipoDeCompras = TipoCompra::query()->whereNot('cod_tipocompra', '0')->orderBy('des_tipocompra')->pluck('des_tipocompra','cod_tipocompra');
        $causaAnulacion = CausaAnulacion::query()->orderBy('des_cau')->pluck('des_cau','cod_cau');

        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.edit', compact('solicitudUnidad','years','ramos','gerencias','compradores','tipoDeCompras','accion','causaAnulacion'));
    }

    public function editar_anexos(Request $request)
    {
        $request->validate([
            'anexos'       => 'required',
        ]);

        try {
            DB::connection('pgsql')->transaction(function () use($request){
                $usuario				= auth()->user()->id;
                $fecha_sistema          = $this->fechaGuardar;

                SolicitudUnidad::query()
                                ->where('ano_pro', $request->ano_pro)
                                ->where('grupo', $request->grupo)
                                ->where('nro_req', $request->nro_req)
                                ->update([
                                            'anexos'        => $request->anexos,
                                            'fec_ane'       => $fecha_sistema,
                                            'usuario'       => $usuario,
                                        ]);
            });

            alert()->success('Éxito','Anexos de la Solicitud de Compras '. $request->grupo.'-'.$request->nro_req.'-'.$request->ano_pro .' Modificado Exitosamente.');

            return to_route('compras.proceso.solicitud_unidad.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function editar_solicitud(Request $request)
    {
        $request->validate([
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
        ]);

        // $solicitud      = $this->getEncSolicitud($request->ano_pro, $request->grupo, $request->nro_req);

        // if(($solicitud->sta_sol == '3') || ($solicitud->sta_sol == '51'))
        // {
        //     alert()->html('',"Por favor Verifique que la Solicitud no este Anulada o Reversada<br>".
        //                     "Estatus Actual de la Solicitud: <br><strong>".$solicitud->sta_des ."</strong>",'warning')
        //                     ->showConfirmButton('Ok', '#3085d6');

        //     return redirect()->route('compras.proceso.solicitud_unidad.index');
        // }else{
        //     try {
        //         DB::connection('pgsql')->transaction(function () use($request, $solicitud){

        //             // Se Busca la ultima unidad tributaria
        //             $unidadTributaria = UnidadTributaria::getUltimaUnidadTributaria();

        //             $total_bs_ut = round($request->monto_tot / $unidadTributaria->bs_ut, 2);

        //             $contratante = SolicitudUnidad::obtenerUnidadContratante($request->grupo, $total_bs_ut);

        //             $productos  = json_decode($request->productos);
        //             $detalles   = json_decode($request->detalle_productos);
        //             $vehiculos  = json_decode($request->vehiculos);

                    // SolicitudUnidad::create([
                    //     'ano_pro'      =>   $this->anoPro,
                    //     'grupo'        =>   $request->grupo,
                    //     'nro_req'      =>   $nro_req,
                    //     'fk_cod_ger'   =>   $request->fk_cod_ger,
                    //     'fec_emi'      =>   $this->fechaGuardar,
                    //     'cla_sol'      =>   $request->cla_sol,
                    //     'pri_sol'      =>   $request->pri_sol,
                    //     'jus_sol'      =>   $request->jus_sol,
                    //     'sta_sol'      =>   0,
                    //     'fec_sta'      =>   $this->fechaGuardar,
                    //     'usuario'      =>   auth()->user()->id,
                    //     'monto_tot'    =>   $request->monto_tot,
                    //     'donacion'     =>   $request->donacion,
                    //     'gru_ram'      =>   $request->gru_ram,
                    //     'cod_uni'      =>   $request->cod_uni,
                    //     'anexos'       =>   $request->anexos,
                    //     'aplica_pre'   =>   $request->aplica_pre,
                    //     'contratante'  =>   $contratante
                    // ]);

                    // foreach ($productos as $producto) {

                    //     $cod_com = DetSolicitud::getCodCom($request->tip_cod, $request->cod_pryacc, $request->cod_obj, $request->gerencia, $request->unidad, $producto->cod_par, $producto->cod_gen, $producto->cod_esp, $producto->cod_sub);

                    //     DetSolicitud::create([
                    //         'nro_req'     => $nro_req,
                    //         'grupo'       => $request->grupo,
                    //         'ano_pro'     => $this->anoPro,
                    //         'nro_ren'     => $producto->nro_ren,
                    //         'fk_cod_mat'  => $producto->fk_cod_mat,
                    //         'des_bien'    => $producto->des_bien,
                    //         'fk_cod_uni'  => $producto->fk_cod_uni,
                    //         'des_uni_med' => $producto->des_uni_med,
                    //         'cantidad'    => $producto->cantidad,
                    //         'sal_can'     => $producto->cantidad,
                    //         'tip_cod'     => $request->tip_cod,
                    //         'cod_pryacc'  => $request->cod_pryacc,
                    //         'cod_obj'     => $request->cod_obj,
                    //         'gerencia'    => $request->gerencia,
                    //         'unidad'      => $request->unidad,
                    //         'cod_par'     => $producto->cod_par,
                    //         'cod_gen'     => $producto->cod_gen,
                    //         'cod_esp'     => $producto->cod_esp,
                    //         'cod_sub'     => $producto->cod_sub,
                    //         'pre_ref'     => $producto->pre_ref,
                    //         'tot_ref'     => $producto->tot_ref,
                    //         'sta_reg'     => '0',
                    //         'cod_com'     => $cod_com,
                    //         'cant_sal'    => $producto->cantidad
                    //     ]);
                    // }

                    // foreach ($detalles as $detalle) {
                    //     DetSolicitudDet::create([
                    //         'ano_pro'     => $this->anoPro,
                    //         'grupo'       => $request->grupo,
                    //         'nro_req'     => $nro_req,
                    //         'nro_ren'     => $detalle->nro_ren,
                    //         'fk_cod_mat'  => $detalle->fk_cod_mat,
                    //         'descripcion' => $detalle->descripcion,
                    //         'fk_cod_uni'  => $detalle->fk_cod_uni,
                    //         'des_uni_med' => $detalle->des_uni_med,
                    //         'cantidad'    => $detalle->cantidad,
                    //         'precio'      => $detalle->precio,
                    //         'total'       => $detalle->total,
                    //         'cod_par'     => $detalle->cod_par,
                    //         'cod_gen'     => $detalle->cod_gen,
                    //         'cod_esp'     => $detalle->cod_esp,
                    //         'cod_sub'     => $detalle->cod_sub,
                    //         'sta_reg'     => '0'
                    //     ]);
                    // }

                    // if ($request->grupo=='SV')
                    // {
                    //     foreach ($vehiculos as $vehiculo) {
                    //         ServicioBien::create([
                    //             'nro_req'     => $nro_req,
                    //             'grupo'       => $request->grupo,
                    //             'ano_pro'     => $this->anoPro,
                    //             'cod_corr'    => $vehiculo->cod_corr,
                    //         ]);
                    //     }
                    // }

                    // // Actualizar el correlativo
                    // CorrSolCompras::incCorrSolCompras($this->anoPro, $request->grupo, $nro_req == 1 ? 2 : 1);
            //     });

            //     alert()->html('',   "SOLICITUD CREADA:<br>".
            //                         "* <strong>Número: </strong>".$nro_req."<br>".
            //                         "* <strong>Grupo: </strong>".$request->grupo."<br>".
            //                         "* <strong>Año: </strong>".$this->anoPro,'success')
            //         ->showConfirmButton('Ok', '#3085d6');

            //     return to_route('compras.proceso.solicitud_unidad.index');
            // } catch (\Exception $ex) {
            //     alert()->error('Error', str($ex)->limit(250));

            //     return redirect()->back()->withInput();
            // }
        // }
    }



    public function show($ano_pro, $grupo, $nro_req)
    {
        $solicitudUnidad = SolicitudUnidad::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion = 'mostrar';
        $years = RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
        $ramos = Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
        $gerencias = Gerencia::query()->where('status', 1)->orderBy('des_ger')->pluck('des_ger','cod_ger');
        $compradores = Comprador::with('usuariot:usuario,nombre')->where('sta_reg', 1)->get(['cod_com','usu_com'])->sortBy('usuariot.nombre')->pluck('usuariot.nombre','cod_com');
        $tipoDeCompras = TipoCompra::query()->whereNot('cod_tipocompra', '0')->orderBy('des_tipocompra')->pluck('des_tipocompra','cod_tipocompra');
        $causaAnulacion = CausaAnulacion::query()->orderBy('des_cau')->pluck('des_cau','cod_cau');

        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.show', compact('solicitudUnidad','years','ramos','gerencias','compradores','tipoDeCompras','accion','causaAnulacion'));
    }

    public function copiar($ano_pro, $grupo, $nro_req)
    {
        $solicitudUnidad = SolicitudUnidad::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion = 'copiar';
        $years = RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
        $ramos = Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
        $gerencias = Gerencia::query()->where('status', 1)->orderBy('des_ger')->pluck('des_ger','cod_ger');
        $compradores = Comprador::with('usuariot:usuario,nombre')->where('sta_reg', 1)->get(['cod_com','usu_com'])->sortBy('usuariot.nombre')->pluck('usuariot.nombre','cod_com');
        $tipoDeCompras = TipoCompra::query()->whereNot('cod_tipocompra', '0')->orderBy('des_tipocompra')->pluck('des_tipocompra','cod_tipocompra');
        $causaAnulacion = CausaAnulacion::query()->orderBy('des_cau')->pluck('des_cau','cod_cau');

        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.copiar', compact('solicitudUnidad','years','ramos','gerencias','compradores','tipoDeCompras','accion','causaAnulacion'));
    }

    public function copiar_solicitud(Request $request)
    {
        $corr_nro_req   = CorrSolCompras::getCorrSolCompras($this->anoPro, $request->grupo);
        $solicitud      = $this->getEncSolicitud($request->ano_pro, $request->grupo, $request->nro_req);

        if(($solicitud->sta_sol != '3') && ($solicitud->sta_sol != '51'))
        {
            alert()->html('',"Por favor Verifique que la Solicitud tenga estatus:<br>".
                            "* <strong>ANULADA EN UNIDAD SOLICITANTE</strong><br>".
                            "* <strong>ANULADA EN ADMINISTRACIÓN</strong><br>".
                            "Estatus Actual de la Solicitud: <br><strong>".$solicitud->sta_des ."</strong>",'warning')
                            ->showConfirmButton('Ok', '#3085d6');

            return redirect()->route('compras.proceso.solicitud_unidad.index');
        }else{

            try {
                DB::connection('pgsql')->transaction(function () use($corr_nro_req, $solicitud){

                    SolicitudUnidad::create([
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

                    if ($solicitud->grupo=='SV')
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

                    CorrSolCompras::incCorrSolCompras($this->anoPro, $solicitud->grupo, 1);
                });

                alert()->html('',   "SOLICITUD CREADA:<br>".
                                    "* <strong>Número: </strong>".$corr_nro_req."<br>".
                                    "* <strong>Grupo: </strong>".$request->grupo."<br>".
                                    "* <strong>Año: </strong>".$this->anoPro,'success')
                    ->showConfirmButton('Ok', '#3085d6');

                return to_route('compras.proceso.solicitud_unidad.index');
            } catch (\Exception $ex) {
                alert()->error('Error', str($ex)->limit(250));

                return redirect()->back()->withInput();
            }
        }
    }

    public function reversar($ano_pro, $grupo, $nro_req)
    {
        $solicitudUnidad = SolicitudUnidad::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion = 'reversar';
        $years = RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
        $ramos = Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
        $gerencias = Gerencia::query()->where('status', 1)->orderBy('des_ger')->pluck('des_ger','cod_ger');
        $compradores = Comprador::with('usuariot:usuario,nombre')->where('sta_reg', 1)->get(['cod_com','usu_com'])->sortBy('usuariot.nombre')->pluck('usuariot.nombre','cod_com');
        $tipoDeCompras = TipoCompra::query()->whereNot('cod_tipocompra', '0')->orderBy('des_tipocompra')->pluck('des_tipocompra','cod_tipocompra');
        $causaAnulacion = CausaAnulacion::query()->orderBy('des_cau')->pluck('des_cau','cod_cau');

        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.reversar', compact('solicitudUnidad','years','ramos','gerencias','compradores','tipoDeCompras','accion','causaAnulacion'));
    }

    public function reversar_solicitud(Request $request)
    {
        $request->validate([
            'fec_anu'       => 'required',
            'fk_cod_cau'    => 'required',
        ]);

        $solicitud = $this->getEncSolicitud($request->ano_pro, $request->grupo, $request->nro_req);

        if(($solicitud->sta_sol != '5') && ($solicitud->sta_sol != '61') && ($solicitud->sta_sol != '63'))
        {
            alert()->html('',"Por favor Verifique que la Solicitud tenga estatus:<br>".
                            "* <strong>APROBADA</strong><br>".
                            "* <strong>DEVUELTA EN LOGISTICA</strong><br>".
                            "* <strong>DEVUELTA EN CONTRATACIONES</strong><br>".
                            "Estatus Actual de la Solicitud: <br><strong>".$solicitud->sta_des ."</strong>",'warning')
                            ->showConfirmButton('Ok', '#3085d6');

            return redirect()->route('compras.proceso.solicitud_unidad.index');
        }else{

            try {
                DB::connection('pgsql')->transaction(function () use($request, $solicitud){
                    $usuario				= auth()->user()->id;
                    $ano_fiscal				= $this->anoPro;
                    $fecha_sistema          = $this->fechaGuardar;

                    $tip_ope				= 9;
                    $sol_tip				= 'OC';
                    $num_doc				= $request->grupo."-".$request->nro_req;
                    $concepto				= "REVERSO DE PRE-COMPROMISO DE SOLICITUD DE COMPRAS";
                    $reverso				= 1;

                    if ($request->aplica_pre == '1')
                    {
                        $result_pre = DetSolicitud::getEstructurasPresupuestarias($request->ano_pro, $request->grupo, $request->nro_req);

                        if(count($result_pre) == 0)
                        {
                            alert()->html('',"No se Encontraron estructuras presupuestarias",'warning')->showConfirmButton('Ok', '#3085d6');

                            return to_route('compras.proceso.solicitud_unidad.index');
                        }

                        foreach ($result_pre as $item) {
                            if($item->mto_tra != 0)
                            {
                                DB::connection('pgsql')->select("  SELECT *
                                                                    FROM movimientopresupuestario('$ano_fiscal','$item->cod_com', '$sol_tip',
                                                                    '$tip_ope', '$num_doc', '$item->mto_tra', '', '$concepto', '$reverso',
                                                                    '$usuario', '$request->ano_pro', '', '', '0', '$fecha_sistema')");
                            }
                        }
                    }

                    SolicitudUnidad::query()
                                    ->where('ano_pro', $request->ano_pro)
                                    ->where('grupo', $request->grupo)
                                    ->where('nro_req', $request->nro_req)
                                    ->update([
                                                'usu_sta'       => $usuario,
                                                'sta_ant'       => $solicitud->sta_sol,
                                                'fec_ant'       => $solicitud->fec_sta,
                                                'fec_anu'       => $fecha_sistema,
                                                'fec_sta'       => $fecha_sistema,
                                                'sta_sol'       => '51',
                                                'fk_cod_cau'    => $request->fk_cod_cau
                                            ]);
                });

                alert()->success('Éxito','Solicitud de Compras '. $request->grupo.'-'.$request->nro_req.'-'.$request->ano_pro .' ha sido REVERSADA Exitosamente.');

                return to_route('compras.proceso.solicitud_unidad.index');
            } catch (\Exception $ex) {
                alert()->error('Error', str($ex)->limit(250));

                return redirect()->back()->withInput();
            }
        }
    }

    public function anular($ano_pro, $grupo, $nro_req)
    {
        $solicitudUnidad = SolicitudUnidad::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion = 'anular';
        $years = RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
        $ramos = Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
        $gerencias = Gerencia::query()->where('status', 1)->orderBy('des_ger')->pluck('des_ger','cod_ger');
        $compradores = Comprador::with('usuariot:usuario,nombre')->where('sta_reg', 1)->get(['cod_com','usu_com'])->sortBy('usuariot.nombre')->pluck('usuariot.nombre','cod_com');
        $tipoDeCompras = TipoCompra::query()->whereNot('cod_tipocompra', '0')->orderBy('des_tipocompra')->pluck('des_tipocompra','cod_tipocompra');
        $causaAnulacion = CausaAnulacion::query()->orderBy('des_cau')->pluck('des_cau','cod_cau');

        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.anular', compact('solicitudUnidad','years','ramos','gerencias','compradores','tipoDeCompras','accion','causaAnulacion'));
    }

    public function anular_solicitud(Request $request)
    {
        $request->validate([
            'fec_anu'       => 'required',
            'fk_cod_cau'    => 'required',
        ]);

        $solicitud = $this->getEncSolicitud($request->ano_pro, $request->grupo, $request->nro_req);

        if(($solicitud->sta_sol != '0') && ($solicitud->sta_sol != '41') && ($solicitud->sta_sol != '12'))
        {
            alert()->html('',"Por favor Verifique que la Solicitud tenga algun estatus <strong>SOLO CREADA o CONFORMADA EN PRESUPUESTO</strong>"."<br>Estatus Actual de la Solicitud: <br><strong>".$solicitud->sta_des ."</strong>",'warning')->showConfirmButton('Ok', '#3085d6');

            return to_route('compras.proceso.solicitud_unidad.index');
        }else{

            try{
                DB::connection('pgsql')->transaction(function () use($request){

                    SolicitudUnidad::query()
                                    ->where('ano_pro', $request->ano_pro)
                                    ->where('grupo', $request->grupo)
                                    ->where('nro_req', $request->nro_req)
                                    ->update([
                                                'usu_sta'       => auth()->user()->id,
                                                'fec_anu'       => $this->fechaGuardar,
                                                'fec_sta'       => $this->fechaGuardar,
                                                'sta_sol'       => '3',
                                                'fk_cod_cau'    => $request->fk_cod_cau,
                                                'sta_ant'       => '3',
                                                'fec_ant'       => $this->fechaGuardar
                                            ]);
                });

                    alert()->success('Éxito','Solicitud de Compras '. $request->grupo.'-'.$request->nro_req.'-'.$request->ano_pro .' ANULADA Exitosamente.');

                return to_route('compras.proceso.solicitud_unidad.index');
            } catch (\Exception $ex) {
                alert()->error('Error', str($ex)->limit(250));

                return redirect()->back()->withInput();
            }
        }

    }

    public function activar($ano_pro, $grupo, $nro_req)
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

        $solicitud = $this->getEncSolicitud($ano_pro, $grupo, $nro_req);

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
            alert()->html('',"Por favor Verifique que la Solicitud tenga estatus <strong>ANULADA EN UNIDAD SOLICITANTE</strong>"."<br>Estatus Actual de la Solicitud: <br><strong>".$solicitud->sta_des ."</strong>",'warning')->showConfirmButton('Ok', '#3085d6');

            return to_route('compras.proceso.solicitud_unidad.index');
        }else{
            try {

                DB::connection('pgsql')->transaction(function () use ($ano_pro, $grupo, $nro_req, $datos) {

                    SolicitudUnidad::query()
                                    ->where('ano_pro', $ano_pro)
                                    ->where('grupo', $grupo)
                                    ->where('nro_req', $nro_req)
                                    ->update($datos);
                });

                alert()->success('Éxito','Solicitud ACTIVADA Exitosamente.');

                return to_route('compras.proceso.solicitud_unidad.index');
            } catch (\Exception $ex) {
                alert()->error('Error', str($ex)->limit(250));

                return redirect()->back()->withInput();
            }
        }

    }

    public function aprobar($ano_pro, $grupo, $nro_req)
    {
        $solicitud = $this->getEncSolicitud($ano_pro, $grupo, $nro_req);

        if(($solicitud->sta_sol != '0'))
        {
            alert()->html('',"Por favor Verifique que la Solicitud tenga estatus <strong>CREADA</strong>"."<br>Estatus Actual de la Solicitud: <br><strong>".$solicitud->sta_des ."</strong>",'warning')->showConfirmButton('Ok', '#3085d6');

            return to_route('compras.proceso.solicitud_unidad.index');
        }else{
            try {

                DB::connection('pgsql')->transaction(function () use ($ano_pro, $grupo, $nro_req) {

                    SolicitudUnidad::query()
                                    ->where('ano_pro', $ano_pro)
                                    ->where('grupo', $grupo)
                                    ->where('nro_req', $nro_req)
                                    ->update([
                                                'usu_sta'  => auth()->user()->id,
                                                'fec_aut'  => $this->fechaGuardar,
                                                'fec_sta'  => $this->fechaGuardar,
                                                'sta_sol'  => '12',
                                                'sta_ant'  => '12',
                                                'fec_ant'  => $this->fechaGuardar
                                            ]);
                });

                alert()->success('Éxito','Solicitud de Compras '. $grupo.'-'.$nro_req.'-'.$ano_pro .' CONFORMADA EN PRESUPUESTO Exitosamente.');

                return to_route('compras.proceso.solicitud_unidad.index');
            } catch (\Exception $ex) {
                alert()->error('Error', str($ex)->limit(250));

                return redirect()->back()->withInput();
            }
        }
    }


    public function precomprometer($ano_pro, $grupo, $nro_req)
    {
        $solicitudUnidad = SolicitudUnidad::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion = 'precomprometer';
        $years = RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
        $ramos = Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
        $gerencias = Gerencia::query()->where('status', 1)->orderBy('des_ger')->pluck('des_ger','cod_ger');
        $compradores = Comprador::with('usuariot:usuario,nombre')->where('sta_reg', 1)->get(['cod_com','usu_com'])->sortBy('usuariot.nombre')->pluck('usuariot.nombre','cod_com');
        $tipoDeCompras = TipoCompra::query()->whereNot('cod_tipocompra', '0')->orderBy('des_tipocompra')->pluck('des_tipocompra','cod_tipocompra');
        $causaAnulacion = CausaAnulacion::query()->orderBy('des_cau')->pluck('des_cau','cod_cau');

        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.precomprometer', compact('solicitudUnidad','years','ramos','gerencias','compradores','tipoDeCompras','accion','causaAnulacion'));
    }

    public function precomprometer_solicitud(Request $request)
    {
        $request->validate([
            'fec_pcom'       => 'required',
        ]);

        $solicitud = $this->getEncSolicitud($request->ano_pro, $request->grupo, $request->nro_req);

        if($solicitud->sta_sol != '12')
        {
            alert()->html('',"Por favor Verifique que la Solicitud tenga estatus CONFORMADA EN PRESUPUESTO.<br>".
                            "Estatus Actual de la Solicitud: <br><strong>".$solicitud->sta_des ."</strong>",'warning')
                            ->showConfirmButton('Ok', '#3085d6');

            return redirect()->route('compras.proceso.solicitud_unidad.index');
        }else{

            try {
                $consulta = SolicitudUnidad::estructuraGastoSolicitudSeleccionada($solicitud->ano_pro, $solicitud->grupo, $solicitud->nro_req);
                $mensaje = '';

                if (count($consulta) != 0) {
                    foreach($consulta as $item)
                    {
                        if($item->dif_dis_tot < 0)
                        {
                            $mensaje .= "[". $item->cod_com ."]".
                                        "<br> Disponibilidad en Partida: ". $item->mto_dis .
                                        "<br> Monto a Pre-Comprometer en la Solicitud Actual: ". $item->sum_tot_ref .
                                        "<br> Monto Faltante en Partida para procesar Solicitud: ". ($item->dif_dis_tot * -1) ."<br><br>";
                        }

                    }

                    if ($mensaje != "") {
                        alert()->html('',
                                        "Disculpe para este momento no tienen disponibilidad <br>".
                                        "las Estructuras de Gastos siguientes:<br><br>".
                                        $mensaje.
                                        "Puede Solicitar traspasos para Procesar la Solicitud."
                                        ,'warning')
                                        ->showConfirmButton('Ok', '#3085d6');

                        return to_route('compras.proceso.solicitud_unidad.index');
                    } else {
                        DB::connection('pgsql')->transaction(function () use($request, $solicitud){
                            $usuario				= auth()->user()->id;
                            $ano_fiscal				= $this->anoPro;
                            $fecha_sistema          = $this->fechaGuardar;

                            $tip_ope				= 8;
                            $sol_tip				= 'OC';
                            $num_doc				= $request->grupo."-".$request->nro_req;
                            $concepto				= "PRE-COMPROMISO DE SOLICITUD DE COMPRA";
                            $reverso				= '0';

                            if ($request->aplica_pre == '1')
                            {
                                $result_pre = DetSolicitud::getEstructurasPresupuestarias($request->ano_pro, $request->grupo, $request->nro_req);

                                if(count($result_pre) == 0)
                                {
                                    alert()->html('',"No se Encontraron estructuras presupuestarias",'warning')->showConfirmButton('Ok', '#3085d6');

                                    return to_route('compras.proceso.solicitud_unidad.index');
                                }

                                foreach ($result_pre as $item) {
                                    if($item->mto_tra != 0)
                                    {
                                        DB::connection('pgsql')->select("  SELECT *
                                                                            FROM movimientopresupuestario('$ano_fiscal','$item->cod_com', '$sol_tip',
                                                                            '$tip_ope', '$num_doc', '$item->mto_tra', '', '$concepto', '$reverso',
                                                                            '$usuario', '$request->ano_pro', '', '', '0', '$fecha_sistema')");
                                    }
                                }
                            }

                            SolicitudUnidad::query()
                                            ->where('ano_pro', $request->ano_pro)
                                            ->where('grupo', $request->grupo)
                                            ->where('nro_req', $request->nro_req)
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
                    alert()->html('',"ERROR: No se encontró coincidencias en el Maestro de Ley<br>".
                                    "para las Estructuras de Gastos de la Solicitud Seleccionada. <br>".
                                    "Por Favor Verifique.",'warning')
                                    ->witd(600)
                                    ->showConfirmButton('Ok', '#3085d6');

                    return to_route('compras.proceso.solicitud_unidad.index');
                }

                alert()->success('Éxito','Solicitud de Compras '. $request->grupo.'-'.$request->nro_req.'-'.$request->ano_pro .' ha sido APROBADA Exitosamente.');

                return to_route('compras.proceso.solicitud_unidad.index');
            } catch (\Exception $ex) {
                alert()->error('Error', str($ex)->limit(250));

                return redirect()->back()->withInput();
            }
        }
    }

    private function getEncSolicitud($ano_pro, $grupo, $nro_req)
    {
        return SolicitudUnidad::query()
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

    public function getUnidades($gerencia = null)
    {
        return Unidad::query()
                        ->where('status', 1)
                        ->where('cod_ger', $gerencia)
                        ->orderBy('des_uni')
                        ->get(['cod_uni', 'des_uni']);
    }

    public function getCentroCosto($gerencia = null)
    {
        $centroCosto = Gerencia::query()->where('cod_ger', $gerencia)->first('centro_costo');

        return str($centroCosto->centro_costo)->explode('.');
    }

    public function getCreditoAdicional($gerencia,$anopro)
    {
        $centroCosto = CentroCosto::query()
                                    ->join('public.gerencias as b', function($q) {
                                        $q->on('b.centro_costo','cod_cencosto')->where('b.status', 1);
                                    })
                                    ->where('ano_pro', $anopro)
                                    ->where('sta_reg', '1')
                                    ->where('b.cod_ger', $gerencia)
                                    ->orderBy('b.cod_ger')
                                    ->get(['b.cod_ger', 'cod_cencosto', 'cre_adi']);

        return $centroCosto;
    }

    public function getCentroCostoUnidades($anopro)
    {
        $centroCosto = CentroCosto::query()
                                    ->join('public.gerencias as b', function($q) {
                                        $q->on('b.centro_costo','cod_cencosto')->where('b.status', 1);
                                    })
                                    ->where('ano_pro', $anopro)
                                    ->where('sta_reg', '1')
                                    ->orderBy('b.cod_ger')
                                    ->get(['b.cod_ger', 'cod_cencosto', 'cre_adi']);

        return $centroCosto;
    }

    public function getUnidadTributaria()
    {
        return UnidadTributaria::getUltimaUnidadTributaria();
    }

    public function getRangosUnidadTributaria($licita = null)
    {
        return TipoCompra::query()->rangos($licita)->get(['cod_tipocompra','ut_bie_ser_des','ut_bie_ser_has']);
    }

    public function getOrdenes($ano_pro, $grupo, $nro_req)
    {
        return DetOrdenCompra::getOrdenes($ano_pro, $grupo, $nro_req);
    }

    public function getOfertas($ano_pro, $grupo, $nro_req)
    {
        return DetOfertaPro::getOfertas($ano_pro, $grupo, $nro_req);
    }

    public function getCotizaciones($ano_pro, $grupo, $nro_req)
    {
        return DetSolCotizacion::getCotizaciones($ano_pro, $grupo, $nro_req);
    }

}
