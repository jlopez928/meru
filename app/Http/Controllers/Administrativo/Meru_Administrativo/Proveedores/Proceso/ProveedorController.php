<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\UbicacionGeografica;
use App\Http\Requests\Administrativo\Meru_Administrativo\Proveedores\Proceso\ProveedorRequest;

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

    // public function store(ProveedorRequest $request)
    public function store(Request $request)
    {
        dd($request->all());

        // return $request->all();
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

    public function getEstados($ubicacion = null)
    {
        return UbicacionGeografica::query()
                                    ->when($ubicacion === 'E',
                                        fn($q) => $q->where('cod_edo', 50)
                                    )
                                    ->where('cod_mun', '0')
                                    ->where('cod_par', '0')
                                    ->orderBy('des_ubi')
                                    ->get(['cod_edo', 'des_ubi']);
    }

    public function getMunicipios($estado = null)
    {
        return UbicacionGeografica::query()
                                    ->where('cod_edo', $estado)
                                    ->where('cod_mun', '!=', 0)
                                    ->where('cod_par', 0)
                                    ->orderBy('des_ubi')
                                    ->get(['cod_mun', 'des_ubi']);
    }
}
