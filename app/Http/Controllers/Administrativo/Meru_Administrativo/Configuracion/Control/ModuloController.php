<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control;

use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Control\ModuloRequest;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Modulo;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ModuloController extends Controller
{  use ReportFpdf;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.configuracion.control.modulo.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


        $modulo= new Modulo();
        return view('administrativo.meru_administrativo.configuracion.control.modulo.create', compact('modulo'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuloRequest $request)
    {
         //
         try {
            modulo::create($request->validated());
            flash()->addSuccess('Modulo Creado Exitosamente.');
            return redirect()->route('configuracion.control.modulo.index');

        } catch(\Illuminate\Database\QueryException $e){
            flash()->addError('Transacci&oacute;n Fallida: '.Str::limit($e, 200));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Configuracion\Configuracion\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function show(Modulo $modulo)
    {

        return view('administrativo.meru_administrativo.configuracion.control.modulo.show', compact('modulo'));
   }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Configuracion\Configuracion\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Modulo $modulo)
    {

        return view('administrativo.meru_administrativo.configuracion.control.modulo.edit', compact('modulo'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Administrativo\Meru_Administrativo\Configuracion\Configuracion\Modulo  $modulo
     * @return \Illuminate\Http\Response
     */
    public function update(ModuloRequest $request, Modulo $modulo)
    { //
        try {

            if ($modulo->status == '0' && $request->status=='0'){
                flash()->addInfo('Registro Inactivo NO puede ser Modificado. Favor verifique.');
                return redirect()->back()->withInput();
            }
            $modulo->update($request->validated());
            flash()->addSuccess('Registro Modificado Exitosamente');
            return redirect()->route('configuracion.control.modulo.index');

        } catch(\Illuminate\Database\QueryException $e){
            flash()->addError('Transacci&oacute;n Fallida'.Str::limit($e, 200));
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
        $data['registros_mostar']           = array('id', 'name','estado');
        $data['nombre_documento']			= 'listado_modulo.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Modulo::query()->where('status', '1')->orderby('name')->get();

        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('Listado de Modulos'));

        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }

}
