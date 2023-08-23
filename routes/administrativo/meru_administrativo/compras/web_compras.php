<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso\SolicitudController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\CompradorController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso\SolicitudCompraController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\UnidadMedidaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\GrupoProductoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\CausaAnulacionController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso\SolicitudPresupuestoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso\SolicitudContratacionController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\SubGrupoProductoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion\BienMaterialServicioController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Proceso\SolicitudUnidadDonanteController;

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

// Solicitudes Unidad
Route::controller(SolicitudController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/proceso')
->name('compras.proceso.solicitud.unidad.')
->group(function () {

    Route::get('solicitud/unidad', 'index')->name('index');
    Route::get('solicitud/unidad/create', 'create')->name('create');
    Route::get('solicitud/unidad/{ano_pro}/{grupo}/{nro_req}', 'show')->name('show');
    Route::get('solicitud/unidad/{ano_pro}/{grupo}/{nro_req}/anular', 'anular')->name('anular');
    Route::get('solicitud/unidad/{ano_pro}/{grupo}/{nro_req}/activar', 'activar')->name('activar');
    Route::get('solicitud/unidad/{ano_pro}/{grupo}/{nro_req}/aprobar', 'aprobar')->name('aprobar');
    Route::get('solicitud/unidad/{ano_pro}/{grupo}/{nro_req}/reversar', 'reversar')->name('reversar');
    Route::get('solicitud/unidad/{ano_pro}/{grupo}/{nro_req}/copiar', 'copiar')->name('copiar');
    Route::get('solicitud/unidad/{ano_pro}/{grupo}/{nro_req}/precomprometer', 'precomprometer')->name('precomprometer');
    Route::get('solicitud/unidad/{ano_pro}/{grupo}/{nro_req}/edit', 'edit')->name('edit');
});

// Solicitudes Compras - Recibir
Route::controller(SolicitudCompraController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/proceso')
->name('compras.proceso.solicitud.compra_recibir.')
->group(function () {

    Route::get('solicitud/compra', 'index')->name('index');
    Route::get('solicitud/compra/{ano_pro}/{grupo}/{nro_req}', 'show')->name('show');
    Route::get('solicitud/compra/{ano_pro}/{grupo}/{nro_req}/recepcionar', 'recepcionar')->name('recepcionar');
    Route::get('solicitud/compra/{ano_pro}/{grupo}/{nro_req}/devolver', 'devolver')->name('devolver');
    Route::get('solicitud/compra/{ano_pro}/{grupo}/{nro_req}/comprador', 'asignar_comprador')->name('comprador');
    Route::get('solicitud/compra/{ano_pro}/{grupo}/{nro_req}/reasignar', 'reasignar')->name('reasignar');
    Route::get('solicitud/compra/{ano_pro}/{grupo}/{nro_req}/imprimir', 'imprimir')->name('imprimir');
});

// Solicitudes Contrataciones - Recibir
Route::controller(SolicitudContratacionController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/proceso')
->name('compras.proceso.solicitud.contratacion_recibir.')
->group(function () {

    Route::get('solicitud/contratacion', 'index')->name('index');
    Route::get('solicitud/contratacion/{ano_pro}/{grupo}/{nro_req}', 'show')->name('show');
    Route::get('solicitud/contratacion/{ano_pro}/{grupo}/{nro_req}/recepcionar', 'recepcionar')->name('recepcionar');
    Route::get('solicitud/contratacion/{ano_pro}/{grupo}/{nro_req}/devolver', 'devolver')->name('devolver');
    Route::get('solicitud/contratacion/{ano_pro}/{grupo}/{nro_req}/comprador', 'asignar_comprador')->name('comprador');
    Route::get('solicitud/contratacion/{ano_pro}/{grupo}/{nro_req}/reasignar', 'reasignar')->name('reasignar');
    Route::get('solicitud/contratacion/{ano_pro}/{grupo}/{nro_req}/imprimir', 'imprimir')->name('imprimir');
});

// Solicitudes Presupuesto
Route::controller(SolicitudPresupuestoController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/proceso')
->name('compras.proceso.solicitud.presupuesto.')
->group(function () {

    Route::get('solicitud/presupuesto', 'index')->name('index');
    Route::get('solicitud/presupuesto/{ano_pro}/{grupo}/{nro_req}', 'show')->name('show');
    Route::get('solicitud/presupuesto/{ano_pro}/{grupo}/{nro_req}/aprobar', 'aprobar')->name('aprobar');
    Route::get('solicitud/presupuesto/{ano_pro}/{grupo}/{nro_req}/reversar', 'reversar')->name('reversar');
});

// Solicitudes Unidad Donante
Route::controller(SolicitudUnidadDonanteController::class)
->middleware(['auth', 'periodo-fiscal'])
->prefix('compras/proceso')
->name('compras.proceso.solicitud.unidad_donante.')
->group(function () {

    Route::get('solicitud/unidaddonante', 'index')->name('index');
    Route::get('solicitud/unidaddonante/{ano_pro}/{grupo}/{nro_req}', 'show')->name('show');
    // Route::get('solicitud/presupuesto/{ano_pro}/{grupo}/{nro_req}/aprobar', 'aprobar')->name('aprobar');
    // Route::get('solicitud/presupuesto/{ano_pro}/{grupo}/{nro_req}/reversar', 'reversar')->name('reversar');
});
