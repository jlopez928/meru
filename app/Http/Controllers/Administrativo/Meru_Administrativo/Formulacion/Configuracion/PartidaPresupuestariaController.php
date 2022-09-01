<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Support\Fpdf;
use App\Traits\ReportFpdf;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Contabilidad\PlanContable;
use App\Http\Requests\Administrativo\Meru_Administrativo\Formulacion\Configuracion\PartidaPresupuestariaRequest;

class PartidaPresupuestariaController extends Controller
{
	use ReportFpdf;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('administrativo.meru_administrativo.formulacion.configuracion.partida_presupuestaria.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$partidaPresupuestaria = new PartidaPresupuestaria;
		$partidaPresupuestaria->sta_reg = '1';
		$partidas411           = PartidaPresupuestaria::where('cod_cta', 'like', '4.11%')->get();

		return view('administrativo.meru_administrativo.formulacion.configuracion.partida_presupuestaria.create', compact('partidaPresupuestaria', 'partidas411'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(PartidaPresupuestariaRequest $request)
	{
		$request->validated();

		DB::beginTransaction();

		try {
			$usuario = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);

			$newPartida          = new PartidaPresupuestaria;
			$newPartida->tip_cod = $request->tipo;
			$newPartida->cod_par = $request->partida;
			$newPartida->cod_gen = $request->generica;
			$newPartida->cod_esp = $request->especifica;
			$newPartida->cod_sub = $request->subespecifica;
			$newPartida->des_con = $request->descripcion;
			$newPartida->cod_cta = $request->cod_partida;
			$newPartida->sta_reg = '0';
			$newPartida->usuario = $usuario;
			$newPartida->user_id = auth()->user()->id;
			$newPartida->part_asociada = $request->partida_asociada;
			$newPartida->save();

			DB::commit();

			alert()->html('¡Éxito!', 'Registro creado exitosamente<br><b>' . $newPartida->cod_cta . '</b>',  'success');
			return redirect()->route('formulacion.configuracion.partida_presupuestaria.index');
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
	public function show(PartidaPresupuestaria $partidaPresupuestaria)
	{
		return view('administrativo.meru_administrativo.formulacion.configuracion.partida_presupuestaria.show', compact('partidaPresupuestaria'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(PartidaPresupuestaria $partidaPresupuestaria)
	{
		$partidas411 = PartidaPresupuestaria::where('cod_cta', 'like', '4.11%')->get();

		return view('administrativo.meru_administrativo.formulacion.configuracion.partida_presupuestaria.edit', compact('partidaPresupuestaria', 'partidas411'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(PartidaPresupuestariaRequest $request, PartidaPresupuestaria $partidaPresupuestaria)
	{
		$request->validated();

		DB::beginTransaction();

		try {
			$partidaPresupuestaria->des_con       = $request->descripcion;
			$partidaPresupuestaria->part_asociada = $request->partida_asociada;
			$partidaPresupuestaria->sta_reg       = '1';
			$partidaPresupuestaria->user_id       = auth()->user()->id;
			$partidaPresupuestaria->save();

			DB::commit();

			alert()->success('¡Éxito!', 'Registro Modificado Exitosamente');
			return redirect()->route('formulacion.configuracion.partida_presupuestaria.index');
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
	public function destroy(PartidaPresupuestaria $partidaPresupuestaria)
	{
		//
	}

	public function editAsociar(PartidaPresupuestaria $partidaPresupuestaria)
	{;
		$partidas411      = PartidaPresupuestaria::where('cod_cta', 'like', '4.11%')->get();
		$cuentasContables = PlanContable::where('ano_cta', RegistroControl::periodoActual())->orderBy('cod_cta')->get();

		return view('administrativo.meru_administrativo.formulacion.configuracion.partida_presupuestaria.asociarcuenta', 
			compact('partidaPresupuestaria', 'partidas411', 'cuentasContables'));
	}

	public function updateAsociar(PartidaPresupuestariaRequest $request, PartidaPresupuestaria $partidaPresupuestaria)
	{
		$request->validated();

		DB::beginTransaction();

		try {
			$partidaPresupuestaria->cta_activo         = $request->cta_activo;
			$partidaPresupuestaria->cta_gasto          = $request->cta_gasto;
			$partidaPresupuestaria->cta_x_pagar        = $request->cta_por_pagar;
			$partidaPresupuestaria->cta_x_pagar_activo = $request->cta_por_pagar_activo;
			$partidaPresupuestaria->cta_provision      = $request->cta_provision;
			$partidaPresupuestaria->sta_reg            = '1';
			$partidaPresupuestaria->user_id            = auth()->user()->id;
			$partidaPresupuestaria->save();

			DB::commit();

			alert()->html('¡Éxito!', 'Registro Modificado Exitosamente<br>Cuentas Contables asociadas.', 'success');
			return redirect()->route('formulacion.configuracion.partida_presupuestaria.index');
		} catch(\Illuminate\Database\QueryException $e){
			DB::rollBack();
			alert()->error('¡Transacción Fallida!', $e->getMessage());
			return redirect()->back()->withInput();
		}
	}

	public function print_partidas_presupuestarias()
	{
		$data['tipo_hoja']                  = 'C'; // C carta
		$data['orientacion']                = 'H'; // V Vertical
		$data['cod_normalizacion']          = '';
		$data['gerencia']                   = '';
		$data['division']                   = '';
		$data['titulo']                     = 'HIDROBOLIVAR';
		$data['subtitulo']                  = 'LISTADO DE PARTIDAS PRESUPUESTARIAS';
		$data['alineacion_columnas']        = array('C','L','C','C','C','C','C','C'); //C centrado R derecha L izquierda
		$data['ancho_columnas']             = array(20,50,30,30,31,31,31,31);//Ancho de Columnas
		$data['nombre_columnas']            = [utf8_decode('Código'), utf8_decode('Descripción'), 'Cuenta de Activo', ' Cuenta de Gasto', 'Cuenta por Pagar Gasto', 'Cuenta por Pagar Activo', 'Cuenta Provisiones', utf8_decode('Partida Disminución de pasivos')];
		$data['funciones_columnas']         = '';
		$data['fuente']                     = 8;
		$data['registros_mostar']           = ['cod_cta', 'des_con', 'cta_activo', 'cta_gasto', 'cta_x_pagar', 'cta_x_pagar_gasto', 'cta_provision', 'part_asociada'];
		$data['nombre_documento']           = 'Listado_Partidas_Presupuestarias.pdf'; //Nombre de Archivo
		$data['con_imagen']                 = true;
		$data['vigencia']                   = '';
		$data['revision']                   = '';
		$data['usuario']                    = auth()->user()->name;
		$data['cod_reporte']                = '';
		$data['registros']                  = PartidaPresupuestaria::query()->orderby('cod_cta')->get();

		$pdf = new Fpdf;
		$pdf->setTitle(utf8_decode('Listado de Partidas Presupuestarias'));

		$this->pintar_listado_pdf($pdf,$data);

		exit;
	}
}
