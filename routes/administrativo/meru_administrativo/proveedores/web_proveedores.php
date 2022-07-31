<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Proceso\ProveedorController;

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