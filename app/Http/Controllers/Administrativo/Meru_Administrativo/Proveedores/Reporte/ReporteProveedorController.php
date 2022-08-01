<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte;

use App\Traits\ReportFpdf;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;

class ReporteProveedorController extends Controller
{
    use ReportFpdf;

    public function proveedoressuspendidos()
    {
        $data['tipo_hoja']                  = 'O'; // O Oficio
        $data['orientacion']                = 'H'; // H Horizontal
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = 'GERENCIA DE LOGISTICA';
        $data['division']                   = 'COORDINACION DE PROVEEDORES';
        $data['titulo']                     = 'PROVEEDORES DE BIENES Y SERVICIOS SUSPENDIDOS';
        $data['subtitulo']                  = '';
        $data['alineacion_columnas']		= array('C','L','C','L'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('30','80','30','180');//Ancho de Columnas
        $data['nombre_columnas']		   	= array('RIF','NOMBRE DE EMPRESA','TELEFONOS','DIRECCION');
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('rif_prov','nom_prov','tlf_prov1','dir_prov');
        $data['nombre_documento']			= 'ProveedoresSuspendidos.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = '';
        $data['cod_reporte']			    = 'MERU';
        $data['registros']                  = Proveedor::query()->where('sta_con', 2)->orderby('nom_prov')->get();
     
        $pdf = new Fpdf;

        $pdf->setTitle('Proveedores Suspendidos');

        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }

}