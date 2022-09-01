<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion\GerenciaRequest;

class GerenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.configuracion.configuracion.gerencia.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gerencia         = new Gerencia;
        $gerencia->status = '1';
        $anoPro           = RegistroControl::periodoActual();
        $centrosCosto     = CentroCosto::where('ano_pro', $anoPro)->orderBy('id')->get();
        $partidas         = PartidaPresupuestaria::all();

        return view('administrativo.meru_administrativo.configuracion.configuracion.gerencia.create', compact('gerencia', 'centrosCosto', 'partidas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GerenciaRequest $request)
    {
        $request->validated();

        DB::beginTransaction();

        try {
            $usuario                = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
            $cenCos                 = CentroCosto::find($request->centro_costo);
            $partidaGastos          = PartidaPresupuestaria::find($request->viaticos_nac);
            $partidaGastosVinternac = PartidaPresupuestaria::find($request->viaticos_internac);

            $gerencia               = new Gerencia;
            $gerencia->des_ger      = $request->gerencia;
            $gerencia->nomenclatura = $request->nomenclatura;
            $gerencia->nom_jefe     = $request->jefe;
            $gerencia->car_jefe     = $request->cargo_jefe;
            $gerencia->correo_jefe  = $request->correo_jefe;
            $gerencia->centro_costo = $cenCos->cod_cencosto;
            $gerencia->centro_costo_anterior = $cenCos->cod_cencosto;
            $gerencia->aplica_pre   = $request->has('aplica_pre') ? '1' : '0';
            $gerencia->status       = $request->has('estado') ? '1' : '0';
            $gerencia->usuario      = $usuario;
            $gerencia->user_id      = auth()->user()->id;
            $gerencia->centro_costo_id         = $request->centro_costo;
            $gerencia->part_gasto_id           = $request->viaticos_nac;
            $gerencia->part_gasto_vinternac_id = $request->viaticos_internac;

            if (!is_null($partidaGastos)) {
                $gerencia->part_gastos  = $cenCos->cod_cencosto . \Str::substr($partidaGastos->cod_cta, 1);
            }

            if (!is_null($partidaGastosVinternac)) {
                $gerencia->part_gastos_vinternac = $cenCos->cod_cencosto . \Str::substr($partidaGastosVinternac->cod_cta, 1);
            }

            $gerencia->save();

            DB::commit();

            alert()->html('¡Éxito!', 'Registro creado exitosamente<br><b>' . $gerencia->des_ger. '</b>', 'success');
            return redirect()->route('configuracion.configuracion.gerencia.index');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            alert()->addError('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Gerencia $gerencia
     * @return \Illuminate\Http\Response
     */
    public function show(Gerencia $gerencia)
    {
        return view('administrativo.meru_administrativo.configuracion.configuracion.gerencia.show', compact('gerencia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Gerencia $gerencia
     * @return \Illuminate\Http\Response
     */
    public function edit(Gerencia $gerencia)
    {
        $anoPro       = RegistroControl::periodoActual();
        $centrosCosto = CentroCosto::where('ano_pro', $anoPro)->orderBy('id')->get();
        $partidas     = PartidaPresupuestaria::all();

        return view('administrativo.meru_administrativo.configuracion.configuracion.gerencia.edit', compact('gerencia', 'centrosCosto', 'partidas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Gerencia $gerencia
     * @return \Illuminate\Http\Response
     */
    public function update(GerenciaRequest $request, Gerencia $gerencia)
    {
        $request->validated();

        DB::beginTransaction();

        try {
            $usuario                = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
            $cenCos                 = CentroCosto::find($request->centro_costo);
            $partidaGastos          = PartidaPresupuestaria::find($request->viaticos_nac);
            $partidaGastosVinternac = PartidaPresupuestaria::find($request->viaticos_internac);

            $gerencia->des_ger      = $request->gerencia;
            $gerencia->nomenclatura = $request->nomenclatura;
            $gerencia->nom_jefe     = $request->jefe;
            $gerencia->car_jefe     = $request->cargo_jefe;
            $gerencia->correo_jefe  = $request->correo_jefe;
            $gerencia->aplica_pre   = $request->has('aplica_pre') ? '1' : '0';
            $gerencia->status       = $request->has('estado') ? '1' : '0';
            $gerencia->usuario      = $usuario;
            $gerencia->user_id      = auth()->user()->id;
            $gerencia->centro_costo_id         = $request->centro_costo;
            $gerencia->part_gasto_id           = $request->viaticos_nac;
            $gerencia->part_gasto_vinternac_id = $request->viaticos_internac;

            if ($gerencia->centro_costo != $cenCos->cod_cencosto) {
                $gerencia->centro_costo_anterior = $gerencia->centro_costo;
            }

            $gerencia->centro_costo = $cenCos->cod_cencosto;

            if (!is_null($partidaGastos)) {
                $gerencia->part_gastos  = $cenCos->cod_cencosto . \Str::substr($partidaGastos->cod_cta, 1);
            }

            if (!is_null($partidaGastosVinternac)) {
                $gerencia->part_gastos_vinternac = $cenCos->cod_cencosto . \Str::substr($partidaGastosVinternac->cod_cta, 1);
            }

            $gerencia->save();

            DB::commit();

            alert()->success('¡Éxito!', 'Registro modificado exitosamente');
            return redirect()->route('configuracion.configuracion.gerencia.index');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Gerencia $gerencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gerencia $gerencia)
    {
        //
    }
}
