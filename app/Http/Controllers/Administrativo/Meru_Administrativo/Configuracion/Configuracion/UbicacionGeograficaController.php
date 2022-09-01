<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Configuracion\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Support\Fpdf;
use App\Traits\ReportFpdf;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\UbicacionGeografica;
use App\Http\Requests\Administrativo\Meru_Administrativo\Configuracion\Configuracion\UbicacionGeograficaRequest;

class UbicacionGeograficaController extends Controller
{
	use ReportFpdf;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('administrativo.meru_administrativo.configuracion.configuracion.ubicacion_geografica.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$ubicacionGeografica = new UbicacionGeografica;
		return view('administrativo.meru_administrativo.configuracion.configuracion.ubicacion_geografica.create',compact('ubicacionGeografica'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(UbicacionGeograficaRequest $request)
	{
		$request->validated();

		$ubicacionGeografica = new UbicacionGeografica;
		$ubicacionGeografica->cod_edo = $request->estado;
		$ubicacionGeografica->cod_mun = $request->municipio;
		$ubicacionGeografica->cod_par = $request->parroquia;
		DB::beginTransaction();

		try {
			$usuario = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);

			$ubicacionGeografica->des_ubi = $request->descripcion;
			$ubicacionGeografica->capital = $request->capital;
			$ubicacionGeografica->cod_ubi = $request->codigo;
			$ubicacionGeografica->sta_reg = '1';
			$ubicacionGeografica->usuario = $usuario;
			$ubicacionGeografica->user_id = auth()->user()->id;
			// Generar el código 
			$ubicacionGeografica->setCodigo();
			$ubicacionGeografica->save();

			DB::commit();

			alert()->success('¡Éxito!', 'Registro creado exitosamente');
			return redirect()->route('configuracion.configuracion.ubicacion_geografica.index');
		} catch (\Illuminate\Database\QueryException $e) {
			DB::rollBack();
			alert()->addError('¡Transacción Fallida!', $e->getMessage());
			return redirect()->back()->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  UbicacionGeografica $ubicacionGeografica
	 * @return \Illuminate\Http\Response
	 */
	public function show(UbicacionGeografica $ubicacionGeografica)
	{
		$estados    = UbicacionGeografica::getEstados();        
		$municipios = UbicacionGeografica::getMunicipios($ubicacionGeografica->cod_edo);
		$parroquias = UbicacionGeografica::getParroquias($ubicacionGeografica->cod_edo, $ubicacionGeografica->cod_mun);

		return view('administrativo.meru_administrativo.configuracion.configuracion.ubicacion_geografica.show', compact('ubicacionGeografica', 'estados', 'municipios', 'parroquias'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  UbicacionGeografica $ubicacionGeografica
	 * @return \Illuminate\Http\Response
	 */
	public function edit(UbicacionGeografica $ubicacionGeografica)
	{
		$estados    = UbicacionGeografica::getEstados();        
		$municipios = UbicacionGeografica::getMunicipios($ubicacionGeografica->cod_edo);
		$parroquias = UbicacionGeografica::getParroquias($ubicacionGeografica->cod_edo, $ubicacionGeografica->cod_mun);

		return view('administrativo.meru_administrativo.configuracion.configuracion.ubicacion_geografica.edit',compact('ubicacionGeografica', 'estados', 'municipios', 'parroquias'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  UbicacionGeografica $ubicacionGeografica
	 * @return \Illuminate\Http\Response
	 */
	public function update(UbicacionGeograficaRequest $request, UbicacionGeografica $ubicacionGeografica)
	{
		$request->validated();

		DB::beginTransaction();

		try {
			$ubicacionGeografica->des_ubi = $request->descripcion;
			$ubicacionGeografica->capital = $request->capital;        
			$ubicacionGeografica->cod_ubi = $request->codigo;
			$ubicacionGeografica->save();

			DB::commit();

			alert()->addSuccess('¡Éxito!', 'Registro Modificado Exitosamente');
			return redirect()->route('configuracion.configuracion.ubicacion_geografica.index');
		} catch(\Illuminate\Database\QueryException $e){
			DB::rollBack();
			alert()->error('¡Transacción Fallida!', $e->getMessage());
			return redirect()->back()->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  UbicacionGeografica $ubicacionGeografica
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(UbicacionGeografica $ubicacionGeografica)
	{
		//
	}

	public function print_ubicaciones_geograficas ()
	{
		$data['tipo_hoja']                  = 'C'; // C carta
		$data['orientacion']                = 'V'; // V Vertical
		$data['cod_normalizacion']          = '';
		$data['gerencia']                   = '';
		$data['division']                   = '';
		$data['titulo']                     = 'HIDROBOLIVAR';
		$data['subtitulo']                  = 'LISTADO UBICACIONES GEOGRAFICAS';
		$data['alineacion_columnas']        = array('C','C','C','L','L','C'); //C centrado R derecha L izquierda
		$data['ancho_columnas']             = array(22,22,22,55,50,25);//Ancho de Columnas
		$data['nombre_columnas']            = array('Estado', 'Municipio', 'Parroquia', utf8_decode('Descripción'), 'Capital', utf8_decode('Código'));
		$data['funciones_columnas']         = '';
		$data['fuente']                     = 8;
		$data['registros_mostar']           = ['cod_edo', 'cod_mun', 'cod_par', 'des_ubi', 'capital', 'cod_ubi'];
		$data['nombre_documento']           = 'Listado_Ubicaciones_Geograficas.pdf'; //Nombre de Archivo
		$data['con_imagen']                 = true;
		$data['vigencia']                   = '';
		$data['revision']                   = '';
		$data['usuario']                    = auth()->user()->name;
		$data['cod_reporte']                = '';
		$data['registros']                  = UbicacionGeografica::query()->orderby('id')->get();

		$pdf = new Fpdf;
		$pdf->SetLeftMargin(5);

		$pdf->setTitle(utf8_decode('Listado de Partidas Presupuestarias'));

		$this->pintar_listado_pdf($pdf,$data);

		exit;
	}
}