<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administrativo\Meru_Administrativo\OtrosPagos\Proceso\CertificacionServicioController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\OtrosPagos\Configuracion\OpConceptosController;
Route::middleware(['auth'])
	->prefix('otrospagos')
	->as('otrospagos.')
	->group(function () {
		    Route::name('configuracion.')
			->group(function () {
                Route::resource('conceptoservicio', OpConceptosController::class)->except('destroy');
                Route::get('print_conceptos_servicios', [OpConceptosController::class, 'print_conceptos_servicios'])->name('print_conceptos_servicios');
	        });
            Route::name('proceso.')
			->group(function () {
                Route::resource('certificacionservicio', CertificacionServicioController::class)->except('destroy', 'show');
                Route::get('print_certificacion_servicios', [CertificacionServicioController::class, 'print_certificacion_servicios'])->name('print_certificacion_servicios');
                Route::get('certificacionservicio/{certificacionservicio}/aprobar_certificacion', [CertificacionServicioController::class, 'aprobar_certificacion'])->name('certificacionservicio.aprobar_certificacion');
                Route::get('certificacionservicio/{certificacionservicio}/anular_certificacion', [CertificacionServicioController::class, 'anular_certificacion'])->name('certificacionservicio.anular_certificacion');
                Route::get('certificacionservicio/{certificacionservicio}/reversar_certificacion', [CertificacionServicioController::class, 'reversar_certificacion'])->name('certificacionservicio.reversar_certificacion');
                Route::get('certificacionservicio/{certificacionservicio}/comprometer_certificacion', [CertificacionServicioController::class, 'comprometer_certificacion'])->name('certificacionservicio.comprometer_certificacion');
                Route::get('certificacionservicio/{certificacionservicio}/reverso_compromiso_certificacion', [CertificacionServicioController::class, 'reverso_compromiso_certificacion'])->name('certificacionservicio.reverso_compromiso_certificacion');
                Route::get('certificacionservicio/{certificacionservicio}/{accion}/show', [CertificacionServicioController::class, 'show'])->name('certificacionservicio.show');
                Route::get('print_certificacion_solicitud', [CertificacionServicioController::class, 'print_certificacion_solicitud'])->name('print_certificacion_solicitud');
                Route::get('print_certificacion', [CertificacionServicioController::class, 'print_certificacion'])->name('print_certificacion');
	        });
            Route::name('reporte.')
			->group(function () {
	        });
    });
