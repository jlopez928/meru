<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Presupuesto\Causado\ActaContObraServController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Presupuesto\Reportes\ReportesPresupuestoController;

    Route::middleware(['auth', 'periodo-fiscal'])
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
                ->prefix('reporte')
                ->group(function () {
                    // Operaciones Presupuestarias
                    Route::get('operaciones', [ReportesPresupuestoController::class, 'operacionesCreate'])->name('operaciones.create');
                    Route::post('operaciones', [ReportesPresupuestoController::class, 'operacionesStore'])->name('operaciones.store');

                    // Diferencias Presupuestarias
                    Route::get('diferencias', [ReportesPresupuestoController::class, 'diferenciasCreate'])->name('diferencias.create');
                    Route::post('diferencias', [ReportesPresupuestoController::class, 'diferenciasStore'])->name('diferencias.store');

                    // Consolidado Partidas
                    Route::get('consolidado_partidas', [ReportesPresupuestoController::class, 'consolidadoPartidasCreate'])->name('consolidado_partidas.create');
                    Route::post('consolidado_partidas', [ReportesPresupuestoController::class, 'consolidadoPartidasStore'])->name('consolidado_partidas.store');

                    // Ajustes Presupuestarios
                    Route::get('ajustes', [ReportesPresupuestoController::class, 'ajustesCreate'])->name('ajustes.create');
                    Route::post('ajustes', [ReportesPresupuestoController::class, 'ajustesStore'])->name('ajustes.store');

                    // Listado de Solicitudes de Traspasos
                    Route::get('solicitudes_traspaso', [ReportesPresupuestoController::class, 'solicitudesTraspasoCreate'])->name('solicitudes_traspaso.create');
                    Route::post('solicitudes_traspaso', [ReportesPresupuestoController::class, 'solicitudesTraspasoStore'])->name('solicitudes_traspaso.store');

                    // Consolidado Servicios
                    Route::get('consolidado_servicios', [ReportesPresupuestoController::class, 'consolidadoServiciosCreate'])->name('consolidado_servicios.create');
                    Route::post('consolidado_servicios', [ReportesPresupuestoController::class, 'consolidadoServiciosStore'])->name('consolidado_servicios.store');
                });
    });
