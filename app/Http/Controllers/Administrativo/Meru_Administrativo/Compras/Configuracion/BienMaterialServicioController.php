<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion;

use App\Traits\ReportFpdf;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\Producto;

class BienMaterialServicioController extends Controller
{
    use ReportFpdf;

    public function index()
    {
        return view('administrativo.meru_administrativo.compras.configuracion.bien-material-servicio.index');
    }
    
    public function create()
    {
        $producto = new Producto;
        
        return view('administrativo.meru_administrativo.compras.configuracion.bien-material-servicio.create', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return view('administrativo.meru_administrativo.compras.configuracion.bien-material-servicio.edit', compact('producto'));
    }

    public function show(Producto $producto)
    {
        return view('administrativo.meru_administrativo.compras.configuracion.bien-material-servicio.show', compact('producto'));
    }
   
    public function asignar(Producto $producto)
    {
        return view('administrativo.meru_administrativo.compras.configuracion.bien-material-servicio.asignar', compact('producto'));
    }

    public function print_productos()
    {
        $data['tipo_hoja']                  = 'O'; // O Oficio
        $data['orientacion']                = 'H'; // H Horizontal
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'BIENES / MATERIALES / SERVICIOS';
        $data['alineacion_columnas']        = array('C', 'L', 'L'); //C centrado R derecha L izquierda
        $data['ancho_columnas']             = array('25', '150', '30'); //Ancho de Columnas
        $data['nombre_columnas']            = array(utf8_decode('Código'), utf8_decode('Descripción'), utf8_decode('Último Precio'));
        $data['funciones_columnas']         = '';
        $data['fuente']                     = 8;
        $data['registros_mostar']           = array('cod_prod', 'des_prod', 'ult_pre');
        $data['nombre_documento']           = 'bienes-materiales-servicios.pdf'; //Nombre de Archivo
        $data['con_imagen']                 = true;
        $data['vigencia']                   = '';
        $data['revision']                   = '';
        $data['usuario']                    = auth()->user()->name;
        $data['cod_reporte']                = '';
        $data['registros']                  = Producto::query()->activo()->orderby('des_prod')->get(['cod_prod', 'des_prod', 'ult_pre']);

        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('Bienes / Materiales / Servicios'));

        $this->pintar_listado_pdf($pdf, $data);
    }
}