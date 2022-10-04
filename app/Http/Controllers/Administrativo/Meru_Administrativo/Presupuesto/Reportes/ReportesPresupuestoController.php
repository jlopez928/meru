<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Presupuesto\Reportes;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoSolicitudTraspaso;
use App\Exports\Administrativo\Meru_Administrativo\Presupuesto\OperacionesPresupuestariasExport;
use App\Exports\Administrativo\Meru_Administrativo\Presupuesto\SolicitudesTraspasoExport;
use App\Exports\FromQueryExport;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\Compras\PrePartidaGasto;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\MovPresupuestario;
use App\Support\Fpdf;
use App\Traits\ReportFpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesPresupuestoController extends Controller
{
    // Imprimir consulta con valores del binding
    //dd(\Str::replaceArray('?', $sql->getBindings(), $sql->toSql()));

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

    public function operacionesCreate()
	{
		$operaciones = [
            'T' => 'Todos',
            'P' => 'Pre-compromisos',
            'C' => 'Compromisos',
            'A' => 'Causados',
            'G' => 'Pagados',
        ];

		return view(
			'administrativo.meru_administrativo.presupuesto.reportes.operaciones', 
			compact('operaciones')
		);
	}

    public function operacionesStore(Request $request)
	{
        $operacion   = $request->get('operacion');
        $tipoReporte = $request->get('tipo_reporte');
        $fecIni      = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('fec_ini'));
        $fecFin      = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('fec_fin'));
        $anio        = $fecIni->format('Y');
        $movimiento  = [
            'T' => 'TODOS LOS MOVIMIENTOS',
            'P' => 'PRE-COMPROMISOS',
            'C' => 'COMPROMISOS',
            'A' => 'CAUSADOS',
            'G' => 'PAGADOS',
        ][$operacion];
        $orden = false;

        $subConsulta = DB::table('pre_movimientos AS a')
                        ->select(
                            'a.ano_pro', 
                            'a.num_reg', 
                            'a.sol_tip', 
                            'a.concepto', 
                            'a.num_doc', 
                            'a.fecha', 
                            'a.tip_ope',
                            DB::raw('z.nro_fac AS factura'),
                            DB::raw(
                                "CASE a.sol_tip 
                                    WHEN 'RM' THEN x.rif_ben
                                    WHEN 'PD' THEN (b.rif_prov)
                                    WHEN 'OC' THEN c.rif_prov
                                    WHEN 'OP' THEN y.rif_ben
                                    WHEN 'RI' THEN e.rif_ben
                                    WHEN 'IF' THEN h.rif_prov
                                    WHEN 'NE' THEN i.rif_prov
                                    WHEN 'AJ' THEN k.rif_prov
                                    WHEN 'RF' THEN l.rif_ben
                                    WHEN 'VI' THEN m.cedula
                                    WHEN 'FA' THEN n.rif_ben
                                    WHEN 'PR' THEN 'PROVISION DE NOMINA'
                                    WHEN 'AN' THEN o.rif_prov
                                    WHEN 'CO' THEN p.rif_prov
                                    WHEN 'NO' THEN 'NOMINA'
                                    WHEN 'LE' THEN r.cod_emp
                                END AS rif"
                            ),
                            DB::raw(
                                "CASE a.sol_tip
                                    WHEN 'PD' THEN 'Pago Directo'
                                    WHEN 'OC' THEN 'Orden de Compra'
                                    WHEN 'OP' THEN 'Orden de Pago'
                                    WHEN 'RI' THEN 'Reintegro'
                                    WHEN 'RM' THEN 'Reembolso Medico'
                                    WHEN 'IF' THEN 'Factura'
                                    WHEN 'NE' THEN 'Nota de entrega'
                                    WHEN 'AJ' THEN 'Ajuste'
                                    WHEN 'RF' THEN 'Rendicion de caja chica'
                                    WHEN 'VI' THEN 'Viatico'
                                    WHEN 'FA' THEN 'Anticipo a Empleado'
                                    WHEN 'PR' THEN 'Provision de nomina'
                                    WHEN 'AN' THEN 'Anticipo a Proveedor'
                                    WHEN 'CO' THEN 'Contrato de Obras'
                                    WHEN 'AD' THEN 'Ajuste al Compromiso'
                                    WHEN 'NO' THEN 'Nomina'
                                    WHEN 'CA' THEN 'Caja de Ahorro'
                                    WHEN 'CC' THEN 'Caja Chica'
                                    WHEN 'DI' THEN 'Declaracion de Iva'
                                    WHEN 'FC' THEN 'Fondo de Caja Comercial'
                                    WHEN 'LE' THEN 'Liquidacion a Empleados'
                                END AS tipo_solicitud"
                            ),
                            DB::raw(
                                "CASE a.tip_ope
                                    WHEN '8' THEN  'Pre-Compromiso'
                                    WHEN '9' THEN  'Reverso del Pre-Compromiso'
                                    WHEN '10' THEN 'Compromiso'
                                    WHEN '20' THEN 'Reverso del Compromiso'
                                    WHEN '30' THEN 'Causado Directo'
                                    WHEN '40' THEN 'Reverso de Causado Directo'
                                    WHEN '50' THEN 'Causado sobre Compromiso'
                                    WHEN '60' THEN 'Reverso de Causado sobre Compromiso'
                                    WHEN '61' THEN 'Pagado Directo'
                                    WHEN '62' THEN 'Reverso de Pagado Directo'
                                    WHEN '70' THEN 'Pagado'
                                    WHEN '80' THEN 'Reverso de Pagado'
                                    WHEN '21' THEN 'Disminución por Ajuste de Compromisos (Años anteriores)'
                                    WHEN '22' THEN 'Aumento por Ajuste de Compromisos (Años anteriores)'
                                    WHEN '23' THEN 'Aumento por Ajuste de Compromiso No Causado'
                                    WHEN '24' THEN 'Disminución por Ajuste de Compromiso No Causado'
                                    WHEN '41' THEN 'Disminución por Ajustes de Causado Directo (Años Anteriores)'
                                    WHEN '42' THEN 'Aumento por Ajustes de Causado Directo (Años Anteriores)'
                                    WHEN '71' THEN 'Ajuste al Causado Directo'
                                    WHEN '72' THEN 'Reverso de Ajuste al Causado Directo'
                                    WHEN '73' THEN 'Ajuste al Pagado'
                                    WHEN '74' THEN 'Reverso de Ajuste al Pagado'
                                    WHEN '75' THEN 'Ajuste al Pagado Directo'
                                    WHEN '76' THEN 'Reverso Ajuste al Pagado Directo'
                                    WHEN '77' THEN 'Ajuste al Pre-Compromiso'
                                    WHEN '83' THEN 'Ajuste por Redistribucion Presupuestaria (Causado Directo)'
                                    WHEN '90' THEN 'Ajuste al Compromiso'
                                    WHEN '91' THEN 'Ajuste al Causado'
                                    WHEN '92' THEN 'Reverso de Ajuste al Compromiso'
                                    WHEN '91' THEN 'Reverso de Ajuste al Causado'
                                    WHEN '95' THEN 'Ajuste al Compromiso Básico'
                                    WHEN '96' THEN 'Reverso de Ajuste al Compromiso Básico'
                                END AS operacion"
                            ),
                            'a.cod_par',
                            'a.cod_gen',
                            'a.cod_esp',
                            'a.cod_sub',
                            'a.cod_com',
                            'a.mto_tra',
                            'a.mto_transaccion',
                            'a.nota_entrega',
                            'a.ano_doc'
                        )
                        ->leftJoin('op_solservicio AS b', function($join) {
                            $join->on('a.num_doc', '=', 'b.xnro_sol')
                                ->on('a.ano_doc', '=', 'b.ano_pro')
                                ->where('a.sol_tip', '=', 'PD');
                        })
                        ->leftJoin('com_encordencompra AS c', function($join) {
                            $join->on('a.num_doc', '=', 'c.xnro_ord')
                                ->on('a.ano_doc', '=', 'c.ano_pro')
                                ->where('a.sol_tip', '=', 'OC')
                                ->whereNotIn('a.tip_ope', [8,9]);
                        })
                        ->leftJoin('reintegro AS e', function($join) {
                            $join->on('a.num_doc', '=', 'e.nro_reintegro')
                                ->on('a.ano_doc', '=', 'e.ano_pro')
                                ->where('a.sol_tip', '=', 'RI');
                        })
                        ->leftJoin('facturas AS h', function($join) {
                            $join->on('a.num_doc', '=', 'h.nro_doc')
                                ->on(DB::raw('trim(a.num_fac)'), '=', DB::raw('trim(h.num_fac)'))
                                ->on(DB::raw('trim(a.num_fac)'), '=', DB::raw('trim(h.num_fac)'))
                                ->on('a.ano_doc', '=', 'h.ano_sol')
                                ->where('a.sol_tip', '=', 'IF');
                        })
                        ->leftJoin('com_encordencompra AS i', function($join) {
                            $join->on('a.num_doc', '=', 'i.xnro_ord')
                                ->on('a.ano_doc', '=', 'i.ano_pro')
                                ->where('a.sol_tip', '=', 'NE');
                        })
                        ->leftJoin('facturas AS k', function($join) {
                            $join->on('a.num_doc', '=', 'k.nro_doc')
                                ->on(DB::raw('trim(a.num_fac)'), '=', DB::raw('trim(k.num_fac)'))
                                ->on('a.ano_doc', '=', 'k.ano_pro')
                                ->where('a.sol_tip', '=', 'AJ');
                        })
                        ->leftJoin('tes_rendicionfondos AS l', function($join) {
                            $join->on('a.num_doc', '=', DB::raw('trim(l.num_rel)'))
                                ->on('a.ano_doc', '=', 'l.ano_pro')
                                ->where('a.sol_tip', '=', 'RF');
                        })
                        ->leftJoin('via_encvia AS m', function($join) {
                            $join->on(DB::raw('trim(a.num_doc)'), '=', DB::raw('trim(m.clave)'))
                                ->on('a.ano_doc', '=', 'm.ano_pro')
                                ->where(DB::raw('trim(a.sol_tip)'), '=', 'VI');
                        })
                        ->leftJoin('tes_rendicion_emp AS n', function($join) {
                            $join->on(DB::raw('trim(a.num_doc)'), '=', DB::raw('trim(n.num_rel)'))
                                ->on('a.ano_doc', '=', 'n.ano_pro')
                                ->where(DB::raw('trim(a.sol_tip)'), '=', 'FA');
                        })
                        ->leftJoin('cxp_solpagoanticipoprov AS o', function($join) {
                            $join->on(DB::raw('trim(a.num_doc)'), '=', DB::raw('trim(o.num_anticipo)'))
                                ->on('a.ano_doc', '=', 'o.ano_form')
                                ->where(DB::raw('trim(a.sol_tip)'), '=', 'AN');
                        })
                        ->leftJoin('op_solservicio AS p', function($join) {
                            $join->on(DB::raw('trim(a.num_doc)'), '=', DB::raw('trim(p.xnro_sol)'))
                                ->on('a.ano_doc', '=', 'p.ano_pro')
                                ->where(DB::raw('trim(a.sol_tip)'), '=', 'CO');
                        })
                        ->leftJoin('solpagoliq AS r', function($join) {
                            $join->on(DB::raw('trim(a.num_doc)'), '=', DB::raw('trim(r.ord_pag)'))
                                ->on('a.ano_doc', '=', 'r.ano_pro')
                                ->where(DB::raw('trim(a.sol_tip)'), '=', 'LE');
                        })
                        ->leftJoin('reembolso AS x', function($join) {
                            $join->on('x.ano_pro', '=', 'a.ano_doc')
                                ->on(DB::raw('cast(x.nro_reembolso AS text)'), '=', DB::raw('trim(a.num_doc)'))
                                ->where(DB::raw('trim(a.sol_tip)'), '=', 'RM');
                        })
                        ->leftJoin('tes_ordpago AS y', function($join) {
                            $join->on('y.ano_pro', '=', 'a.ano_doc')
                                ->on(DB::raw('cast(y.ord_pag AS text)'), '=', DB::raw('trim(a.num_doc)'))
                                ->where(DB::raw('trim(a.sol_tip)'), '=', 'OP');
                        })
                        ->leftJoin('tes_detordpago AS z', function($join) {
                            $join->on('y.ano_pro', '=', 'z.ano_pro')
                                ->on('y.ord_pag', '=', 'z.nro_ord');
                                
                        })
                        ->where('a.ano_pro', $anio)
                        ->where('a.cierre', '1')
                        ->whereBetween('a.fecha', [$fecIni->format('Y/m/d'), $fecFin->format('Y/m/d')])
                        ->orderBy('a.fecha');

        switch($operacion) {
            case 'P': // Pre-Compromisos
                $subConsulta->whereIn('a.tip_ope', [8,9,77,10,20])
                    ->where('sol_tip', 'OC')
                    ->where('a.cod_com', '!=', '02.01.00.02.00.03.18.01.00');
                    $orden = true;
                break;
            case 'C': // Compromisos
                $subConsulta->whereIn('a.tip_ope', [10,20,21,22,23,24,30,40,41,42,61,62,71,72,75,76,83,90,92,94,95,96]);
                break;
            case 'A': // Causados
                $subConsulta->whereIn('a.tip_ope', [30,40,41,42,50,60,61,62,71,72,75,76,83,91,93]);
                break;
            case 'G': // Pagados
                $subConsulta->whereIn('a.tip_ope', [61,62,70,73,74,80,75,76]);
                break;
            default: // Todos
                $subConsulta->whereIn('a.tip_ope', [8,9,10,20,21,22,23,24,30,40,41,42,50,60,61,62,70,71,72,73,75,76,77,80,83,90,91,92,93,94,95,96]);
                break;
        }

        if ($operacion == 'P') {
            $monto = "CASE 
                        WHEN i.tip_ope = 10 AND i.sol_tip = 'OC' THEN w.mto_pcom
                        WHEN i.tip_ope = 20 AND i.sol_tip = 'OC' THEN w.mto_pcom												
                        ELSE i.mto_tra
                    END AS monto";	
        } else {
            $monto = "CASE
                        WHEN i.tip_ope IN (90,92,94) THEN i.mto_transaccion  
                        ELSE i.mto_tra
                    END AS monto";
        }

        $sql = DB::query()
                ->select(
                    DB::raw('UPPER(i.operacion) AS tipo_solicitud'), 
                    'i.tip_ope', 
                    DB::raw('UPPER(i.operacion) AS operacion'),
                    'i.concepto',
                    DB::raw("i.ano_doc || '-' || i.num_doc AS num_doc"), 
                    'i.fecha', 
                    'i.rif',
                    DB::raw("
                        CASE i.rif 
                        WHEN 'NOMINA' THEN 'NOMINA'
                        WHEN 'PROVISION DE NOMINA' THEN 'PROVISION DE NOMINA'
                        ELSE UPPER(j.nom_ben) END AS nombre
                    "),
                    DB::raw('i.factura'),
                    DB::raw('i.cod_par AS partida'),
                    DB::raw('i.cod_gen AS generica'),
                    DB::raw('i.cod_esp AS especifica'),
                    DB::raw('i.cod_sub AS Subespecifica'),
                    DB::raw('i.cod_com AS estructura'),
                    DB::raw($monto),
                    DB::raw('k.nro_forpago AS cheque'),
                    'i.mto_transaccion',
                    'i.nota_entrega',
                    'i.sol_tip'
                )
                ->fromSub($subConsulta, 'i')
                ->leftJoin('tes_beneficiarios AS j', DB::raw('trim(i.rif)'), '=', 'j.rif_ben')
                ->leftJoin('tes_ordpago AS k', function($join) {
                    $join->on('i.num_doc', '=', DB::raw("to_char(k.ord_pag,'999999')"))
                        ->on('i.ano_pro', '=', 'k.ano_pro')
                        ->where('i.sol_tip', 'OP');
                })
                ->leftJoin('com_detgastosordencompra AS w', function($join) {
                    $join->on('i.num_doc', '=', 'w.xnro_ord')
                        ->on('i.ano_doc', '=', 'w.ano_pro')
                        ->on('i.cod_com', '=', 'w.cod_com')
                        ->where('w.mto_pcom', '!=', 0);
                });

        if ($orden) {
            $sql->orderBy('tip_ope')
                ->orderBy('fecha');
        } else {
            $sql->orderBy('fecha');
        }

        $resOpe = $sql->get();
        $rows = [];

        foreach ($resOpe AS $ope) {
            $accion = '';
            $monto  = $ope->monto;

            if ($operacion == 'P' && in_array($ope->tip_ope, [9,10])) {
                $accion = '-';
            } elseif ($operacion == 'C' && in_array($ope->tip_ope, [20,21,24,40,41,62,75,71,96])) {
                $accion = '-';
            } elseif ($operacion == 'A' && in_array($ope->tip_ope, [40,41,60,62,91,75,71])) {
                $accion = '-';
            } elseif ($operacion == 'G' && in_array($ope->tip_ope, [62,80,75,73])) {
                $accion = '-';
            } elseif ($operacion == 'T' && in_array($ope->tip_ope, [20,21,24,40,41,62,75,71,96])) {
                $accion = '-';
            }

            if (in_array($ope->tip_ope, [90,91,92,93,94,95])) {
                $monto = abs($ope->mto_transaccion);

                if ($ope->mto_transaccion < 0) {
                    $accion = '-';
                }
            }

            $numDoc = $ope->num_doc;

            if ($operacion == 'A' && $ope->sol_tip == 'NE') {
                $numDoc = $ope->nota_entrega;
            }

            $rows[] = [
                'tipo_solicitud' => ($tipoReporte == 'E') ? $ope->tipo_solicitud : utf8_decode($ope->tipo_solicitud),
                'num_doc'        => $ope->num_doc,
                'fecha'          => \Carbon\Carbon::createFromFormat('Y-m-d', $ope->fecha)->format('d/m/Y'),
                'nombre'         => utf8_decode($ope->nombre),
                'monto'          => $accion . number_format($ope->monto, 2, ',', '.'),
                'partida'        => $ope->partida,
                'generica'       => $ope->generica,
                'especifica'     => $ope->especifica,
                'subespecifica'  => $ope->subespecifica,
                'estructura'     => $ope->estructura
            ];
        }

        $archivo = 'Operaciones_Presupuesto';

        if ($tipoReporte == 'E') {
            return (new OperacionesPresupuestariasExport($rows))
                    ->titulo('MOVIMIENTOS PRESUPUESTARIOS: ' . $movimiento)
                    ->rango('DEL ' . $fecIni->format('d/m/Y')  . ' AL ' . $fecFin->format('d/m/Y'))
                    ->download($archivo . '.xlsx');
        } else {
            $data['tipo_hoja']           = 'A4';
            $data['orientacion']         = 'H';
            $data['cod_normalizacion']   = '';
            $data['gerencia']            = '';
            $data['division']            = '';
            $data['titulo']              = 'HIDROBOLIVAR';
            $data['subtitulo']           = 'Subtitulo';
            $data['alineacion_columnas'] = ['L','L','C','L','R','C','C','C','C','C'];
            $data['ancho_columnas']      = [60,25,20,55,25,15,15,15,15,45];
            $data['nombre_columnas']     = ['OPERACION','DOCUMENTO','FECHA','NOMBRE','MONTO MOV','PA','GN','ESP','SUB.ESP','ESTRUCTURA'];
            $data['funciones_columnas']  = '';
            $data['fuente']              = 8;
            $data['registros_mostar']    = ['tipo_solicitud','num_doc','fecha','nombre','monto','partida','generica','especifica','subespecifica','estructura'];
            $data['nombre_documento']    = $archivo . '.pdf';
            $data['con_imagen']          = true;
            $data['vigencia']            = '';
            $data['revision']            = '';
            $data['usuario']             = auth()->user()->name;
            $data['cod_reporte']         = '';
            $data['registros']           = $rows;

            $pdf = new Fpdf;
            $pdf->SetLeftMargin(5);
            $pdf->setTitle(utf8_decode('Operaciones de Presupuesto'));
            $this->pintar_listado_pdf($pdf,$data,false);
            exit;
        }
	}

    public function diferenciasCreate()
    {
        $periodosRep  = Registrocontrol::orderBy('ano_pro', 'desc')->get()->pluck('ano_pro', 'ano_pro');
		$meses        = $this->meses;
        $criterios    = [
            1 => 'Estructuras Negativas',
            2 => 'Causado > Compromiso',
            3 => 'Pagado > Causado'
        ];

        return view(
            'administrativo.meru_administrativo.presupuesto.reportes.diferencias',
            compact('periodosRep', 'meses', 'criterios')
        );
    }

    public function diferenciasStore(Request $request)
    {
        $anoPro   = $request->get('ano_pro');
        $mes      = $request->get('mes');
        $criterio = $request->get('criterio');

        $mesPre = RegistroControl::where('ano_pro', $anoPro)->first();
        $nmes   = $mesPre ? $mesPre->mes_pre : 0;

        $anioPre = RegistroControl::where([
            ['ano_pro', '=', $anoPro],
            ['sta_pre', '!=', '0']
        ])->first();

        if ($nmes == $mes && !empty($anioPre)) {
            $fecha = \Carbon\Carbon::createFromDate($anoPro, $mes);

            $preCierre = DB::select('SELECT * FROM cierrepreliminar(?,?,?,?)', [
                $anoPro, 
                $mes, 
                $fecha->firstOfMonth()->format('y-m-d'), 
                $fecha->lastOfMonth()->format('y-m-d'),
                \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email)
            ]);

            if ($preCierre) {
                $sql = DB::table('pre_cierrepreliminar AS b')
                    ->select(
                        'b.cod_com',
                        'b.ley_for',
                        'b.mto_mod',
                        'b.mto_apa',
                        'b.mto_pre',
                        'b.mto_com', 
                        'b.mto_cau',
                        'b.mto_pag',
                        'b.mto_dis'
                    )
                    ->where('b.ano_pro', $anoPro);
            }
        } else {
            $sql = DB::table('pre_cierremensual AS b')
                    ->select(
                        'b.cod_com',
                        'b.ley_for',
                        'b.mto_mod',
                        'b.mto_apa',
                        'b.mto_pre',
                        'b.mto_com', 
                        'b.mto_cau',
                        'b.mto_pag',
                        'b.mto_dis'
                    )
                    ->where([
                        ['b.ano_pro', '=', $anoPro],
                        ['b.mes_pro', '=', $mes]
                    ]);
        }

        if ($criterio == 1) { // Partidas en Negativo
            $sql->where(function ($query) {
                $query->where('b.mto_dis', '<', 0)
                    ->orWhere('b.mto_pre', '<', 0)
                    ->orWhere('b.mto_com', '<', 0)
                    ->orWhere('b.mto_cau', '<', 0)
                    ->orWhere('b.mto_pag', '<', 0);
            });
        } elseif ($criterio == 2) { // Partidas con Causados Mayor a Compromisos
            $sql->where('b.mto_com', '<', 'b.mto_cau');
        } elseif ($criterio == 3) { // Partidas con Pagados Mayor a Causados
            $sql->where('b.mto_cau', '<', 'b.mto_pag');
        }

        $sql->orderByRaw('1,2,3,4,5,6,7,8,9');

        $desc = [
            1 => 'ESTRUCTURAS NEGATIVAS',
            2 => 'CAUSADO > COMPROMISO',
            3 => 'PAGADO > CAUSADO',
        ][$criterio];

        $titulo    = 'PRESUPUESTO: ' . $desc;
        $subtitulo = 'PERIODO: ' . $anoPro . ' MES: ' . $this->meses[$mes];
        $archivo   = 'Diferencias_Prespuestarias';

        if ($request->get('tipo_reporte') == 'E') {
            $data = [
                'query'      => $sql,
                'titulo'     => [$titulo, $subtitulo],
                'ancho'      => [30,20,20,20,20,20,20,20,20],
                'alineacion' => ['C','R','R','R','R','R','R','R','R'],
                'formatos'   => ['T','N','N','N','N','N','N','N','N'],
                'columnas'   => ['ESTRUCTURA','MONTO LEY','MODIFICADO','APARTADO','PRE-COMP','COMP','CAUSADO','PAGADO','DISPONIBLE']
            ];

            return (new FromQueryExport($data))->download($archivo . '.xlsx');
        } else {
            $data['tipo_hoja']           = 'A4';
            $data['orientacion']         = 'V';
            $data['cod_normalizacion']   = '';
            $data['gerencia']            = '';
            $data['division']            = '';
            $data['titulo']              = $titulo;
            $data['subtitulo']           = $subtitulo;
            $data['alineacion_columnas'] = ['C','R','R','R','R','R','R','R','R'];
            $data['ancho_columnas']      = [30,21,21,21,21,21,21,21,21];
            $data['nombre_columnas']     = ['ESTRUCTURA','MONTO LEY','MODIFICADO','APARTADO','PRE-COMP','COMP','CAUSADO','PAGADO','DISPONIBLE'];
            $data['funciones_columnas']  = '';
            $data['fuente']              = 7;
            $data['registros_mostar']    = ['cod_com','ley_for','mto_mod','mto_apa','mto_pre','mto_com','mto_cau','mto_pag','mto_dis'];
            $data['nombre_documento']    = $archivo . '.pdf';
            $data['con_imagen']          = true;
            $data['vigencia']            = '';
            $data['revision']            = '';
            $data['usuario']             = auth()->user()->name;
            $data['cod_reporte']         = '';
            $data['registros']           = json_decode(json_encode($sql->get()), true);

            $pdf = new Fpdf;
            $pdf->SetLeftMargin(5);
            $pdf->setTitle(utf8_decode('Ajustes Presupuestarios'));
            $this->pintar_listado_pdf($pdf,$data,false);
            exit;
        }


    }

    public function consolidadoPartidasCreate()
    {
        $periodosRep  = Registrocontrol::orderBy('ano_pro', 'desc')->get()->pluck('ano_pro', 'ano_pro');
		$meses        = $this->meses;
		$partidas     = PartidaPresupuestaria::all();

        return view(
            'administrativo.meru_administrativo.presupuesto.reportes.consolidado_partidas',
            compact('periodosRep', 'meses', 'partidas')
        );
    }

    public function consolidadoPartidasStore(Request $request)
    {
        $nmes    = 0;
        $anoPro  = $request->get('ano_pro');
        $mes     = $request->get('mes');
        $partida = $request->only('cod_par', 'cod_gen', 'cod_esp', 'cod_sub');

        $anoActual  = Registrocontrol::periodoActual();
        $periodoReq = Registrocontrol::where('ano_pro', $anoPro)->first();

        if ($periodoReq) {
            $nmes = $periodoReq->mes_pre;
        }

        $periodoParalelo = Registrocontrol::where('ano_pro', $anoPro)
							->where('mes_pre', $mes)
							->where('sta_pre', '2')
							->first();

        if (($anoPro == $anoActual && $mes == $nmes) || (!is_null($periodoParalelo) && $periodoParalelo->ano_pro == $anoPro && $periodoParalelo->mes_pre == $mes)) {
            $sql = DB::table('pre_maestroley AS a')
                    ->select(
                        'a.cod_par',
                        'a.cod_gen',
                        'a.cod_esp',
                        'a.cod_sub',
                        DB::raw('sum(a.ley_for) AS ley_for'),
                        DB::raw('sum(a.mto_ley) AS mto_mod'),
                        DB::raw('sum(a.mto_com) AS mto_com'),
                        DB::raw('sum(a.mto_cau) AS mto_cau'),
                        DB::raw('sum(a.mto_pag) AS mto_pag'),
                        DB::raw('sum(a.mto_dis) AS mto_dis'),
                        DB::raw('sum(a.mto_pre) AS mto_pre')
                    )
                    ->where('a.ano_pro', $anoPro);
        } else {
            $sql = DB::table('pre_maestroley As a')
                    ->select(
                        'a.cod_par',
                        'a.cod_gen',
                        'a.cod_esp',
                        'a.cod_sub',
                        DB::raw('sum(a.ley_for) AS ley_for'),
                        DB::raw('sum(b.mto_mod) AS mto_mod'),
                        DB::raw('sum(b.mto_com) AS mto_com'),
                        DB::raw('sum(b.mto_cau) AS mto_cau'),
                        DB::raw('sum(b.mto_pag) AS mto_pag'),
                        DB::raw('sum(b.mto_dis) AS mto_dis'),
                        DB::raw('sum(b.mto_pre) AS mto_pre')
                    )
                    ->join('pre_cierremensual AS b', function ($join) use ($mes) {
                        $join->on('a.ano_pro','=','b.ano_pro')
                            ->on('a.cod_com','=','b.cod_com')
                            ->where('b.mes_pro', $mes);
                    })->where('a.ano_pro', $anoPro);
        }

        foreach ($partida as $key => $part) {
			if (is_null($part)) {
				break;
			} else {
				$sql->where('a.' . $key, '=', $part);
			}
		}

        $sql->groupBy(['a.cod_par','a.cod_gen','a.cod_esp','a.cod_sub',])
            ->orderBy('a.cod_par')
            ->orderBy('a.cod_gen')
            ->orderBy('a.cod_esp')
            ->orderBy('a.cod_sub');

        $res = $sql->get();

        if ($res->count() > 0) {
            $pdf = new Fpdf;
            $pdf->AliasNbPages();
            $pdf->SetLeftMargin(5);
            $pdf->setTitle(utf8_decode('Consolidad de Partidas'));
            $pdf->SetAuthor(auth()->user()->name);
            $pdf->SetAutoPageBreak(true, 5);

            $data['tipo_hoja']           = 'A4';
            $data['orientacion']         = 'H';
            $data['cod_normalizacion']   = '';
            $data['gerencia']            = '';
            $data['division']            = '';
            $data['titulo']              = 'HIDROBOLIVAR';
            $data['subtitulo']           = 'CONSOLIDADO POR PARTIDAS - ' . $this->meses[$mes] . ' ' . $anoPro;
            $data['alineacion_columnas'] = ['L','L','R','R','R','R','R','R','R'];
            $data['ancho_columnas']      = [25,60,28,28,28,28,28,28,28];
            $data['nombre_columnas']     = ['PARTIDA','DESCRIPCION','MONTO FORMULADO','MONTO MODIFICADO','PRE-COMPROMETIDO','COMPROMETIDO','CAUSADO','PAGADO','DISPONIBLE'];
            $data['funciones_columnas']  = '';
            $data['fuente']              = 7;
            $data['registros_mostar']    = [];
            $data['nombre_documento']    = 'Consolidado_Partidas.pdf';
            $data['con_imagen']          = true;
            $data['vigencia']            = '';
            $data['revision']            = '';
            $data['usuario']             = auth()->user()->name;
            $data['cod_reporte']         = '';
            $data['registros']           = [];

            $this->pintar_encabezado_pdf($pdf, $data);
            $this->pintar_cabecera_columnas_pdf($pdf, $data, false);

            $par_actual   = 0;
            $par_anterior = 0;
            $t1 = 0; $t2 = 0; $t3 = 0; $t4 = 0; $t5 = 0; $t6 = 0; $t7 = 0; // Totales Generales
            $pt1 = 0; $pt2 = 0; $pt3 = 0; $pt4 = 0; $pt5 = 0; $pt6 = 0; $pt7 = 0; // Totales Por Partida Presupuestaria

            foreach ($res as $r) {
                $pdf->SetWidths([25,60,28,28,28,28,28,28,28]);
                $pdf->SetAligns(['L','L','R','R','R','R','R','R','R']);
                $pdf->SetFont('Arial','',7);

                // Acumulados Generales
                $t1 += $r->ley_for;
                $t2 += $r->mto_mod;
                $t3 += $r->mto_com;
                $t4 += $r->mto_cau;
                $t5 += $r->mto_pag;
                $t6 += $r->mto_dis;
                $t7 += $r->mto_pre;

                // Probar condicional Juan
                if ($par_actual == 0) {
                    $par_actual   = $r->cod_par;
                    $par_anterior = $r->cod_par;

                    $par = '4.' . \Str::padLeft($r->cod_par, 2, '0');
                    $row = array('Partida '.$par,'','','','','','','','');
                    $pdf->Row($row, 'S');
                } else {
                    $par_anterior = $par_actual;
                    $par_actual   = $r->cod_par;
                }

                if ($par_actual != $par_anterior) {
                    $row = array(
                        '',
                        '',
                        '________________',
                        '________________',
                        '_______________',
                        '_______________',
                        '_______________',
                        '_______________',
                        '_______________'
                    );
                    $pdf->Row($row, 'S');
                
                    $par = '4.' . \Str::padLeft($par_anterior, 2, '0');
                    $row = array(
                        '',
                        'Total Partida ' . $par,
                        number_format($pt1,2,',','.'),
                        number_format($pt2,2,',','.'),
                        number_format($pt7,2,',','.'),
                        number_format($pt3,2,',','.'),
                        number_format($pt4,2,',','.'),
                        number_format($pt5,2,',','.'),
                        number_format($pt6,2,',','.')
                    );
                    $pdf->Row($row, 'S');
                    
                    $row = array('','','','','','','','','');
                    $pdf->Row($row, 'S');

                    $par = '4.' . \Str::padLeft($par_actual, 2, '0');
                    $row = array('Partida ' . $par,'','','','','','','');

                    $pdf->Row($row, 'S');
                    $pt1 = 0; $pt2 = 0; $pt3 = 0; $pt4 = 0; $pt5 = 0; $pt6 = 0; $pt7 = 0;
                }

                $partida = PrePartidaGasto::where([
                    ['tip_cod', '=', 4],
                    ['cod_par', '=', $r->cod_par],
                    ['cod_gen', '=', $r->cod_gen],
                    ['cod_esp', '=', $r->cod_esp],
                    ['cod_sub', '=', $r->cod_sub],
                ])->first();

                $despar = $partida ? $partida->des_con : 'No Existe';
                $par = '4.' . PartidaPresupuestaria::generarCodPartida($r->cod_par, $r->cod_gen, $r->cod_esp, $r->cod_sub);
                $row = array(
                    $par,
                    $despar,
                    number_format($r->ley_for,2,',','.'),
                    number_format($r->mto_mod,2,',','.'),
                    number_format($r->mto_pre,2,',','.'),
                    number_format($r->mto_com,2,',','.'),
                    number_format($r->mto_cau,2,',','.'),
                    number_format($r->mto_pag,2,',','.'),
                    number_format($r->mto_dis,2,',','.')
                );
                $pdf->Row($row, 'S');

                // Acumulados Por Partida presupuestaria
                $pt1 += $r->ley_for;
                $pt2 += $r->mto_mod;
                $pt3 += $r->mto_com;
                $pt4 += $r->mto_cau;
                $pt5 += $r->mto_pag;
                $pt6 += $r->mto_dis;
                $pt7 += $r->mto_pre;

                if ($pdf->GetY() >= 175) {
                    $this->pintar_encabezado_pdf($pdf, $data);
                    $this->pintar_cabecera_columnas_pdf($pdf, $data, false);
                }
            }

            $row = array(
                '',
                '',
                '________________',
                '________________',
                '_______________',
                '_______________',
                '_______________',
                '_______________',
                '_______________'
            );
            $pdf->Row($row, 'S');

            $par = '4.' . \Str::padLeft($par_anterior, 2, '0');
            $row = array(
                '',
                'Total Partida ' . $par,
                number_format($pt1,2,',','.'),
                number_format($pt2,2,',','.'),
                number_format($pt7,2,',','.'),
                number_format($pt3,2,',','.'),
                number_format($pt4,2,',','.'),
                number_format($pt5,2,',','.'),
                number_format($pt6,2,',','.')
            );
            $pdf->Row($row, 'S');
            
            $row = array(
                '',
                '',
                '________________',
                '________________',
                '_______________',
                '_______________',
                '_______________',
                '_______________',
                '_______________'
            );
            $pdf->Row($row, 'S');

            $row = array(
                '',
                'Total General',
                number_format($t1,2,',','.'),
                number_format($t2,2,',','.'),
                number_format($t7,2,',','.'),
                number_format($t3,2,',','.'),
                number_format($t4,2,',','.'),
                number_format($t5,2,',','.'),
                number_format($t6,2,',','.')
            );
            $pdf->Row($row, 'S');

            $pdf->Output('Consolidado Partidas', 'I');
            exit;
        }
    }

    public function ajustesCreate()
    {
        $periodosRep  = Registrocontrol::orderBy('ano_pro', 'desc')->get()->pluck('ano_pro', 'ano_pro');
		$meses        = $this->meses;
        $criterios    = MovPresupuestario::where('ajustes', '1')
                            ->orderBy('des_movpre')
                            ->pluck('des_movpre', 'mov_pre');

        return view(
			'administrativo.meru_administrativo.presupuesto.reportes.ajustes',
            compact('periodosRep','meses','criterios')
		);
    }

    public function ajustesStore(Request $request)
    {
        $anoPro   = $request->get('ano_pro');
        $mes      = $request->get('mes');
        $criterio = $request->get('criterio');
        
        $sql = DB::table('pre_movimientos AS a')
                ->select(
                    'a.cod_com',
                    'a.num_doc',
                    DB::raw("to_char(a.fecha, 'dd/mm/yyyy') AS fecha"),
                    'a.mto_tra',
                    DB::raw("CASE a.tip_ope
                                WHEN 21 THEN 'Mto_Comp. (-)\nMto_Disp. (+)'
                                WHEN 22 THEN 'Mto_Comp. (+)\n Mto_Disp. (-)'
                                WHEN 41 THEN 'Mto_Mod. (-)\nMto_Comp. (-)\nMto_Cau. (-)'
                                WHEN 42 THEN 'Mto_Mod. (+)\nMto_Comp. (+)\nMto_Cau. (+)'
                            END AS tip_ope"),
                    DB::raw('initcap(b.des_movpre) AS des_movpre'),
                )
                ->join('pre_movpresupuestario AS b', function ($join) {
                    $join->on('a.tip_ope', '=', DB::raw("to_number(b.mov_pre,'999')"))
                        ->where('b.ajustes', '1');
                })
                ->where('a.ano_pro', $anoPro)
                ->orderBY('a.fecha')
                ->orderBy('a.cod_com');

        if ($criterio == '') {
            $sql->whereIn('a.tip_ope', [21,22,41,42]);
        } else {
            $sql->where('a.tip_ope', $criterio);
        }
    
        if($mes != ''){
            $sql->where('a.fecha', 'like', $anoPro . '-' . \Str::padLeft($mes, 2, '0') . '%');
        }

        $archivo = 'Ajustes_Presupuestarios';

        if ($request->get('tipo_reporte') == 'E') {
            $data = [
                'query'      => $sql,
                'titulo'     => ['PRESUPUESTO HIDROBOLIVAR', 'AJUSTES PRESUPUESTARIOS'],
                'ancho'      => [30,20,20,20,50,50],
                'alineacion' => ['C','C','C','R','L','C'],
                'formatos'   => ['T','T','D','N','T','T'],
                'columnas'   => ['ESTRUCTURA','DOCUMENTO','FECHA','MONTO','OPERACION','MOVIMIENTO']
            ];

            return (new FromQueryExport($data))->download($archivo . '.xlsx');
        } else {
            $data['tipo_hoja']           = 'A4';
            $data['orientacion']         = 'V';
            $data['cod_normalizacion']   = '';
            $data['gerencia']            = '';
            $data['division']            = '';
            $data['titulo']              = 'PRESUPUESTO HIDROBOLIVAR';
            $data['subtitulo']           = 'AJUSTES PRESUPUESTARIOS';
            $data['alineacion_columnas'] = ['C','C','C','R','L','C'];
            $data['ancho_columnas']      = [37,21,20,20,32,73];
            $data['nombre_columnas']     = ['ESTRUCTURA','DOCUMENTO','FECHA','MONTO','OPERACION','MOVIMIENTO'];
            $data['funciones_columnas']  = '';
            $data['fuente']              = 7;
            $data['registros_mostar']    = ['cod_com','num_doc','fecha','mto_tra','tip_ope','des_movpre'];
            $data['nombre_documento']    = $archivo . '.pdf';
            $data['con_imagen']          = true;
            $data['vigencia']            = '';
            $data['revision']            = '';
            $data['usuario']             = auth()->user()->name;
            $data['cod_reporte']         = '';
            $data['registros']           = json_decode(json_encode($sql->get()), true);

            $pdf = new Fpdf;
            $pdf->SetLeftMargin(5);
            $pdf->setTitle(utf8_decode('Ajustes Presupuestarios'));
            $this->pintar_listado_pdf($pdf,$data,false);
            exit;
        }
    }

    public function solicitudesTraspasoCreate()
	{
        $gerencias = Gerencia::orderBy('cod_ger')->pluck('des_ger', 'cod_ger');
        $estados   = EstadoSolicitudTraspaso::cases();

		return view(
			'administrativo.meru_administrativo.presupuesto.reportes.solicitudes_traspaso',
            compact('gerencias', 'estados')
		);
	}

    public function solicitudesTraspasoStore(Request $request)
    {
        $codGer      = $request->get('gerencia');
        $staReg      = $request->get('sta_reg');
        $tipoReporte = $request->get('tipo_reporte');
        $archivo     = 'Solicitudes_Traspaso';

        $sql = DB::table('mod_soltraspasos AS a')
                ->select(
                    DB::raw("a.nro_sol || '-' || a.ano_pro AS solicitud"),
                    DB::raw("CASE a.sta_reg
                                WHEN '0' THEN 'Registrado'
                                WHEN '1' THEN 'Aprobado en Unidad'
                                WHEN '2' THEN 'Modificada en Unidad'
                                WHEN '3' THEN 'Anulada en unidad'
                                WHEN '4' THEN 'Rechazada'
                                WHEN '5' THEN 'Solicitud de Traspaso Procesada'
                            END AS estatus"),
                    DB::raw("to_char(a.fec_sol, 'dd/mm/yyyy') AS fecha"),
                    'b.des_ger AS gerencia',
                    'c.cod_com',
                    'c.mto_tra'
                )
                ->join('gerencias AS b', 'a.cod_ger', '=', 'b.cod_ger')
                ->join('mod_detsoltraspasos AS c', function($join){
                    $join->on('a.nro_sol', '=', 'c.nro_sol')
                        ->on('a.ano_pro', '=', 'c.ano_pro');
                })
                ->where('a.ano_pro', '=', $this->anoPro)
                ->orderBy('a.nro_sol');

        if ($codGer) {
            $sql->where('a.cod_ger', $codGer);
        }

        if ($staReg){
            $sql->where('a.sta_reg', $staReg);
        }

        if ($request->get('fec_ini') && $request->get('fec_fin')) {
            $fecIni = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('fec_ini'));
            $fecFin = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('fec_fin'));
            $sql->whereRaw('a.fec_sol BETWEEN ? AND ?', [$fecIni->format('Y-m-d'), $fecFin->format('Y-m-d')]);
        }

        if ($tipoReporte == 'P') {
            $data['tipo_hoja']           = 'A4';
            $data['orientacion']         = 'H';
            $data['cod_normalizacion']   = '';
            $data['gerencia']            = '';
            $data['division']            = '';
            $data['titulo']              = 'SOLICITUDES DE TRASPASOS PRESUPUESTARIOS';
            $data['subtitulo']           = '';
            $data['alineacion_columnas'] = ['C', 'L', 'C', 'L', 'C', 'R'];
            $data['ancho_columnas']      = [30, 45, 24, 85, 50, 30];
            $data['nombre_columnas']     = ['SOLICITUD', 'STATUS', 'FECHA', 'GERENCIA', 'ESTRUCTURA DE GASTOS', 'MONTO'];
            $data['funciones_columnas']  = '';
            $data['fuente']              = 8;
            $data['registros_mostar']    = ['solicitud','estatus','fecha','gerencia','cod_com', 'mto_tra'];
            $data['nombre_documento']    = $archivo . '.pdf';
            $data['con_imagen']          = true;
            $data['vigencia']            = '';
            $data['revision']            = '';
            $data['usuario']             = auth()->user()->name;
            $data['cod_reporte']         = '';
            $data['registros']           = json_decode(json_encode($sql->get()), true);

            $pdf = new Fpdf;
            $pdf->SetLeftMargin(16);
            $pdf->setTitle(utf8_decode('Consolidado de Servicios'));
            $this->pintar_listado_pdf($pdf,$data,false);
            exit;
        } else {
            // Agregar columnas para reporte en Excel
            $sql->addSelect(
                'a.concepto',
                'a.justificacion'
            );

            return (new SolicitudesTraspasoExport)->setQuery($sql)->download($archivo . '.xlsx');
        }
    }

    public function consolidadoServiciosCreate()
	{
		return view(
			'administrativo.meru_administrativo.presupuesto.reportes.consolidado_servicios',
		);
	}

    public function consolidadoServiciosStore(Request $request)
    {
        $fecIni  = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('fec_ini'));
        $fecFin  = \Carbon\Carbon::createFromFormat('Y-m-d', $request->get('fec_fin'));
        $fecInif = $fecIni->format('Y-m-d');
        $fecFinf = $fecFin->format('Y-m-d');

        $reembolso = DB::table('reembolso')
                        ->selectRaw('1 AS orden')
                        ->selectRaw("'Reembolso' AS proceso")
                        ->selectRaw("sum(CASE WHEN fecha_reg BETWEEN ? AND ? THEN 1 ELSE 0 END) AS recibido", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fecha_apr BETWEEN ? AND ? THEN 1 ELSE 0 END) AS procesado", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fecha_anu BETWEEN ? AND ? THEN 1 ELSE 0 END) AS anulado", [$fecInif, $fecFinf])
                        ->selectRaw("COALESCE(sum(CASE WHEN fecha_apr BETWEEN ? AND ? THEN total_aprobado ELSE 0 END), 0) AS bs_proc", [$fecInif, $fecFinf]);

        $reintegro = DB::table('reintegro')
                        ->selectRaw('2 AS orden')
                        ->selectRaw("'Reintegro' AS proceso")
                        ->selectRaw("sum(CASE WHEN fec_reg BETWEEN ? AND ? THEN 1 ELSE 0 END) AS recibido", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_apr BETWEEN ? AND ? THEN 1 ELSE 0 END) AS procesado", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_dev BETWEEN ? AND ? THEN 1 ELSE 0 END) AS anulado", [$fecInif, $fecFinf])
                        ->selectRaw("COALESCE(sum(CASE WHEN fec_apr BETWEEN ? AND ? THEN total ELSE 0 END), 0) AS bs_proc", [$fecInif, $fecFinf]);

        $solicitudFA = DB::table('op_solpagoanticipo_emp')
                        ->selectRaw('3 AS orden')
                        ->selectRaw("'Solicitud de F.A.' AS proceso")
                        ->selectRaw("sum(CASE WHEN fec_recep BETWEEN ? AND ? THEN 1 ELSE 0 END) AS recibido", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_cont BETWEEN ? AND ? THEN 1 ELSE 0 END) AS procesado", [$fecInif, $fecFinf])
                        ->selectRaw("COALESCE(sum(CASE WHEN status ='5' AND fec_status BETWEEN ? AND ? THEN 1 ELSE 0 END), 0) AS anulado", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_cont BETWEEN ? AND ? THEN monto ELSE 0 END) AS bs_proc", [$fecInif, $fecFinf]);


        $rendicionFA = DB::table('tes_rendicion_emp')
                        ->selectRaw('4 AS orden')
                        ->selectRaw("'Rendicion de F.A.' AS proceso")
                        ->selectRaw("sum(CASE WHEN fec_rec BETWEEN ? AND ? THEN 1 ELSE 0 END) AS recibido", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_cont BETWEEN ? AND ? THEN 1 ELSE 0 END) AS procesado", [$fecInif, $fecFinf])
                        ->selectRaw("COALESCE(sum(CASE WHEN fec_anu BETWEEN ? AND ? THEN 1 ELSE 0 END), 0) AS anulado", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_cont BETWEEN ? AND ? THEN mto_ren ELSE 0 END) AS bs_proc", [$fecInif, $fecFinf]);

        $solicitudCC = DB::table('caja_chica')
                        ->selectRaw('5 AS orden')
                        ->selectRaw("'Solicitud de Caja Chica' AS proceso")
                        ->selectRaw("sum(CASE WHEN fecha BETWEEN ? AND ? THEN 1 ELSE 0 END) AS recibido", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_apr BETWEEN ? AND ? THEN 1 ELSE 0 END) AS procesado", [$fecInif, $fecFinf])
                        ->selectRaw('0 AS anulado')
                        ->selectRaw("COALESCE(sum(CASE WHEN fec_apr BETWEEN ? AND ? THEN mon_ori ELSE 0 END), 0) AS bs_proc", [$fecInif, $fecFinf]);

        $rendicionCC = DB::table('tes_rendicionfondos')
                        ->selectRaw('6 AS orden')
                        ->selectRaw("'Rendicion de Caja Chica' AS proceso")
                        ->selectRaw("sum(CASE WHEN fec_rec BETWEEN ? AND ? THEN 1 ELSE 0 END) AS recibido", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_cont BETWEEN ? AND ? THEN 1 ELSE 0 END) AS procesado", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_anu BETWEEN ? AND ? THEN 1 ELSE 0 END) AS anulado", [$fecInif, $fecFinf])
                        ->selectRaw("COALESCE(sum(CASE WHEN fec_cont BETWEEN ? AND ? THEN total ELSE 0 END), 0) AS bs_proc", [$fecInif, $fecFinf]);

        $solicitudVi = DB::table('via_encvia')
                        ->selectRaw('7 AS orden')
                        ->selectRaw("'Solicitud de Viaticos' AS proceso")
                        ->selectRaw("sum(CASE WHEN fecha_recb BETWEEN ? AND ? THEN 1 ELSE 0 END) AS recibido", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fecha_aprob BETWEEN ? AND ? THEN 1 ELSE 0 END) AS procesado", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fecha_anul BETWEEN ? AND ? THEN 1 ELSE 0 END) AS anulado", [$fecInif, $fecFinf])
                        ->selectRaw("COALESCE(sum(CASE WHEN fecha_aprob BETWEEN ? AND ? THEN total_viatico ELSE 0 END), 0) AS bs_proc", [$fecInif, $fecFinf]);
        
        $rendicionVi = DB::table('via_encvia')
                        ->selectRaw('8 AS orden')
                        ->selectRaw("'Solicitud de Viaticos' AS proceso")
                        ->selectRaw("sum(CASE WHEN fecha_rrinde BETWEEN ? AND ? THEN 1 ELSE 0 END) AS recibido", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fecha_arind BETWEEN ? AND ? THEN 1 ELSE 0 END) AS procesado", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fecha_devolverr BETWEEN ? AND ? THEN 1 ELSE 0 END) AS anulado", [$fecInif, $fecFinf])
                        ->selectRaw("COALESCE(sum(CASE WHEN fecha_arind BETWEEN ? AND ? THEN total_viatico ELSE 0 END), 0) AS bs_proc", [$fecInif, $fecFinf]);
        
        $certificacionPD = DB::table('op_solservicio')
                        ->selectRaw('9 AS orden')
                        ->selectRaw("'Certificacion de PD' AS proceso")
                        ->selectRaw("sum(CASE WHEN fec_apr BETWEEN ? AND ? THEN 1 ELSE 0 END) AS recibido", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_comp BETWEEN ? AND ? THEN 1 ELSE 0 END) AS procesado", [$fecInif, $fecFinf])
                        ->selectRaw("sum(CASE WHEN fec_anu BETWEEN ? AND ? THEN 1 ELSE 0 END) AS anulado", [$fecInif, $fecFinf])
                        ->selectRaw("COALESCE(sum(CASE WHEN fec_comp BETWEEN ? AND ? THEN monto_total ELSE 0 END), 0) AS bs_proc", [$fecInif, $fecFinf]);

        // Consultas para calcular números de facturas
        $facRecibido = "(SELECT count(num_fac)
                        FROM fac_recepfacturas  
                        WHERE 
                            substring (nro_doc,1,2) =  ?
                            AND fec_rec BETWEEN ? AND ?) AS recibido";

        $facProcesado = "(SELECT count(num_fac) 
                        FROM facturas 
                        WHERE
                            substring (nro_doc,1,2) =  ?
                            AND fec_apr BETWEEN ? AND ?) AS procesado";

        $facAnulado = "(SELECT count(num_fac) 
                        FROM facturas 
                        WHERE 
                            substring (nro_doc,1,2) =  ?
                            and fec_anu BETWEEN ? AND ?) AS anulado";

        $facMonto = "(SELECT COALESCE(sum(mto_fac), 0)
                    FROM facturas 
                    WHERE
                        substring (nro_doc,1,2) =  ?
                        AND fec_apr BETWEEN ? AND ?) AS bs_proc";

        $facturaPD = DB::table('fac_recepfacturas')
                        ->selectRaw('10 AS orden')
                        ->selectRaw("'Facturas de PD' AS proceso")
                        ->selectRaw($facRecibido,['PD',$fecInif, $fecFinf])
                        ->selectRaw($facProcesado,['PD',$fecInif, $fecFinf])
                        ->selectRaw($facAnulado,['PD',$fecInif, $fecFinf])
                        ->selectRaw($facMonto,['PD',$fecInif, $fecFinf]);

        $facturaSG = DB::table('fac_recepfacturas')
                        ->selectRaw('11 AS orden')
                        ->selectRaw("'Facturas de SG' AS proceso")
                        ->selectRaw($facRecibido,['SG',$fecInif, $fecFinf])
                        ->selectRaw($facProcesado,['SG',$fecInif, $fecFinf])
                        ->selectRaw($facAnulado,['SG',$fecInif, $fecFinf])
                        ->selectRaw($facMonto,['SG',$fecInif, $fecFinf]);

        $facturaSV = DB::table('fac_recepfacturas')
                        ->selectRaw('12 AS orden')
                        ->selectRaw("'Facturas de SV' AS proceso")
                        ->selectRaw($facRecibido,['SV',$fecInif, $fecFinf])
                        ->selectRaw($facProcesado,['SV',$fecInif, $fecFinf])
                        ->selectRaw($facAnulado,['SV',$fecInif, $fecFinf])
                        ->selectRaw($facMonto,['SV',$fecInif, $fecFinf]);

        $facturaBM = DB::table('fac_recepfacturas')
                        ->selectRaw('13 AS orden')
                        ->selectRaw("'Facturas de BM' AS proceso")
                        ->selectRaw($facRecibido,['BM',$fecInif, $fecFinf])
                        ->selectRaw($facProcesado,['BM',$fecInif, $fecFinf])
                        ->selectRaw($facAnulado,['BM',$fecInif, $fecFinf])
                        ->selectRaw($facMonto,['BM',$fecInif, $fecFinf]);

        $facturaCO = DB::table('fac_recepfacturas')
                        ->selectRaw('14 AS orden')
                        ->selectRaw("'Facturas de CO' AS proceso")
                        ->selectRaw($facRecibido,['OC',$fecInif, $fecFinf])
                        ->selectRaw($facProcesado,['OC',$fecInif, $fecFinf])
                        ->selectRaw($facAnulado,['OC',$fecInif, $fecFinf])
                        ->selectRaw($facMonto,['OC',$fecInif, $fecFinf]);

        $reembolso->union($reintegro)
                    ->union($solicitudFA)
                    ->union($rendicionFA)
                    ->union($solicitudCC)
                    ->union($rendicionCC)
                    ->union($solicitudVi)
                    ->union($rendicionVi)
                    ->union($certificacionPD)
                    ->union($facturaPD)
                    ->union($facturaSG)
                    ->union($facturaSV)
                    ->union($facturaBM)
                    ->union($facturaCO);

        $res = DB::query()
                ->fromSub($reembolso, 'a')
                ->orderBy('orden')
                ->get();

        $data['tipo_hoja']           = 'C';
        $data['orientacion']         = 'V';
        $data['cod_normalizacion']   = '';
        $data['gerencia']            = '';
        $data['division']            = '';
        $data['titulo']              = 'CONSOLIDADO DE ACTIVIDADES DE LA DIVISION DE CUENTAS POR PAGAR';
        $data['subtitulo']           = 'DESDE ' . $fecIni->format('d/m/Y') . ' HASTA ' . $fecFin->format('d/m/Y');
        $data['alineacion_columnas'] = ['C','C','C','C','C'];
        $data['ancho_columnas']      = [80,20,20,20,40];
        $data['nombre_columnas']     = ['Actividad','Recibidos', 'Procesados', 'Anulados', 'MONTO Procesado'];
        $data['funciones_columnas']  = '';
        $data['fuente']              = 8;
        $data['registros_mostar']    = ['proceso','recibido','procesado','anulado','bs_proc'];
        $data['nombre_documento']    = 'Consolidado_Servicios.pdf';
        $data['con_imagen']          = true;
        $data['vigencia']            = '';
        $data['revision']            = '';
        $data['usuario']             = auth()->user()->name;
        $data['cod_reporte']         = '';
        $data['registros']           = json_decode(json_encode($res), true);

        $pdf = new Fpdf;
        $pdf->SetLeftMargin(17);
        $pdf->setTitle(utf8_decode('Consolidado de Servicios'));
        $this->pintar_listado_pdf($pdf,$data,false);
        exit;
    }
}