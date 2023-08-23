<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoModificacion;
use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoSolicitudTraspaso;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\DisminucionRequest;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\CorrModificaciones;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\Modificacion;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PartidaCedente;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;
use DB;
use Exception;
use Illuminate\Http\Request;

class InsubsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.modificaciones.movimientos.insubsistencias.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $insubsistencia = new Modificacion;
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.insubsistencias.create',
            compact('insubsistencia')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DisminucionRequest $request)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $estructurasCedentes   = json_decode($request->safe()->estructurasCedentes, true);
        $totalCed = $request->safe()->total_ced;
        $numDoc   = $request->safe()->num_doc;
        $multi    = $this->_esMulticentro($estructurasCedentes);

        if (($msj = $this->_validacionesUsuario($multi, $totalCed)) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        DB::beginTransaction();

        try {
            $corr = CorrModificaciones::find($this->anoPro);

            if (is_null($corr)) {
                $corr = CorrModificaciones::create([
                    'ano_pro' => $this->anoPro,
                    'nro_reg' => 1,
                    'nro_sol' => 1
                ]);
            }

            $mes = \Carbon\Carbon::parse($this->fechaGuardar)->format('m');
            $xnroMod = $this->anoPro . '-' . $mes . '-05-' . $corr->num_reg;

            // Crear cabecera Modificacion
            $insubsistencia = new Modificacion;
            $insubsistencia->ano_pro        = $this->anoPro;
            $insubsistencia->num_mes        = $mes;
            $insubsistencia->tip_ope        = 5;
            $insubsistencia->nro_mod        = $corr->num_reg;
            $insubsistencia->xnro_mod       = $xnroMod;
            $insubsistencia->tip_doc        = 9;
            $insubsistencia->num_doc        = $numDoc;
            $insubsistencia->fec_pos        = $this->fechaGuardar;
            $insubsistencia->fec_tra        = $this->fechaGuardar;
            $insubsistencia->concepto       = $request->safe()->concepto;
            $insubsistencia->justificacion  = $request->safe()->justificacion;
            $insubsistencia->sta_reg        = EstadoModificacion::Creado;
            $insubsistencia->usu_sta        = $username;
            $insubsistencia->user_id_status = $usuario->id;
            $insubsistencia->fec_sta        = $this->fechaGuardar;
            $insubsistencia->usuario        = $username;
            $insubsistencia->user_id        = $usuario->id;
            $insubsistencia->fecha          = $this->fechaGuardar;
            $insubsistencia->save();

            // Crear Partidas Cedentes
            foreach ($estructurasCedentes as $row) {
                $maestroLey = MaestroLey::where('ano_pro', $this->anoPro)
                    ->where('cod_com', $row['cod_com'])
                    ->get(['mto_mod', 'mto_apa', 'mto_pre', 'mto_com', 'mto_cau', 'mto_pag'])
                    ->first();

                if (is_null($maestroLey)) {
                    throw new Exception(
                        'La estructura presupuestaria ' . $row['cod_com'] . ' no existe', -2706
                    );
                }

                PartidaCedente::create($row  + [
                    'xnro_mod' => $xnroMod,
                    'sdo_dis'  => $row['mto_dis'],
                    'sdo_mod'  => $maestroLey->mto_mod,
                    'sdo_apa'  => $maestroLey->mto_apa,
                    'sdo_pre'  => $maestroLey->mto_pre,
                    'sdo_com'  => $maestroLey->mto_com,
                    'sdo_cau'  => $maestroLey->mto_cau,
                    'sdo_pag'  => $maestroLey->mto_pag,
                ]);
            }

            $corr->increment('num_reg');

            /*
            if (!empty($numDoc)) {
                SolicitudTraspaso::where('ano_pro', $this->anoPro)
                    ->where('nro_sol', $numDoc)
                    ->update([
                        'sta_reg'        => EstadoSolicitudTraspaso::Procesada,
                        'usu_sta'        => $username,
                        'user_id_status' => $usuario->id,
                        'fec_sta'        => $this->fechaGuardar
                    ]);
            }
            */

            DB::commit();
            alert()->success('¡Éxito!', 'Insubsistencia creada exitosamente con el código: ' . $xnroMod);
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        } catch (Exception $e) {
            DB::rollBack();

            switch($e->getCode()) {
                case -2706: // Exception generada por desarrollador
                    $msg = $e->getMessage();
                    break;
                default:
                    $msg = $e->getMessage();
                    break;
            }

            alert()->error('¡Transacción Fallida!', $msg);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Modificacion $insubsistencia
     * @return \Illuminate\Http\Response
     */
    public function show(Modificacion $insubsistencia)
    {
        $partidasCedentes   = $insubsistencia->estructurasCedentes();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.insubsistencias.show',
            compact('insubsistencia', 'partidasCedentes')
        ); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Modificacion $insubsistencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Modificacion $insubsistencia)
    {
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.insubsistencias.edit',
            compact('insubsistencia')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Modificacion $insubsistencia
     * @return \Illuminate\Http\Response
     */
    public function update(DisminucionRequest $request, Modificacion $insubsistencia)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $estructurasCedentes   = json_decode($request->safe()->estructurasCedentes, true);
        $totalCed = $request->safe()->total_ced;
        $numDoc   = $request->safe()->num_doc;
        $multi    = $this->_esMulticentro($estructurasCedentes);

        if (($msj = $this->_validacionesUsuario($multi, $totalCed)) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        DB::beginTransaction();

        try {
            // Actualizar cabecera Modificacion
            $insubsistencia->num_doc        = $numDoc;
            $insubsistencia->concepto       = $request->safe()->concepto;
            $insubsistencia->justificacion  = $request->safe()->justificacion;
            $insubsistencia->sta_reg        = EstadoModificacion::Modificado;
            $insubsistencia->usu_sta        = $username;
            $insubsistencia->user_id_status = $usuario->id;
            $insubsistencia->fec_sta        = $this->fechaGuardar;
            $insubsistencia->save();

            // Eliminar estructuras Cedentes de Base de Datos
            PartidaCedente::where('xnro_mod', $insubsistencia->xnro_mod)->delete();

            // Crear Estructuras Cedentes
            foreach ($estructurasCedentes as $row) {
                $maestroLey = MaestroLey::where('ano_pro', $this->anoPro)
                    ->where('cod_com', $row['cod_com'])
                    ->get(['mto_mod', 'mto_apa', 'mto_pre', 'mto_com', 'mto_cau', 'mto_pag'])
                    ->first();

                if (is_null($maestroLey)) {
                    throw new Exception(
                        'La estructura presupuestaria ' . $row['cod_com'] . ' no existe', -2706
                    );
                }

                PartidaCedente::create($row  + [
                    'xnro_mod' => $insubsistencia->xnro_mod,
                    'sdo_dis'  => $row['mto_dis'],
                    'sdo_mod'  => $maestroLey->mto_mod,
                    'sdo_apa'  => $maestroLey->mto_apa,
                    'sdo_pre'  => $maestroLey->mto_pre,
                    'sdo_com'  => $maestroLey->mto_com,
                    'sdo_cau'  => $maestroLey->mto_cau,
                    'sdo_pag'  => $maestroLey->mto_pag,
                ]);
            }

            DB::commit();
            alert()->success('¡Éxito!', 'Insubsistencia modificado exitosamente');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        } catch (Exception $e) {
            DB::rollBack();

            switch($e->getCode()) {
                case -2706: // Exception generada por desarrollador
                    $msg = $e->getMessage();
                    break;
                default:
                    $msg = $e->getMessage();
                    break;
            }

            alert()->error('¡Transacción Fallida!', $msg);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Modificacion $insubsistencia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function anularEdit(Modificacion $insubsistencia)
    {
        if (!in_array($insubsistencia->sta_reg->value, [0, 4, 5]) ) {
            alert()->warning('¡Advertencia!', 'Insubsistencia en estado inválido para Anular');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        $partidasCedentes   = $insubsistencia->estructurasCedentes();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.insubsistencias.anular',
            compact('insubsistencia', 'partidasCedentes')
        ); 
    }

    public function anularUpdate(Request $request, Modificacion $insubsistencia)
    {
        $usuario   = auth()->user();
        $username  = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);

        DB::beginTransaction();

        try {
            $insubsistencia->update([
                'sta_reg' => EstadoModificacion::Anulado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            /*
            $solicitud = $insubsistencia->solicitudTraspaso;

            if (!is_null($solicitud)) {
                $solicitud->update([
                    'sta_reg' => EstadoSolicitudTraspaso::Aprobada,
                    'fec_sta' => $this->fechaGuardar,
                    'usu_sta' => $username,
                    'user_id_status' => $usuario->id
                ]);
            }
            */

            DB::commit();
            alert()->success('¡Éxito!', 'Registro Anulado Exitosamente');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function apartarEdit(Modificacion $insubsistencia)
    {
        if (!in_array($insubsistencia->sta_reg->value, [0, 3, 4, 5]) ) {
            alert()->warning('¡Advertencia!', 'Insubsistencia en estado inválido para Apartar');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        if (($msj = $this->_validacionesUsuario($insubsistencia->esMulticentro(), $insubsistencia->totalCedentes())) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        $partidasCedentes = $insubsistencia->estructurasCedentes();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.insubsistencias.apartar',
            compact('insubsistencia', 'partidasCedentes')
        ); 
    }

    public function apartarUpdate(Request $request, Modificacion $insubsistencia)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasCedentes = $insubsistencia->partidasCedentes;

        DB::beginTransaction();

        try {
            foreach($partidasCedentes as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $insubsistencia->ano_pro)
                                ->where('cod_com', $partida->cod_com)
                                ->first();

                if ($maestroLey->mto_dis < $partida->mto_tra) {
                    throw(new Exception(
                        'No existe suficiente disponible para la estructura cedente: ' . $partida->cod_com . 
                        '. Monto a apartar: ' . number_format($partida->mto_tra, 2, ',', '.') . 
                        '. Monto disponible: ' . number_format($maestroLey->mto_dis, 2, ',', '.'), -2706
                    ));
                } else {
                    $maestroLey->decrement('mto_dis', $partida->mto_tra);
                    $maestroLey->increment('mto_apa', $partida->mto_tra);

                    $tipOpe   = '1';
                    $concepto = 'APARTADO DE TRASPASO PRESUPUESTARIO';

                    DB::select("SELECT * 
                                FROM movimientotraspaso(
                                    '{$insubsistencia->ano_pro}', '{$partida->cod_com}', 
                                    '$tipOpe', '{$insubsistencia->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                    '$username', '{$insubsistencia->ano_pro}', '{$this->fechaGuardar}')"
                            );
                }
            }

            $insubsistencia->update([
                'sta_reg' => EstadoModificacion::Apartado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            DB::commit();
            alert()->success('¡Éxito!', 'Apartado realizado Exitosamente');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        } catch (Exception $e) {
            DB::rollBack();

            switch($e->getCode()) {
                case -2706: // Exception generada por desarrollador
                    $msg = $e->getMessage();
                    break;
                case 'P0001': // Raise Exception Postgres
                    $msg = 'Error intentando ejecutar el movimiento presupuestario';
                    break;
                default:
                    $msg = $e->getMessage();
                    break;
            }

            alert()->error('¡Transacción Fallida!', $msg);
            return redirect()->back()->withInput();
        }
    }

    public function reversarApartadoEdit(Modificacion $insubsistencia)
    {
        if ($insubsistencia->sta_reg->value != 1) {
            alert()->warning('¡Advertencia!', 'Insubsistencia en estado inválido para Reversar Apartado');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        $partidasCedentes   = $insubsistencia->estructurasCedentes();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.insubsistencias.reversar_apartado',
            compact('insubsistencia', 'partidasCedentes')
        ); 
    }

    public function reversarApartadoUpdate(Request $request, Modificacion $insubsistencia)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasCedentes = $insubsistencia->partidasCedentes;

        DB::beginTransaction();

        try {
            foreach($partidasCedentes as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $insubsistencia->ano_pro)
                                ->where('cod_com', $partida->cod_com)
                                ->first();

                if ($maestroLey->mto_apa < $partida->mto_tra) {
                    throw(new Exception(
                        'No existe suficiente apartado para la estructura: ' . $partida->cod_com, -2706
                    ));
                } else {
                    $maestroLey->increment('mto_dis', $partida->mto_tra);
                    $maestroLey->decrement('mto_apa', $partida->mto_tra);

                    $tipOpe   = '4';
                    $concepto = 'REVERSO DE APARTADO DE TRASPASO PRESUPUESTARIO';

                    DB::select("SELECT * 
                                FROM movimientotraspaso(
                                    '{$insubsistencia->ano_pro}', '{$partida->cod_com}', 
                                    '$tipOpe', '{$insubsistencia->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                    '$username', '{$insubsistencia->ano_pro}', '{$this->fechaGuardar}')"
                            );
                }
            }

            $insubsistencia->update([
                'sta_reg' => EstadoModificacion::Reverso_Apartado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            DB::commit();
            alert()->success('¡Éxito!', 'Reverso de Apartado realizado Exitosamente');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        } catch (Exception $e) {
            DB::rollBack();

            switch($e->getCode()) {
                case -2706: // Exception generada por desarrollador
                    $msg = $e->getMessage();
                    break;
                case 'P0001': // Raise Exception Postgres
                    $msg = 'Error intentando ejecutar el movimiento presupuestario';
                    break;
                default:
                    $msg = $e->getMessage();
                    break;
            }

            alert()->error('¡Transacción Fallida!', $msg);
            return redirect()->back()->withInput();
        }
    }

    public function aprobarEdit(Modificacion $insubsistencia)
    {
        if ($insubsistencia->sta_reg->value != 1) {
            alert()->warning('¡Advertencia!', 'Insubsistencia en estado inválido para Aprobar');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        if (($msj = $this->_validacionesUsuario($insubsistencia->esMulticentro(), $insubsistencia->totalCedentes())) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        $partidasCedentes   = $insubsistencia->estructurasCedentes();
        $partidasReceptoras = $insubsistencia->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.insubsistencias.aprobar',
            compact('insubsistencia', 'partidasCedentes', 'partidasReceptoras')
        ); 
    }

    public function aprobarUpdate(Request $request, Modificacion $insubsistencia)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasCedentes = $insubsistencia->partidasCedentes;

        DB::beginTransaction();

        try {
            // Partidas Cedentes
            foreach($partidasCedentes as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $insubsistencia->ano_pro)
                                ->where('cod_com', $partida->cod_com)
                                ->first();

                if (($maestroLey->mto_apa < $partida->mto_tra) || ($maestroLey->mto_mod < $partida->mto_tra) || ($maestroLey->mto_ley < $partida->mto_tra)) {
                    throw(new Exception(
                        'No existe suficiente apartado para la estructura cedente: ' . $partida->cod_com, -2706
                    ));
                } else {
                    $maestroLey->decrement('mto_apa', $partida->mto_tra);
                    $maestroLey->decrement('mto_mod', $partida->mto_tra);
                    $maestroLey->decrement('mto_ley', $partida->mto_tra);

                    $tipOpe   = '21';
                    $concepto = 'APROBACION CEDENTE DE TRASPASO PRESUPUESTARIO';

                    DB::select("SELECT * 
                                FROM movimientotraspaso(
                                    '{$insubsistencia->ano_pro}', '{$partida->cod_com}', 
                                    '$tipOpe', '{$insubsistencia->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                    '$username', '{$insubsistencia->ano_pro}', '{$this->fechaGuardar}')"
                            );
                }
            }

            $insubsistencia->update([
                'sta_reg' => EstadoModificacion::Aprobado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            $solicitud = $insubsistencia->solicitudTraspaso;

            if (!is_null($solicitud)) {
                $solicitud->update([
                    'sta_reg' => EstadoSolicitudTraspaso::Procesada,
                    'fec_sta' => $this->fechaGuardar,
                    'usu_sta' => $username,
                    'user_id_status' => $usuario->id
                ]);
            }

            /* CONSULTA RARA
            // Consulta de verificación de estados de partidas
            $subQuery1 = DB::table('modificaciones AS a')
                            ->select(
                                'a.ano_pro',
                                'b.cod_com',
                                DB::raw('b.mto_tra * -1 AS mto_tra')
                            )
                            ->join('mod_partidascedentes AS b', 'b.xnro_mod', '=', 'a.xnro_mod')
                            ->where('a.ano_pro', $insubsistencia->ano_pro)
                            ->where('a.sta_reg', 2);

            $subQuery2 = DB::table('modificaciones AS a')
                            ->select(
                                'a.ano_pro',
                                'b.cod_com',
                                'b.mto_tra'
                            )
                            ->join('mod_partidasreceptoras AS b', 'b.xnro_mod', '=', 'a.xnro_mod')
                            ->where('a.ano_pro', $insubsistencia->ano_pro)
                            ->where('a.sta_reg', 2);

            $subQuery1->union($subQuery2);

            $sql1 = DB::query()
                        ->select(
                            'ano_pro',
                            'cod_com',
                            DB::raw('sum(mto_tra) AS mto_tra')
                        )
                        ->fromSub($subQuery1, 'a')
                        ->groupByRaw('1, 2')
                        ->orderByRaw('1, 2');

            $sql = DB::table('pre_maestroley AS a')
                    ->select(
                        'a.ano_pro',
                        'a.cod_com',
                        'a.ley_for',
                        'a.mto_mod', 
                        DB::raw('a.ley_for + a.mto_com_anterior + a.mto_cau_anterior + COALESCE(b.mto_tra, 0) AS mto_mod2'),
                        DB::raw('a.mto_mod - (a.ley_for + mto_com_anterior + mto_cau_anterior + COALESCE(b.mto_tra, 0)) AS diferencia')
                    )
                    ->leftJoinSub($sql1, 'b', function($join) {
                        $join->on('a.cod_com', '=', 'b.cod_com')
                            ->on('a.ano_pro', '=', 'b.ano_pro');
                    })
                    ->where('a.ano_pro', $insubsistencia->ano_pro)
                    ->whereRaw('a.mto_mod != a.ley_for + a.mto_com_anterior + a.mto_cau_anterior + COALESCE(b.mto_tra, 0)')
                    ->whereIn('a.cod_com', function ($query) use ($insubsistencia) {
                        $query->select('cod_com')
                            ->from('mod_partidascedentes')
                            ->where('xnro_mod', $insubsistencia->xnro_mod)
                            ->unionAll(
                                DB::table('mod_partidasreceptoras')
                                    ->select('cod_com')
                                    ->where('xnro_mod', $insubsistencia->xnro_mod)
                            );
                    })
                    ->groupByRaw('1, 2');

            $res = $sql->get();

            if (is_null($res)) {
                throw(new Exception(
                    'Ocurrió un error en las Partidas involucradas, contacte al Administrador de Sistemas.', -2706
                ));
            }

            throw(new Exception('Hizo todo', -2706));
            */

            DB::commit();
            alert()->success('¡Éxito!', 'Registro aaprobado Exitosamente');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        } catch (Exception $e) {
            DB::rollBack();

            switch($e->getCode()) {
                case -2706: // Exception generada por desarrollador
                    $msg = $e->getMessage();
                    break;
                case 'P0001': // Raise Exception Postgres
                    $msg = 'Error intentando ejecutar el movimiento presupuestario';
                    break;
                default:
                    $msg = $e->getMessage();
                    break;
            }

            alert()->error('¡Transacción Fallida!', $msg);
            return redirect()->back()->withInput();
        }
    }

    public function reversarAprobacionEdit(Modificacion $insubsistencia)
    {
        if ($insubsistencia->sta_reg->value != 2) {
            alert()->warning('¡Advertencia!', 'Insubsistencia en estado inválido para Reversar la Aprobación');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        if (($msj = $this->_validacionesUsuario($insubsistencia->esMulticentro(), $insubsistencia->totalCedentes())) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        }

        $partidasCedentes   = $insubsistencia->estructurasCedentes();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.insubsistencias.reversar_aprobacion',
            compact('insubsistencia', 'partidasCedentes')
        ); 
    }

    public function reversarAprobacionUpdate(Request $request, Modificacion $insubsistencia)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasCedentes   = $insubsistencia->partidasCedentes;

        DB::beginTransaction();

        try {
            // Partidas Cedentes
            foreach($partidasCedentes as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $insubsistencia->ano_pro)
                                ->where('cod_com', $partida->cod_com)
                                ->first();

                $maestroLey->increment('mto_dis', $partida->mto_tra);
                $maestroLey->increment('mto_mod', $partida->mto_tra);
                $maestroLey->increment('mto_ley', $partida->mto_tra);

                $tipOpe   = '31';
                $concepto = 'REVERSO DE APROBACION CEDENTE DE TRASPASO PRESUPUESTARIO';

                DB::select("SELECT * 
                            FROM movimientotraspaso(
                                '{$insubsistencia->ano_pro}', '{$partida->cod_com}', 
                                '$tipOpe', '{$insubsistencia->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                '$username', '{$insubsistencia->ano_pro}', '{$this->fechaGuardar}')"
                        );
            }

            $insubsistencia->update([
                'sta_reg' => EstadoModificacion::Reverso_Aprobacion,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            DB::commit();
            alert()->success('¡Éxito!', 'Registro aaprobado Exitosamente');
            return redirect()->route('modificaciones.movimientos.insubsistencia.index');
        } catch (Exception $e) {
            DB::rollBack();

            switch($e->getCode()) {
                case -2706: // Exception generada por desarrollador
                    $msg = $e->getMessage();
                    break;
                case 'P0001': // Raise Exception Postgres
                    $msg = 'Error intentando ejecutar el movimiento presupuestario';
                    break;
                default:
                    $msg = $e->getMessage();
                    break;
            }

            alert()->error('¡Transacción Fallida!', $msg);
            return redirect()->back()->withInput();
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////// MÉTODOS PRIVADOS ///////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    private function _validacionesUsuario(bool $multi, float $monto)
    {
        if (!$this->_validarMulticentro($multi)) {
            return 'Usted no puede aprobar Traspasos Multicentro';
        }

        if (!$this->_validarMonto($monto)) {
            return 'Usted no puede aprobar el monto del Traspaso';
        }

        return true;
    }

    private function _validarMulticentro(bool $multi): bool
    {
        if ($multi) {
            return PermisoTraspaso::puedeAprobarMulticentro(\Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email));
        }

        return true;
    }

    private function _validarMonto(float $monto): bool
    {
        return PermisoTraspaso::puedeAprobarMonto(\Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email), $monto);
    }

    /**
     * Verificar si una modificación es multicentro
     * 
     * @return bool
     */
    private function _esMulticentro(array $cedentes): bool
    {
        $ctroCed = '';
        $partidasCedentes   = $cedentes;

        foreach($partidasCedentes as $cedente)
        {
            $ceco = CentroCosto::generarCodCentroCosto($cedente['tip_cod'], $cedente['cod_pryacc'], $cedente['cod_obj'], $cedente['gerencia'], $cedente['unidad']);

            if ($ctroCed == '') {
                $ctroCed = $ceco;
            } else {
                if ($ctroCed != $ceco) {
                    return true;
                }
            }
        }

        if ($ctroCed != '') {
            return true;
        }

        return false;
    }
}