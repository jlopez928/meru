<?php
namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Control;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Rol;
use App\Models\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserRolController extends Controller
{   use ReportFpdf;

    public function index()
    {
         return view('administrativo.meru_administrativo.configuracion.control.userrol.index');
    }
    public function show(User $userrol)
    {  $rol = Rol::query()->get();
        return view('administrativo.meru_administrativo.configuracion.control.userrol.show', compact('userrol','rol'));
    }

    public function edit(User $userrol)
    {
        $rol = Rol::query()->get();
        return view('administrativo.meru_administrativo.configuracion.control.userrol.edit', compact('userrol','rol'));
        //
    }

    public function update(Request $request,User $userrol)
    {
       $userrol->syncRoles($request->rolesItem_id);
        alert()->success('¡Éxito!','Roles asociados al Usuario Exitosamente.');
        app()['cache']->forget('spatie.permission.cache');
        return redirect()->route('configuracion.control.userrol.index');
    }
    public function print_userrol()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE USUARIOS';
        $data['alineacion_columnas']		= array('C','L','L','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','20','80','80');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Código'),utf8_decode('Cédula'),utf8_decode('Nombre'),utf8_decode('Email'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('id', 'cedula','name','email');
        $data['nombre_documento']			= 'listado_Usuario.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = User::query()->where('status', '1')->orderby('name')->get();

        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('Listado de Usuarios'));

        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }


}
