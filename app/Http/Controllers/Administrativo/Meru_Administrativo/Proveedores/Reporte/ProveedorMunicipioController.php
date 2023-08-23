<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Proveedores\Reporte;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Http\Controllers\Controller;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use Illuminate\Http\Request;
class ProveedorMunicipioController extends Controller
{   use ReportFpdf;
    //
    public function index()
    {
        return view('administrativo.meru_administrativo.proveedores.reporte.proveedormunicipio.index');

    }

    public function print_proveedormunicipio(Request $request)
    {
        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'H'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'PROVEEDORES DE BIENES Y SERVICIOS';
        $data['subtitulo']                  = '';
        $data['alineacion_columnas']		= array('C','L','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','90','40','100');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Rif'),utf8_decode('Nombe'),utf8_decode('Telefono'),utf8_decode('Direccion'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('rif_prov', 'nom_prov','tlf_prov1','dir_prov');
        $data['nombre_documento']			= 'listado_modulo.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Proveedor::query()
                                                    ->where('cod_edo', 'like', '%'.$request->estado.'%')
                                                    ->where(function($query) use($request){
                                                        $query->orWhere('cod_mun', 'like', '%'.$request->municipio.'%')
                                                        ->orwhere('tip_emp', 'like', '%'.$request->tip_emp.'%')
                                                        ->orwhere('objetivo', 'like', '%'.$request->objetivo.'%');
                                                    })
                                                    ->orderby('nom_prov')->get();
        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('Listado de Modulos'));

        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }
}
