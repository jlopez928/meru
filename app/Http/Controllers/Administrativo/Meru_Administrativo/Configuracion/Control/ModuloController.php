<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control;

use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Control\ModuloRequest;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Modulo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use App\Http\Controllers\Controller;
class ModuloController extends Controller
{  use ReportFpdf;

    public function index()
    {
        return view('administrativo.meru_administrativo.configuracion.control.modulo.index');

    }
    public function create()
    {


        $modulo= new Modulo();
        return view('administrativo.meru_administrativo.configuracion.control.modulo.create', compact('modulo'));
    }

    public function store(ModuloRequest $request)
    {
         //
         try {

            Modulo::create($request->validated() + ['codigo' =>  $request->nombre]);
            alert()->success('¡Éxito!','Modulo Creado Exitosamente.');
            return redirect()->route('configuracion.control.modulo.index');

        } catch(\Illuminate\Database\QueryException $e){
            alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
            return redirect()->back()->withInput();
        }
    }

    public function show(Modulo $modulo)
    {

        return view('administrativo.meru_administrativo.configuracion.control.modulo.show', compact('modulo'));
   }

    public function edit(Modulo $modulo)
    {

        return view('administrativo.meru_administrativo.configuracion.control.modulo.edit', compact('modulo'));


    }

    public function update(ModuloRequest $request, Modulo $modulo)
    {
        try {

            if ($modulo->status == '0' && $request->status=='0'){
                alert()->info('Registro Inactivo NO puede ser Modificado. Favor verifique.');
                return redirect()->back()->withInput();
            }
            $modulo->update($request->validated());
            alert()->success('¡Éxito!','Registro Modificado Exitosamente');
            return redirect()->route('configuracion.control.modulo.index');

        } catch(\Illuminate\Database\QueryException $e){
            alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
            return redirect()->back()->withInput();
        }
    }

    public function print_modulo()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE MODULOS';
        $data['alineacion_columnas']		= array('C','L','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','80','80');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Código'),utf8_decode('Nombe'),utf8_decode('Estado'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('id', 'nombre','sta_reg');
        $data['nombre_documento']			= 'listado_modulo.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Modulo::query()
                                                ->select(
                                                    DB::raw("id"),
                                                    DB::raw("nombre"),
                                                    DB::raw("(CASE WHEN status = '0' THEN 'Inactivo' ELSE 'Activo' END) as sta_reg"))
                                                    ->orderby('nombre','desc')->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Modulos'));
        $this->pintar_listado_pdf($pdf,$data);
        exit;
    }

}
