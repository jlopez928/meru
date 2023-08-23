<?php

namespace App\Http\Middleware;

use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use Closure;
use Illuminate\Http\Request;

class PeriodoFiscal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Valida que en dualidad esté seleccionado un año
        if (empty(session('ano_pro'))) {
            alert()->error('Error','¡Debe seleccionar un Periodo Fiscal para poder realizar esta acción!');
            return redirect('home');
        }

        /*
        // Valida que no se puedan modificar documentos de años que no estén abiertos
        if (!empty($request->route()->parameters())) {
            $param = $request->route()->parameter($request->route()->parameterNames()[0]);

            // Si el parámetro es un modelo y tiene atributo ano_pro
            if (is_a($param, 'Illuminate\Database\Eloquent\Model') && !empty($param->ano_pro)) {
                if (!in_array($param->ano_pro, RegistroControl::periodosAbiertos()->toArray())) {
                    alert()->error('Error','¡No puede ejecutar acciones sobre documentos de años cerrados!');
                    return redirect('home');
                } 
            }
        }
        */

        return $next($request);
    }
}