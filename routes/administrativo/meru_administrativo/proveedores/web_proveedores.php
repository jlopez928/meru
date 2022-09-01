<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte\CronologiaProveedorController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte\RepProvObjetivoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte\ConstanciaProveedorController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte\ProveedorMunicipioController;

/*-------------------------------------------------------------------------*/
/*                     Rutas del Modulo Proveedores                        */
/*-------------------------------------------------------------------------*/
      Route::middleware(['auth'])
      ->prefix('proveedores')
      ->as('proveedores.')
      ->group(function () {
              Route::name('reporte.')
              ->group(function () {
                Route::get('print_repprovobjetivo', [RepProvObjetivoController::class, 'print_repprovobjetivo'])->name('print_repprovobjetivo');
                Route::resource('repprovobjetivo',  RepProvObjetivoController::class)->except('destroy','store', 'show', 'update','create');
                Route::get('print_consproveedor', [ConstanciaProveedorController::class, 'print_consproveedor'])->name('print_consproveedor');
                Route::resource('constanciaproveedores',  ConstanciaProveedorController::class)->except('destroy','store', 'show', 'update','create');
                Route::get('print_cronologiaproveedor', [CronologiaProveedorController::class, 'print_cronologiaproveedor'])->name('print_cronologiaproveedor');
                Route::resource('cronologiaproveedor', CronologiaProveedorController::class)->except('destroy','show','store','edit','create');
                Route::get('print_proveedormunicipio', [ProveedorMunicipioController::class, 'print_proveedormunicipio'])->name('print_proveedormunicipio');
                Route::resource('proveedormunicipio', ProveedorMunicipioController::class)->except('destroy','show','store','edit','create');
            });
      });
