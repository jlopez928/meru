<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion;

use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\TasaCambio;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion\TasaCambioRequest;
use Illuminate\Support\Str;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Traits\ReportFpdf;


class TasaCambioController extends Controller
{use ReportFpdf;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('administrativo.meru_administrativo.configuracion.configuracion.tasacambio.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasacambio= new TasaCambio();
        $tasacambio->fecha = Carbon::now()->format('Y-m-d');
         return view('administrativo.meru_administrativo.configuracion.configuracion.tasacambio.create', compact('tasacambio'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TasaCambioRequest $request)
    {
        try {
             TasaCambio::where('sta_reg', '1')->update(array('sta_reg' => '0'));
             TasaCambio::create($request->validated());
             flash()->addSuccess('Registro Guardado Exitosamente');
             return redirect()->route('configuracion.configuracion.tasacambio.index');
         } catch(\Illuminate\Database\QueryException $e){
             flash()->addError('Transacci&oacute;n Fallida: '.Str::limit($e, 120));
             return redirect()->back()->withInput();
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Configuracion\TasaCambio  $tasaCambio
     * @return \Illuminate\Http\Response
     */
    public function show(TasaCambio $tasacambio)
    {

        return view('administrativo.meru_administrativo.configuracion.configuracion.tasacambio.show',compact('tasacambio'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Administrativo\Meru_Administrativo\Configuracion\TasaCambio  $tasaCambio
     * @return \Illuminate\Http\Response
     */
    public function edit(TasaCambio $tasacambio)
    {
        //$tasacambio->fecha = Carbon::parse($tasacambio->fecha)->format('Y-m-d');
        return view('administrativo.meru_administrativo.configuracion.configuracion.tasacambio.edit', compact('tasacambio'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Administrativo\Meru_Administrativo\Configuracion\TasaCambio  $tasaCambio
     * @return \Illuminate\Http\Response
     */
    public function update(TasaCambioRequest $request, TasaCambio $tasacambio)
    {

        try {

            if ($tasacambio->sta_reg == '0' && $request->sta_reg=='0'){
                flash()->addInfo('Registro Inactivo NO puede ser Modificado. Favor verifique.');
                return redirect()->back()->withInput();
            }
            $tasacambio->update($request->validated());
            flash()->addSuccess('Registro Modificado Exitosamente');
            return redirect()->route('configuracion.configuracion.tasacambio.index');

        } catch(\Illuminate\Database\QueryException $e){
           flash()->addError('Transacci&oacute;n Fallida: '.Str::limit($e, 120));
            return redirect()->back()->withInput();
        }
    }


    public function print_tasacambio()
    {

        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO DE TASA DE CAMBIO';
        $data['alineacion_columnas']		= array('C','C','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']		    	= array('40','80','80');//Ancho de Columnas
        $data['nombre_columnas']		   	= array(utf8_decode('Fecha Vigencia'),utf8_decode('Monto'),utf8_decode('Estado'));
        $data['funciones_columnas']         = '';
        $data['fuente']		   	            = 8;
        $data['registros_mostar']           = array('fec_tasa', 'bs_tasa','sta_reg');
        $data['nombre_documento']			= 'listado_tasacambio.pdf'; //Nombre de Archivo
        $data['con_imagen']			        = true;
        $data['vigencia']			        = '';
        $data['revision']			        = '';
        $data['usuario']			        = auth()->user()->name;
        $data['cod_reporte']			    = '';
        $data['registros']                  = TasaCambio::query()
                                                        ->select(
                                                            DB::raw("fec_tasa"),
                                                            DB::raw("coalesce(bs_tasa, 0) as bs_tasa"),
                                                            DB::raw("(CASE WHEN sta_reg = '0' THEN 'Inactivo' ELSE 'Activo' END) as sta_reg"))
                                                             ->orderby('fec_tasa','desc')->get();

        $pdf = new Fpdf;
        $pdf->setTitle(utf8_decode('Listado de Modulos'));
        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }

}
