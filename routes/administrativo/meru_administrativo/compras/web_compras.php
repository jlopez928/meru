<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compra\Configuracion\CausaAnulacionController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compra\Configuracion\UnidadMedidaController;
Route::middleware(['auth'])
	->prefix('compra')
	->as('compra.')
	->group(function () {
		    Route::name('configuracion.')
			->group(function () {
               Route::resource('causaanulacion', CausaAnulacionController::class)->except('destroy');
                Route::get('print_causa_anulacion', [CausaAnulacionController::class, 'print_causa_anulacion'])->name('print_causa_anulacion');

                Route::resource('unidadmedida', UnidadMedidaController::class)->except('destroy');
                Route::get('print_unidad_medida', [UnidadMedidaController::class, 'print_unidad_medida'])->name('print_unidad_medida');

	        });
            Route::name('proceso.')
			->group(function () {
            });

            Route::name('reporte.')
			->group(function () {


	        });
    });
