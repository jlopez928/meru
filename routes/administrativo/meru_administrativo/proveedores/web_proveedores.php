<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Proceso\ProveedorController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Configuracion\RamoController;

/*-------------------------------------------------------------------------*/
/*                     Rutas del Modulo Proveedores                        */
/*-------------------------------------------------------------------------*/


// Proveedores
Route::controller(ProveedorController::class)
	->middleware(['auth'])
	->prefix('proveedores/procesos')
	->name('proveedores.proceso.proveedor.')
	->group(function () {

		Route::get('proveedores', 'index')->name('index');
		Route::get('proveedores/create', 'create')->name('create');
		Route::post('proveedores', 'store')->name('store');
		Route::get('proveedores/{proveedor}/edit', 'edit')->name('edit');
		Route::get('proveedores/{proveedor}', 'show')->name('show');
		Route::put('proveedores/{proveedor}', 'update')->name('update');
	});

// Ramos
Route::controller(RamoController::class)
	->middleware(['auth'])
	->prefix('proveedores/configuracion')
	->name('proveedores.configuracion.ramo.')
	->group(function () {

		Route::get('ramos', 'index')->name('index');
		Route::get('ramos/create', 'create')->name('create');
		Route::post('ramos', 'store')->name('store');
		Route::get('ramos/{ramo}/edit', 'edit')->name('edit');
		Route::put('ramos/{ramo}', 'update')->name('update');
		Route::get('print_ramos', 'print_ramos')->name('print_ramos');
	});