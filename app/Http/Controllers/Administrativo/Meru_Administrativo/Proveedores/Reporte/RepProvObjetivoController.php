<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;

class RepProvObjetivoController extends Controller
{
    use ReportFpdf;

    public function index()
    {
        return view('administrativo.meru_administrativo.proveedores.reporte.repprovobjetivo.index');

    }

    public function print_repprovobjetivo(Request $request )
    {
        $proveedor=Proveedor::where('id',$request->id)->first();

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'H'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = 'GERENCIA DE LOGISTICA';
        $data['division']                   = 'COORDINACION DE PROVEEDORES';
        $data['titulo']                     = 'PROVEEDORES DE BIENES Y SERVICIOS';
        $data['subtitulo']                  = '';
        $data['alineacion_columnas']		= array('C','L','C','L'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('30','70','30','120');//Ancho de Columnas
        $data['nombre_columnas']		   	= array('RIF','NOMBRE DE EMPRESA','TELEFONOS','DIRECCION');
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array("rif_prov","nom_prov","tlf_prov1","dir_prov");
        $data['nombre_documento']			= 'listado_tasacambio.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Proveedor::query()->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Modulos'));
        $this->pintar_listado_pdf($pdf,$data);

        exit;

    }
    //
}
