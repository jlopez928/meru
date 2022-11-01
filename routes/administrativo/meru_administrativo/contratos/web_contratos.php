<?php
use App\Http\Controllers\Administrativo\Meru_Administrativo\Contratos\Configuracion\ConceptosContratosController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Contratos\Proceso\CertificacionContratoController;
use App\Http\Controllers\Administrativo\Meru_Administrativo\Contratos\Proceso\ActaContratoObraServController;
use Illuminate\Support\Facades\Route;
Route::middleware(['auth'])
	->prefix('contratos')
	->as('contratos.')
	->group(function () {
		    Route::name('configuracion.')
			->group(function () {
                Route::resource('conceptoscontratos', ConceptosContratosController::class)->except('destroy');
                Route::get('print_conceptos_contratos', [ConceptosContratosController::class, 'print_conceptos_contratos'])->name('print_conceptos_contratos');
	        });
            Route::name('proceso.')
			->group(function () {
                Route::resource('certificacioncontrato', CertificacionContratoController::class)->except('destroy', 'show','create');
                Route::get('print_certificacion_contrato/{accion}', [CertificacionContratoController::class, 'print_certificacion_contrato'])->name('print_certificacion_contrato');
                Route::get('certificacioncontrato/{accion}/crear', [CertificacionContratoController::class, 'crear'])->name('certificacioncontrato.crear');
                Route::get('certificacioncontrato/{certificacioncontrato}/aprobar_certificacion', [CertificacionContratoController::class, 'aprobar_certificacion'])->name('certificacioncontrato.aprobar_certificacion');
                Route::get('certificacioncontrato/{certificacioncontrato}/anular_anticipo', [CertificacionContratoController::class, 'anular_anticipo'])->name('certificacioncontrato.anular_anticipo');
                Route::get('certificacioncontrato/{certificacioncontrato}/anular_certificacion', [CertificacionContratoController::class, 'anular_certificacion'])->name('certificacioncontrato.anular_certificacion');
                Route::get('certificacioncontrato/{certificacioncontrato}/reversar_certificacion', [CertificacionContratoController::class, 'reversar_certificacion'])->name('certificacioncontrato.reversar_certificacion');
                Route::get('certificacioncontrato/{certificacioncontrato}/comprometer_certificacion', [CertificacionContratoController::class, 'comprometer_certificacion'])->name('certificacioncontrato.comprometer_certificacion');
                Route::get('certificacioncontrato/{certificacioncontrato}/reverso_compromiso_certificacion', [CertificacionContratoController::class, 'reverso_compromiso_certificacion'])->name('certificacioncontrato.reverso_compromiso_certificacion');
                Route::get('certificacioncontrato/{certificacioncontrato}/cierre_compromiso_certificacion', [CertificacionContratoController::class, 'cierre_compromiso_certificacion'])->name('certificacioncontrato.cierre_compromiso_certificacion');
                Route::get('certificacioncontrato/{certificacioncontrato}/{accion}/show', [CertificacionContratoController::class, 'show'])->name('certificacioncontrato.show');
                Route::get('print_certificacionobras', [CertificacionContratoController::class, 'print_certificacionobras'])->name('print_certificacionobras');
                Route::resource('actacontratobraserv', ActaContratoObraServController::class)->except('destroy','show');
                Route::get('iniciarentrega/{actacontratobraserv}', [ActaContratoObraServController::class, 'iniciarentrega'])->name('actacontratobraserv.iniciarentrega');
                Route::get('terminarentrega/{actacontratobraserv}', [ActaContratoObraServController::class, 'terminarentrega'])->name('actacontratobraserv.terminarentrega');
                Route::get('aceptarentrega/{actacontratobraserv}', [ActaContratoObraServController::class, 'aceptarentrega'])->name('actacontratobraserv.aceptarentrega');
                Route::get('anularentrega/{actacontratobraserv}', [ActaContratoObraServController::class, 'anularentrega'])->name('actacontratobraserv.anularentrega');
                Route::get('reimprimirentrega/{actacontratobraserv}', [ActaContratoObraServController::class, 'reimprimirentrega'])->name('actacontratobraserv.reimprimirentrega');
                Route::get('reimprimirservicio/{actacontratobraserv}/{tipo}', [ActaContratoObraServController::class, 'reimprimirservicio'])->name('actacontratobraserv.reimprimirservicio');
                Route::get('actacontratobraserv/{actacontratobraserv}/{accion}/show', [ActaContratoObraServController::class, 'show'])->name('actacontratobraserv.show');
                Route::resource('certificacioncontratoaddendum', CertificacionContratoController::class)->except('destroy', 'show','create');

            });
            Route::name('reporte.')
			->group(function () {
	        });
    });
