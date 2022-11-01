<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Proceso;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxpTipoDoc;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\LineasAereas;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\Solpago;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class SolicitudPagoController extends Controller
{

    public function index()
    {
        return view('administrativo.meru_administrativo.cuentasxpagar.proceso.solicitud_pago.index');
    }
    public function show(Solpago $solicititudpago)
    { //dd($solicititudpago->cxpdetcontablesolpago);

        $registrocontrol=  RegistroControl::query()
            ->select('ano_pro','ano_pro')
            ->orderBy('ano_pro', 'desc')
            ->get();
             $cxptipodoc=  CxpTipoDoc::query()
            ->where('status','1')
            ->where('sol_pago','1')
            ->select('siglas','descripcion_doc')
            ->orderBy('descripcion_doc', 'desc')
            ->get();
            $beneficiario = Beneficiario::query()
                        ->select('rif_ben','nom_ben')
                        ->orderBy('nom_ben', 'asc')
                        ->get();
            $lineasaereas = LineasAereas::query()
            ->select('rif_aerolinea','nom_aerolinea')
            ->orderBy('nom_aerolinea', 'asc')
            ->get();
        return view('administrativo.meru_administrativo.cuentasxpagar.proceso.solicitud_pago.show',
        [
            'solicititudpago'   => $solicititudpago,
            'registrocontrol'   => $registrocontrol,
            'beneficiario'      => $beneficiario,
            'cxptipodoc'        => $cxptipodoc,
            'lineasaereas'        => $lineasaereas


        ]);
    }

    public function create()
    {

    }
    public function store(Request $request)
    {

    }

    public function edit($id)
    {

    }
    public function update(Request $request, $id)
    {

    }

}
