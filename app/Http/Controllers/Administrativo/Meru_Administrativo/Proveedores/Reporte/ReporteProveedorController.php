<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte;

use App\Support\Fpdf;
use App\Traits\ReportFpdf;
use Illuminate\Support\Facades\DB;
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

    public function proveedores()
    {
        $data['tipo_hoja']                  = 'O'; // O Oficio
        $data['orientacion']                = 'H'; // H Horizontal
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = 'Departamento de Contrataciones';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE PROVEEDORES';
        $data['alineacion_columnas']		= array('C','C','C','C','L','C','L','L','C','L','C','L','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('26','26','25','20','40','20','40','25','20','20','18','30','18');//Ancho de Columnas
        $data['nombre_columnas']		   	= array('Tipo','R.I.F',utf8_decode('Código'),'Registro','Nombre','Siglas',utf8_decode('Dirección'),utf8_decode('Teléfono'),'Empresa','Nro. RNC',utf8_decode('Cédula'),'Responsable','Status');
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 9;
        $data['registros_mostar']           = array("tipo_empresa","rif_prov","cod_prov","tipo_registro","nom_prov","sig_prov","dir_prov","tlf_prov1","estado_empresa","nro_rnc","ced_res","nom_res","estado");
        $data['nombre_documento']			= 'Proveedores.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = '';
        $data['cod_reporte']			    = 'repProveedores.Class.php';
        $data['registros']                  = Proveedor::select(DB::raw("   CASE
                                                                                WHEN tip_emp = 'C' THEN 'C.A.'
                                                                                WHEN tip_emp = 'S' THEN 'S.A.'
                                                                                WHEN tip_emp = 'R' THEN 'S.R.L.'
                                                                                WHEN tip_emp = 'L' THEN 'C.R.L.'
                                                                                WHEN tip_emp = 'F' THEN 'FIRMA PERSONAL'
                                                                                WHEN tip_emp = 'P' THEN 'COOPERATIVA'
                                                                                WHEN tip_emp = 'o' THEN 'OTRA'
                                                                            END AS tipo_empresa"),
                                                                            DB::raw("   CASE
                                                                                WHEN sta_con = '0' THEN 'Activo'
                                                                                WHEN sta_con = '2' THEN 'Suspendido'
                                                                            END AS estado"),
                                                                            DB::raw("   CASE
                                                                                when tip_reg = 'P' then 'Proveedor'
                                                                                when tip_reg = 'C' then 'Contratista'
                                                                                when tip_reg = 'A' then 'Ambos'
                                                                                ELSE '-'
                                                                            END AS tipo_registro"),
                                                                            DB::raw("   CASE
                                                                                when sta_emp='E' then 'EXCELENTE'
                                                                                when sta_emp='B' then 'BUENA'
                                                                                when sta_emp='R' then 'REGULAR'
                                                                                when sta_emp='M' then 'MALA'
                                                                                when sta_emp='N' then 'NUEVA'
                                                                                ELSE '-'
                                                                            END AS estado_empresa"),
                                                                        'rif_prov','cod_prov','nom_prov','sig_prov','dir_prov','tlf_prov1','nro_rnc','ced_res','nom_res'
                                                                        )->get();

        $pdf = new Fpdf;

        $pdf->setTitle('Proveedores');

        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }

}
