<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspaso;
use App\Http\Requests\Administrativo\Meru_Administrativo\Modificaciones\Configuracion\PermisoTraspasoRequest;
use App\Models\User;

class PermisoTraspasoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.modificaciones.configuracion.permiso_traspaso.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permisoTraspaso = new PermisoTraspaso;
        $usuarios = User::all();
        return view(
            'administrativo.meru_administrativo.modificaciones.configuracion.permiso_traspaso.create',
            compact('permisoTraspaso', 'usuarios')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermisoTraspasoRequest $request)
    {
        DB::beginTransaction();
        try {
            PermisoTraspaso::create($request->validated() + [
                //'usuario' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),
                'usuario' => \Str::replace('@hidrobolivar.com.ve', '', User::find($request->usuario_id)->email),
                'user_id' => auth()->user()->id,
            ]);
            DB::commit();

            alert()->success('¡Éxito!', 'Registro Modificado Exitosamente');
            return to_route('modificaciones.configuracion.permiso_traspaso.index');
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  PermisoTraspaso $permisoTraspaso
     * @return \Illuminate\Http\Response
     */
    public function show(PermisoTraspaso $permisoTraspaso)
    {
        $usuarios = User::all();
        return view(
            'administrativo.meru_administrativo.modificaciones.configuracion.permiso_traspaso.show',
            compact('permisoTraspaso', 'usuarios')
        ); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PermisoTraspaso $permisoTraspaso
     * @return \Illuminate\Http\Response
     */
    public function edit(PermisoTraspaso $permisoTraspaso)
    {
        $usuarios = User::all();
        return view(
            'administrativo.meru_administrativo.modificaciones.configuracion.permiso_traspaso.edit',
            compact('permisoTraspaso', 'usuarios')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PermisoTraspaso $permisoTraspaso
     * @return \Illuminate\Http\Response
     */
    public function update(PermisoTraspasoRequest $request, PermisoTraspaso $permisoTraspaso)
    {
        DB::beginTransaction();

        try {
            $permisoTraspaso->update($request->validated());
            DB::commit();

            alert()->success('¡Éxito!', 'Registro Modificado Exitosamente');
            return redirect()->route('modificaciones.configuracion.permiso_traspaso.index');
        } catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PermisoTraspaso $permisoTraspaso
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermisoTraspaso $permisoTraspaso)
    {
        DB::beginTransaction();

        try {
            $permisoTraspaso->delete();
            DB::commit();

            alert()->success('¡Éxito!', 'Registro Eliminado Exitosamente');
            return redirect()->route('modificaciones.configuracion.permiso_traspaso.index');
        } catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back();
        }
    }
}