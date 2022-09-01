<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\OtrosPagos\Configuracion;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpConceptos;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpConceptosDet;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Http\Requests\Administrativo\Meru_Administrativo\OtrosPagos\Configuracion\ConceptoServicioRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;

class OpConceptosController extends Controller
{
    use ReportFpdf;
    public function index()
    {  return view('administrativo.meru_administrativo.otrospagos.configuracion.op_conceptos.index');
    }

    public function create()
    {   $conceptoservicio= new OpConceptos();
        return view('administrativo.meru_administrativo.otrospagos.configuracion.op_conceptos.create', compact('conceptoservicio'));
    }
    public function store(ConceptoServicioRequest $request)
    {
        try {
            if ($request->validated()){
                DB::connection('pgsql')->beginTransaction();
                $datos=OpConceptos::create( $request->only(['cod_con', 'des_con','sta_reg','usuario','fecha'] ));
                $estructuras=json_decode($request->estructuras,true);
                foreach($estructuras as $detalle){
                    OpConceptosDet::create([
                    'cod_con'                  => $request->cod_con,
                    'cod_par'                  => $detalle['cod_par'],
                    'cod_gen'                  => $detalle['cod_gen'],
                    'cod_esp'                  => $detalle['cod_esp'],
                    'cod_sub'                  => $detalle['cod_sub'],
                    'cod_cta'                  => \Str::replace('4.', '', PartidaPresupuestaria::find($detalle['estructura'])->cod_cta),
                    'op_conceptos_id'          => $datos->id,
                    'partida_presupuestaria_id'=> $detalle['estructura'],
                    ]);
                }
                DB::connection('pgsql')->commit();
                alert()->success('¡Éxito!', 'Registro Ingresado Exitosamente');
                return redirect()->route('otrospagos.configuracion.conceptoservicio.index');
            }
        } catch(\Illuminate\Database\QueryException $e){
           DB::connection('pgsql')->rollBack();
           alert()->error('¡Transacción Fallida!', $e->getMessage());
           return redirect()->back()->withInput();
        }
    }

    public function show(OpConceptos $conceptoservicio)
    {  return view('administrativo.meru_administrativo.otrospagos.configuracion.op_conceptos.show',['conceptoservicio'     => $conceptoservicio ]);
    }

    public function edit(OpConceptos $conceptoservicio)
    { return view('administrativo.meru_administrativo.otrospagos.configuracion.op_conceptos.edit', compact('conceptoservicio'));
    }


    public function update(ConceptoServicioRequest $request, OpConceptos $conceptoservicio)
    {
        try {
            if ($request->validated()){
                DB::connection('pgsql')->beginTransaction();
                $conceptoservicio->update($request->only([ 'des_con','sta_reg'] ));
                $OpConceptosDet=OpConceptosDet::where('op_conceptos_id','=',$conceptoservicio->id);
                $OpConceptosDet->delete();
                $estructuras=json_decode($request->estructuras,true);
                foreach($estructuras as $detalle){
                    OpConceptosDet::create([
                    'cod_con'                  => $request->cod_con,
                    'cod_par'                  => $detalle['cod_par'],
                    'cod_gen'                  => $detalle['cod_gen'],
                    'cod_esp'                  => $detalle['cod_esp'],
                    'cod_sub'                  => $detalle['cod_sub'],
                    'cod_cta'                  => \Str::replace('4.', '', PartidaPresupuestaria::find($detalle['estructura'])->cod_cta),
                    'op_conceptos_id'          => $conceptoservicio->id,
                    'partida_presupuestaria_id'=> $detalle['estructura'],
                    ]);
                }
                DB::connection('pgsql')->commit();
                alert()->success('¡Éxito!', 'Registro Modificado Exitosamente');
                return redirect()->route('otrospagos.configuracion.conceptoservicio.index');
            }
        } catch(\Illuminate\Database\QueryException $e){
           DB::connection('pgsql')->rollBack();
           alert()->error('¡Transacción Fallida!', $e->getMessage());
           return redirect()->back()->withInput();
        }
    }

    public function print_conceptos_servicios()
    {   $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE CONCEPTOS';
        $data['alineacion_columnas']		= array('C','L','C');//C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('20','150','30');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Código'),utf8_decode('Descripción'),utf8_decode('Estado'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('cod_con', utf8_decode('des_con'),'sta_reg');
        $data['nombre_documento']			= 'listado_certificacionconceptos.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = OpConceptos::query()
                                                        ->select(
                                                            DB::raw("cod_con"),
                                                            DB::raw("des_con"),
                                                            DB::raw("(CASE WHEN sta_reg = '0' THEN 'Inactivo' ELSE 'Activo' END) as sta_reg"))
                                                            ->where('sta_reg','=','1')
                                                            ->orderby('des_con','desc')->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Conceptos'));
        $this->pintar_listado_pdf($pdf,$data);
        exit;
    }
}
