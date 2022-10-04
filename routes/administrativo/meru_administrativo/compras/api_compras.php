<?php

use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso\SolicitudUnidadController;
use Illuminate\Support\Facades\Route;

Route::controller(SolicitudUnidadController::class)->group(function () {
    Route::get('unidades/{gerencia?}', 'getUnidades');
    Route::get('centrocosto/{gerencia?}', 'getCentroCosto');
    Route::get('ultimaunidadtributaria', 'getUnidadTributaria');
    Route::get('rangosunidadtributaria/{licita?}', 'getRangosUnidadTributaria');
});
