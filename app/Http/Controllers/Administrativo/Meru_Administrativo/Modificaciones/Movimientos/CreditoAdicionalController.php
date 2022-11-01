<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoModificacion;
use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoSolicitudTraspaso;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\CreditoAdicionalRequest;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\CorrModificaciones;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\Modificacion;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PartidaReceptora;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;
use DB;
use Exception;
use Illuminate\Http\Request;

class CreditoAdicionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.modificaciones.movimientos.creditos_adicionales.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $creditoAdicional = new Modificacion;
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.creditos_adicionales.create',
            compact('creditoAdicional')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreditoAdicionalRequest $request)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $estructurasReceptoras = json_decode($request->safe()->estructurasReceptoras, true);
        $totalRec = $request->safe()->total_rec;
        $numDoc   = $request->safe()->num_doc;
        $multi    = $this->_esMulticentro($estructurasReceptoras);

        if (($msj = $this->_validacionesUsuario($multi, $totalRec)) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
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
            $xnroMod = $this->anoPro . '-' . $mes . '-03-' . $corr->num_reg;

            // Crear cabecera Modificacion
            $creditoAdicional = new Modificacion;
            $creditoAdicional->ano_pro        = $this->anoPro;
            $creditoAdicional->num_mes        = $mes;
            $creditoAdicional->tip_ope        = 3;
            $creditoAdicional->nro_mod        = $corr->num_reg;
            $creditoAdicional->xnro_mod       = $xnroMod;
            $creditoAdicional->tip_doc        = 57;
            $creditoAdicional->num_doc        = $numDoc;
            $creditoAdicional->fec_pos        = $this->fechaGuardar;
            $creditoAdicional->fec_tra        = $this->fechaGuardar;
            $creditoAdicional->concepto       = $request->safe()->concepto;
            $creditoAdicional->justificacion  = $request->safe()->justificacion;
            $creditoAdicional->sta_reg        = EstadoModificacion::Creado;
            $creditoAdicional->usu_sta        = $username;
            $creditoAdicional->user_id_status = $usuario->id;
            $creditoAdicional->fec_sta        = $this->fechaGuardar;
            $creditoAdicional->usuario        = $username;
            $creditoAdicional->user_id        = $usuario->id;
            $creditoAdicional->fecha          = $this->fechaGuardar;
            $creditoAdicional->save();

            // Crear Partidas Receptoras
            foreach ($estructurasReceptoras as $row) {
                $maestroLey = MaestroLey::where('ano_pro', $this->anoPro)
                    ->where('cod_com', $row['cod_com'])
                    ->get(['mto_mod', 'mto_apa', 'mto_pre', 'mto_com', 'mto_cau', 'mto_pag'])
                    ->first();

                if (is_null($maestroLey)) {
                    throw new Exception(
                        'La estructura presupuestaria ' . $row['cod_com'] . ' no existe', -2706
                    );
                }

                PartidaReceptora::create($row  + [
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
            alert()->success('¡Éxito!', 'Crédito Adicional  creado exitosamente con el código: ' . $xnroMod);
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
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
     * @param  Modificacion $creditoAdicional
     * @return \Illuminate\Http\Response
     */
    public function show(Modificacion $creditoAdicional)
    {
        $partidasReceptoras = $creditoAdicional->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.creditos_adicionales.show',
            compact('creditoAdicional', 'partidasReceptoras')
        ); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Modificacion $creditoAdicional
     * @return \Illuminate\Http\Response
     */
    public function edit(Modificacion $creditoAdicional)
    {
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.creditos_adicionales.edit',
            compact('creditoAdicional')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Modificacion $creditoAdicional
     * @return \Illuminate\Http\Response
     */
    public function update(CreditoAdicionalRequest $request, Modificacion $creditoAdicional)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $estructurasReceptoras = json_decode($request->safe()->estructurasReceptoras, true);
        $totalRec = $request->safe()->total_rec;
        $numDoc   = $request->safe()->num_doc;
        $multi    = $this->_esMulticentro($estructurasReceptoras);

        if (($msj = $this->_validacionesUsuario($multi, $totalRec)) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
        }

        DB::beginTransaction();

        try {
            // Actualizar cabecera Modificacion
            $creditoAdicional->num_doc        = $numDoc;
            $creditoAdicional->concepto       = $request->safe()->concepto;
            $creditoAdicional->justificacion  = $request->safe()->justificacion;
            $creditoAdicional->sta_reg        = EstadoModificacion::Modificado;
            $creditoAdicional->usu_sta        = $username;
            $creditoAdicional->user_id_status = $usuario->id;
            $creditoAdicional->fec_sta        = $this->fechaGuardar;
            $creditoAdicional->save();

            // Eliminar Estructuras Receptoras de Base de Datos
            PartidaReceptora::where('xnro_mod', $creditoAdicional->xnro_mod)->delete();

            // Crear Estructuras Receptoras
            foreach ($estructurasReceptoras as $row) {
                $maestroLey = MaestroLey::where('ano_pro', $this->anoPro)
                    ->where('cod_com', $row['cod_com'])
                    ->get(['mto_mod', 'mto_apa', 'mto_pre', 'mto_com', 'mto_cau', 'mto_pag'])
                    ->first();

                if (is_null($maestroLey)) {
                    throw new Exception(
                        'La estructura presupuestaria ' . $row['cod_com'] . ' no existe', -2706
                    );
                }

                PartidaReceptora::create($row  + [
                    'xnro_mod' => $creditoAdicional->xnro_mod,
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
            alert()->success('¡Éxito!', 'Crédito Adicional  modificado exitosamente');
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
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
     * @param  Modificacion $creditoAdicional
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function anularEdit(Modificacion $creditoAdicional)
    {
        if (!in_array($creditoAdicional->sta_reg->value, [0, 4, 5]) ) {
            alert()->warning('¡Advertencia!', 'Crédito Adicional en estado inválido para Anular');
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
        }

        $partidasReceptoras = $creditoAdicional->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.creditos_adicionales.anular',
            compact('creditoAdicional', 'partidasReceptoras')
        ); 
    }

    public function anularUpdate(Request $request, Modificacion $creditoAdicional)
    {
        $usuario   = auth()->user();
        $username  = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);

        DB::beginTransaction();

        try {
            $creditoAdicional->update([
                'sta_reg' => EstadoModificacion::Anulado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            /*
            $solicitud = $creditoAdicional->solicitudTraspaso;

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
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function aprobarEdit(Modificacion $creditoAdicional)
    {
        if (!in_array($creditoAdicional->sta_reg->value, [0, 3, 5])) {
            alert()->warning('¡Advertencia!', 'Crédito Adicional en estado inválido para Aprobar');
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
        }

        if (($msj = $this->_validacionesUsuario($creditoAdicional->esMulticentro(), $creditoAdicional->totalCedentes())) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
        }

        $partidasReceptoras = $creditoAdicional->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.creditos_adicionales.aprobar',
            compact('creditoAdicional', 'partidasReceptoras')
        ); 
    }

    public function aprobarUpdate(Request $request, Modificacion $creditoAdicional)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasReceptoras = $creditoAdicional->partidasReceptoras;

        DB::beginTransaction();

        try {
            // Partidas Receptoras
            foreach($partidasReceptoras as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $creditoAdicional->ano_pro)
                                ->where('cod_com', $partida->cod_com)
                                ->first();

                $maestroLey->increment('mto_dis', $partida->mto_tra);
                $maestroLey->increment('mto_mod', $partida->mto_tra);
                $maestroLey->increment('mto_ley', $partida->mto_tra);

                $tipOpe   = '22';
                $concepto = 'APROBACION RECEPTORA DE TRASPASO PRESUPUESTARIO';

                DB::select("SELECT * 
                            FROM movimientotraspaso(
                                '{$creditoAdicional->ano_pro}', '{$partida->cod_com}', 
                                '$tipOpe', '{$creditoAdicional->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                '$username', '{$creditoAdicional->ano_pro}', '{$this->fechaGuardar}')"
                        );
            }

            $creditoAdicional->update([
                'sta_reg' => EstadoModificacion::Aprobado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            /*
            $solicitud = $creditoAdicional->solicitudTraspaso;

            if (!is_null($solicitud)) {
                $solicitud->update([
                    'sta_reg' => EstadoSolicitudTraspaso::Procesada,
                    'fec_sta' => $this->fechaGuardar,
                    'usu_sta' => $username,
                    'user_id_status' => $usuario->id
                ]);
            }
            */

            /* CONSULTA RARA
            // Consulta de verificación de estados de partidas
            $subQuery1 = DB::table('modificaciones AS a')
                            ->select(
                                'a.ano_pro',
                                'b.cod_com',
                                DB::raw('b.mto_tra * -1 AS mto_tra')
                            )
                            ->join('mod_partidascedentes AS b', 'b.xnro_mod', '=', 'a.xnro_mod')
                            ->where('a.ano_pro', $creditoAdicional->ano_pro)
                            ->where('a.sta_reg', 2);

            $subQuery2 = DB::table('modificaciones AS a')
                            ->select(
                                'a.ano_pro',
                                'b.cod_com',
                                'b.mto_tra'
                            )
                            ->join('mod_partidasreceptoras AS b', 'b.xnro_mod', '=', 'a.xnro_mod')
                            ->where('a.ano_pro', $creditoAdicional->ano_pro)
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
                    ->where('a.ano_pro', $creditoAdicional->ano_pro)
                    ->whereRaw('a.mto_mod != a.ley_for + a.mto_com_anterior + a.mto_cau_anterior + COALESCE(b.mto_tra, 0)')
                    ->whereIn('a.cod_com', function ($query) use ($creditoAdicional) {
                        $query->select('cod_com')
                            ->from('mod_partidascedentes')
                            ->where('xnro_mod', $creditoAdicional->xnro_mod)
                            ->unionAll(
                                DB::table('mod_partidasreceptoras')
                                    ->select('cod_com')
                                    ->where('xnro_mod', $creditoAdicional->xnro_mod)
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
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
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

    public function reversarAprobacionEdit(Modificacion $creditoAdicional)
    {
        if ($creditoAdicional->sta_reg->value != 2) {
            alert()->warning('¡Advertencia!', 'Crédito Adicional en estado inválido para Reversar la Aprobación');
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
        }

        if (($msj = $this->_validacionesUsuario($creditoAdicional->esMulticentro(), $creditoAdicional->totalReceptoras())) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
        }

        $partidasReceptoras = $creditoAdicional->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.creditos_adicionales.reversar_aprobacion',
            compact('creditoAdicional', 'partidasReceptoras')
        ); 
    }

    public function reversarAprobacionUpdate(Request $request, Modificacion $creditoAdicional)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasReceptoras = $creditoAdicional->partidasReceptoras;

        DB::beginTransaction();

        try {
            // Partidas Receptoras
            foreach($partidasReceptoras as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $creditoAdicional->ano_pro)
                                ->where('cod_com', $partida->cod_com)
                                ->first();

                if (($maestroLey->mto_dis < $partida->mto_tra) || ($maestroLey->mto_mod < $partida->mto_tra) || ($maestroLey->mto_ley < $partida->mto_tra)) {
                    throw(new Exception(
                        'No existe suficiente disponible para la estructura receptora: ' . $partida->cod_com, -2706
                    ));
                } else {
                    $maestroLey->decrement('mto_dis', $partida->mto_tra);
                    $maestroLey->decrement('mto_mod', $partida->mto_tra);
                    $maestroLey->decrement('mto_ley', $partida->mto_tra);

                    $tipOpe   = '32';
                    $concepto = 'REVERSO DE APROBACION RECEPTORA DE TRASPASO PRESUPUESTARIO';

                    DB::select("SELECT * 
                                FROM movimientotraspaso(
                                    '{$creditoAdicional->ano_pro}', '{$partida->cod_com}', 
                                    '$tipOpe', '{$creditoAdicional->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                    '$username', '{$creditoAdicional->ano_pro}', '{$this->fechaGuardar}')"
                            );
                }
            }

            $creditoAdicional->update([
                'sta_reg' => EstadoModificacion::Reverso_Aprobacion,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            DB::commit();
            alert()->success('¡Éxito!', 'Registro aaprobado Exitosamente');
            return redirect()->route('modificaciones.movimientos.credito_adicional.index');
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
    private function _esMulticentro(array $receptoras): bool
    {
        $ctroRec = '';
        $partidasReceptoras = $receptoras;

        foreach($partidasReceptoras as $receptora)
        {
            $ceco = CentroCosto::generarCodCentroCosto($receptora['tip_cod'], $receptora['cod_pryacc'], $receptora['cod_obj'], $receptora['gerencia'], $receptora['unidad']);

            if ($ctroRec == '') {
                $ctroRec = $ceco;
            } else {
                if ($ctroRec != $ceco) {
                    return true;
                }
            }
        }

        if ($ctroRec != '') {
            return true;
        }

        return false;
    }
}