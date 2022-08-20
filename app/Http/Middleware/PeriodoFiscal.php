<?php

namespace App\Http\Middleware;

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
        if (empty(session('ano_pro'))) {
            alert()->error('Error','¡Debe seleccionar un Periodo Fiscal para poder realizar esta acción!');
            return redirect('home');
        }

        return $next($request);
    }
}