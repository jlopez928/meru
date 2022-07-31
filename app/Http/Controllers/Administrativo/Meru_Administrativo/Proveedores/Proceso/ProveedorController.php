<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Proceso;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrativo\Meru_Administrativo\Proveedores\Proceso\ProveedorRequest;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{

    public function index()
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.index');
    }
    
    public function create()
    {
        $proveedor = new Proveedor();

        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.create', compact('proveedor'));
    }

        
    public function store(ProveedorRequest $request)
    // public function store(Request $request)
    {
        return $request->all();
        
    }

    public function edit(Proveedor $proveedor)
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.edit', compact('proveedor'));
    }
  
    public function show(Proveedor $proveedor)
    {
        return view('administrativo.meru_administrativo.proveedores.proceso.proveedor.show', compact('proveedor'));
    }

    public function update(ProveedorRequest $request, Proveedor $proveedor)
    {
        return $request->all();
    }

}