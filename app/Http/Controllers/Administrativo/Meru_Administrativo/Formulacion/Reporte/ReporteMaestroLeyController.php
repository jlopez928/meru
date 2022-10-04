<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Formulacion\Reporte;

use App\Exports\Administrativo\Meru_Administrativo\Formulacion\ResumenPresupuestarioExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ReportFpdf;
use App\Support\Fpdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaestroLeyExport;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;

class ReporteMaestroLeyController extends Controller
{
	use ReportFpdf;

	private $meses;

	public function __construct()
	{
		$this->meses = [
			1  => 'ENERO',
			2  => 'FEBRERO',
			3  => 'MARZO',
			4  => 'ABRIL',
			5  => 'MAYO',
			6  => 'JUNIO',
			7  => 'JULIO',
			8  => 'AGOSTO',
			9  => 'SEPTIEMBRE',
			10 => 'OCTUBRE',
			11 => 'NOVIEMBRE',
			12 => 'DICIEMBRE',
		];
	}

	//////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////// RESUMEN PRESUPUESTARIO ///////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////

	public function resumenCreate()
	{
		$periodosRep  = Registrocontrol::orderBy('ano_pro', 'desc')->get()->pluck('ano_pro', 'ano_pro');
		$meses        = $this->meses;
		$centrosCosto = CentroCosto::where('ano_pro', RegistroControl::periodoActual())->orderBy('id')->get();
		$partidas     = PartidaPresupuestaria::all();

		return view(
			'administrativo.meru_administrativo.formulacion.reportes.maestro_ley.resumen', 
			compact('periodosRep', 'meses', 'centrosCosto', 'partidas')
		);
	}

	public function resumenStore(Request $request)
	{
		$anoPro        = $request->ano_pro;
		$mes           = $request->mes;
		$anoActual     = Registrocontrol::periodoActual();
		$periodoActual = Registrocontrol::where('ano_pro', $anoActual)->first();
		$cenCos        = $request->only('tip_cod','cod_pryacc','cod_obj','gerencia','unidad');
		$partida       = $request->only('cod_par', 'cod_gen', 'cod_esp', 'cod_sub');

		$periodoParalelo = Registrocontrol::where('ano_pro', $anoPro)
							->where('mes_pre', $mes)
							->where('sta_pre', '2')
							->first();

		if (($anoPro == $periodoActual->ano_pro && $mes == $periodoActual->mes_pre) || (!is_null($periodoParalelo) && $periodoParalelo->ano_pro == $anoPro && $periodoParalelo->mes_pre == $mes)) {
			$sql = MaestroLey::select(
					'pre_maestroley.tip_cod',
					'pre_maestroley.cod_pryacc',
					'pre_maestroley.cod_obj',
					'pre_maestroley.gerencia',
					'pre_maestroley.unidad',
					'pre_maestroley.cod_par',
					'pre_maestroley.cod_gen',
					'pre_maestroley.cod_esp',
					'pre_maestroley.cod_sub',
					'pre_maestroley.ley_for',
					'pre_maestroley.mto_ley',
					'pre_maestroley.mto_com',
					'pre_maestroley.mto_cau',
					'pre_maestroley.mto_pag',
					'pre_maestroley.mto_dis',
					'pre_maestroley.mto_pre',
					'bloqueado.mto_blo AS bloqueado',
					'cc.cod_cencosto',
					DB::raw('UPPER(cc.des_con) AS des_centro'),
					'p.cod_cta AS cod_partida',
					DB::raw('UPPER(p.des_con) AS des_partida')
					)->leftJoin('bloqueado', function($join){
						$join->on('pre_maestroley.ano_pro', '=', 'bloqueado.ano_pro')
							->on('pre_maestroley.cod_com', '=', 'bloqueado.cod_com');
					})
					->join('pre_centrocosto AS cc', 'pre_maestroley.centro_costo_id', '=', 'cc.id')
					->join('pre_partidasgastos AS p', 'pre_maestroley.partida_presupuestaria_id', '=', 'p.id')
					->where('pre_maestroley.ano_pro', $anoPro);
		} else {
			$sql =  MaestroLey::select(
						'pre_maestroley.tip_cod',
						'pre_maestroley.cod_pryacc',
						'pre_maestroley.cod_obj',
						'pre_maestroley.gerencia',
						'pre_maestroley.unidad',
						'pre_maestroley.cod_par',
						'pre_maestroley.cod_gen',
						'pre_maestroley.cod_esp',
						'pre_maestroley.cod_sub',
						'pre_maestroley.ley_for',
						'cm.mto_mod AS mto_ley',
						'cm.mto_com',
						'cm.mto_cau',
						'cm.mto_pag',
						'cm.mto_dis',
						'cm.mto_pre',
						'cc.cod_cencosto',
						// DB::raw('UPPER(cc.des_con) AS des_centro'),
						DB::raw('cc.des_con AS des_centro'),
						'p.cod_cta AS cod_partida',
						//DB::raw('UPPER(p.des_con) AS des_partida')
						DB::raw('p.des_con AS des_partida')
					)
					->join('pre_cierremensual AS cm', 
						function($join) use ($mes) {
							$join->on('pre_maestroley.ano_pro', '=', 'cm.ano_pro')
								->on('pre_maestroley.cod_com', '=', 'cm.cod_com')
								->on('cm.mes_pro', '=', DB::raw($mes));
						})
					->join('pre_centrocosto AS cc', 'pre_maestroley.centro_costo_id', '=', 'cc.id')
					->join('pre_partidasgastos AS p', 'pre_maestroley.partida_presupuestaria_id', '=', 'p.id')
					->where('pre_maestroley.ano_pro', $anoPro);
		}

		foreach ($cenCos as $key => $ceco) {
			if (is_null($ceco)) {
				break;
			} else {
				$sql->where('pre_maestroley.' . $key, '=', $ceco);
			}
		}

		foreach ($partida as $key => $part) {
			if (is_null($part)) {
				break;
			} else {
				$sql->where('pre_maestroley.' . $key, '=', $part);
			}
		}

		$sql->orderByRaw('1,2,3,4,5,6,7,8,9');

		$res = $sql->get();

		if ($request->get('tipo_reporte') == 'E') {
			return Excel::download(new ResumenPresupuestarioExport($anoPro, $this->meses[$mes], $res), 'resumen_presupuestario.xlsx');
		} else {
			if ($res->count() > 0) {
				$pdf = new Fpdf;
				$pdf->AliasNbPages();
				$pdf->SetLeftMargin(5);
				$pdf->setTitle(utf8_decode('Resumen Presupuestario'));
				$pdf->SetAuthor(auth()->user()->name);
				$pdf->SetAutoPageBreak(true, 5);

				$data['tipo_hoja']           = 'A4';
				$data['orientacion']         = 'H';
				$data['cod_normalizacion']   = '';
				$data['gerencia']            = '';
				$data['division']            = '';
				$data['titulo']              = 'HIDROBOLIVAR';
				$data['subtitulo']           = 'RESUMEN PRESUPUESTARIO - ' . $this->meses[$request->mes] . ' ' . $request->ano_pro;
				$data['alineacion_columnas'] = ['C','C','L','R','R','R','R','R','R','R','R'];
				$data['ancho_columnas']      = [20,30,45,23,23,23,23,23,23,23,23]; //Ancho de Columnas
				$data['nombre_columnas']     = ['CENTRO COSTO','PARTIDA','DESCRIPCION','MONTO LEY','MODIFICADO','PRE-COMP','COMP','CAUSADO','PAGADO','BLOQUEADO','DISPONIBLE'];
				$data['funciones_columnas']  = '';
				$data['fuente']              = 7;
				$data['registros_mostar']    = [];
				$data['nombre_documento']    = 'Resumen_Presupuestario.pdf'; //Nombre del archivo
				$data['con_imagen']          = true;
				$data['vigencia']            = '';
				$data['revision']            = '';
				$data['usuario']             = auth()->user()->name;
				$data['cod_reporte']         = '';
				$data['registros']           = [];

				$this->pintar_encabezado_pdf($pdf, $data);
				$this->pintar_cabecera_columnas_pdf($pdf, $data, false);

				// $pdf->SetWidths([20,30,45,23,23,23,23,23,23,23,23]);
				// $pdf->SetAligns(['C','C','L','R','R','R','R','R','R','R','R']);
				// $pdf->SetLeftMargin(5);
				// $pdf->SetFont('Arial','',7);

				$xctro = ''; // Control de Cambio de Centro de Costo
				$xgen  = ''; // Control de Cambio de Partida generica
				$t1  = 0; $t2  = 0; $t3  = 0; $t4  = 0; $t5  = 0; $t6  = 0; $t7  = 0; $t8  = 0; // Totales Generales
				$ct1 = 0; $ct2 = 0; $ct3 = 0; $ct4 = 0; $ct5 = 0; $ct6 = 0; $ct7 = 0; $ct8 = 0; // Totales Por centro de Costo
				$pt1 = 0; $pt2 = 0; $pt3 = 0; $pt4 = 0; $pt5 = 0; $pt6 = 0; $pt7 = 0; $pt8 = 0; // Totales Por Partida Presupuestaria

				foreach($res as $r)
				{
					$pdf->SetWidths([20,30,45,23,23,23,23,23,23,23,23]);
					$pdf->SetAligns(['C','C','L','R','R','R','R','R','R','R','R']);
					//$pdf->SetLeftMargin(5);
					$pdf->SetFont('Arial','',7);
				
					$ctro = $r->cod_cencosto;
					$gen  = substr($r->cod_partida, 0, 4);

					if ($xctro == '') {
						$xctro = $ctro; // Guardar Centro de Costo
						$xgen  = $gen;
						$row   = array($ctro,utf8_decode($r->des_centro),'','','','','','','','','');
						$pdf->Row($row, 'S');
						$row   = array('','','','','','','','','','','');       
						$row   = array(' ',"Partida $gen                                     ",' ','','','','','','','','');
						$pdf->Row($row, 'S');
					}

					if ($xctro!=$ctro) { // Cambio centro de costo
						$row = array('','','','_____________','_____________','____________','___________','___________','___________','___________','___________');
						$pdf->Row($row, 'S');
						$row = array('','Total Partida ',$xgen.' ......................................................',
							number_format($pt1,2,',','.'),
							number_format($pt2,2,',','.'),
							number_format($pt3,2,',','.'),
							number_format($pt4,2,',','.'),
							number_format($pt5,2,',','.'),
							number_format($pt6,2,',','.'),
							number_format($pt8,2,',','.'),
							number_format($pt7,2,',','.')
						);
						$pdf->Row($row, 'S');
						$row = array('','','','============','============','============','============','============','============','============','============');
						$pdf->Row($row, 'S');                              
						$row = array('Total Centro ',$xctro.'','..............................................................',
							number_format($ct1,2,',','.'),
							number_format($ct2,2,',','.'),
							number_format($ct3,2,',','.'),
							number_format($ct4,2,',','.'),
							number_format($ct5,2,',','.'),
							number_format($ct6,2,',','.'),
							number_format($ct8,2,',','.'),
							number_format($ct7,2,',','.')
							);
						$pdf->Row($row, 'S');
						$row = array('','','','','','','','','','','');
						$pdf->Row($row, 'S');
						$row = array($ctro,utf8_decode($r->des_centro),'','','','','','','','','');
						$pdf->Row($row, 'S');          
						$row = array(' ','','','','','','','','','','');
						$pdf->Row($row, 'S');
						$row =array('',"Partida $gen                                ",' ','','','','','','','','');                                   
						$pdf->Row($row, 'S');

						$gen = substr($r->cod_partida, 0, 4);

						$row = array(
							'',
							$r->cod_partida, 
							utf8_decode($r->des_partida),
							number_format($r['ley_for'],2,',','.'),
							number_format($r['mto_ley'],2,',','.'),
							number_format($r['mto_pre'],2,',','.'),
							number_format($r['mto_com'],2,',','.'),
							number_format($r['mto_cau'],2,',','.'),
							number_format($r['mto_pag'],2,',','.'),
							number_format($r['bloqueado'],2,',','.'),
							number_format($r['mto_dis'],2,',','.')
						);

						$xctro = $ctro; // Guardar nuevo centro de costo
						$xgen  = $gen;
						$ct1 = 0; $ct2 = 0; $ct3 = 0; $ct4 = 0; $ct5 = 0; $ct6 = 0; $ct7 = 0; $ct8 = 0;
						$pt1 = 0; $pt2 = 0; $pt3 = 0; $pt4 = 0; $pt5 = 0; $pt6 = 0; $pt7 = 0; $pt8 = 0;
					}

					if ($xgen == '') {
						$xgen = $gen;
					}

					if ($xgen != $gen) { // Cambio Partida generica
						$row = array("","","","_____________","_____________","____________","___________","___________","___________","___________","___________");
						$pdf->Row($row, 'S');
						$row = array("","Total Partida ",$xgen." ......................................................",
							number_format($pt1,2,',','.'),
							number_format($pt2,2,',','.'),
							number_format($pt3,2,',','.'),
							number_format($pt4,2,',','.'),
							number_format($pt5,2,',','.'),
							number_format($pt6,2,',','.'),
							number_format($pt8,2,',','.'),
							number_format($pt7,2,',','.')
						);

						$pdf->Row($row, 'S');
						$row  = array("","","=======================","","","","","","","","");   	                             
						$xgen = $gen; // Guardar nueva partida generica
						$row  = array("","Partida  $xgen                                                "," ","","","","","","","","");
						$pdf->Row($row, 'S');                                   
						$pt1 = 0; $pt2 = 0; $pt3 = 0; $pt4= 0; $pt5 = 0; $pt6 = 0; $pt7 = 0; $pt8 = 0;
					}

					$par = $r->cod_partida;
					$gen = substr($r->cod_partida, 0, 4);

					$row = array(
							'', 
							$par, 
							utf8_decode($r->des_partida),
							number_format($r->ley_for,2,',','.'),
							number_format($r->mto_ley,2,',','.'),
							number_format($r->mto_pre,2,',','.'),
							number_format($r->mto_com,2,',','.'),
							number_format($r->mto_cau,2,',','.'),
							number_format($r->mto_pag,2,',','.'),
							number_format($r->bloqueado,2,',','.'),
							number_format($r->mto_dis,2,',','.')
						);
					$pdf->Row($row, 'S');                                                     

					// Acumulados Generales
					$t1 += $r->ley_for;
					$t2 += $r->mto_ley;
					$t3 += $r->mto_pre;
					$t4 += $r->mto_com;
					$t5 += $r->mto_cau;
					$t6 += $r->mto_pag;                           
					$t7 += $r->mto_dis;
					$t8 += $r->bloqueado ?? 0;

					// Acumulados Por Centro de Costo
					$ct1 += $r->ley_for;
					$ct2 += $r->mto_ley;
					$ct3 += $r->mto_pre;
					$ct4 += $r->mto_com;
					$ct5 += $r->mto_cau;
					$ct6 += $r->mto_pag;
					$ct7 += $r->mto_dis;
					$ct8 += $r->bloqueado ?? 0;

					// Acumulados Por Partida presupuestaria
					$pt1 += $r->ley_for;
					$pt2 += $r->mto_ley;
					$pt3 += $r->mto_pre;
					$pt4 += $r->mto_com;
					$pt5 += $r->mto_cau;
					$pt6 += $r->mto_pag;
					$pt7 += $r->mto_dis;
					$pt8 += $r->bloqueado ?? 0;

					if ($pdf->GetY() >= 175) {
						$this->pintar_encabezado_pdf($pdf, $data);
						$this->pintar_cabecera_columnas_pdf($pdf, $data, false);
					}
				}

				$row = array('','','_______________________________','_____________','_____________','____________','___________','___________','___________','___________','___________');
				$pdf->Row($row, 'S');

				// Imprimir total de Ultima partida
				$row = array(
							'','Total Partida ',
							$xgen . ' ......................................................',
							number_format($pt1,2,',','.'),
							number_format($pt2,2,',','.'),
							number_format($pt3,2,',','.'),
							number_format($pt4,2,',','.'),
							number_format($pt5,2,',','.'),
							number_format($pt6,2,',','.'),
							number_format($pt8,2,',','.'),
							number_format($pt7,2,',','.')
						);
				$pdf->Row($row, 'S');
				$row = array('','','','============','============','============','============','============','============','============','============');
				$pdf->Row($row, 'S');

				// Imprimir total de Ultimo Centro de Costo
				$row = array('Total Centro ',
							$xctro . '', '..............................................................',
							number_format($ct1,2,',','.'),
							number_format($ct2,2,',','.'),
							number_format($ct3,2,',','.'),
							number_format($ct4,2,',','.'),
							number_format($ct5,2,',','.'),
							number_format($ct6,2,',','.'),
							number_format($ct8,2,',','.'),
							number_format($ct7,2,',','.')
						);
				$pdf->Row($row, 'S');
				$row = array('','','','','','','','','');
				$pdf->Row($row, 'S');
				$row = array('','::::::::::::::::::::::::::','::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::',
							':::::::::::::::::::::::::::',':::::::::::::::::::::::::::',':::::::::::::::::::::::::::',':::::::::::::::::::::::::::',
							':::::::::::::::::::::::::::',':::::::::::::::::::::::::::',':::::::::::::::::::::::::::',':::::::::::::::::::::::::::');
				$pdf->Row($row, 'S');

				// Imprimir total de General
				$row = array('','TOTAL GENERAL . . .','',
							number_format($t1,2,',','.'),
							number_format($t2,2,',','.'),
							number_format($t3,2,',','.'),
							number_format($t4,2,',','.'),
							number_format($t5,2,',','.'),
							number_format($t6,2,',','.'),
							number_format($t8,2,',','.'),
							number_format($t7,2,',','.')
						);

				$pdf->Row($row, 'S');
				$pdf->Output('Resumen Presupuestario', 'I');
				exit;
			} else {
				dd('No se encontraron datos');
			}
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////// MAESTRO DE LEY ///////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////

	public function maestroleyCreate()
	{
		$periodosRep  = Registrocontrol::orderBy('ano_pro', 'desc')->get()->pluck('ano_pro', 'ano_pro');
		$meses        = $this->meses;
		$centrosCosto = CentroCosto::where('ano_pro', RegistroControl::periodoActual())->orderBy('id')->get();
		$partidas     = PartidaPresupuestaria::all();

		return view(
			'administrativo.meru_administrativo.formulacion.reportes.maestro_ley.maestroley', 
			compact('periodosRep', 'meses', 'centrosCosto', 'partidas')
		);
	}

	public function maestroleyStore(Request $request)
	{
		$anoPro        = $request->ano_pro;
		$mes           = $request->mes;
		$anoActual     = Registrocontrol::periodoActual();
		$periodoActual = Registrocontrol::where('ano_pro', $anoActual)->first();
		$cenCos        = $request->only('tip_cod','cod_pryacc','cod_obj','gerencia','unidad');
		$partida       = $request->only('cod_par', 'cod_gen', 'cod_esp', 'cod_sub');

		$periodoParalelo = Registrocontrol::where('ano_pro', $anoPro)
							->where('mes_pre', $mes)
							->where('sta_pre', '2')
							->first();

		if (($anoPro == $periodoActual->ano_pro && $mes == $periodoActual->mes_pre) || (!is_null($periodoParalelo) && $periodoParalelo->ano_pro == $anoPro && $periodoParalelo->mes_pre == $mes)) {
			$sql = MaestroLey::select(
					'cod_com',
					'ley_for',
					'mto_ley AS mto_mod',
					'mto_apa',
					'mto_pre',
					'mto_com',
					'mto_cau',
					'mto_pag',
					'mto_dis'
					)->where('ano_pro', $anoPro);
		} else {
			$sql =  MaestroLey::select(
						'pre_maestroley.cod_com',
						'pre_maestroley.ley_for',
						'pre_cierremensual.mto_mod',
						'pre_cierremensual.mto_apa',
						'pre_cierremensual.mto_pre',
						'pre_cierremensual.mto_com',
						'pre_cierremensual.mto_cau',
						'pre_cierremensual.mto_pag',
						'pre_cierremensual.mto_dis'
					)
					->join('pre_cierremensual', 
						function($join) use ($mes) {
							$join->on('pre_maestroley.ano_pro', '=', 'pre_cierremensual.ano_pro')
								->on('pre_maestroley.cod_com', '=', 'pre_cierremensual.cod_com')
								->on('pre_cierremensual.mes_pro', '=', DB::raw($mes));
						})
					->where('pre_maestroley.ano_pro', $anoPro);
		}

		foreach ($cenCos as $key => $ceco) {
			if (is_null($ceco)) {
				break;
			} else {
				$sql->where('pre_maestroley.' . $key, '=', $ceco);
			}
		}

		foreach ($partida as $key => $part) {
			if (is_null($part)) {
				break;
			} else {
				$sql->where('pre_maestroley.' . $key, '=', $part);
			}
		}

		$sql->orderBy('pre_maestroley.cod_com');

		$archivo   = 'Maestrol_Ley'; 
		$subtitulo = 'PRESUPUESTO ' . $this->meses[$mes] . ' - ' . $anoPro;

		if ($request->tipo_reporte == 'E') {
			return (new MaestroLeyExport)->titulo('HIDROBOLIVAR ' . $subtitulo)->setQuery($sql)->download($archivo . '.xlsx');
		} else {
			$data['tipo_hoja']           = 'C'; // C carta
			$data['orientacion']         = 'V'; // V Vertical
			$data['cod_normalizacion']   = '';
			$data['gerencia']            = '';
			$data['division']            = '';
			$data['titulo']              = 'HIDROBOLIVAR';
			$data['subtitulo']           = $subtitulo;
			$data['alineacion_columnas'] = ['C','R','R','R','R','R','R','R','R']; //C centrado R derecha L izquierda
			$data['ancho_columnas']      = [35,19,21,19,19,19,19,19,19]; //Ancho de Columnas
			$data['nombre_columnas']     = ['ESTRUCTURA','MONTO LEY','MODIFICADO','APARTADO','PRE-COMP','COMP','CAUSADO','PAGADO','DISPONIBLE'];
			$data['funciones_columnas']  = '';
			$data['fuente']              = 7;
			$data['registros_mostar']    = ['cod_com','ley_for','mto_mod','mto_apa','mto_pre','mto_com','mto_cau','mto_pag','mto_dis'];
			$data['nombre_documento']    = $archivo . '.pdf'; //Nombre de Archivo
			$data['con_imagen']          = true;
			$data['vigencia']            = '';
			$data['revision']            = '';
			$data['usuario']             = auth()->user()->name;
			$data['cod_reporte']         = '';
			$data['registros']           = $sql->get();

			$pdf = new Fpdf;
			$pdf->setTitle(utf8_decode('Listado de Partidas Presupuestarias'));
			$this->pintar_listado_pdf($pdf,$data);
			exit;
		}
	}

	//////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// EJECUCIÓN PRESUPUESTARIA //////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////////////////

	public function ejecucionCreate()
	{
		$periodosRep = Registrocontrol::orderBy('ano_pro', 'desc')->get()->pluck('ano_pro', 'ano_pro');
		$meses       = $this->meses;

		return view(
			'administrativo.meru_administrativo.formulacion.reportes.maestro_ley.ejecucion', 
			compact('periodosRep', 'meses')
		);
	}

	public function ejecucionStore(Request $request)
	{
		$anoPro          = $request->ano_pro;
		$mes             = $request->mes;
		$anoActual       = Registrocontrol::periodoActual();
		$periodoActual   = Registrocontrol::where('ano_pro', $anoActual)->first();

		$periodoParalelo = Registrocontrol::where('ano_pro', $anoPro)
							->where('mes_pre', $mes)
							->where('sta_pre', '2')
							->first();

		// Subconsulta más interna
		$sqlPreCie = DB::table('pre_cierremensual')
							->select('cod_com', DB::raw('(mto_com - mto_cau) AS xsalant'))
							->whereRaw('ano_pro = ? - 1', [$anoPro])
							->where('mes_pro', 12);

		// SubcOnsulta intermedia
		if (($mes == 13 && $anoActual == $anoPro) || ($mes == 13 && !is_null($periodoParalelo) && $anoPro == $periodoParalelo->ano_pro)) {
			$sqlMaestro = DB::table('pre_maestroley AS a')
							->select(
								'a.ano_pro', 
								'a.cod_com',
								'a.mto_dis'
							)
							->selectRaw('a.ley_for AS xasiini')
							->selectRaw('(COALESCE(a.mto_mod, 0) - COALESCE(b.xsalant, 0)) AS xnueasi')
							->selectRaw('(COALESCE(a.mto_com, 0) - COALESCE(a.mto_pre, 0) - COALESCE(b.xsalant, 0)) AS xnuecom')
							->selectRaw('(COALESCE(a.mto_mod, 0) - COALESCE(b.xsalant, 0)) - (COALESCE(a.mto_com, 0) - COALESCE(a.mto_pre, 0) - COALESCE(b.xsalant, 0)) AS xdisponible')
							->leftJoinSub($sqlPreCie, 'b', function($join) {
								$join->on('a.cod_com', '=', 'b.cod_com');
							})
							->where('a.ano_pro', DB::raw($anoPro));
		} else {
			$sqlMaestro = DB::table('pre_cierremensual AS a')
							->select(
								'a.ano_pro', 
								'a.cod_com',
								'a.mto_dis'
							)
							->selectRaw('a.ley_for AS xasiini')
							->selectRaw('(COALESCE(a.mto_mod, 0) - COALESCE(b.xsalant, 0)) AS xnueasi')
							->selectRaw('(COALESCE(a.mto_com, 0) - COALESCE(a.mto_pre, 0) - COALESCE(b.xsalant, 0)) AS xnuecom')
							->selectRaw('(COALESCE(a.mto_mod, 0) - COALESCE(b.xsalant, 0)) - (COALESCE(a.mto_com, 0) - COALESCE(a.mto_pre, 0) - COALESCE(b.xsalant, 0)) AS xdisponible')
							->leftJoinSub($sqlPreCie, 'b', function($join) {
								$join->on('a.cod_com', '=', 'b.cod_com');
							})
							->where('a.ano_pro', DB::raw($anoPro))
							->where('a.mes_pro', DB::raw($mes));
		}

		// Consulta completa
		$sql = DB::query()
					->select('a.ano_pro', 'c.des_con')
					->selectRaw('SUBSTRING(a.cod_com, 1, 14) AS cencos')
					->selectRaw('SUM(xasiini) AS asiini')
					->selectRaw('SUM(xnueasi) AS asimod')
					->selectRaw('SUM(xnueasi - mto_dis) AS ejecutado')
					->selectRaw('SUM(mto_dis) AS disponible')
					->fromSub($sqlMaestro, 'a')
					->join('pre_centrocosto AS c', function($join) use ($anoPro) {
						$join->on(DB::raw('SUBSTRING(a.cod_com, 1, 14)'), '=', 'c.cod_cencosto')
							->on('c.ano_pro', '=', DB::raw($anoPro))
							->on('cre_adi', '=', DB::raw("'0'"))
							->on('sta_reg', '=', DB::raw("'1'"));
					})
					->groupBy('a.ano_pro')
					->groupByRaw('SUBSTRING(a.cod_com, 1, 14)')
					->groupBy('c.des_con')
					->havingRaw('SUM(xasiini) > 0')
					->orderBy('c.des_con');

		$hoy       = today()->format('d/m/Y');
		$archivo   = 'Ejecucion_Prespuestaria';
		$subtitulo = 'EJECUCION PRESUPUESTARIA AL ' . $hoy . ' POR CENTRO DE COSTO';

		$data['tipo_hoja']           = 'C'; // C carta
		$data['orientacion']         = 'V'; // V Vertical
		$data['cod_normalizacion']   = '';
		$data['gerencia']            = '';
		$data['division']            = '';
		$data['titulo']              = 'HIDROBOLIVAR';
		$data['subtitulo']           = $subtitulo;
		$data['alineacion_columnas'] = ['C','L','R','R','R','R']; //C centrado R derecha L izquierda
		$data['ancho_columnas']      = [25,70,25,25,25,25]; //Ancho de Columnas
		$data['nombre_columnas']     = ['CENTRO','GERENCIA','ASIG. INICIAL','ASIG. MODIFICADA','EJECUTADO','DISPONIBLE'];
		$data['funciones_columnas']  = '';
		$data['fuente']              = 8;
		$data['registros_mostar']    = ['cencos', 'des_con', 'asiini', 'asimod', 'ejecutado', 'disponible'];
		$data['nombre_documento']    = $archivo . '.pdf'; //Nombre de Archivo
		$data['con_imagen']          = true;
		$data['vigencia']            = '';
		$data['revision']            = '';
		$data['usuario']             = auth()->user()->name;
		$data['cod_reporte']         = '';
		$data['registros']           = json_decode(json_encode($sql->get()), true);

		$pdf = new Fpdf;
		$pdf->setTitle(utf8_decode('Ejecución Prespuestaria'));
		$this->pintar_listado_pdf($pdf,$data);
		exit;
	}
}