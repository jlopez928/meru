<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Configuracion\PermisoTraspasoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\SolicitudTraspasoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\TraspasoPresupuestarioController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\CreditoAdicionalController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\DisminucionController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Modificaciones\Movimientos\InsubsistenciaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Presupuesto\Reportes\ReportesPresupuestoController;

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

				// Creditos Adicionales
				Route::resource('credito_adicional', CreditoAdicionalController::class)
					->except('destroy');

				Route::controller(CreditoAdicionalController::class)
					->as('credito_adicional.')
					->prefix('credito_adicional')
					->group(function() {
						// Anular
						Route::get('{credito_adicional}/anular', [CreditoAdicionalController::class, 'anularEdit'])->name('anular.edit');
						Route::put('anular/{credito_adicional}', [CreditoAdicionalController::class, 'anularUpdate'])->name('anular.update');

						// Aprobar
						Route::get('{credito_adicional}/aprobar', [CreditoAdicionalController::class, 'aprobarEdit'])->name('aprobar.edit');
						Route::put('aprobar/{credito_adicional}', [CreditoAdicionalController::class, 'aprobarUpdate'])->name('aprobar.update');

						// Reversar Aprobación
						Route::get('{credito_adicional}/reversar_aprobacion', [CreditoAdicionalController::class, 'reversarAprobacionEdit'])->name('reversar_aprobacion.edit');
						Route::put('reversar_aprobacion/{credito_adicional}', [CreditoAdicionalController::class, 'reversarAprobacionUpdate'])->name('reversar_aprobacion.update');
					});

				// Disminuciones
				Route::resource('disminucion', DisminucionController::class)
					->except('destroy');

				Route::controller(DisminucionController::class)
					->as('disminucion.')
					->prefix('disminucion')
					->group(function() {
						// Anular
						Route::get('{disminucion}/anular', [DisminucionController::class, 'anularEdit'])->name('anular.edit');
						Route::put('anular/{disminucion}', [DisminucionController::class, 'anularUpdate'])->name('anular.update');

						// Apartar
						Route::get('{disminucion}/apartar', [DisminucionController::class, 'apartarEdit'])->name('apartar.edit');
						Route::put('apartar/{disminucion}', [DisminucionController::class, 'apartarUpdate'])->name('apartar.update');

						// Reversar Apartado
						Route::get('{disminucion}/reversar_apartado', [DisminucionController::class, 'reversarApartadoEdit'])->name('reversar_apartado.edit');
						Route::put('reversar_apartado/{disminucion}', [DisminucionController::class, 'reversarApartadoUpdate'])->name('reversar_apartado.update');

						// Aprobar
						Route::get('{disminucion}/aprobar', [DisminucionController::class, 'aprobarEdit'])->name('aprobar.edit');
						Route::put('aprobar/{disminucion}', [DisminucionController::class, 'aprobarUpdate'])->name('aprobar.update');

						// Reversar Aprobación
						Route::get('{disminucion}/reversar_aprobacion', [DisminucionController::class, 'reversarAprobacionEdit'])->name('reversar_aprobacion.edit');
						Route::put('reversar_aprobacion/{disminucion}', [DisminucionController::class, 'reversarAprobacionUpdate'])->name('reversar_aprobacion.update');
					});

				// Insubsistencias
				Route::resource('insubsistencia', InsubsistenciaController::class)
					->except('destroy')
					->parameters([
						'insubsistencia' => 'insubsistencia'
					]);

				Route::controller(InsubsistenciaController::class)
					->as('insubsistencia.')
					->prefix('insubsistencia')
					->group(function() {
						// Anular
						Route::get('{insubsistencia}/anular', [InsubsistenciaController::class, 'anularEdit'])->name('anular.edit');
						Route::put('anular/{insubsistencia}', [InsubsistenciaController::class, 'anularUpdate'])->name('anular.update');

						// Apartar
						Route::get('{insubsistencia}/apartar', [InsubsistenciaController::class, 'apartarEdit'])->name('apartar.edit');
						Route::put('apartar/{insubsistencia}', [InsubsistenciaController::class, 'apartarUpdate'])->name('apartar.update');

						// Reversar Apartado
						Route::get('{insubsistencia}/reversar_apartado', [InsubsistenciaController::class, 'reversarApartadoEdit'])->name('reversar_apartado.edit');
						Route::put('reversar_apartado/{insubsistencia}', [InsubsistenciaController::class, 'reversarApartadoUpdate'])->name('reversar_apartado.update');

						// Aprobar
						Route::get('{insubsistencia}/aprobar', [InsubsistenciaController::class, 'aprobarEdit'])->name('aprobar.edit');
						Route::put('aprobar/{insubsistencia}', [InsubsistenciaController::class, 'aprobarUpdate'])->name('aprobar.update');

						// Reversar Aprobación
						Route::get('{insubsistencia}/reversar_aprobacion', [InsubsistenciaController::class, 'reversarAprobacionEdit'])->name('reversar_aprobacion.edit');
						Route::put('reversar_aprobacion/{insubsistencia}', [InsubsistenciaController::class, 'reversarAprobacionUpdate'])->name('reversar_aprobacion.update');
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

			Route::name('reporte.')
			->prefix('reporte')
			->group(function () {
				// Listado de Solicitudes de Traspasos
				Route::get('modificaciones', [TraspasoPresupuestarioController::class, 'listadoModificacionesCreate'])->name('listado_modificaciones.create');
				Route::post('modificaciones', [TraspasoPresupuestarioController::class, 'listadoModificacionesStore'])->name('listado_modificaciones.store');

				// Listado de Solicitudes de Traspasos
				Route::get('solicitudes_traspaso', [ReportesPresupuestoController::class, 'solicitudesTraspasoCreate'])->name('solicitudes_traspaso.create');
				Route::post('solicitudes_traspaso', [ReportesPresupuestoController::class, 'solicitudesTraspasoStore'])->name('solicitudes_traspaso.store');
			});
	});