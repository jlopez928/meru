<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control\PermisoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control\RegistroControlController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control\RolController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion\UnidadTributariaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control\ModuloController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control\UserRolController;
use  App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion\TasaCambioController;
use  App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion\DescuentoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion\GerenciaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion\UbicacionGeograficaController;

Route::middleware(['auth', 'periodo-fiscal'])
	->prefix('configuracion')
	->as('configuracion.')
	->group(function () {
		    Route::name('configuracion.')
			->group(function () {
				Route::resource('gerencia', GerenciaController::class)
					->except('destroy')
					->parameters([
						'gerencia' => 'gerencia'
					]);
                Route::resource('tasacambio',  TasaCambioController ::class)->except('destroy');
                Route::resource('unidadtributaria',  UnidadTributariaController ::class)->except('destroy');
                Route::resource('descuento',  DescuentoController ::class)->except('destroy');
                Route::resource('ubicacion_geografica', UbicacionGeograficaController::class)->except('destroy');
				Route::controller(UbicacionGeograficaController::class)
					->as('ubicacion_geografica.')
					->group(function() {
			    Route::get('print_ubicaciones_geograficas', [UbicacionGeograficaController::class, 'print_ubicaciones_geograficas'])->name('print_ubicaciones_geograficas');
				});
                Route::controller(TasaCambioController::class)
                ->as('tasacambio.')
                ->group(function() {
                     Route::get('print_tasacambio', [TasaCambioController::class, 'print_tasacambio'])->name('print_tasacambio');
                 });
                 Route::controller(UnidadTributariaController::class)
                 ->as('unidadtributaria.')
                 ->group(function() {
                      Route::get('print_unidadtributaria', [UnidadTributariaController::class, 'print_unidadtributaria'])->name('print_unidadtributaria');
                  });
                  Route::controller(DescuentoController::class)
                  ->as('descuento.')
                  ->group(function() {
                      Route::get('print_descuento', [DescuentoController::class, 'print_descuento'])->name('print_descuento');
                 });



			});
            Route::name('control.')
			->group(function () {
                Route::resource('modulo',  ModuloController::class)->except('destroy');
                Route::resource('rol',  RolController::class)->except('destroy');
                Route::resource('permiso',  PermisoController::class)->except('destroy');
                Route::resource('userrol',  UserRolController::class)->except('destroy');
                Route::resource('registrocontrol',  RegistroControlController::class)->except('destroy');
                Route::controller(RegistroControlController::class)
                ->as('registrocontrol.')
                ->group(function() {
                     Route::get('print_registrocontrol', [RegistroControlController::class, 'print_registrocontrol'])->name('print_registrocontrol');

                     // Periodo Fiscal global
                     Route::post('periodo_actual', [RegistroControlController::class, 'periodoActual'])->name('periodo_actual');
                 });
                 Route::controller(PermisoController::class)
                 ->as('permiso.')
                 ->group(function() {
                     Route::get('print_permiso', [PermisoController::class, 'print_permiso'])->name('print_permiso');
                });
                Route::controller(RolController::class)
                ->as('rol.')
                ->group(function() {
                     Route::get('print_rol', [RolController::class, 'print_rol'])->name('print_rol');
                 });
                 Route::controller(UserRolController::class)
                 ->as('userrol.')
                 ->group(function() {
                     Route::get('print_userrol', [UserRolController::class, 'print_userrol'])->name('print_userrol');
                });
                Route::controller(RolController::class)
                ->as('asignarpermiso.')
                ->group(function() {
                    Route::get('asignarpermiso/{rol}', [RolController::class, 'asignarpermiso'])->name('asignarpermiso');
               });
               Route::controller(ModuloController::class)
               ->as('modulo.')
               ->group(function() {
                    Route::get('print_modulo', [ModuloController::class, 'print_modulo'])->name('print_modulo');
                });


			});

		Route::name('reportes.')
			->group(function () {
				//
			});
	});
