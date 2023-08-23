<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Compras\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\CausaAnulacion;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Administrativo\Meru_Administrativo\Compras\Configuracion\CausaAnulacionRequest;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;

class CausaAnulacionController extends Controller
{
    use ReportFpdf;

    public function index()
    {  return view('administrativo.meru_administrativo.compras.configuracion.causaanulacion.index');
    }
    public function create()
    {
        $causaanulacion= new CausaAnulacion();
        return view('administrativo.meru_administrativo.compras.configuracion.causaanulacion.create', compact('causaanulacion'));
    }

    public function store(CausaAnulacionRequest $request)
    {
        try {       DB::connection('pgsql')->beginTransaction();
                    CausaAnulacion::create( $request->only(['cod_cau', 'des_cau','sta_reg','usuario','fecha'] ));
                    DB::connection('pgsql')->commit();
                    alert()->success('¡Éxito!', 'Registro Ingresado Exitosamente');
                    return redirect()->route('compras.configuracion.causaanulacion.index');

         } catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
         }
    }

    public function show(CausaAnulacion $causaanulacion)
    {        return view('administrativo.meru_administrativo.compras.configuracion.causaanulacion.show',compact('causaanulacion'));
    }
    public function edit(CausaAnulacion $causaanulacion)
    {   return view('administrativo.meru_administrativo.compras.configuracion.causaanulacion.edit', compact('causaanulacion'));
    }
    public function update(CausaAnulacionRequest $request, CausaAnulacion $causaanulacion)
    {   try {
            DB::connection('pgsql')->beginTransaction();
            if ($causaanulacion->sta_reg == '0' && $request->sta_reg=='0'){
                alert()->error('Registro Inactivo NO puede ser Modificado. Favor verifique');
                return redirect()->back()->withInput();
            }
            $causaanulacion->update($request->validated());
            DB::connection('pgsql')->commit();
            alert()->success('¡Éxito!', 'Registro Modificado Exitosamente');
            return redirect()->route('compras.configuracion.causaanulacion.index');

        } catch(\Illuminate\Database\QueryException $e){
            DB::connection('pgsql')->rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function print_causa_anulacion()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE CAUSAS DE ANULACION';
        $data['alineacion_columnas']		= array('C','C','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('40','80','80');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Codigo'),utf8_decode('Descripción'),utf8_decode('Estado'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('cod_cau', utf8_decode('des_cau'),'sta_reg');
        $data['nombre_documento']			= 'listado_causaanulacion.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = CausaAnulacion::query()
                                                        ->select(
                                                            DB::raw("cod_cau"),
                                                            DB::raw("des_cau"),
                                                            DB::raw("(CASE WHEN sta_reg = '0' THEN 'Inactivo' ELSE 'Activo' END) as sta_reg"))
                                                             ->orderby('des_cau')->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Causas de Anulación'));
        $this->pintar_listado_pdf($pdf,$data);
        exit;
    }

}
