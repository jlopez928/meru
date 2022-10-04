<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Configuracion\PermisoTraspasoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\SolicitudTraspasoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\TraspasoPresupuestarioController;

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
				// Traspasos Presupuestarios
				Route::resource('traspaso_presupuestario', TraspasoPresupuestarioController::class)
					->except('destroy')
					->parameters([
						'traspaso_presupuestario' => 'traspaso'
					]);

				Route::controller(TraspasoPresupuestarioController::class)
					->as('traspaso_presupuestario.')
					->prefix('traspaso_presupuestario')
					->group(function() {
						// Anular
						Route::get('{traspaso}/anular', [TraspasoPresupuestarioController::class, 'anularEdit'])->name('anular.edit');
						Route::put('anular/{traspaso}', [TraspasoPresupuestarioController::class, 'anularUpdate'])->name('anular.update');

						// Apartar
						Route::get('{traspaso}/apartar', [TraspasoPresupuestarioController::class, 'apartarEdit'])->name('apartar.edit');
						Route::put('apartar/{traspaso}', [TraspasoPresupuestarioController::class, 'apartarUpdate'])->name('apartar.update');

						// Reversar Apartado
						Route::get('{traspaso}/reversar_apartado', [TraspasoPresupuestarioController::class, 'reversarApartadoEdit'])->name('reversar_apartado.edit');
						Route::put('reversar_apartado/{traspaso}', [TraspasoPresupuestarioController::class, 'reversarApartadoUpdate'])->name('reversar_apartado.update');

						// Aprobar
						Route::get('{traspaso}/aprobar', [TraspasoPresupuestarioController::class, 'aprobarEdit'])->name('aprobar.edit');
						Route::put('aprobar/{traspaso}', [TraspasoPresupuestarioController::class, 'aprobarUpdate'])->name('aprobar.update');

						// Reversar Aprobación
						Route::get('{traspaso}/reversar_aprobacion', [TraspasoPresupuestarioController::class, 'reversarAprobacionEdit'])->name('reversar_aprobacion.edit');
						Route::put('reversar_aprobacion/{traspaso}', [TraspasoPresupuestarioController::class, 'reversarAprobacionUpdate'])->name('reversar_aprobacion.update');
					});

				// Solicitud de Traspasos
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