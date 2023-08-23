<?php

use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso\SolicitudUnidadController;
use Illuminate\Support\Facades\Route;

Route::controller(SolicitudUnidadController::class)->group(function () {
    Route::get('unidades/{gerencia?}', 'getUnidades');
    Route::get('centrocosto/{gerencia?}', 'getCentroCosto');
    Route::get('creditoadicional/{gerencia}/{anopro}', 'getCreditoAdicional');
    Route::get('centrocostounidades/{anopro}', 'getCentroCostoUnidades');
    Route::get('ultimaunidadtributaria', 'getUnidadTributaria');
    Route::get('rangosunidadtributaria/{licita?}', 'getRangosUnidadTributaria');
    Route::get('ordenes/{ano_pro}/{grupo}/{nro_req}', 'getOrdenes');
    Route::get('ofertas/{ano_pro}/{grupo}/{nro_req}', 'getOfertas');
    Route::get('cotizaciones/{ano_pro}/{grupo}/{nro_req}', 'getCotizaciones');
});
