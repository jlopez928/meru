<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control;


use App\Models\Administrativo\Meru_Administrativo\Configuracion\Permiso;
use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Control\PermisoRequest;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Modulo;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Traits\ReportFpdf;
use App\Http\Controllers\Controller;


class PermisoController extends Controller
{    use ReportFpdf;

    public function index()
    {
        //
        return view('administrativo.meru_administrativo.configuracion.control.permiso.index');
    }
    public function create()
    {
        //

        $permiso= new permiso();
        $modulo= Modulo::query()->whereNull('deleted_at')->orderBy('nombre','ASC')->get();
        return view('administrativo.meru_administrativo.configuracion.control.permiso.create', compact('permiso','modulo'));

    }
    public function store(PermisoRequest $request)
    {
        //
        try {
            permiso::create($request->validated());
            alert()->success('¡Éxito!','Permiso Creado Exitosamente.');
            return redirect()->route('configuracion.control.permiso.index');

        } catch(\Illuminate\Database\QueryException $e){
            alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
            return redirect()->back()->withInput();
        }
    }

    public function show(Permiso $permiso)
    {
        $modulo= Modulo::query()->whereNull('deleted_at')->get();
        return view('administrativo.meru_administrativo.configuracion.control.permiso.show', compact('permiso','modulo'));
   }
    public function edit(Permiso $permiso)
    {
        $modulo= Modulo::query()->whereNull('deleted_at')->get();
        return view('administrativo.meru_administrativo.configuracion.control.permiso.edit', compact('permiso','modulo'));

    }

    public function update(PermisoRequest $request, Permiso $permiso)
    {
        //
        try {
            $status =$permiso->status;
            if ($status == '0' && $request->status=='0'){
                alert()->info('Registro Inactivo NO puede ser Modificado. Favor verifique.');
                return redirect()->back()->withInput();
            }
            $permiso->update($request->validated());
            app()['cache']->forget('spatie.permission.cache');
            alert()->success('¡Éxito!','Registro Modificado Exitosamente');
            return redirect()->route('configuracion.control.permiso.index');

        } catch(\Illuminate\Database\QueryException $e){
            alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
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
        $data['nombre_columnas']		   	= array(utf8_decode('Código'),utf8_decode('Nombe'),utf8_decode('Nombre de la Ruta '),utf8_decode('Guard Name'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('id', 'name','route_name','guard_name');
        $data['nombre_documento']			= 'listado_Usuario.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = Permiso::query()
                                                ->select(
                                                    DB::raw("id"),
                                                    DB::raw("route_name"),
                                                    DB::raw("guard_name"),
                                                    DB::raw("name"),
                                                    DB::raw("(CASE WHEN status = '0' THEN 'Inactivo' ELSE 'Activo' END) as status"))
                                                    ->orderby('name','desc')->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Usuarios'));
        $this->pintar_listado_pdf($pdf,$data);
        exit;
    }

}
