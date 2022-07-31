<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Proceso;

use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;

class RamoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.ramo-proveedor.index');
    }

    /**
     * Show a object of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedor $proveedor)
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.ramo-proveedor.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Administrativo\Meru_administrativo\Proveedores\Ramo  $ramo
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedor)
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.ramo-proveedor.edit', compact('proveedor'));
    }

}
