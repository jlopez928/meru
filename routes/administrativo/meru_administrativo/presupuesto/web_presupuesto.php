<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Presupuesto\Causado\ActaContObraServController;

//Actas de Contratos Obras /Servicios
 /*   Route::controller(ActaContObraServController::class)
	->middleware(['auth'])
	->prefix('presupuesto/causado')
	->name('presupuesto.causado.actacontobraserv.')
	->group(function () {
		Route::get('actacontobraserv', 'index')->name('index');
		Route::get('actacontobraserv/create', 'create')->name('create');
		Route::get('actacontobraserv/{encnotaentrega}/edit', 'edit')->name('edit');
		Route::get('actacontobraserv/{encnotaentrega}', 'show')->name('show');
        Route::get('causar/{encnotaentrega}', 'causar')->name('causar');
        Route::get('causar_ejecutar/{encnotaentrega}', 'causar_ejecutar')->name('causar_ejecutar');
        Route::get('aprobar/{encnotaentrega}', 'aprobar')->name('aprobar');
        Route::get('aprobar_ejecutar/{encnotaentrega}', 'aprobar_ejecutar')->name('aprobar_ejecutar');
        Route::get('reversar/{encnotaentrega}', 'reversar')->name('reversar');
        Route::get('reversar_ejecutar/{encnotaentrega}', 'reversar_ejecutar')->name('reversar_ejecutar');
	});*/

    Route::middleware(['auth'])
	->prefix('presupuesto')
	->as('presupuesto.')
	->group(function () {
		    Route::name('configuracion.')
			->group(function () {

	        });
            Route::name('causado.')
			->group(function () {
                //Actas de Contratos Obras /Servicios
                Route::get('actacontobraserv', [ActaContObraServController::class, 'index'])->name('actacontobraserv.index');
                Route::get('actacontobraserv/{encnotaentrega}/edit', [ActaContObraServController::class, 'edit'])->name('actacontobraserv.edit');
                Route::get('actacontobraserv/{encnotaentrega}', [ActaContObraServController::class, 'show'])->name('actacontobraserv.show');
                Route::get('causar/{encnotaentrega}', [ActaContObraServController::class, 'causar'])->name('actacontobraserv.causar');
                Route::get('causar_ejecutar/{encnotaentrega}', [ActaContObraServController::class, 'causar_ejecutar'])->name('actacontobraserv.causar_ejecutar');
                Route::get('aprobar/{encnotaentrega}',[ActaContObraServController::class, 'aprobar'])->name('actacontobraserv.aprobar');
                Route::get('aprobar_ejecutar/{encnotaentrega}', [ActaContObraServController::class, 'aprobar_ejecutar'])->name('actacontobraserv.aprobar_ejecutar');
                Route::get('reversar/{encnotaentrega}', [ActaContObraServController::class, 'reversar'])->name('actacontobraserv.reversar');
                Route::get('reversar_ejecutar/{encnotaentrega}', [ActaContObraServController::class, 'reversar_ejecutar'])->name('actacontobraserv.reversar_ejecutar');
            });
            Route::name('reporte.')
			->group(function () {
	        });
    });
