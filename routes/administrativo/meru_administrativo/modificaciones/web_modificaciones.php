<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Configuracion\PermisoTraspasoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\SolicitudTraspasoController;

Route::middleware(['auth', 'periodo-fiscal'])
	->prefix('modificaciones')
	->as('modificaciones.')
	->group(function () {
		Route::name('configuracion.')
			->group(function () {
				// Permisos para Traspasos
				Route::resource('permiso_traspaso', PermisoTraspasoController::class)->except('show');
			});

        Route::name('movimientos.')
			->group(function () {
				// Permisos para Traspasos
				Route::resource('solicitud_traspaso', SolicitudTraspasoController::class)->except('destroy');
				Route::controller(SolicitudTraspasoController::class)
					->as('solicitud_traspaso.')
					->group(function() {
						// Aprobar
						Route::get('solicitud_traspaso/{solicitud_traspaso}/aprobar', [SolicitudTraspasoController::class, 'aprobarEdit'])->name('aprobar.edit');
						Route::put('solicitud_traspaso/aprobar/{solicitud_traspaso}', [SolicitudTraspasoController::class, 'aprobarUpdate'])->name('aprobar.update');

						// Anular
						Route::get('solicitud_traspaso/{solicitud_traspaso}/anular', [SolicitudTraspasoController::class, 'anularEdit'])->name('anular.edit');
						Route::put('solicitud_traspaso/anular/{solicitud_traspaso}', [SolicitudTraspasoController::class, 'anularUpdate'])->name('anular.update');

						// Rechazar
						Route::get('solicitud_traspaso/{solicitud_traspaso}/rechazar', [SolicitudTraspasoController::class, 'rechazarEdit'])->name('rechazar.edit');
						Route::put('solicitud_traspaso/rechazar/{solicitud_traspaso}', [SolicitudTraspasoController::class, 'rechazarUpdate'])->name('rechazar.update');

						// Imprimir
						Route::get('solicitud_traspaso/{solicitud_traspaso}/imprimir', [SolicitudTraspasoController::class, 'imprimir'])->name('imprimir');
				});
				
			});

		Route::name('reportes.')
			->group(function () {
				//
			});
	});