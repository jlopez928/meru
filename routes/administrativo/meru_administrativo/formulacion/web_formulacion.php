<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Configuracion\CentroCostoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Configuracion\PartidaPresupuestariaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Configuracion\MaestroLeyController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Reporte\ReporteMaestroLeyController;

Route::middleware(['auth', 'periodo-fiscal'])
	->prefix('formulacion')
	->as('formulacion.')
	->group(function () {
		Route::name('configuracion.')
			->group(function () {
				// Centros de Costo
				Route::resource('centro_costo', CentroCostoController::class)->except('destroy');
				Route::controller(CentroCostoController::class)
					->as('centro_costo.')
					->group(function() {
					Route::get('print_centros_costos', [CentroCostoController::class, 'print_centros_costos'])->name('print_centros_costos');
				});

				// Partidas Presupuestarias
				Route::resource('partida_presupuestaria', PartidaPresupuestariaController::class)
					->except('destroy')
					->parameters([
						'partida_presupuestaria' => 'partida_presupuestaria'
					]);
				Route::controller(PartidaPresupuestariaController::class)
					->as('partida_presupuestaria.')
					->group(function() {
					Route::get('partida_presupuestaria/{partida_presupuestaria}/asociar_cuenta', 'editAsociar')->name('asociar_cuenta.edit');
					Route::put('partida_presupuestaria/asociar_cuenta/{partida_presupuestaria}', 'updateAsociar')->name('asociar_cuenta.update');
					Route::get('print_partidas_presupuestarias', [PartidaPresupuestariaController::class, 'print_partidas_presupuestarias'])->name('print_partidas');;
				});

				// Maestro de Ley
				Route::resource('maestro_ley', MaestroLeyController::class)->except('destroy');
				Route::controller(MaestroLeyController::class)
					->as('maestro_ley.')
					->group(function() {
					Route::get('importar_maestro_ley', [MaestroLeyController::class, 'createImportar'])->name('importar.create');
					Route::post('importar_maestro_ley', [MaestroLeyController::class, 'storeImportar'])->name('importar.store');
				});
			});

		Route::name('reportes.')
			->group(function () {
				Route::controller(MaestroLeyController::class)
					->as('maestro_ley.')
					->group(function() {
						// Reporte Resumen Presupuestario
						Route::get('reporte/resumen', [ReporteMaestroLeyController::class, 'resumenCreate'])->name('resumen.create');
						Route::post('reporte/resumen', [ReporteMaestroLeyController::class, 'resumenStore'])->name('resumen.store');

						// Reporte Maestro de Ley
						Route::get('reporte/maestroley', [ReporteMaestroLeyController::class, 'maestroleyCreate'])->name('maestroley.create');
						Route::post('reporte/maestroley', [ReporteMaestroLeyController::class, 'maestroleyStore'])->name('maestroley.store');

						// Reporte Ejecución Presupuestaria
						Route::get('reporte/ejecucion', [ReporteMaestroLeyController::class, 'ejecucionCreate'])->name('ejecucion.create');
						Route::post('reporte/ejecucion', [ReporteMaestroLeyController::class, 'ejecucionStore'])->name('ejecucion.store');
				});
			});
	});