<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
//use Codedge\Fpdf\Fpdf\Fpdf;
use App\Support\Fpdf;
use App\Traits\ReportFpdf;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Http\Requests\Administrativo\Meru_Administrativo\Formulacion\Configuracion\CentroCostoRequest;

class CentroCostoController extends Controller
{
    use ReportFpdf;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrativo.meru_administrativo.formulacion.configuracion.centro_costo.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $centroCosto          = new CentroCosto;
        $centroCosto->ano_pro = RegistroControl::periodoActual();
        $centroCosto->cre_adi = '0';
        $centroCosto->sta_reg = '1';

        return view('administrativo.meru_administrativo.formulacion.configuracion.centro_costo.create', compact('centroCosto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CentroCostoRequest $request)
    {
        $request->validated();

        DB::beginTransaction();

        try {
            $usuario     = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
            $cenCos      = CentroCosto::generarCodCentroCosto($request->tipo, $request->proyecto, $request->objetivo, $request->gerencia, $request->unidad);

            $newCenCos                  = new CentroCosto;
            $newCenCos->ano_pro         = $request->ano_pro;
            $newCenCos->tip_cod         = $request->tipo;
            $newCenCos->cod_pryacc      = $request->proyecto;
            $newCenCos->cod_obj         = $request->objetivo;
            $newCenCos->gerencia        = $request->gerencia;
            $newCenCos->unidad          = $request->unidad;
            $newCenCos->des_con         = $request->descripcion;
            $newCenCos->sta_reg         = isset($request->estado) ? '1' : '0';
            $newCenCos->cod_cencosto    = $cenCos;
            $newCenCos->ajust_ctrocosto = $cenCos;
            $newCenCos->cre_adi         = isset($request->credito_adicional) ? '1' : '0';
            $newCenCos->nivel           = CentroCosto::getNivel($cenCos);
            $newCenCos->usuario         = $usuario;
            $newCenCos->user_id = auth()->user()->id;
            $newCenCos->save();

            // Si es Crédito Adicional agregarlo como Gerencia
            if (isset($request->credito_adicional)) {
                if (isset($request->credito_adicional) && empty(trim($request->siglas))) {
                    throw new \Exception('Debe agregar unas siglas para el Centro de Costo', -1);
                }

                $gerencia               = new Gerencia;
                $gerencia->des_ger      = $request->descripcion;
                $gerencia->nomenclatura = $request->siglas;
                $gerencia->centro_costo = $cenCos;
                $gerencia->centro_costo_id = $newCenCos->id;
                $gerencia->centro_costo_anterior = $cenCos;
                $gerencia->status       = '1';
                $gerencia->usuario      = $usuario;
                $gerencia->user_id      = auth()->user()->id;
                $gerencia->save();
            }

            DB::commit();

            alert()->html('¡Éxito!', 'Registro creado exitosamente<br><b>' . $newCenCos->ano_pro . ' - ' . $newCenCos->cod_cencosto . '</b>',  'success');
            return redirect()->route('formulacion.configuracion.centro_costo.index');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CentroCosto $centroCosto)
    {
        return view('administrativo.meru_administrativo.formulacion.configuracion.centro_costo.show', compact('centroCosto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CentroCosto $centroCosto)
    {
        return view('administrativo.meru_administrativo.formulacion.configuracion.centro_costo.edit', compact('centroCosto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CentroCostoRequest $request, CentroCosto $centroCosto)
    {
        $request->validated();

        DB::beginTransaction();

        try {
            $centroCosto->des_con = $request->descripcion;
            $centroCosto->user_id = auth()->user()->id;
            $centroCosto->save();

            DB::commit();

            alert()->success('¡Éxito!', 'Registro Modificado Exitosamente');
            return redirect()->route('formulacion.configuracion.centro_costo.index');
        } catch(\Illuminate\Database\QueryException $e){
            DB::rollBack();
            alert()->error('¡Transacción Fallida!', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CentroCosto $centroCosto)
    {
        //
    }

    public function print_centros_costos()
    {
        $data['tipo_hoja']                  = 'C'; // C carta
        $data['orientacion']                = 'V'; // V Vertical
        $data['cod_normalizacion']          = '';
        $data['gerencia']                   = '';
        $data['division']                   = '';
        $data['titulo']                     = 'HIDROBOLIVAR';
        $data['subtitulo']                  = 'LISTADO CENTROS DE COSTOS';
        $data['alineacion_columnas']        = array('C','C','L','C'); //C centrado R derecha L izquierda
        $data['ancho_columnas']             = array(20,35,105,30);//Ancho de Columnas
        $data['nombre_columnas']            = array(utf8_decode('Año'),utf8_decode('Código'),utf8_decode('Descipción'),utf8_decode('Crédito Adicional'));
        $data['funciones_columnas']         = '';
        $data['fuente']                     = 8;
        $data['registros_mostar']           = ['ano_pro', 'cod_cencosto', 'des_con', 'cre_adi'];
        $data['nombre_documento']           = 'Listado_Partidas_Presupuestarias.pdf'; //Nombre de Archivo
        $data['con_imagen']                 = true;
        $data['vigencia']                   = '';
        $data['revision']                   = '';
        $data['usuario']                    = auth()->user()->name;
        $data['cod_reporte']                = '';
        $data['registros']                  = CentroCosto::query()->where('ano_pro', RegistroControl::periodoActual())->orderby('cod_cencosto')->get();

        $pdf = new Fpdf;
        $pdf->SetLeftMargin(5);

        $pdf->setTitle(utf8_decode('Listado de Partidas Presupuestarias'));

        $this->pintar_listado_pdf($pdf,$data);

        exit;
    }
}