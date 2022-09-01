<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion;

use App\Traits\ReportFpdf;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\GrupoProducto;
use App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Configuracion\GrupoProductoRequest;

class GrupoProductoController extends Controller
{
    use ReportFpdf;

    public function index()
    {
        return view('administrativo.meru_administrativo.compras.configuracion.grupo-producto.index');
    }

    public function create()
    {
        $grupoproducto = new GrupoProducto();

        return view('administrativo.meru_administrativo.compras.configuracion.grupo-producto.create', compact('grupoproducto'));
    }

    public function show(GrupoProducto $grupoproducto)
    {
        return view('administrativo.meru_administrativo.compras.configuracion.grupo-producto.show', compact('grupoproducto'));
    }

    public function store(GrupoProductoRequest $request)
    {
        try {

            DB::connection('pgsql')->transaction(function() use($request){

                GrupoProducto::create($request->validated() + [
                    'usuario'   => auth()->id(),
                ]);

            });

            alert()->success('Éxito','Registro Guardado Exitosamente');

            return to_route('compras.configuracion.grupo_producto.index');
        } catch (\Exception $ex) {

            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function edit(GrupoProducto $grupoproducto)
    {
        return view('administrativo.meru_administrativo.compras.configuracion.grupo-producto.edit', compact('grupoproducto'));
    }

    public function update(GrupoProductoRequest $request, GrupoProducto $grupoproducto)
    {
        try {
            $grupoproducto->update($request->validated());

            alert()->success('Éxito','Registro Modificado Exitosamente');

            return to_route('compras.configuracion.grupo_producto.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function destroy(GrupoProducto $grupoproducto)
    {

        try {
            $grupoproducto->delete();

            alert()->success('Éxito','Registro Eliminado Exitosamente');

            return to_route('compras.configuracion.grupo_producto.index');
        } catch (\Exception $ex) {

            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back();
        }
    }

    public function print_grupo_productos()
    {
        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'GRUPOS DE PRODUCTOS';
        $data['alineacion_columnas']		= array('C','L'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','170');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Grupo'),utf8_decode('Descripción'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('grupo', 'des_grupo');
        $data['nombre_documento']			= 'GrupoProductos.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = GrupoProducto::query()->activo()->orderby('des_grupo')->get();

        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('Grupos de Productos'));

        $this->pintar_listado_pdf($pdf,$data);
    }
}