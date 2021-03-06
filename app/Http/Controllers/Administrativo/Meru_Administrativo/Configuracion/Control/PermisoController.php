<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control;


use App\Models\Administrativo\Meru_Administrativo\Configuracion\Permiso;
use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Control\PermisoRequest;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Modulo;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use App\Http\Controllers\Controller;


class PermisoController extends Controller
{    use ReportFpdf;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('administrativo.meru_administrativo.configuracion.control.permiso.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $permiso= new permiso();
        $modulo= Modulo::query()->whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        return view('administrativo.meru_administrativo.configuracion.control.permiso.create', compact('permiso','modulo'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermisoRequest $request)
    {
        //
        try {
            permiso::create($request->validated());
            flash()->addSuccess('Permiso Creado Exitosamente.');
            return redirect()->route('configuracion.control.permiso.index');

        } catch(\Illuminate\Database\QueryException $e){
            flash()->addError('Transacci&oacute;n Fallida: '.Str::limit($e, 200));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Configuracion\control\Permiso  $permiso
     * @return \Illuminate\Http\Response
     */
    public function show(Permiso $permiso)
    {
        $modulo= Modulo::query()->whereNull('deleted_at')->get();
        return view('administrativo.meru_administrativo.configuracion.control.permiso.show', compact('permiso','modulo'));
   }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Configuracion\control\Permiso  $permiso
     * @return \Illuminate\Http\Response
     */
    public function edit(Permiso $permiso)
    {
        $modulo= Modulo::query()->whereNull('deleted_at')->get();
        return view('administrativo.meru_administrativo.configuracion.control.permiso.edit', compact('permiso','modulo'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Administrativo\Meru_Administrativo\Configuracion\control\Permiso  $permiso
     * @return \Illuminate\Http\Response
     */
    public function update(PermisoRequest $request, Permiso $permiso)
    {
        //
        try {
            $status =$permiso->status;
            if ($status == '0' && $request->status=='0'){
                flash()->addInfo('Registro Inactivo NO puede ser Modificado. Favor verifique.');
                return redirect()->back()->withInput();
            }
            $permiso->update($request->validated());
            app()['cache']->forget('spatie.permission.cache');
            flash()->addSuccess('Registro Modificado Exitosamente');
            return redirect()->route('configuracion.control.permiso.index');

        } catch(\Illuminate\Database\QueryException $e){
            flash()->addError('Transacci&oacute;n Fallida'.Str::limit($e, 200));
            return redirect()->back()->withInput();
        }
    }
    public function print_permiso()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE PERMISOS';
        $data['alineacion_columnas']		= array('C','L','L','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','60','60','40');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('C??digo'),utf8_decode('Nombe'),utf8_decode('Nombre de la Ruta '),utf8_decode('Guard Name'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('id', 'name','route_name','guard_name');
        $data['nombre_documento']			= 'listado_Usuario.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Permiso::query()->where('status', '1')->orderby('name')->get();

        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('Listado de Usuarios'));

        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }

}
