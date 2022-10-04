<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\Bien;
use App\Models\Administrativo\Meru_Administrativo\General\Unidad;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;
use App\Models\Administrativo\Meru_Administrativo\Compras\Comprador;
use App\Models\Administrativo\Meru_Administrativo\Compras\TipoCompra;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Compras\SolicitudUnidad;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_administrativo\Configuracion\UnidadTributaria;

class SolicitudUnidadController extends Controller
{

    public function index()
    {
        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.index');
    }

    public function create(Request $request)
    {
        $opcion = $request->opcion;
        $solicitudUnidad = new SolicitudUnidad();
        $years = RegistroControl::query()->orderBy('ano_pro')->pluck('ano_pro');
        $ramos = Ramo::query()->where('sta_reg', 1)->orderBy('des_ram')->pluck('des_ram','cod_ram');
        $gerencias = Gerencia::query()->where('status', 1)->where('aplica_pre', $opcion)->orderBy('des_ger')->pluck('des_ger','cod_ger');
        $compradores = Comprador::with('usuariot:usuario,nombre')->where('sta_reg', 1)->get(['cod_com','usu_com'])->sortBy('usuariot.nombre')->pluck('usuariot.nombre','cod_com');
        $tipoDeCompras = TipoCompra::query()->whereNot('cod_tipocompra', '0')->orderBy('des_tipocompra')->pluck('des_tipocompra','cod_tipocompra');

        return view('administrativo.meru_administrativo.compras.proceso.solicitud-unidad.create', compact('solicitudUnidad','years','ramos','gerencias','compradores','tipoDeCompras','opcion'));
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

    public function getUnidadTributaria()
    {
        return UnidadTributaria::getUltimaUnidadTributaria();
    }

    public function getRangosUnidadTributaria($licita = null)
    {
        return TipoCompra::query()->rangos($licita)->get(['cod_tipocompra','ut_bie_ser_des','ut_bie_ser_has']);
    }

}
