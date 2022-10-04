<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Contratos\Configuracion;

use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\ConceptoContrato;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\ConceptosContratoDet;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Http\Requests\Administrativo\Meru_Administrativo\Contratos\Configuracion\ConceptosContratosRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;

class ConceptosContratosController extends Controller
{   use ReportFpdf;
    public function index()
    {
        return view('administrativo.meru_administrativo.contratos.configuracion.op_conceptos.index');
    }

    public function create()
    {
        $conceptoscontratos= new ConceptoContrato();
        return view('administrativo.meru_administrativo.contratos.configuracion.op_conceptos.create', compact('conceptoscontratos'));
    }

    public function store(ConceptosContratosRequest $request)
    {
        try {
            if ($request->validated()){
                DB::connection('pgsql')->beginTransaction();
                $datos=ConceptoContrato::create( $request->only(['cod_con', 'des_con','sta_reg','usuario','fecha'] ));
                $estructuras=json_decode($request->estructuras,true);
                foreach($estructuras as $detalle){
                    ConceptosContratoDet::create([
                    'cod_con'                  => $request->cod_con,
                    'cod_par'                  => $detalle['cod_par'],
                    'cod_gen'                  => $detalle['cod_gen'],
                    'cod_esp'                  => $detalle['cod_esp'],
                    'cod_sub'                  => $detalle['cod_sub'],
                    'cod_com'                  => \Str::replace('4.', '', PartidaPresupuestaria::find($detalle['estructura'])->cod_cta),
                    'op_conceptos_id'          => $datos->id,
                    'partida_presupuestaria_id'=> $detalle['estructura'],
                    ]);
                }
                DB::connection('pgsql')->commit();
                alert()->success('¡Éxito!', 'Registro Ingresado Exitosamente');
                return redirect()->route('contratos.configuracion.conceptoscontratos.index');
            }
        } catch(\Illuminate\Database\QueryException $e){
           DB::connection('pgsql')->rollBack();
           alert()->error('¡Transacción Fallida!', $e->getMessage());
           return redirect()->back()->withInput();
        }
    }

    public function show(ConceptoContrato $conceptoscontrato)
    {
        return view('administrativo.meru_administrativo.contratos.configuracion.op_conceptos.show',['conceptoscontrato'     => $conceptoscontrato ]);
    }

    public function edit(ConceptoContrato $conceptoscontrato)
    {
        return view('administrativo.meru_administrativo.contratos.configuracion.op_conceptos.edit', compact('conceptoscontrato'));
    }

    public function update(ConceptosContratosRequest $request, ConceptoContrato $conceptoscontrato)
    {
        try {
            if ($request->validated()){
                DB::connection('pgsql')->beginTransaction();
                $conceptoscontrato->update($request->only([ 'des_con','sta_reg'] ));
                $OpConceptosDet=ConceptosContratoDet::where('op_conceptos_id','=',$conceptoscontrato->id);
                $OpConceptosDet->delete();
                $estructuras=json_decode($request->estructuras,true);
                foreach($estructuras as $detalle){
                    ConceptosContratoDet::create([
                    'cod_con'                  => $request->cod_con,
                    'cod_par'                  => $detalle['cod_par'],
                    'cod_gen'                  => $detalle['cod_gen'],
                    'cod_esp'                  => $detalle['cod_esp'],
                    'cod_sub'                  => $detalle['cod_sub'],
                    'cod_com'                  => \Str::replace('4.', '', PartidaPresupuestaria::find($detalle['estructura'])->cod_cta),
                    'op_conceptos_id'          => $conceptoscontrato->id,
                    'partida_presupuestaria_id'=> $detalle['estructura'],
                    ]);
                }
                DB::connection('pgsql')->commit();
                alert()->success('¡Éxito!', 'Registro Modificado Exitosamente');
                return redirect()->route('contratos.configuracion.conceptoscontratos.index');
            }
        } catch(\Illuminate\Database\QueryException $e){
           DB::connection('pgsql')->rollBack();
           alert()->error('¡Transacción Fallida!', $e->getMessage());
           return redirect()->back()->withInput();
        }
    }
    public function  print_conceptos_contratos()
    {
        $data['tipo_hoja']                  = 'C'; // C carta
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
        $data['registros']                  = ConceptoContrato::query()
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

