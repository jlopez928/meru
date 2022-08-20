<?php

namespace App\Http\Controllers;

use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $anoPro;
    protected $fechaGuardar;

    public function __construct()
    {
        $this->middleware(function($request, $next) {
            if (!empty(session('ano_pro'))) {
                $this->anoPro = session('ano_pro');
    
                if (RegistroControl::periodoActual() != $this->anoPro) {
                    $this->fechaGuardar = \Carbon\Carbon::createFromFormat('Y-m-d', $this->anoPro . '-12-31');
                } else {
                    $this->fechaGuardar = now()->format('Y-m-d');
                }
            }

            return $next($request);
        });
    }
}