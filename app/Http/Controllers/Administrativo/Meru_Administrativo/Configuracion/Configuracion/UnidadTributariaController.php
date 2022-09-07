<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion;
use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion\UnidadTributariaRequest;
use App\Models\Administrativo\Meru_administrativo\Configuracion\UnidadTributaria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;
class UnidadTributariaController extends Controller
{   use ReportFpdf;

    public function index()
    {    return view('administrativo.meru_administrativo.configuracion.configuracion.unidadtributaria.index');
    }

    public function create()
    {
        $unidadtributarium= new UnidadTributaria();
        return view('administrativo.meru_administrativo.configuracion.configuracion.unidadtributaria.create', compact('unidadtributarium'));

    }

    public function store(UnidadTributariaRequest $request)
    {

        try {
            UnidadTributaria::where('vigente', '1')->update(array('vigente' => '0'));
            UnidadTributaria::create($request->validated());
              alert()->success('¡Éxito!','Unidad Tributaria Creada Exitosamente.');
            return redirect()->route('configuracion.configuracion.unidadtributaria.index');

        } catch(\Illuminate\Database\QueryException $e){
            alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
            return redirect()->back()->withInput();
        }
    }

    public function show(UnidadTributaria $unidadtributarium)
    {
        return view('administrativo.meru_administrativo.configuracion.configuracion.unidadtributaria.show', compact('unidadtributarium'));
    }

    public function edit(UnidadTributaria $unidadtributarium)
    {
        return view('administrativo.meru_administrativo.configuracion.configuracion.unidadtributaria.edit', compact('unidadtributarium'));
    }

    public function update(UnidadTributariaRequest $request, UnidadTributaria $unidadtributarium)
    {
        try {
            if ($unidadtributarium->vigente == '0' && $request->unidadtributarium->vigente=='0'){
                alert()->info('Registro Inactivo NO puede ser Modificado. Favor verifique.');
                return redirect()->back()->withInput();
            }
            $unidadtributarium->update($request->validated());
              alert()->success('¡Éxito!','Registro Modificado Exitosamente');
            return redirect()->route('configuracion.configuracion.unidadtributaria.index');

        } catch(\Illuminate\Database\QueryException $e){
            alert()->error('Transacci&oacute;n Fallida: ',Str::limit($e->getMessage(), 120));
            return redirect()->back()->withInput();
        }
    }
    public function print_unidadtributaria()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE UNIDAD TRIBUTARIA';
        $data['alineacion_columnas']		= array('C','C','C','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','60','60','60');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Fecha Vigencia'),utf8_decode('Monto UT'),utf8_decode('Monto UCAU'),utf8_decode('Estado'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array( 'fec_ut','bs_ut','bs_ucau','vigente');
        $data['nombre_documento']			= 'listado_modulo.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = UnidadTributaria::query()
                                                                ->select(
                                                                        DB::raw("fec_ut"),
                                                                        DB::raw("coalesce(bs_ut, 0) as bs_ut"),
                                                                        DB::raw("coalesce(bs_ucau, 0) as bs_ucau"),
                                                                        DB::raw("(CASE WHEN vigente = '0' THEN 'Inactivo' ELSE 'Activo' END) as vigente"))
                                                                        ->orderby('fec_ut','desc')->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Unidades Tributarias'));
        $this->pintar_listado_pdf($pdf,$data);
        exit;
    }

}
