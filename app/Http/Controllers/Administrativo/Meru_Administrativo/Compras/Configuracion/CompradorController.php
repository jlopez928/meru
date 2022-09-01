<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion;

use App\Traits\ReportFpdf;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\Comprador;

class CompradorController extends Controller
{
    use ReportFpdf;

    public function index()
    {
        return view('administrativo.meru_administrativo.compras.configuracion.comprador.index');
    }

    public function show(Comprador $comprador)
    {
        return view('administrativo.meru_administrativo.compras.configuracion.comprador.show', compact('comprador'));
    }

    public function create()
    {
        return view('administrativo.meru_administrativo.compras.configuracion.comprador.create');
    }

    public function print_compradores()
    {

        $data['tipo_hoja']                  = 'O'; // O Oficio
        $data['orientacion']                = 'H'; // H Horizontal
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'COMPRADORES';
        $data['alineacion_columnas']		= array('C','L','L','L','L','L'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('40','40','100','30','90','20');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Código'),utf8_decode('Usuario'),utf8_decode('Nombre'),utf8_decode('Cédula'),utf8_decode('Correo'),utf8_decode('Estado'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('codigo','usuario','nombre','cedula','correo','estado');
        $data['nombre_documento']			= 'Compradores.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Comprador::query()
                                                        ->join('public.usuarios', 'usuarios.usuario', 'com_compradores.usu_com')
                                                        ->orderby('codigo')
                                                        ->get([
                                                                    'com_compradores.cod_com as codigo',
                                                                    'com_compradores.usu_com as usuario',
                                                                    DB::raw("(CASE WHEN (com_compradores.sta_reg = '1') THEN 'Activo' ELSE 'Inactivo' END) as estado"),
                                                                    'usuarios.nombre as nombre',
                                                                    'usuarios.cedula as cedula',
                                                                    'usuarios.correo as correo'
                                                                ]);

        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('Compradores'));

        $this->pintar_listado_pdf($pdf,$data);
    }
}
