<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Http\Requests\Administrativo\Meru_Administrativo\Formulacion\Configuracion\MaestroLeyRequest;
use App\Imports\MaestroLeyImport;

class MaestroLeyController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('administrativo.meru_administrativo.formulacion.configuracion.maestro_ley.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$maestroLey   = new MaestroLey;
        $maestroLey->ano_pro = RegistroControl::periodoActual();
		$centrosCosto = CentroCosto::where('ano_pro', $maestroLey->ano_pro)->orderBy('id')->get();
		$partidas     = PartidaPresupuestaria::all();

		return view('administrativo.meru_administrativo.formulacion.configuracion.maestro_ley.create', compact('maestroLey', 'centrosCosto', 'partidas'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(MaestroLeyRequest $request)
	{
		$request->validated();

		DB::beginTransaction();

		try {
			$usuario    = \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
			$cenCos  	= CentroCosto::where('cod_cencosto', $request->centro_costo)
							->where('ano_pro', $request->ano_pro)
							->first();
			$partida 	= PartidaPresupuestaria::where('cod_cta', $request->partida_presupuestaria)->first();
			$estructura = explode('.', $request->estructura);

			$maestroLey                  = new MaestroLey;
			$maestroLey->ano_pro         = $request->ano_pro;
			$maestroLey->tip_cod         = $estructura[0];
			$maestroLey->cod_pryacc      = $estructura[1];
			$maestroLey->cod_obj         = $estructura[2];
			$maestroLey->gerencia        = $estructura[3];
			$maestroLey->unidad          = $estructura[4];
			$maestroLey->cod_par 		 = $estructura[5];
			$maestroLey->cod_gen 		 = $estructura[6];
			$maestroLey->cod_esp 		 = $estructura[7];
			$maestroLey->cod_sub 		 = $estructura[8];
			$maestroLey->cod_com 		 = $request->estructura;
			$maestroLey->ley_for		 = 0;
			$maestroLey->mto_ley		 = 0;
			$maestroLey->mto_mod		 = 0;
			$maestroLey->mto_apa		 = 0;
			$maestroLey->mto_pre		 = 0;
			$maestroLey->mto_com		 = 0;
			$maestroLey->mto_cau		 = 0;
			$maestroLey->mto_dis		 = 0;
			$maestroLey->mto_pag		 = 0;
			$maestroLey->sta_reg         = $request->has('estructura') ? '1' : '0';
			$maestroLey->usuario         = $usuario;
			$maestroLey->user_id 		 = auth()->user()->id;
			$maestroLey->centro_costo_id = $cenCos->id;
			$maestroLey->partida_presupuestaria_id = $partida->id;
			$maestroLey->save();

			DB::commit();

			alert()->html('¡Éxito!', 'Registro creado exitosamente<br><b>' . $maestroLey->cod_com . '</b>');
			return redirect()->route('formulacion.configuracion.maestro_ley.index');
		} catch (\Illuminate\Database\QueryException $e) {
			DB::rollBack();
			alert()->error('¡Transacción Fallida!', $e->getMessage());
			return redirect()->back()->withInput();
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  MaestroLey $maestroLey
	 * @return \Illuminate\Http\Response
	 */
	public function show(MaestroLey $maestroLey)
	{
		return view('administrativo.meru_administrativo.formulacion.configuracion.maestro_ley.show', compact('maestroLey'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  MaestroLey $maestroLey
	 * @return \Illuminate\Http\Response
	 */
	public function edit(MaestroLey $maestroLey)
	{
		$centrosCosto = CentroCosto::where('ano_pro', $maestroLey->ano_pro)->orderBy('id')->get();
		$partidas     = PartidaPresupuestaria::all();
		return view('administrativo.meru_administrativo.formulacion.configuracion.maestro_ley.edit', compact('maestroLey', 'centrosCosto', 'partidas'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  MaestroLey $maestroLey
	 * @return \Illuminate\Http\Response
	 */
	public function update(MaestroLeyRequest $request, MaestroLey $maestroLey)
	{
		$request->validated();

		DB::beginTransaction();

		try {
			$maestroLey->exc_pag = $request->has('exceder_pago') ? '1' : '0';
			$maestroLey->user_id = auth()->user()->id;
			$maestroLey->save();

			DB::commit();

			alert()->success('¡Éxito!', 'Registro Modificado Exitosamente!');
			return redirect()->route('formulacion.configuracion.maestro_ley.index');
		} catch(\Illuminate\Database\QueryException $e){
			DB::rollBack();
			alert()->error('¡Transacción Fallida!', $e->getMessage());
			return redirect()->back()->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  MaestroLey $maestroLey
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(MaestroLey $maestroLey)
	{
		//
	}

	public function createImportar()
    {
        return view('administrativo.meru_administrativo.formulacion.configuracion.maestro_ley.importar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeImportar(Request $request)
    {
    	// Validación tipos de archivo
    	$validated = $request->validate([
			'import_file' => [
				'required',
				'file',
				'mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.spreadsheet',
				'mimes:xls,xlsx,ods'
			],	        
	    ]);

        try {
            $import = new MaestroLeyImport();
            $import->import($request->file('import_file'));
            $failures = $import->getErrores();

            alert()->info('¡Éxito!', 'Proceso realizado');
            return view('administrativo.meru_administrativo.formulacion.configuracion.maestro_ley.importar', compact('failures'));
        } catch (\Illuminate\Database\QueryException $e) {
            alert()->error('¡Error!', $e->getMessage());
            return redirect()->route('formulacion.configuracion.maestro_ley.importar.create');
        }
    }
}