<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Proceso\ProveedorController;


Route::controller(ProveedorController::class)->group(function () {
    Route::get('estados/{ubicacion?}', 'getEstados');
    Route::get('municipios/{estado?}', 'getMunicipios');
});