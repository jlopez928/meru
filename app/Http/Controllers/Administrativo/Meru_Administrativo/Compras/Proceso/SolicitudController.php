<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\EncSolicitud;

class SolicitudController extends Controller
{
    public function index()
    {
        $modulo = 'unidad';
        $descripcionModulo = 'Solicitudes (Unidad)';

        return view('administrativo.meru_administrativo.compras.proceso.solicitud.index', compact('modulo','descripcionModulo'));
    }

    public function show($ano_pro, $grupo, $nro_req)
    {
        $solicitud = EncSolicitud::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion = 'mostrar';
        $modulo = 'unidad';
        $descripcionModulo = 'Solicitudes (Unidad)';

        return view('administrativo.meru_administrativo.compras.proceso.solicitud.unidad.show', compact('solicitud','accion','modulo','descripcionModulo'));
    }

    public function create(Request $request)
    {
        $opcion         = $request->opcion;
        $solicitud      = new EncSolicitud();
        $accion         = 'nuevo';
        $anoPro         = $this->anoPro;
        $fechaGuardar   = $this->fechaGuardar;
        $modulo = 'unidad';
        $descripcionModulo = 'Solicitudes (Unidad)';

        return view('administrativo.meru_administrativo.compras.proceso.solicitud.unidad.create', compact('solicitud','opcion','accion','anoPro','fechaGuardar','modulo','descripcionModulo'));
    }

    public function edit($ano_pro, $grupo, $nro_req)
    {
        $solicitud =  EncSolicitud::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion = ($solicitud->sta_sol != '0') && ($solicitud->sta_sol != '2') ? 'editar_anexos' : 'editar';
        $anoPro         = $this->anoPro;
        $fechaGuardar   = $this->fechaGuardar;
        $modulo = 'unidad';
        $descripcionModulo = 'Solicitudes (Unidad)';

        return view('administrativo.meru_administrativo.compras.proceso.solicitud.unidad.edit', compact('solicitud','accion','anoPro','fechaGuardar','modulo','descripcionModulo'));
    }

    public function copiar($ano_pro, $grupo, $nro_req)
    {
        $solicitud      =  EncSolicitud::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion         = 'copiar';
        $anoPro         = $this->anoPro;
        $fechaGuardar   = $this->fechaGuardar;
        $modulo = 'unidad';
        $descripcionModulo = 'Solicitudes (Unidad)';

        return view('administrativo.meru_administrativo.compras.proceso.solicitud.unidad.copiar', compact('solicitud','accion','anoPro','fechaGuardar','modulo','descripcionModulo'));
    }

    public function reversar($ano_pro, $grupo, $nro_req)
    {
        $solicitud      =  EncSolicitud::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion         = 'reversar';
        $anoPro         = $this->anoPro;
        $fechaGuardar   = $this->fechaGuardar;
        $modulo = 'unidad';
        $descripcionModulo = 'Solicitudes (Unidad)';

        return view('administrativo.meru_administrativo.compras.proceso.solicitud.unidad.reversar', compact('solicitud','accion','anoPro','fechaGuardar','modulo','descripcionModulo'));
    }

    public function anular($ano_pro, $grupo, $nro_req)
    {
        $solicitud      = EncSolicitud::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion         = 'anular';
        $anoPro         = $this->anoPro;
        $fechaGuardar   = $this->fechaGuardar;
        $modulo = 'unidad';
        $descripcionModulo = 'Solicitudes (Unidad)';

        return view('administrativo.meru_administrativo.compras.proceso.solicitud.unidad.anular', compact('solicitud','accion','anoPro','fechaGuardar','modulo','descripcionModulo'));
    }

    public function activar($ano_pro, $grupo, $nro_req)
    {
        $solicitud      = EncSolicitud::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion         = 'activar';
        $anoPro         = $this->anoPro;
        $fechaGuardar   = $this->fechaGuardar;
        $modulo = 'unidad';
        $descripcionModulo = 'Solicitudes (Unidad)';

        return view('administrativo.meru_administrativo.compras.proceso.solicitud.unidad.activar', compact('solicitud','accion','anoPro','fechaGuardar','modulo','descripcionModulo'));
    }

    public function precomprometer($ano_pro, $grupo, $nro_req)
    {
        $solicitud      = EncSolicitud::with('estado','detalles')->where('ano_pro', $ano_pro)->where('grupo', $grupo)->where('nro_req', $nro_req)->first();
        $accion         = 'precomprometer';
        $anoPro         = $this->anoPro;
        $fechaGuardar   = $this->fechaGuardar;
        $modulo = 'unidad';
        $descripcionModulo = 'Solicitudes (Unidad)';

        return view('administrativo.meru_administrativo.compras.proceso.solicitud.unidad.precomprometer', compact('solicitud','accion','anoPro','fechaGuardar','modulo','descripcionModulo'));
    }
}
