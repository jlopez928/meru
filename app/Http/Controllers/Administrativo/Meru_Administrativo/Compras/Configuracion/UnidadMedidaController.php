<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion;
use App\Models\Administrativo\Meru_Administrativo\Compras\UnidadMedida;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Configuracion\UnidadMedidaRequest;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;

class UnidadMedidaController extends Controller
{  use ReportFpdf;

    public function index()
    {  return view('administrativo.meru_administrativo.compras.configuracion.unidadmedida.index');
    }
    public function create()
    {
        $unidadmedida= new UnidadMedida();
        return view('administrativo.meru_administrativo.compras.configuracion.unidadmedida.create', compact('unidadmedida'));
    }

    public function store(UnidadMedidaRequest $request)
    {
        try {
                    DB::connection('pgsql')->beginTransaction();
                    UnidadMedida::create( $request->only(['cod_uni', 'des_uni','sta_reg','usuario','fecha'] ));
                    DB::connection('pgsql')->commit();
                    alert()->success('¡Éxito!', 'Registro Ingresado Exitosamente');
                    return redirect()->route('compras.configuracion.unidadmedida.index');

         } catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
         }
    }

    public function show(unidadmedida $unidadmedida)
    {        return view('administrativo.meru_administrativo.compras.configuracion.unidadmedida.show',compact('unidadmedida'));
    }
    public function edit(unidadmedida $unidadmedida)
    {   return view('administrativo.meru_administrativo.compras.configuracion.unidadmedida.edit', compact('unidadmedida'));
    }
    public function update(UnidadMedidaRequest $request, unidadmedida $unidadmedida)
    {   try {
            DB::connection('pgsql')->beginTransaction();
            if ($unidadmedida->sta_reg == '0' && $request->sta_reg=='0'){
                alert()->error('Registro Inactivo NO puede ser Modificado. Favor verifique');
                return redirect()->back()->withInput();
            }
            $unidadmedida->update($request->validated());
            DB::connection('pgsql')->commit();
            alert()->success('¡Éxito!', 'Registro Modificado Exitosamente');
            return redirect()->route('compras.configuracion.unidadmedida.index');

        } catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function print_unidad_medida()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE UNIDAD DE MEDIDA';
        $data['alineacion_columnas']		= array('C','C','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('40','80','80');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Codigo'),utf8_decode('Descripción'),utf8_decode('Estado'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('cod_uni', utf8_decode('des_uni'),'sta_reg');
        $data['nombre_documento']			= 'listado_unidadmedida.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = unidadmedida::query()
                                                        ->select(
                                                            DB::raw("cod_uni"),
                                                            DB::raw("des_uni"),
                                                            DB::raw("(CASE WHEN sta_reg = '0' THEN 'Inactivo' ELSE 'Activo' END) as sta_reg"))
                                                             ->orderby('des_uni')->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Unidad de Medida'));
        $this->pintar_listado_pdf($pdf,$data);
        exit;
    }

}
