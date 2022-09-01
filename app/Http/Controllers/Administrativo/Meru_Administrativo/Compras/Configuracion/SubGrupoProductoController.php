<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion;

use App\Traits\ReportFpdf;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\GrupoProducto;
use App\Models\Administrativo\Meru_Administrativo\Compras\SubGrupoProducto;
use App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Configuracion\SubGrupoProductoRequest;

class SubGrupoProductoController extends Controller
{
    use ReportFpdf;

    public function index()
    {
        return view('administrativo.meru_administrativo.compras.configuracion.subgrupo-producto.index');
    }

    public function create()
    {
        $subgrupoproducto = new SubGrupoProducto();

        $grupos = GrupoProducto::query()->activo()->orderby('grupo')->get();

        return view('administrativo.meru_administrativo.compras.configuracion.subgrupo-producto.create', compact('subgrupoproducto', 'grupos'));
    }

    public function show(SubGrupoProducto $subgrupoproducto)
    {
        $subgrupo = $subgrupoproducto->load('grupoproducto');

        return view('administrativo.meru_administrativo.compras.configuracion.subgrupo-producto.show', compact('subgrupo'));
    }

    public function store(SubGrupoProductoRequest $request)
    {
        try {

            DB::connection('pgsql')->transaction(function () use ($request) {

                SubGrupoProducto::create($request->validated() + [
                    'usuario'   => auth()->id(),
                ]);
            });

            alert()->success('Éxito','Registro Guardado Exitosamente');

            return to_route('compras.configuracion.subgrupo_producto.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function edit(SubGrupoProducto $subgrupoproducto)
    {
        $grupos = GrupoProducto::query()->activo()->orderby('grupo')->get();

        return view('administrativo.meru_administrativo.compras.configuracion.subgrupo-producto.edit', compact('subgrupoproducto', 'grupos'));
    }

    public function update(SubGrupoProductoRequest $request, SubGrupoProducto $subgrupoproducto)
    {
        try {
            $subgrupoproducto->update($request->validated());

            alert()->success('Éxito','Registro Modificado Exitosamente');

            return to_route('compras.configuracion.subgrupo_producto.index');
        } catch (\Exception $ex) {
            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back()->withInput();
        }
    }

    public function destroy(SubGrupoProducto $subgrupoproducto)
    {

        try {
            $subgrupoproducto->delete();

            alert()->success('Éxito','Registro Eliminado Exitosamente');

            return to_route('compras.configuracion.subgrupo_producto.index');
        } catch (\Exception $ex) {

            alert()->error('Error', str($ex)->limit(250));

            return redirect()->back();
        }
    }

    public function print_subgrupo_productos()
    {
        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'SUBGRUPOS DE PRODUCTOS';
        $data['alineacion_columnas']        = array('C', 'C', 'L'); //C centrado R derecha L izquierda
        $data['ancho_columnas']             = array('20', '20', '150'); //Ancho de Columnas
        $data['nombre_columnas']            = array(utf8_decode('Grupo'), utf8_decode('SubGrupo'), utf8_decode('Descripción'));
        $data['funciones_columnas']         = '';
        $data['fuente']                     = 8;
        $data['registros_mostar']           = array('grupo', 'subgrupo', 'des_subgrupo');
        $data['nombre_documento']           = 'SubGrupoProductos.pdf'; //Nombre de Archivo
        $data['con_imagen']                 = true;
        $data['vigencia']                   = '';
        $data['revision']                   = '';
        $data['usuario']                    = auth()->user()->name;
        $data['cod_reporte']                = '';
        $data['registros']                  = SubGrupoProducto::query()->activo()->orderby('des_subgrupo')->get();

        $pdf = new Fpdf;

        $pdf->setTitle(utf8_decode('SubGrupos de Productos'));

        $this->pintar_listado_pdf($pdf, $data);
    }
}