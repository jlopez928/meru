<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control;

use App\Models\Administrativo\Meru_Administrativo\Configuracion\Rol;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Permiso;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Modulo;
use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Control\RolRequest;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class RolController extends Controller
{   use ReportFpdf;

    public function index()
    {
        return view('administrativo.meru_administrativo.configuracion.control.rol.index');
    }
    public function create()
    {
        $rol= new rol();
        $modulos = Modulo::where('status', '>=', '1')->Orderby('nombre')->get();
        $permisos = Permiso::where('status', '>=', '1')->Orderby('name')->get();

        return view('administrativo.meru_administrativo.configuracion.control.rol.create',
        compact('rol','permisos','modulos'));
    }
    public function store(RolRequest $request)
    {
        try {


            Rol::create($request->validated());
            alert()->success('¡Éxito!','Registro Guardado Exitosamente.');
            return redirect()->route('configuracion.control.rol.index');

        } catch(\Illuminate\Database\QueryException $e){
            alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
          return redirect()->back()->withInput();
        }
    }
    public function show(rol $rol)
    {
        //
        return view('administrativo.meru_administrativo.configuracion.control.rol.show', compact('rol'));
    }
    public function edit(Rol $rol)
    {
        $modulo= Modulo::query()->whereNull('deleted_at')->get();
        return view('administrativo.meru_administrativo.configuracion.control.rol.edit', compact('rol','modulo'));
    }
    public function update(RolRequest $request, rol $rol)
    {
        try {
            $status =$rol->status;
            if ($status == '0' && $request->estado=='0'){
                alert()->info('¡Éxito!','Registro Inactivo NO puede ser Modificado. Favor verifique.');
                return redirect()->back()->withInput();
            }
            $rol->update($request->validated());
            app()['cache']->forget('spatie.permission.cache');
            alert()->success('¡Éxito!','Registro Modificado Exitosamente.');
            return redirect()->route('configuracion.control.rol.index');

        } catch(\Illuminate\Database\QueryException $e){
            alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
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
        $data['registros']                  = Rol::query()
                                                ->select(
                                                    DB::raw("id"),
                                                    DB::raw("name"),
                                                    DB::raw("(CASE WHEN status = '0' THEN 'Inactivo' ELSE 'Activo' END) as status"))
                                                    ->orderby('name','desc')->get();
        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Roles'));
        $this->pintar_listado_pdf($pdf,$data);
        exit;
    }
}
