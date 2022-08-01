<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Configuracion\CentroCostoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Configuracion\PartidaPresupuestariaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Configuracion\MaestroLeyController;

Route::middleware(['auth'])
	->prefix('formulacion')
	->as('formulacion.')
	->group(function () {
		Route::name('configuracion.')
			->group(function () {
				Route::resource('centro_costo', CentroCostoController::class)->except('destroy');

				Route::controller(CentroCostoController::class)
					->as('centro_costo.')
					->group(function() {
			    	Route::get('print_centros_costos', [CentroCostoController::class, 'print_centros_costos'])->name('print_centros_costos');
				});

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
				//
			});
	});