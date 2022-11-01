<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\CompradorController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso\SolicitudUnidadController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\UnidadMedidaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\GrupoProductoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\CausaAnulacionController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\SubGrupoProductoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\BienMaterialServicioController;


Route::middleware(['auth', 'periodo-fiscal'])
	->prefix('compras')
	->as('compras.')
	->group(function () {
		    Route::name('configuracion.')
			->group(function () {
               Route::resource('causaanulacion', CausaAnulacionController::class)->except('destroy');
                Route::get('print_causa_anulacion', [CausaAnulacionController::class, 'print_causa_anulacion'])->name('print_causa_anulacion');

                Route::resource('unidadmedida', UnidadMedidaController::class)->except('destroy');
                Route::get('print_unidad_medida', [UnidadMedidaController::class, 'print_unidad_medida'])->name('print_unidad_medida');

	        });
            Route::name('proceso.')
			->group(function () {
            });

            Route::name('reporte.')
			->group(function () {


	        });
    });
// Grupos de Productos
Route::controller(GrupoProductoController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/configuracion')
->name('compras.configuracion.grupo_producto.')
->group(function () {

    Route::get('gruposdeproductos', 'index')->name('index');
    Route::post('gruposdeproductos', 'store')->name('store');
    Route::get('gruposdeproductos/create', 'create')->name('create');
    Route::get('gruposdeproductos/{grupoproducto}', 'show')->name('show');
    Route::get('gruposdeproductos/{grupoproducto}/edit', 'edit')->name('edit');
    Route::put('gruposdeproductos/{grupoproducto}', 'update')->name('update');
    Route::delete('gruposdeproductos/{grupoproducto}', 'destroy')->name('destroy');
    Route::get('print_grupo_productos', 'print_grupo_productos')->name('print_grupo_productos');
});

// SubGrupo de Productos
Route::controller(SubGrupoProductoController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/configuracion')
->name('compras.configuracion.subgrupo_producto.')
->group(function () {

    Route::get('subgrupodeproductos', 'index')->name('index');
    Route::get('subgrupodeproductos/create', 'create')->name('create');
    Route::get('subgrupodeproductos/{subgrupoproducto}', 'show')->name('show');
    Route::post('subgrupodeproductos', 'store')->name('store');
    Route::get('subgrupodeproductos/{subgrupoproducto}/edit', 'edit')->name('edit');
    Route::put('subgrupodeproductos/{subgrupoproducto}', 'update')->name('update');
    Route::delete('subgrupodeproductos/{subgrupoproducto}', 'destroy')->name('destroy');
    Route::get('print_subgrupo_productos', 'print_subgrupo_productos')->name('print_subgrupo_productos');
});


// Bienes/Materiales/Servicios Compras
Route::controller(BienMaterialServicioController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/configuracion')
->name('compras.configuracion.bien_material_servicio.')
->group(function () {

    Route::get('bienmaterialservicio', 'index')->name('index');
    Route::get('bienmaterialservicio/create', 'create')->name('create');
    Route::get('bienmaterialservicio/{producto}/edit', 'edit')->name('edit');
    Route::get('bienmaterialservicio/{producto}', 'show')->name('show');
    Route::get('bienmaterialservicio/{producto}/asignar', 'asignar')->name('asignar');
    Route::get('print_productos', 'print_productos')->name('print_productos');
});

// Compradores
Route::controller(CompradorController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/configuracion')
->name('compras.configuracion.comprador.')
->group(function () {

    Route::get('compradores', 'index')->name('index');
    Route::get('compradores/create', 'create')->name('create');
    Route::get('compradores/{comprador}', 'show')->name('show');
    Route::get('print_compradores', 'print_compradores')->name('print_compradores');
});

// Solicitudes - Unidad
Route::controller(SolicitudUnidadController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/proceso')
->name('compras.proceso.solicitud_unidad.')
->group(function () {

    Route::get('solicitudunidad', 'index')->name('index');
    Route::get('solicitudunidad/create', 'create')->name('create');
    Route::post('solicitudunidad', 'crear_solicitud')->name('crear_solicitud');
    Route::get('solicitudunidad/{ano_pro}/{grupo}/{nro_req}', 'show')->name('show');
    Route::get('solicitudunidad/{ano_pro}/{grupo}/{nro_req}/anular', 'anular')->name('anular');
    Route::post('solicitudunidad/anularsolicitud', 'anular_solicitud')->name('anular_solicitud');
    Route::get('solicitudunidad/{ano_pro}/{grupo}/{nro_req}/activar', 'activar')->name('activar');
    Route::get('solicitudunidad/{ano_pro}/{grupo}/{nro_req}/aprobar', 'aprobar')->name('aprobar');
    Route::get('solicitudunidad/{ano_pro}/{grupo}/{nro_req}/reversar', 'reversar')->name('reversar');
    Route::post('solicitudunidad/reversarsolicitud', 'reversar_solicitud')->name('reversar_solicitud');
    Route::get('solicitudunidad/{ano_pro}/{grupo}/{nro_req}/copiar', 'copiar')->name('copiar');
    Route::post('solicitudunidad/copiarsolicitud', 'copiar_solicitud')->name('copiar_solicitud');
    Route::get('solicitudunidad/{ano_pro}/{grupo}/{nro_req}/precomprometer', 'precomprometer')->name('precomprometer');
    Route::post('solicitudunidad/precomprometersolicitud', 'precomprometer_solicitud')->name('precomprometer_solicitud');
    Route::get('solicitudunidad/{ano_pro}/{grupo}/{nro_req}/edit', 'edit')->name('edit');
    Route::post('solicitudunidad/editarsolicitud', 'editar_solicitud')->name('editar_solicitud');
    Route::post('solicitudunidad/editaranexos', 'editar_anexos')->name('editar_anexos');
});
