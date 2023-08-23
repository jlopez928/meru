<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasPorPagar\Proceso\RecepFacturaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasPorPagar\Proceso\FacturaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Proceso\SolicitudPagoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte\RepSolicitudPagoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte\RepComprobanteIvaController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte\RepComprobanteUnoxCienController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte\RepComprobanteISRLController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte\RepComprobanteCSOCController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\CuentasxPagar\Reporte\RepComprobanteunoxmilController;



Route::middleware(['auth', 'periodo-fiscal'])
	->prefix('cuentasxpagar')
	->as('cuentasxpagar.')
	->group(function () {
		    Route::name('configuracion.')
			->group(function () {

	        });
            Route::name('proceso.')
			->group(function () {
                Route::resource('solicititudpago', SolicitudPagoController::class)->except('destroy');
                Route::get('print_solicitud_pago', [SolicitudPagoController::class, 'print_solicitud_pago'])->name('print_solicitud_pago');
                //----------------------------------Recepción Facturas------------------------------------------------------------------------------------------
                Route::resource('recepfactura', RecepFacturaController::class)->except('destroy','show');
                Route::get('print_recep_factura', [RecepFacturaController::class, 'print_recep_factura'])->name('print_recep_factura');
                Route::post('recepfactura/{recepfactura}/{accion}/show', [RecepFacturaController::class, 'show'])->name('recepfactura.show');
                Route::get('recepfactura/{recepfactura}/{accion}/show', [RecepFacturaController::class, 'show'])->name('recepfactura.show');
                Route::post('recepfactura/{recepfactura}/entregar', [RecepFacturaController::class, 'entregar'])->name('recepfactura.entregar');
                Route::post('recepfactura/{recepfactura}/reactivar', [RecepFacturaController::class, 'reactivar'])->name('recepfactura.reactivar');
                Route::post('recepfactura/{recepfactura}/modificar', [RecepFacturaController::class, 'modificar'])->name('recepfactura.modificar');
                Route::post('recepfactura/{recepfactura}/eliminar', [RecepFacturaController::class, 'eliminar'])->name('recepfactura.eliminar');
                //----------------------------------Facturas------------------------------------------------------------------------------------------
                Route::resource('factura', FacturaController::class)->except('destroy','show');
                Route::get( 'factura/{factura}/{accion}/show', [FacturaController::class, 'show'])->name('factura.show');

	        });
            Route::name('reporte.')
			->group(function () {
                Route::get('print_fact_pend_devolver', [RecepFacturaController::class, 'print_fact_pend_devolver'])->name('print_fact_pend_devolver');
                Route::get('print_devolver_fact_recibida/{recepfactura}', [RecepFacturaController::class, 'print_devolver_fact_recibida'])->name('print_devolver_fact_recibida');
                Route::get('print_generar_solicitud/{solicititudpago}', [RepSolicitudPagoController::class, 'print_generar_solicitud'])->name('print_generar_solicitud');
                Route::get('print_generar_iva/{solicititudpago}', [RepComprobanteIvaController::class, 'print_generar_iva'])->name('print_generar_iva');
                Route::get('print_generar_unoxcien/{solicititudpago}', [RepComprobanteUnoxCienController::class, 'print_generar_unoxcien'])->name('print_generar_unoxcien');
                Route::get('print_generar_ISLR/{solicititudpago}', [RepComprobanteISRLController::class, 'print_generar_ISLR'])->name('print_generar_ISLR');
                Route::get('print_generar_CSOC/{solicititudpago}', [RepComprobanteCSOCController::class, 'print_generar_CSOC'])->name('print_generar_CSOC');
                Route::get('print_generar_UNOXMIL/{solicititudpago}', [RepComprobanteunoxmilController::class, 'print_generar_UNOXMIL'])->name('print_generar_UNOXMIL');


	        });
    });
