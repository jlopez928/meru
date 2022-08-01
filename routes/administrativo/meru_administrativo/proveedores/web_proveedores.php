<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reportes\ProveedorMunicipioController;

Route::middleware(['auth'])
	->prefix('proveedores')
	->as('proveedores.')
	->group(function () {
		Route::name('configuracion.')
			->group(function () {
        });
        Route::name('proceso.')
			->group(function () {
		});

		Route::name('reportes.')
			->group(function () {
                Route::resource('proveedormunicipio',  ProveedorMunicipioController ::class)->except('destroy');
                Route::controller(ProveedorMunicipioController::class)
                        ->as('proveedormunicipio.')
                        ->group(function() {
                            Route::get('print_proveedormunicipio', [ProveedorMunicipioController::class, 'print_proveedormunicipio'])->name('print_proveedormunicipio');
                        });
		});
	});
