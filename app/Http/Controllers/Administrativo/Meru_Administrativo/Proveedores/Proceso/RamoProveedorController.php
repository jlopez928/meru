<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Proceso;

use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;

class RamoProveedorController extends Controller
{
    
    public function index()
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.ramo-proveedor.index');
    }

    public function show(Proveedor $proveedor)
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.ramo-proveedor.show', compact('proveedor'));
    }

    public function edit(Proveedor $proveedor)
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.ramo-proveedor.edit', compact('proveedor'));
    }

}