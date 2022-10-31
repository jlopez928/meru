<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoModificacion;
use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoSolicitudTraspaso;
use App\Exports\FromQueryExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\TraspasoRequest;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\CorrModificaciones;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\Modificacion;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PartidaCedente;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PartidaReceptora;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;
use App\Traits\ReportFpdf;
use App\Support\Fpdf;
use App\Support\Helper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TraspasoPresupuestarioController extends Controller
{
    use ReportFpdf;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.modificaciones.movimientos.traspaso.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $traspaso = new Modificacion;
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.traspaso.create',
            compact('traspaso')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TraspasoRequest $request)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $estructurasCedentes   = json_decode($request->safe()->estructurasCedentes, true);
        $estructurasReceptoras = json_decode($request->safe()->estructurasReceptoras, true);
        $totalCed = $request->safe()->total_ced;
        $totalRec = $request->safe()->total_rec;
        $numDoc   = $request->safe()->num_doc;
        $multi    = $this->_esMulticentro($estructurasCedentes, $estructurasReceptoras);

        if (($msj = $this->_validacionesUsuario($multi, $totalCed)) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        DB::beginTransaction();

        try {
            if ($totalCed != $totalRec) {
                throw new Exception(
                    'El total de las Partidas Cedentes es diferente al de las Partidas Receptoras.', -2706
                );
            }

            $corr = CorrModificaciones::find($this->anoPro);

            if (is_null($corr)) {
                $corr = CorrModificaciones::create([
                    'ano_pro' => $this->anoPro,
                    'nro_reg' => 1,
                    'nro_sol' => 1
                ]);
            }

            $mes = \Carbon\Carbon::parse($this->fechaGuardar)->format('m');
            $xnroMod = $this->anoPro . '-' . $mes . '-01-' . $corr->num_reg;

            // Crear cabecera Modificacion
            $traspaso = new Modificacion;
            $traspaso->ano_pro        = $this->anoPro;
            $traspaso->num_mes        = $mes;
            $traspaso->tip_ope        = 1;
            $traspaso->nro_mod        = $corr->num_reg;
            $traspaso->xnro_mod       = $xnroMod;
            $traspaso->tip_doc        = 9;
            $traspaso->num_doc        = $numDoc;
            $traspaso->fec_pos        = $this->fechaGuardar;
            $traspaso->fec_tra        = $this->fechaGuardar;
            $traspaso->concepto       = $request->safe()->concepto;
            $traspaso->justificacion  = $request->safe()->justificacion;
            $traspaso->sta_reg        = EstadoModificacion::Creado;
            $traspaso->usu_sta        = $username;
            $traspaso->user_id_status = $usuario->id;
            $traspaso->fec_sta        = $this->fechaGuardar;
            $traspaso->usuario        = $username;
            $traspaso->user_id        = $usuario->id;
            $traspaso->fecha          = $this->fechaGuardar;
            $traspaso->save();

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

            DB::commit();
            alert()->success('¡Éxito!', 'Traspaso creado exitosamente con el código: ' . $xnroMod);
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
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
     * @param  Modificacion $traspaso
     * @return \Illuminate\Http\Response
     */
    public function show(Modificacion $traspaso)
    {
        $partidasCedentes   = $traspaso->estructurasCedentes();
        $partidasReceptoras = $traspaso->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.traspaso.show',
            compact('traspaso', 'partidasCedentes', 'partidasReceptoras')
        ); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Modificacion $traspaso
     * @return \Illuminate\Http\Response
     */
    public function edit(Modificacion $traspaso)
    {
        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.traspaso.edit',
            compact('traspaso')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Modificacion $traspaso
     * @return \Illuminate\Http\Response
     */
    public function update(TraspasoRequest $request, Modificacion $traspaso)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $estructurasCedentes   = json_decode($request->safe()->estructurasCedentes, true);
        $estructurasReceptoras = json_decode($request->safe()->estructurasReceptoras, true);
        $totalCed = $request->safe()->total_ced;
        $totalRec = $request->safe()->total_rec;
        $numDoc   = $request->safe()->num_doc;
        $multi    = $this->_esMulticentro($estructurasCedentes, $estructurasReceptoras);

        if (($msj = $this->_validacionesUsuario($multi, $totalCed)) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        DB::beginTransaction();

        try {
            if ($totalCed != $totalRec) {
                throw new Exception(
                    'El total de las Partidas Cedentes es diferente al de las Partidas Receptoras.', -2706
                );
            }

            // Actualizar cabecera Modificacion
            $traspaso->num_doc        = $numDoc;
            $traspaso->concepto       = $request->safe()->concepto;
            $traspaso->justificacion  = $request->safe()->justificacion;
            $traspaso->sta_reg        = EstadoModificacion::Modificado;
            $traspaso->usu_sta        = $username;
            $traspaso->user_id_status = $usuario->id;
            $traspaso->fec_sta        = $this->fechaGuardar;
            $traspaso->save();

            // Eliminar estructuras Cedentes de Base de Datos
            PartidaCedente::where('xnro_mod', $traspaso->xnro_mod)->delete();

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
                    'xnro_mod' => $traspaso->xnro_mod,
                    'sdo_dis'  => $row['mto_dis'],
                    'sdo_mod'  => $maestroLey->mto_mod,
                    'sdo_apa'  => $maestroLey->mto_apa,
                    'sdo_pre'  => $maestroLey->mto_pre,
                    'sdo_com'  => $maestroLey->mto_com,
                    'sdo_cau'  => $maestroLey->mto_cau,
                    'sdo_pag'  => $maestroLey->mto_pag,
                ]);
            }

            // Eliminar Estructuras Receptoras de Base de Datos
            PartidaReceptora::where('xnro_mod', $traspaso->xnro_mod)->delete();

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
                    'xnro_mod' => $traspaso->xnro_mod,
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
            alert()->success('¡Éxito!', 'Traspaso modificado exitosamente');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
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
     * @param  Modificacion $traspaso
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function anularEdit(Modificacion $traspaso)
    {
        if (!in_array($traspaso->sta_reg->value, [0, 4, 5]) ) {
            alert()->warning('¡Advertencia!', 'Traspaso en estado inválido para Anular');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        $partidasCedentes   = $traspaso->estructurasCedentes();
        $partidasReceptoras = $traspaso->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.traspaso.anular',
            compact('traspaso', 'partidasCedentes', 'partidasReceptoras')
        ); 
    }

    public function anularUpdate(Request $request, Modificacion $traspaso)
    {
        $usuario   = auth()->user();
        $username  = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);

        DB::beginTransaction();

        try {
            $traspaso->update([
                'sta_reg' => EstadoModificacion::Anulado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            $solicitud = $traspaso->solicitudTraspaso;

            if (!is_null($solicitud)) {
                $solicitud->update([
                    'sta_reg' => EstadoSolicitudTraspaso::Aprobada,
                    'fec_sta' => $this->fechaGuardar,
                    'usu_sta' => $username,
                    'user_id_status' => $usuario->id
                ]);
            }

            DB::commit();
            alert()->success('¡Éxito!', 'Registro Anulado Exitosamente');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        } catch (Exception $e) {
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function apartarEdit(Modificacion $traspaso)
    {
        if (!in_array($traspaso->sta_reg->value, [0, 3, 4, 5]) ) {
            alert()->warning('¡Advertencia!', 'Traspaso en estado inválido para Apartar');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        if (($msj = $this->_validacionesUsuario($traspaso->esMulticentro(), $traspaso->totalCedentes())) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        $partidasCedentes   = $traspaso->estructurasCedentes();
        $partidasReceptoras = $traspaso->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.traspaso.apartar',
            compact('traspaso', 'partidasCedentes', 'partidasReceptoras')
        ); 
    }

    public function apartarUpdate(Request $request, Modificacion $traspaso)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasCedentes = $traspaso->partidasCedentes;

        DB::beginTransaction();

        try {
            foreach($partidasCedentes as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $traspaso->ano_pro)
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
                                    '{$traspaso->ano_pro}', '{$partida->cod_com}', 
                                    '$tipOpe', '{$traspaso->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                    '$username', '{$traspaso->ano_pro}', '{$this->fechaGuardar}')"
                            );
                }
            }

            $traspaso->update([
                'sta_reg' => EstadoModificacion::Apartado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            DB::commit();
            alert()->success('¡Éxito!', 'Apartado realizado Exitosamente');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
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

    public function reversarApartadoEdit(Modificacion $traspaso)
    {
        if ($traspaso->sta_reg->value != 1) {
            alert()->warning('¡Advertencia!', 'Traspaso en estado inválido para Reversar Apartado');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        $partidasCedentes   = $traspaso->estructurasCedentes();
        $partidasReceptoras = $traspaso->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.traspaso.reversar_apartado',
            compact('traspaso', 'partidasCedentes', 'partidasReceptoras')
        ); 
    }

    public function reversarApartadoUpdate(Request $request, Modificacion $traspaso)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasCedentes = $traspaso->partidasCedentes;

        DB::beginTransaction();

        try {
            foreach($partidasCedentes as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $traspaso->ano_pro)
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
                                    '{$traspaso->ano_pro}', '{$partida->cod_com}', 
                                    '$tipOpe', '{$traspaso->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                    '$username', '{$traspaso->ano_pro}', '{$this->fechaGuardar}')"
                            );
                }
            }

            $traspaso->update([
                'sta_reg' => EstadoModificacion::Reverso_Apartado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            DB::commit();
            alert()->success('¡Éxito!', 'Reverso de Apartado realizado Exitosamente');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
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

    public function aprobarEdit(Modificacion $traspaso)
    {
        if ($traspaso->sta_reg->value != 1) {
            alert()->warning('¡Advertencia!', 'Traspaso en estado inválido para Aprobar');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        if (($msj = $this->_validacionesUsuario($traspaso->esMulticentro(), $traspaso->totalCedentes())) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        $partidasCedentes   = $traspaso->estructurasCedentes();
        $partidasReceptoras = $traspaso->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.traspaso.aprobar',
            compact('traspaso', 'partidasCedentes', 'partidasReceptoras')
        ); 
    }

    public function aprobarUpdate(Request $request, Modificacion $traspaso)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasCedentes = $traspaso->partidasCedentes;
        $partidasReceptoras = $traspaso->partidasReceptoras;

        DB::beginTransaction();

        try {
            // Partidas Cedentes
            foreach($partidasCedentes as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $traspaso->ano_pro)
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
                                    '{$traspaso->ano_pro}', '{$partida->cod_com}', 
                                    '$tipOpe', '{$traspaso->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                    '$username', '{$traspaso->ano_pro}', '{$this->fechaGuardar}')"
                            );
                }
            }

            // Partidas Receptoras
            foreach($partidasReceptoras as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $traspaso->ano_pro)
                                ->where('cod_com', $partida->cod_com)
                                ->first();

                $maestroLey->increment('mto_dis', $partida->mto_tra);
                $maestroLey->increment('mto_mod', $partida->mto_tra);
                $maestroLey->increment('mto_ley', $partida->mto_tra);

                $tipOpe   = '22';
                $concepto = 'APROBACION RECEPTORA DE TRASPASO PRESUPUESTARIO';

                DB::select("SELECT * 
                            FROM movimientotraspaso(
                                '{$traspaso->ano_pro}', '{$partida->cod_com}', 
                                '$tipOpe', '{$traspaso->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                '$username', '{$traspaso->ano_pro}', '{$this->fechaGuardar}')"
                        );
            }

            $traspaso->update([
                'sta_reg' => EstadoModificacion::Aprobado,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            $solicitud = $traspaso->solicitudTraspaso;

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
                            ->where('a.ano_pro', $traspaso->ano_pro)
                            ->where('a.sta_reg', 2);

            $subQuery2 = DB::table('modificaciones AS a')
                            ->select(
                                'a.ano_pro',
                                'b.cod_com',
                                'b.mto_tra'
                            )
                            ->join('mod_partidasreceptoras AS b', 'b.xnro_mod', '=', 'a.xnro_mod')
                            ->where('a.ano_pro', $traspaso->ano_pro)
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
                    ->where('a.ano_pro', $traspaso->ano_pro)
                    ->whereRaw('a.mto_mod != a.ley_for + a.mto_com_anterior + a.mto_cau_anterior + COALESCE(b.mto_tra, 0)')
                    ->whereIn('a.cod_com', function ($query) use ($traspaso) {
                        $query->select('cod_com')
                            ->from('mod_partidascedentes')
                            ->where('xnro_mod', $traspaso->xnro_mod)
                            ->unionAll(
                                DB::table('mod_partidasreceptoras')
                                    ->select('cod_com')
                                    ->where('xnro_mod', $traspaso->xnro_mod)
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
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
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

    public function reversarAprobacionEdit(Modificacion $traspaso)
    {
        if ($traspaso->sta_reg->value != 2) {
            alert()->warning('¡Advertencia!', 'Traspaso en estado inválido para Reversar la Aprobación');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        if (($msj = $this->_validacionesUsuario($traspaso->esMulticentro(), $traspaso->totalCedentes())) !== true) {
            alert()->warning('¡Advertencia!', $msj);
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
        }

        $partidasCedentes   = $traspaso->estructurasCedentes();
        $partidasReceptoras = $traspaso->estructurasReceptoras();

        return view(
            'administrativo.meru_administrativo.modificaciones.movimientos.traspaso.reversar_aprobacion',
            compact('traspaso', 'partidasCedentes', 'partidasReceptoras')
        ); 
    }

    public function reversarAprobacionUpdate(Request $request, Modificacion $traspaso)
    {
        $usuario  = auth()->user();
        $username = \Str::replace('@hidrobolivar.com.ve', '', $usuario->email);
        $partidasCedentes   = $traspaso->partidasCedentes;
        $partidasReceptoras = $traspaso->partidasReceptoras;

        DB::beginTransaction();

        try {
            // Partidas Cedentes
            foreach($partidasCedentes as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $traspaso->ano_pro)
                                ->where('cod_com', $partida->cod_com)
                                ->first();

                $maestroLey->increment('mto_dis', $partida->mto_tra);
                $maestroLey->increment('mto_mod', $partida->mto_tra);
                $maestroLey->increment('mto_ley', $partida->mto_tra);

                $tipOpe   = '31';
                $concepto = 'REVERSO DE APROBACION CEDENTE DE TRASPASO PRESUPUESTARIO';

                DB::select("SELECT * 
                            FROM movimientotraspaso(
                                '{$traspaso->ano_pro}', '{$partida->cod_com}', 
                                '$tipOpe', '{$traspaso->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                '$username', '{$traspaso->ano_pro}', '{$this->fechaGuardar}')"
                        );
            }

            // Partidas Receptoras
            foreach($partidasReceptoras as $partida) {
                $maestroLey = MaestroLey::where('ano_pro', $traspaso->ano_pro)
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
                                    '{$traspaso->ano_pro}', '{$partida->cod_com}', 
                                    '$tipOpe', '{$traspaso->xnro_mod}', '{$partida->mto_tra}', '$concepto', 
                                    '$username', '{$traspaso->ano_pro}', '{$this->fechaGuardar}')"
                            );
                }
            }

            $traspaso->update([
                'sta_reg' => EstadoModificacion::Reverso_Aprobacion,
                'fec_sta' => $this->fechaGuardar,
                'usu_sta' => $username,
                'user_id_status' => $usuario->id
            ]);

            DB::commit();
            alert()->success('¡Éxito!', 'Registro aaprobado Exitosamente');
            return redirect()->route('modificaciones.movimientos.traspaso_presupuestario.index');
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
    /////////////////////////////////////// REPORTES ///////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function listadoModificacionesCreate()
    {
		return view(
			'administrativo.meru_administrativo.modificaciones.reportes.operaciones',
		);
    }

    public function listadoModificacionesStore(Request $request)
    {
        $operacion = $request->operacion;
        $fecIni  = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('fec_ini'));
        $fecFin  = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('fec_fin'));
        $fecInif = $fecIni->format('Y-m-d');
        $fecFinf = $fecFin->format('Y-m-d');
        $tipo = $request->tipo_reporte;

        $sqlCed = DB::table('modificaciones AS m')
                    ->select(
                        'm.xnro_mod',
                        DB::raw(
                            "CASE m.sta_reg 
                                WHEN '0' THEN 'SOLO CREADO' 
                                WHEN '1' THEN 'APARTADO' 
                                WHEN '2' THEN 'APROBADO' 
                                WHEN '3' THEN 'REV. APROBACION' 
                                WHEN '4' THEN 'REV. APARTADO' 
                                WHEN '5' THEN 'MODIFICADO' 
                                WHEN '6' THEN 'ANULADO' 
                            END AS sta_reg"
                        ),
                        'm.fec_tra', 
                        'm.justificacion',
                        'c.cod_com',
                        DB::raw('(c.mto_tra * -1) AS mto_tra')
                    )
                    ->join('mod_partidascedentes AS c', 'm.xnro_mod', '=', 'c.xnro_mod')
                    ->whereBetween('m.fec_tra', [$fecInif, $fecFinf]);

        $sqlRec = DB::table('modificaciones AS m')
                    ->select(
                        'm.xnro_mod',
                        DB::raw(
                            "CASE m.sta_reg 
                                WHEN '0' THEN 'SOLO CREADO' 
                                WHEN '1' THEN 'APARTADO' 
                                WHEN '2' THEN 'APROBADO' 
                                WHEN '3' THEN 'REV. APROBACION' 
                                WHEN '4' THEN 'REV. APARTADO' 
                                WHEN '5' THEN 'MODIFICADO' 
                                WHEN '6' THEN 'ANULADO' 
                            END AS sta_reg"
                        ),
                        'm.fec_tra', 
                        'm.justificacion',
                        'r.cod_com',
                        'r.mto_tra'
                    )
                    ->join('mod_partidasreceptoras AS r', 'm.xnro_mod', '=', 'r.xnro_mod')
                    ->whereBetween('m.fec_tra', [$fecInif, $fecFinf]);

        $orderBy = '3,1,5,6';

        switch ($operacion) {
            case 'C': // Creditos Adicionales
                $sqlRec->where('m.tip_ope', 3);
                $sql    = $sqlRec;
                $titulo = 'CREDITOS ADICIONALES';
                break;
            case 'D': // Disminuciones
                $sqlCed->where('m.tip_ope', 4);
                $sql    = $sqlCed;
                $titulo = 'DISMINUCIONES';
                break;
            case 'I': // Insubsistencias
                $sqlCed->where('m.tip_ope', 5);
                $sql    = $sqlCed;
                $titulo = 'INSUBSISTENCIAS';
                break;
            default: // Traspasos
                $sqlCed->where('m.tip_ope', 1);
                $sqlRec->where('m.tip_ope', 1);
                $sqlCed->union($sqlRec);
                $sql     = DB::query()->fromSub($sqlCed, 'a');
                $orderBy = '3,1,6,5';
                $titulo  = 'TRASPASOS';
                break;
        }

        $sql->orderByRaw($orderBy);

        $archivo = 'Listado_' . \Str::lower($titulo);
        $subtitulo = 'DEL: ' . $fecIni->format('d/m/Y') . ' AL: ' . $fecFin->format('d/m/Y');

        if ($tipo == 'E') {
            $data = [
                'query'      => $sql,
                'titulo'     => ['PRESUPUESTO HIDROBOLIVAR', $titulo, $subtitulo],
                'ancho'      => [20,20,20,35,30,20],
                'alineacion' => ['C','C','C','L','C','R'],
                'formatos'   => ['T','T','T','T','T','N'],
                'columnas'   => ['MODIFICACION','ESTATUS','FECHA','JUSTITICACION','ESTRUCTURA DE GASTOS','MONTO']
            ];

            return (new FromQueryExport($data))->download($archivo . '.xlsx');
        } else {
            $res = $sql->get();

            if ($res->count() > 0) {
                $pdf = new Fpdf;
				$pdf->AliasNbPages();
				$pdf->SetLeftMargin(5);
				$pdf->setTitle(utf8_decode('Resumen Presupuestario'));
				$pdf->SetAuthor(auth()->user()->name);
				$pdf->SetAutoPageBreak(true, 5);

				$data['tipo_hoja']           = 'A4';
				$data['orientacion']         = 'H';
				$data['cod_normalizacion']   = '';
				$data['gerencia']            = '';
				$data['division']            = '';
				$data['titulo']              = 'HIDROBOLIVAR';
				$data['subtitulo']           = $titulo . ' - DEL: ' . $fecIni->format('d/m/Y') . ' AL: ' . $fecFin->format('d/m/Y');
				$data['alineacion_columnas'] = ['C','C','C','L','C','R'];
				$data['ancho_columnas']      = [30,45,24,95,50,30]; //Ancho de Columnas
				$data['nombre_columnas']     = ['MODIFICACION','ESTATUS','FECHA','JUSTITICACION','ESTRUCTURA DE GASTOS','MONTO'];
				$data['funciones_columnas']  = '';
				$data['fuente']              = 7;
				$data['registros_mostar']    = [];
				$data['nombre_documento']    = $archivo . '.pdf'; //Nombre del archivo
				$data['con_imagen']          = true;
				$data['vigencia']            = '';
				$data['revision']            = '';
				$data['usuario']             = auth()->user()->name;
				$data['cod_reporte']         = '';
				$data['registros']           = [];

				$this->pintar_encabezado_pdf($pdf, $data);
				$this->pintar_cabecera_columnas_pdf($pdf, $data, false);

                $xmod = '';

                foreach ($res as $r) {
                    $stru = $r->cod_com;

                    if($xmod == '') {
                        $xmod    = $r->xnro_mod;
                        $pmod    = $r->xnro_mod;
                        $sta_reg = $r->sta_reg;
                        $pfec    = $r->fec_tra;
                        $pjus    = trim($r->justificacion);
                    } elseif ($xmod == $r->xnro_mod) {
                        $pmod    = '';
                        $sta_reg = '';
                        $pfec    = '';
                        $pjus    = '';
                    } elseif ($xmod != $r->xnro_mod) {
                        /** Pintar Linea doble una delgada y la otra gruesa **/
                        //$row = array(true, true, true, true, true, true, true);
                        //$this->drawRowLine($row, true, false, false);
                        $pdf->Row(['____________________',
                        '_______________________________',
                        '________________',
                        '___________________________________________________________________',
                        '__________________________________ ',
                        '____________________',], 'S');
                        $xmod = $r->xnro_mod; //Guardar No. de Modificacion
                        $pmod = $r->xnro_mod;
                        $sta_reg = $r->sta_reg;
                        $pfec = $r->fec_tra;
                        $pjus = trim($r->justificacion);
                    }

                    $row = [$pmod, $sta_reg, $pfec, $pjus, $stru, Helper::formatNumber($r->mto_tra, 2, ',', '.', '(')];
                    $pdf->Row($row, 'S');

                    if ($pdf->GetY() >= 175) {
						$this->pintar_encabezado_pdf($pdf, $data);
						$this->pintar_cabecera_columnas_pdf($pdf, $data, false);
					}
                }

                $pdf->Output('Resumen Presupuestario', 'I');
				exit;
            } else {
                dd('No se encontraron datos');
            }
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
    private function _esMulticentro(array $cedentes, array $receptoras): bool
    {
        $ctroCed = '';
        $ctroRec = '';
        $partidasCedentes   = $cedentes;
        $partidasReceptoras = $receptoras;

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

        if ($ctroCed != '' && $ctroRec != '' && $ctroCed != $ctroRec) {
            return true;
        }

        return false;
    }
}