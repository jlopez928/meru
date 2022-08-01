<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control;

use App\Models\Administrativo\Meru_Administrativo\Configuracion\Rol;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Permiso;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Modulo;
use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Control\RolRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use App\Http\Controllers\Controller;
class RolController extends Controller
{   use ReportFpdf;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.configuracion.control.rol.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rol= new rol();
        $modulos = Modulo::where('status', '>=', '1')->Orderby('nombre')->get();
        $permisos = Permiso::where('status', '>=', '1')->Orderby('name')->get();

        return view('administrativo.meru_administrativo.configuracion.control.rol.create',
        compact('rol','permisos','modulos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolRequest $request)
    {
        try {


            Rol::create($request->validated());
            flash()->addSuccess('Registro Guardado Exitosamente.');
            return redirect()->route('configuracion.control.rol.index');

        } catch(\Illuminate\Database\QueryException $e){
            flash()->addError('Transacci&oacute;n Fallida: '.Str::limit($e, 200));
          return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Administrativo\Meru_Administraivo\Configuracion\control\rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(rol $rol)
    {
        //
        return view('administrativo.meru_administrativo.configuracion.control.rol.show', compact('rol'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Administrativo\Meru_Administraivo\Configuracion\control\rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function edit(Rol $rol)
    {
        $modulo= Modulo::query()->whereNull('deleted_at')->get();
        return view('administrativo.meru_administrativo.configuracion.control.rol.edit', compact('rol','modulo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Administrativo\Meru_Administraivo\Configuracion\control\rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(RolRequest $request, rol $rol)
    {
        try {
            $status =$rol->status;
            if ($status == '0' && $request->estado=='0'){
                flash()->addSuccess('Registro Inactivo NO puede ser Modificado. Favor verifique.');
                return redirect()->back()->withInput();
            }
            $rol->update($request->validated());
            app()['cache']->forget('spatie.permission.cache');
            flash()->addSuccess('Registro Modificado Exitosamente.');
            return redirect()->route('configuracion.control.rol.index');

        } catch(\Illuminate\Database\QueryException $e){
            flash()->addError('Transacci&oacute;n Fallida: '.Str::limit($e, 200));
            return redirect()->back()->withInput();
        }

    }


    public function asignarpermiso(Rol $rol)
    {
        $modulo= Modulo::query()->whereNull('deleted_at')->get();
        return view('administrativo.meru_administrativo.configuracion.control.rol.asignarpermiso', compact('rol','modulo'));
    }

    public function print_rol()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE ROLES';
        $data['alineacion_columnas']		= array('C','L','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','120','40');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Código'),utf8_decode('Descripción'),utf8_decode('status'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('id', 'name', 'status');
        $data['nombre_documento']			= 'listado_roles.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Rol::query()->where('status', '1')->orderby('name')->get();

        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('Listado de Roles'));

        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }
}
