<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\Bien;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;
use App\Models\Administrativo\Meru_Administrativo\Compras\SolicitudUnidad;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\General\Unidad;

class SolicitudUnidadController extends Controller
{

    public function index()
    {
        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.index');
    }

    public function create()
    {
        $solicitudUnidad = new SolicitudUnidad();
        $years = RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
        $ramos = Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
        $gerencias = Gerencia::query()->where('status', 1)->orderBy('des_ger')->pluck('des_ger','cod_ger');

        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.create', compact('solicitudUnidad','years','ramos','gerencias'));
    }

    public function store(Request $request)
    {
        dd($request->all());

        dd(Bien::query()->whereIn('cod_corr', $request->selectedVehiculos)->get());
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

}
