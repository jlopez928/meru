<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Contratos\Proceso;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administrativo\Meru_Administrativo\Compra\EncNotaEntrega;
use App\Models\Administrativo\Meru_Administrativo\General\DatosEmpresa;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\ConceptoContrato;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpDetgastossolservicio;
use App\Models\Administrativo\Meru_Administrativo\Compra\CorrEntCompra;
use App\Models\Administrativo\Meru_Administrativo\Compra\Acta;
use App\Models\Administrativo\Meru_Administrativo\Compra\DetNotaEntrega;
use App\Models\Administrativo\Meru_Administrativo\Compra\DetGastosNotaEntrega;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Http\Requests\Administrativo\Meru_Administrativo\Compra\ActaRequest;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;
use App\Traits\ReportFpdf;
use App\Traits\funcActas;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
class ActaContratoObraServController extends Controller
{
      use ReportFpdf;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use funcActas;

    public function index()
    {
        $causar=1;

        return view('administrativo.meru_administrativo.contratos.proceso.actacontratoobraserv.index',compact('causar'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $encnotaentrega = new EncNotaEntrega();
        return view('administrativo.meru_administrativo.contratos.proceso.actacontratoobraserv.create', compact('encnotaentrega'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        DB::beginTransaction();

        try
		{
            //-------------------------------------------------------------------------------------------
            //                       Campos para guardar
            //-------------------------------------------------------------------------------------------
            $fk_ano_pro				= $request->fk_ano_pro;
            $ano_ord_com			= $request->ano_ord_com;
            $grupo					= $request->grupo;
            $xnro_ord				= $request->xnro_ord;
            $porc_ant				= $request->porc_ant;
            $antc_amort				= $request->antc_amort;
            $fk_rif_con				= $request->fk_rif_con;
            $fec_ent				= $request->fec_ent;
            $tipo_orden				= 1;
            $fk_tip_ord				= $request->fk_tip_ord;
            $fec_pos				= $request->fec_pos;
            $fec_ord				= $request->fec_ord;
            $mto_ord				= $request->mto_ord;
            $tip_ent				= $request->tip_ent;
            $fec_notaentrega		= now()->format('Y-m-d');
            $mto_siniva				= $request->mto_siniva;
            $mto_iva				= $request->mto_iva;
            $mto_ent				= $request->mto_ent;
            $mto_anticipo			= $request->mto_anticipo;
            $base_imponible			= $request->base_imponible;
            $base_exenta			= $request->base_exenta;
            $ano_fiscal				= $request->ano_fiscal;
            $observacion			= $request->observacion;
            $jus_sol 				= $request->jus_sol;
            $var					= explode("-", $request->xnro_ord);
            $fk_nro_ord				= $var[1];
            $por_iva				= $request->por_iva;
            $usuario				= auth()->user()->id;
            $fecha_sistema			= $this->FechaSistema($ano_fiscal, "Y/m/d H:i:s");
            $cont_fis               = $request->cont_fis;
            $causar			        = 'SI';
            $totrecep               = $request->totrecep;


            //-------------------------------------------------------------------------------------------
            //                       Buscar el correlativo de la nota de entrega
            //-------------------------------------------------------------------------------------------
            $query = CorrEntCompra::query()
                                ->where('ano_pro', $fk_ano_pro)
                                ->where('grupo', $grupo)
                                ->select('nro_ent')
                                ->first();

            if($query){
                $nro_ent	= $query->nro_ent;
            }else{
                $nro_ent	= 1;
            }

            //-------------------------------------------------------------------------------------------
            //                      Actualizamos Correlativo de la nota de entrega
            //-------------------------------------------------------------------------------------------
            $msj = "Error Actualizando Correlativo de la Nota de Entrega";
            if($nro_ent == 1){
                $correntcompra = new CorrEntCompra;

                $correntcompra->ano_pro = $fk_ano_pro;
                $correntcompra->grupo   = $grupo;
                $correntcompra->nro_ent = 2;

                $correntcompra->save();

            }else{
                $correntcompra = CorrEntCompra::where('ano_pro', $fk_ano_pro)
                                                ->where('grupo', $grupo)
                                                ->update(['nro_ent' => $nro_ent+1]);
            }

            $xnro_ent		= $grupo.'-'.$nro_ent;
            $nro_entrega	= $grupo."-".$nro_ent."-".$fk_ano_pro;

            //-------------------------------------------------------------------------------------------
            //                      Insertar en el encabezado de la nota de entrega
            //-------------------------------------------------------------------------------------------
            $msj = "Error al Ingresar en el Encabezado de Contrato.";

            $encnotent = EncNotaEntrega::create([
                            'grupo'              => $grupo,
                            'nro_ent'            => $nro_ent,
                            'fk_ano_pro'         => $fk_ano_pro,
                            'fk_nro_ord'         => $fk_nro_ord,
                            'xnro_ent'           => $xnro_ent,
                            'fec_pos'            => $fec_pos,
                            'fec_ent'            => $fec_ent,
                            'fk_tip_ord'         => $fk_tip_ord,
                            'fec_ord'            => $fec_ord,
                            'mto_ord'            => $mto_ord ,
                            'fk_rif_con'         => $fk_rif_con,
                            'tip_ent'            => $tip_ent,
                            'nota_entrega'       =>  'N/A',
                            'fec_notaentrega'    => $fec_notaentrega,
                            'mto_siniva'         => $mto_siniva,
                            'mto_iva'            => $mto_iva,
                            'mto_ent'            => $mto_ent,
                            'observacion'        => $observacion,
                            'sta_ent'            => '0',
                            'fec_sta'            => $fecha_sistema,
                            'usuario'            => $usuario,
                            'jus_sol'            => $jus_sol,
                            'antc_amort'         => $antc_amort,
                            'porc_ant'           => $porc_ant,
                            'mto_anticipo'       => $mto_anticipo,
                            'base_imponible'     => $base_imponible,
                            'base_exenta'        => $base_exenta,
                            'ano_ord_com'        => $ano_ord_com,
                            'tipo_orden'         => $tipo_orden,
                            'xnro_ord'           => $xnro_ord,
                            'cont_fis'           => $cont_fis
                    ]);


            //-------------------------------------------------------------------------------------------
            //        Para buscar si la Orden de Compra tiene un anticipo de la forma vieja o nueva
            //-------------------------------------------------------------------------------------------
            $msj = "Error al buscar el anticipo y su forma";

            $solservicio = OpSolservicio::where('ano_pro',$ano_ord_com)
                                        ->where('xnro_sol',$xnro_ord)
                                        ->select('ant_old')
                                        ->get();

            $ant_old = $solservicio[0]->ant_old;

            $solservicio = OpSolservicio::where('ano_pro',$ano_ord_com)
                                        ->where('xnro_sol',$xnro_ord)
                                        ->update(['cont_fis' => $cont_fis]);

            $msj = "Error buscando detalle de contrato";

            $detnotaentrega = DB::select("SELECT a.nro_ren, b.cod_prod, h.des_con, a.mto_tra, a.saldo, 0.00 as entrega,
                                                b.por_iva, 0.00 as mto_iva,
                                                e.tip_cod, e.cod_pryacc, e.cod_obj, e.gerencia, e.unidad,
                                                e.cod_par, e.cod_gen, e.cod_esp, e.cod_sub,
                                                'Si' as gasto,
                                                cta_gasto,
                                                c.cta_x_pagar
                                        FROM op_detgastossolservicio a
                                        INNER JOIN op_detsolservicio b ON
                                                    a.ano_pro = b.ano_pro AND a.xnro_sol = b.xnro_sol
                                        INNER JOIN pre_partidasgastos c ON
                                                    c.cod_par = a.cod_par AND c.cod_gen = a.cod_gen AND c.cod_esp = a.cod_esp AND c.cod_sub = a.cod_sub
                                        INNER JOIN pre_centrocosto d ON
                                                    d.ano_pro = a.ano_pro and d.cod_cencosto = substring(a.cod_com from 0 for 15)
                                        INNER JOIN pre_maestroley e ON
                                                    e.ano_pro =   $fk_ano_pro   and e.cod_com = (d.ajust_ctrocosto || substring(a.cod_com from 15 for 26))
                                        INNER JOIN registrocontrol f ON f.ano_pro = e.ano_pro AND e.cod_com != f.cod_comi
                                        INNER JOIN op_conceptos_contrato h on h.cod_con=b.cod_prod
                                        WHERE a.ano_pro =   $fk_ano_pro   and a.xnro_sol = '$xnro_ord'
                                                and a.saldo > 0 and a.gasto='1'
                                        UNION
                                    SELECT a.nro_ren, b.cod_prod, h.des_con, a.mto_tra, a.saldo, 0.00 as entrega,
                                                b.por_iva, 0.00 as mto_iva,
                                                e.tip_cod, e.cod_pryacc, e.cod_obj, e.gerencia, e.unidad,
                                                e.cod_par, e.cod_gen, e.cod_esp, e.cod_sub,
                                                'Si' as gasto,
                                                c.cta_gasto,
                                                c.cta_x_pagar
                                        FROM op_detgastossolservicio a
                                        INNER JOIN op_detsolservicio b ON
                                                    a.ano_pro = b.ano_pro AND a.xnro_sol = b.xnro_sol
                                        INNER JOIN pre_partidasgastos c ON
                                                    c.cod_par = a.cod_par AND c.cod_gen = a.cod_gen AND c.cod_esp = a.cod_esp AND c.cod_sub = a.cod_sub
                                        INNER JOIN pre_centrocosto d ON
                                                    d.ano_pro = a.ano_pro and d.cod_cencosto = substring(a.cod_com from 0 for 15)
                                        INNER JOIN pre_maestroley e ON
                                                    e.ano_pro =   $fk_ano_pro   and e.cod_com = (d.ajust_ctrocosto || substring(a.cod_com from 15 for 26))
                                        INNER JOIN registrocontrol f ON f.ano_pro = e.ano_pro AND e.cod_com != f.cod_comi
                                        INNER JOIN op_solservicio g ON a.ano_pro = g.ano_pro AND a.xnro_sol = g.xnro_sol AND
                                                    g.sta_sol IN ('4', '6') AND g.mod = '0'
                                        INNER JOIN op_conceptos_contrato h on h.cod_con=b.cod_prod
                                        WHERE a.ano_pro =   $fk_ano_pro   and a.xnro_sol LIKE '$xnro_ord'
                                                and a.saldo > 0 and a.gasto='1'
                                        ORDER BY mto_tra DESC;");

            if ($detnotaentrega){
                foreach($detnotaentrega as $tabCols){
                    if ($totrecep !=0 ){
                        $tabCols->pre_uni   = 1;
                        $tabCols->nro_sol	= 1;
                        $tabCols->mon_iva	= ($totrecep * ($tabCols->por_iva / 100));
                        $tabCols->saldo		= $tabCols->saldo - $totrecep;

                        if ($tabCols->gasto=='1' || $tabCols->gasto=='Si'){
                            $tabCols->gasto=1;
                        }else{
                            $tabCols->gasto=0;
                        }
                        $tabCols->saldo = $tabCols->saldo-$totrecep;

                        $msj = "Error al Ingresar en el Detalle de la Nota de Entrega";

                        $detnotaentrega = DetNotaEntrega::create([
                                'fk_ano_pro'         => $fk_ano_pro,
                                'grupo'             => $grupo,
                                'nro_ent'            => $nro_ent,
                                'xnro_ent'           => $xnro_ent,
                                'nro_ren'            => $tabCols->nro_ren,
                                'fk_cod_prod'        => $tabCols->cod_prod,
                                'nro_sol'            => $tabCols->nro_sol,
                                'cantidad'           => $tabCols->mto_tra,
                                'totrecep'           => $totrecep,
                                'pre_uni'            => $tabCols->pre_uni,
                                'por_iva'            => $tabCols->por_iva,
                                'mon_iva'            => $tabCols->mto_iva,
                                'tip_cod'            => $tabCols->tip_cod,
                                'cod_pryacc'         => $tabCols->cod_pryacc,
                                'cod_obj'            => $tabCols->cod_obj,
                                'gerencia'           => $tabCols->gerencia,
                                'unidad'             => $tabCols->unidad,
                                'cod_par'            => $tabCols->cod_par,
                                'cod_gen'            => $tabCols->cod_gen,
                                'cod_esp'            => $tabCols->cod_esp,
                                'cod_sub'            => $tabCols->cod_sub,
                                'saldo'              => $tabCols->saldo,
                                'cta_cont'           => $tabCols->cta_gasto,
                                'cta_x_pagar'        => $tabCols->cta_x_pagar,
                                'gasto'              => $tabCols->gasto,
                                'encnotaentrega_id'  => $encnotent->id
                        ]);


                        //-------------------------------------------------------------------------------------------
                        //        Armando codigo partida
                        //-------------------------------------------------------------------------------------------
                        $cod_com = $this->armar_cod_com($detnotaentrega->tip_cod,
                                                        $detnotaentrega->cod_pryacc,
                                                        $detnotaentrega->cod_obj,
                                                        $detnotaentrega->gerencia,
                                                        $detnotaentrega->unidad,
                                                        $detnotaentrega->cod_par,
                                                        $detnotaentrega->cod_gen,
                                                        $detnotaentrega->cod_esp,
                                                        $detnotaentrega->cod_sub);

                        if ($ano_ord_com != $fk_ano_pro){

                            $msj = "Error al Intentar Obtener Centro de Costo Original. \n";

                            $centro = $this->ObtenerCentroCostoViejo($ano_ord_com,$cod_com);

                            if ($centro){
                                $cod_com =  $this->armar_cod_com( $centro->tip_cod, $centro->cod_pryacc, $centro->cod_obj, $centro->gerencia, $centro->unidad, $detnotaentrega->cod_par, $detnotaentrega->cod_gen, $detnotaentrega->cod_esp, $detnotaentrega->cod_sub);
                            }
                        }

                        $msj = "Error Actualizando en el detalle de la Solicitud de Servicio";

                        $detgastossolservicio = OpDetgastossolservicio::where('ano_pro',$ano_ord_com)
                                                                    ->where('grupo',  $grupo)
                                                                    ->where('nro_sol',$fk_nro_ord)
                                                                    ->where('cod_com',$cod_com)
                                                                    ->where('nro_ren',$tabCols->nro_ren)
                                                                    ->update(['saldo' => $tabCols->saldo]);

                    }
                    $msj = "Error insertando pastidas de gasto";
                    $detgastosnotaentrega = DB::select(" SELECT tip_cod, cod_pryacc, cod_obj, gerencia, unidad,
                                                                cod_par, cod_gen, cod_esp, cod_sub,
                                                                CAST(sum(total) as numeric(18,2)) as total, gasto
                                                        FROM ( SELECT   1  as renglon,   tip_cod,  cod_pryacc,
                                                                        cod_obj,  gerencia,   unidad,  cod_par,
                                                                        cod_gen,   cod_esp,   cod_sub, 0   as total,
                                                                        'Si' as gasto
                                                                            from com_detnotaentrega det
                                                                            where det.fk_ano_pro= $fk_ano_pro and det.grupo='$grupo' and  nro_ent=$encnotent->nro_ent
                                                                UNION ALL
                                                                            SELECT 1 as renglon, tip_codi as tip_cod, cod_pryacci as cod_pryacc, cod_obji as cod_obj, gerenciai as gerencia,
                                                                    unidadi as unidad, cod_pari as cod_par, cod_geni as cod_gen, cod_espi as cod_esp,
                                                                    cod_subi as cod_sub,   0   as total, (cast('No' as character(2))) as gasto
                                                                FROM registrocontrol
                                                                WHERE ano_pro=  $fk_ano_pro  ) as a
                                                                GROUP by 1,2,3,4,5,6,7,8,9,11");


                    foreach($detgastosnotaentrega as $tabGastos){

                        $cod_com = $this->armar_cod_com($tabGastos->tip_cod,
                                                        $tabGastos->cod_pryacc,
                                                        $tabGastos->cod_obj,
                                                        $tabGastos->gerencia,
                                                        $tabGastos->unidad,
                                                        $tabGastos->cod_par,
                                                        $tabGastos->cod_gen,
                                                        $tabGastos->cod_esp,
                                                        $tabGastos->cod_sub);

                        $tabGastos->mto_tra = $tabGastos->total;

                        if ($tabGastos->gasto=='Si'){
                            $causar='1';
                        }else{
                            $causar='0';
                        }

                        $cod_com_iva = $this->ObtenerCodComIva($fk_ano_pro);

                        if ($cod_com != $cod_com_iva){
                            //-------------------------------------------------------------------------------------------
                            //      Si La Orden es de anticipo viejo se hace amortizacion presupuestaria
                            //-------------------------------------------------------------------------------------------
                            if ($ant_old==1 or $ant_old=''){
                                //-------------------------------------------------------------------------------------------
                                //      Se le resta la amortizacion a la partida de gasto
                                //-------------------------------------------------------------------------------------------
                                $tabGastos->mto_cau = $tabGastos->mto_cau - $antc_amort;
                            }
                        }

                        $msj = "Error al Ingresar en el Encabezado del Gasto.";
                        //dd($encnotent->id);
                        $detgastosnotaentrega = DetGastosNotaEntrega::create([
                        'ano_pro'           => $fk_ano_pro,
                        'grupo'             => $grupo,
                        'nro_ent'           => $encnotent->nro_ent,
                        'tip_cod'           => $tabGastos->tip_cod,
                        'cod_pryacc'        => $tabGastos->cod_pryacc,
                        'cod_obj'           => $tabGastos->cod_obj,
                        'gerencia'          => $tabGastos->gerencia,
                        'unidad'            => $tabGastos->unidad,
                        'cod_par'           => $tabGastos->cod_par,
                        'cod_gen'           => $tabGastos->cod_gen,
                        'cod_esp'           => $tabGastos->cod_esp,
                        'cod_sub'           => $tabGastos->cod_sub,
                        'cod_com'           => $cod_com,
                        'causar'            => $causar,
                        'encnotaentrega_id' => $encnotent->id,
                        'mto_tra'           => ($causar == 1)? $totrecep:$mto_iva,
                        'mto_cau'           => ($causar == 1)? $totrecep:$mto_iva,
                        ]);

                    }
                }
            }
                //dd($msj);
                DB::commit();

                alert()->Success('NOTA DE ENTREGA CREADA: Numero: '.$nro_ent.' Grupo: '.$grupo.' Año: '.$fk_ano_pro);

                return redirect()->route('contratos.proceso.actacontratobraserv.index');

            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                //dd($e->getMessage());
                $msj = 'A ocurrido un ERROR en la transaccion.\\n Por Favor Intente de Nuevo.';
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj.' '. $e->getMessage());
                return redirect()->back()->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(EncNotaEntrega $actacontratobraserv,$valor)
    {

        $beneficiarios = Beneficiario::where('rif_ben',$actacontratobraserv->fk_rif_con )
                                      ->where('sta_reg','1')
                                      ->first();

        $responsablehb = Beneficiario::where('rif_ben',$actacontratobraserv->ced_hb )
                                     ->where('tipo','E')
                                     ->where('sta_reg','1')
                                     ->first();

        $solservicio = OpSolservicio::where('ano_pro',$actacontratobraserv->ano_ord_com)
                                    ->where('xnro_sol',$actacontratobraserv->xnro_ord)
                                    ->select('por_iva','monto_neto')
                                    ->first();

        $entera = $actacontratobraserv->detnotaentrega[0]->fk_cod_prod;

        $des_con =ConceptoContrato::where('cod_con',DB::Raw("$entera::integer"))
                                    ->select('des_con')
                                    ->first();

        $statusent =   $this->descrip_statu($actacontratobraserv->sta_ent);
        $statcomprob = $this->descrip_statu2($actacontratobraserv->stat_causacion);
        $encnotaentrega = $actacontratobraserv;

        switch ($valor) {
            case "show":
                $ruta='administrativo.meru_administrativo.contratos.proceso.actacontratoobraserv.show';
                break;
            case "iniciar":
                $ruta='administrativo.meru_administrativo.contratos.proceso.actacontratoobraserv.iniciar';
                break;
            case "terminar":
                $ruta='administrativo.meru_administrativo.contratos.proceso.actacontratoobraserv.terminar';
                break;
            case "aceptar":
                $ruta='administrativo.meru_administrativo.contratos.proceso.actacontratoobraserv.aceptar';
                break;
            case "modificar":
                $ruta='administrativo.meru_administrativo.contratos.proceso.actacontratoobraserv.edit';
                break;
            case "anular":
                $ruta='administrativo.meru_administrativo.contratos.proceso.actacontratoobraserv.anular';
                break;
            case "reimprimir":
                    $ruta='administrativo.meru_administrativo.contratos.proceso.actacontratoobraserv.edit';
                    break;
        }
        return view($ruta,compact('encnotaentrega','beneficiarios','responsablehb','statusent','statcomprob','solservicio','des_con','valor'));

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,EncNotaEntrega $actacontratobraserv)
    {
        if ($request->acta!=''){
            DB::beginTransaction();

            try{

                $fecha_sistema			= $this->FechaSistema($actacontratobraserv->ano_fiscal, "Y-m-d H:i:s");

                if ($request->acta == 'A'){
                    $msj = 'Error Actualizando Acta de Aceptación.';

                    $actas = Acta::where('acta',$request->acta)
                                ->where('fk_ano_pro',$actacontratobraserv->fk_ano_pro)
                                ->where('grupo',$actacontratobraserv->grupo)
                                ->where('nro_ent',$request->nro_ent)
                                ->update([
                                    'jus_sol'		=> $request->jus_sol,
                                    'observacion'	=> $request->observacion,
                                    'ced_hb'		=> $request->ced_hb,
                                    'nom_hb'		=> $request->nom_hb,
                                    'nom_con'		=> $actacontratobraserv->nom_con,
                                    'ced_con'		=> $actacontratobraserv->ced_con,
                                    'fec_act'		=> $request->fecha_acta,
                                    'recomen'		=> $request->recomen,
                                    'cargo_hb'	    => $request->cargo_hb,
                                    'lug_reunion'	=> $request->lug_reunion,
                                    'revision'	    => $request->revision,
                                    'gerencia'	    => $request->gerencia,
                                    'usu_mod'		=> auth()->user()->id,
                                    'fec_mod'		=> $fecha_sistema
                                ]);
                }
                else{
                    if ($request->acta == 'I'){
                        $msj = 'Error Actualizando Acta de Inicio.';
                    }
                    else{
                        $msj = 'Error Actualizando Acta de Terminación.';
                    }

                    $actas = Acta::where('acta',$request->acta)
                                ->where('fk_ano_pro',$actacontratobraserv->fk_ano_pro)
                                ->where('xnro_ord',$actacontratobraserv->xnro_ord)
                                ->update([
                                    'jus_sol'		=> $request->jus_sol,
                                    'observacion'	=> $request->observacion,
                                    'ced_hb'		=> $request->ced_hb,
                                    'nom_hb'		=> $request->nom_hb,
                                    'nom_con'		=> $request->nom_con,
                                    'ced_con'		=> $request->ced_con,
                                    'fec_act'		=> $request->fecha_acta,
                                    'usu_mod'		=> auth()->user()->id,
                                    'fec_mod'		=> $fecha_sistema
                                ]);
                }


                DB::commit();

                alert()->Success('Acta Modificada Exitosamente.');

                return redirect()->route('contratos.proceso.actacontratobraserv.index');

            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                dd($e);
                $msj = 'A ocurrido un ERROR en la transaccion.\\n Por Favor Intente de Nuevo.';
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj.' '. $e->getMessage());
                return redirect()->back()->withInput();
            }
        }else{
            $msj = 'Debe seleccionar el tipo de Acta (I,T o A)';
            alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function iniciarentrega(ActaRequest $actacontratobraserv)
    {

        DB::beginTransaction();

        try{

			$fecha_sistema			= $this->FechaSistema($actacontratobraserv->ano_fiscal, "Y-m-d H:i:s");

            $msj = 'Error Actualizando Entrega.';

            $conencnotent   =EncNotaEntrega::where('fk_ano_pro',$actacontratobraserv->fk_ano_pro)
                                           ->where('grupo',$actacontratobraserv->grupo)
                                           ->where('nro_ent',$actacontratobraserv->nro_ent)
                                           ->update(['fec_ant' => DB::Raw("fec_sta"),
                                                     'fec_sta' => $fecha_sistema,
                                                     'sta_ent' => 1,
                                                     'sta_ant' => DB::Raw("sta_ent"),
                                                     'usu_sta' => auth()->user()->id]);

            $msj = 'Error Ingresando Actas de Inicio';

            Acta::create($actacontratobraserv->only(['acta','grupo','nro_ent','xnro_ord','ano_ord','fk_ano_pro','jus_sol','nom_hb','ced_hb','nom_con',
                                                      'ced_con','observacion','fecha','usuario','fec_act','gerencia','cargo_hb','encnotaentrega_id']));

            DB::commit();

            alert()->Success('ACTA DE INICIO creada Exitosamente');

            // return redirect()->route('contratos.proceso.actacontratobraserv.index');
            return redirect()->route('contratos.proceso.actacontratobraserv.reimprimirservicio',[EncNotaEntrega::find($actacontratobraserv->id),$actacontratobraserv->acta]);

        } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();
                dd($e->getMessage());
                $msj = 'A ocurrido un ERROR en la transaccion.\\n Por Favor Intente de Nuevo.';
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj.' '. $e->getMessage());
                return redirect()->back()->withInput();
		}
    }
    public function terminarentrega(ActaRequest $actacontratobraserv)
    {
        //return $actacontratobraserv;
        DB::beginTransaction();

        try{

			$fecha_sistema			= $this->FechaSistema($actacontratobraserv->ano_fiscal, "Y-m-d H:i:s");

            $msj = 'Error Actualizando Entrega Terminacion.';

            $conencnotent = EncNotaEntrega::where('fk_ano_pro',$actacontratobraserv->fk_ano_pro)
                                          ->where('grupo',$actacontratobraserv->grupo)
                                          ->where('nro_ent',$actacontratobraserv->nro_ent)
                                          ->update(['fec_ant' => DB::Raw("fec_sta"),
                                                    'fec_sta' => $fecha_sistema,
                                                    'sta_ent' => 2,
                                                    'sta_ant' => DB::Raw("sta_ent"),
                                                    'usu_sta' =>  auth()->user()->id]);

            Acta::create($actacontratobraserv->only(['acta','grupo','nro_ent','xnro_ord','ano_ord','fk_ano_pro','jus_sol','nom_hb','ced_hb','nom_con',
                                                     'ced_con','observacion','fecha','usuario','fec_act','gerencia','cargo_hb','encnotaentrega_id']));

                        DB::commit();

                        alert()->Success('ACTA DE TERMINACION creada Exitosamente.');

                       // return redirect()->route('contratos.proceso.actacontratobraserv.index');
                       return redirect()->route('contratos.proceso.actacontratobraserv.reimprimirservicio',[EncNotaEntrega::find($actacontratobraserv->id),$actacontratobraserv->acta]);


                    } catch (\Illuminate\Database\QueryException $e) {
                            DB::rollBack();
                            //dd($e->getMessage());
                            $msj = 'A ocurrido un ERROR en la transaccion.\\n Por Favor Intente de Nuevo.';
                            alert()->Error('Transacci&oacute;n Fallida<br>'.$msj.' '. $e->getMessage());
                            return redirect()->back()->withInput();
                    }
    }
    public function aceptarentrega(ActaRequest $actacontratobraserv)
    {
        DB::beginTransaction();

        try{

			$fecha_sistema			= $this->FechaSistema($actacontratobraserv->ano_fiscal, "Y-m-d H:i:s");

			$msj = 'Error Actualizando Entrega Aceptacion.';


            $conencnotent = EncNotaEntrega::where('fk_ano_pro',$actacontratobraserv->fk_ano_pro)
                                          ->where('grupo',$actacontratobraserv->grupo)
                                          ->where('nro_ent',$actacontratobraserv->nro_ent)
                                          ->update(['fec_ant' => DB::Raw("fec_sta"),
                                                    'fec_sta' => $fecha_sistema,
                                                    'sta_ent' => 3,
                                                    'sta_ant' => DB::Raw("sta_ent"),
                                                    'usu_sta' =>  auth()->user()->id]);


            $msj = 'Error Ingresando Actas Aceptacion.';

            Acta::create($actacontratobraserv->only(['acta','grupo','nro_ent','xnro_ord','ano_ord','fk_ano_pro','jus_sol','nom_hb','ced_hb','nom_con',
                                                     'ced_con','observacion','fecha','usuario', 'recomen','cargo_hb','lug_reunion','revision','gerencia', 'fec_act','encnotaentrega_id']));


            DB::commit();

            alert()->Success('ACTA DE ACEPTACION creada Exitosamente.');

            return redirect()->route('contratos.proceso.actacontratobraserv.reimprimirservicio',[EncNotaEntrega::find($actacontratobraserv->id),$actacontratobraserv->acta]);
            //return redirect()->route('contratos.proceso.actacontratobraserv.index');


        } catch (\Illuminate\Database\QueryException $e) {
                DB::rollBack();

                $msj = 'A ocurrido un ERROR en la transaccion.\\n Por Favor Intente de Nuevo.';
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj.' '. $e->getMessage());
                return redirect()->back()->withInput();
        }
    }



    public function anularentrega(Request $actacontratobraserv)
    {
        DB::beginTransaction();

        try{
            $fk_ano_pro				= $actacontratobraserv->fk_ano_pro;
            $ano_ord_com			= $actacontratobraserv->ano_ord_com;
            $grupo					= $actacontratobraserv->grupo;
            $xnro_ord				= $actacontratobraserv->xnro_ord;
            $var					= explode("-", $xnro_ord);
            $fk_tip_ord				= $actacontratobraserv->fk_tip_ord;
            $nro_ent				= $actacontratobraserv->nro_ent;
            $sta_ent				= $actacontratobraserv->sta_ent;
            $ano_fiscal				= $actacontratobraserv->ano_fiscal;
            $usuario				= auth()->user()->id;
            $fecha_sistema			= $this->FechaSistema($ano_fiscal, "Y-m-d H:i:s");
            $fk_nro_ord             = $actacontratobraserv->fk_nro_ord;

            $msj = "Error Actualizando Encabezado de la Nota de Entrega.";

            $conencnotent  =  EncNotaEntrega::where('fk_ano_pro',$fk_ano_pro)
                                            ->where('grupo',$grupo)
                                            ->where('nro_ent',$nro_ent)
                                            ->update(['fec_ant' => DB::Raw("fec_sta"),
                                                    'fec_sta' => $fecha_sistema,
                                                    'sta_ent' => 8,
                                                    'sta_ant' => DB::Raw("sta_ent"),
                                                    'usu_sta' => $usuario]);

            $conencnotent  =  EncNotaEntrega::where('id',$actacontratobraserv->id)->get();


            foreach($conencnotent[0]->detnotaentrega as $tabCols){
                $cod_com = $this->armar_cod_com($tabCols->tip_cod,
                                            $tabCols->cod_pryacc,
                                            $tabCols->cod_obj,
                                            $tabCols->gerencia,
                                            $tabCols->unidad,
                                            $tabCols->cod_par,
                                            $tabCols->cod_gen,
                                            $tabCols->cod_esp,
                                            $tabCols->cod_sub);
            }

            if ($ano_ord_com!=$fk_ano_pro){
                $centro = $this->ObtenerCentroCostoViejo($ano_ord_com,$cod_com);
                if (!$centro){
                    $msj = "Error al Intentar Obtener Centro de Costo Original. \n";
                    dd($msj);
                }else{
                    extract($centro);
                    $cod_com = $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub);
                }
            }

            $msj = "Error Actualizando en el detalle de la Solicitud de Servicio";

            $detgastossolservicio = OpDetgastossolservicio::where('ano_pro',$ano_ord_com)
                                                        ->where('grupo',  $grupo)
                                                        ->where('nro_sol',$fk_nro_ord)
                                                        ->where('cod_com',$cod_com)
                                                        ->where('nro_ren',$tabCols->nro_ren)
                                                        ->update(['saldo' =>  $tabCols->saldo + $tabCols->totrecep]);

            DB::commit();

            alert()->Success('LA NOTA DE ENTREGA FUE ANULADA EXITOSAMENTE.');

            return redirect()->route('contratos.proceso.actacontratobraserv.index');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $msj = 'A ocurrido un ERROR en la transaccion.\\n Por Favor Intente de Nuevo.';
            alert()->Error('Transacci&oacute;n Fallida<br>'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function reimprimirentrega(Request $request,EncNotaEntrega $actacontratobraserv)
    {

        if ($request->acta == "A")
            $dat_act    = $actacontratobraserv->acta;
        if  ($request->acta == "I")
            $dat_act    = $actacontratobraserv->actai;
        if  ($request->acta == "T")
             $dat_act    = $actacontratobraserv->actat;

        //return $request;
        $texto2='';
        $texto3='';
        $texto4='';
        $dat_gerencia='';
        $mes='';
        if ($dat_act){
            if ($request->acta == "A"){
                //$cod_gerenc = $dat_act->gerencia;

                $dat_gerencia = Gerencia::where('cod_ger',$dat_act->gerencia)->first();
            }

            $dat_emp = DatosEmpresa::where('cod_empresa','01')
                                   ->first();

            switch (substr($dat_act->fec_act, 5, 2)){
                case 1: $mes = "Enero"; break;
                case 2: $mes = "Febrero"; break;
                case 3: $mes = "Marzo"; break;
                case 4: $mes = "Abril"; break;
                case 5: $mes = "Mayo"; break;
                case 6: $mes = "Junio"; break;
                case 7: $mes = "Julio"; break;
                case 8: $mes = "Agosto"; break;
                case 9: $mes = "Septiembre"; break;
                case 10: $mes = "Octubre"; break;
                case 11: $mes = "Noviembre"; break;
                case 12: $mes = "Diciembre"; break;
            };
            switch ($request->acta){
                case "I":
                    $titulo_sol = "ACTA DE INICIO ";
                    $titulo_sol2 = "DE CONTRATOS";
                    $membrete1 = utf8_decode("Código: F-LS-005");
                    $membrete2 = "Vigencia: 01/03/2016";
                    $membrete3 = utf8_decode("Revisión: 2");
                    $texto = "En fecha ".$dat_act->fec_act.utf8_decode(", se hace constar según Entrega");
                    $texto .= " HB-".trim($dat_act->grupo)."-".$dat_act->nro_ent."/".substr($dat_act->fk_ano_pro,2,2);
                    $texto .=", que se iniciaron los";
                    $texto .= " trabajos correspondientes al";
                    //dd($request->cont_fis);
                    if ($request->cont_fis!=NULL){
                        $texto .= " Contrato MERU Nro. HB-".trim($dat_act->grupo);
                        $texto .= "-".substr($request->cont_fis,3,2)."/".$request->ano_ord_com." ";
                    }else {
                        $texto .= " Contrato MERU Nro. HB-".trim($dat_act->grupo);
                        $texto .= "-".$dat_act->fk_nro_ord."/".$request->ano_ord_com;
                    }
                    $texto .= ", referente a la Obra de: ".utf8_decode(trim($dat_act->jus_sol));
                    $texto .= " a cargo de la Empresa/Cooperativa: ".utf8_decode( trim($request->fk_rif_con_desc)).".";

                    break;
                case "T":
                    $titulo_sol = utf8_decode("ACTA DE TERMINACIÓN");
                    $titulo_sol2 = "DE CONTRATOS";
                    $membrete1 = utf8_decode("Código: F-LS-007");
                    $membrete2 = "Vigencia: 01/03/2016";
                    $membrete3 = utf8_decode("Revisión: 2");
                    $texto = "En fecha ".$dat_act->fec_act2.utf8_decode(", se hace constar según Entrega");
                    $texto .= " HB-".trim($dat_act->grupo)."-".trim($dat_act->nro_ent)."/".substr($dat_act->fk_ano_pro,2,2).", que se culminaron satisfactoriamente los ";
                    if ($request->cont_fis!=NULL){
                        $texto .= " Contrato MERU Nro. HB-".trim($dat_act->grupo);
                        $texto .= "-".substr($request->cont_fis,3,2)."/".$request->ano_ord_com."";
                    }else {
                        $texto .= " trabajos correspondientes al Contrato MERU Nro. HB-".trim($dat_act->grupo);
                        $texto .= "-".$dat_act->fk_nro_ord."/".$request->ano_ord_com." (".trim($dat_act->num_contrato).")";
                    }
                    $texto .= ", referente a la Obra de: ".utf8_decode(trim($dat_act->jus_sol));
                    $texto .= " a cargo de la Empresa/Cooperativa: ".utf8_decode(trim($request->fk_rif_con_desc)).".";
                    break;
                case "A":
                    $titulo_sol = utf8_decode("ACTA DE ACEPTACIÓN");
                    $titulo_sol2 = "DE CONTRATOS";
                    $membrete1 = utf8_decode("Código: F-LS-050");
                    $membrete2 = "Vigencia: 01/03/2016";
                    $membrete3 = utf8_decode("Revisión: 2");
                    $texto = "El ".trim(substr($dat_act->fec_act, 8, 2))." del mes de ".$mes." del ".trim(substr($dat_act->fec_act, 0, 4));
                    $texto .= utf8_decode(", se hace constar según Entrega");
                    $texto .= " HB-".trim($dat_act->grupo)."-".trim($dat_act->nro_ent)."/".substr($dat_act->fk_ano_pro,2,2);
                    $texto .= " , y reunidos en: ".utf8_decode(trim($dat_act->lug_reunion));
                    $texto .= " en la presencia del ciudadano(a): ".trim($dat_act->nom_hb).utf8_decode(", portador(a) de la Cédula de Identidad Nro. ").trim($dat_act->ced_hb);
                    $texto .= "; quien funge como ".trim($dat_act->cargo_hb).", adscrito a la ".utf8_decode(trim($dat_gerencia->des_ger));
                    $texto .= utf8_decode(" de Hidrobolivar, C.A., se efectuó revisión Nro. ").$dat_act->revision;
                    if ($request->cont_fis!=NULL){
                        $texto .= ", de los trabajos correspondientes al Contrato Nro. ".trim($request->cont_fis)." (Contrato MERU Nro. HB-".trim($dat_act->grupo);
                        $texto .= "-".substr($request->cont_fis,3,2)."/".$request->ano_ord_com.")";
                    }else {
                        $texto .= ", de los trabajos correspondientes al Contrato MERU Nro. HB-".trim($dat_act->grupo);
                        $texto .= "-".substr($request->cont_fis,3,2)."/".$dat_act->ano_ord_com." (".trim($dat_act->num_contrato).")";
                    }
                    $texto .= utf8_decode(", culminado según valuación presentada");
                    $texto .= ", referente a la obra de: ".trim($dat_act->jus_sol);
                    $texto .= " a cargo de la Empresa/Cooperativa: ".utf8_decode(trim($request->fk_rif_con_desc)).".";
                    $texto2 = utf8_decode("Análisis del Servicio: a objeto de verificar la ejecución y terminación de la ");
                    $texto2.= utf8_decode("prestación del servicio; se procede a suscribir la presente Acta, para dejar ");
                    $texto2.= "constancia de los siguientes hechos y observaciones ".utf8_decode(trim($dat_act->observacion)).".";
                    $texto3 = utf8_decode("Recomendación: ".utf8_decode(trim($dat_act->recomen))).".";
                    $texto4 = utf8_decode("Es todo, se leyó y las partes suscriben dos ejemplares, en conformidad del acto realizado .");
                    break;
            };


            $pdf = new Fpdf('p','mm','letter','true');
            $pdf->SetLeftMargin(2);
            $pdf->SetRightMargin(2);
            $pdf->AddPage("P");

            $pdf->SetFont('Arial','',6);
            $pdf->SetY(8);
            $pdf->SetX(182);
            $pdf->Cell(17,3,$membrete1,0,'L',1);

            $pdf->SetFont('Arial','',6);
            $pdf->SetY(11);
            $pdf->SetX(179);
            $pdf->Cell(20,3,$membrete2,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',14);
            $pdf->SetY(45);
            $pdf->SetX(20);
            $pdf->Cell(175,5,$titulo_sol,0,0,'C',0);

            $pdf->SetFont('Arial','B',14);
            $pdf->SetY(50);
            $pdf->SetX(20);
            $pdf->Cell(175,5,$titulo_sol2,0,1,'C',1);


            $pdf->Image('img/hidrobolivar.jpg', 10,15,40,15,'JPG');
            $pdf->Image('img/fondonorma.png', 185,15,18,18,'PNG');

            /*Imagenes pie*/
            $pdf->Image('img/rangel.png', 5,262,25,15,'PNG');
            $pdf->Image('img/iso.png', 200,262,10,15,'PNG');

            if (($request->acta == "I") || ($request->acta == "T")){
                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(70);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto,0,'J',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','B',11);
                $pdf->SetY(120);
                $pdf->SetX(20);
                $pdf->Cell(175,4,'OBSERVACIONES',0,0,'L',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(125);
                $pdf->SetX(20);
                $pdf->MultiCell(175,4,utf8_decode(trim($dat_act->observacion)),0,'J',1);

                $pdf->SetFont('Arial','',10);
                $pdf->SetY(225);
                $pdf->SetX(20);
                $pdf->MultiCell(31,4,'Empresa: ',0,'L',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',10);
                $pdf->SetY(225);
                $pdf->SetX(51);
                $pdf->MultiCell(52,4,'Hidrobolivar, C.A.',0,'L',1);

                $pdf->SetFont('Arial','',6);
                $pdf->SetY(256);
                $pdf->SetX(184);
                $pdf->Cell(11,3,$membrete3,0,'L',1);

            }else{
                $pdf->SetFont('Arial','',6);
                $pdf->SetY(18);
                $pdf->SetX(184);
                $pdf->Cell(11,3,$membrete3,0,'L',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(40);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto,0,'J',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(120);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto2,0,'J',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(155);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto3,0,'J',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(190);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto4,0,'J',1);

                $pdf->Line(20, 198, 190, 198);//linea horizontal 1

                $pdf->Line(20, 205, 190, 205);//linea horizontal 2

                $pdf->Line(105, 198, 105, 250);//linea vertical

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',10);
                $pdf->SetY(225);
                $pdf->SetX(20);
                $pdf->MultiCell(31,4,'Gerencia: ',0,'L',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',10);
                $pdf->SetY(225);
                $pdf->SetX(51);
                if ($dat_gerencia)
                    $pdf->MultiCell(52,4,utf8_decode(trim($dat_gerencia->des_ger)));
            }

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',12);
            $pdf->SetY(200);
            $pdf->SetX(20);
            $pdf->Cell(23,3,'Por HIDROBOLIVAR, C.A.:',0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(210);
            $pdf->SetX(20);
            $pdf->MultiCell(31,4,'Nombre y Apellido: ',0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(210);
            $pdf->SetX(51);
            $pdf->MultiCell(52,4,utf8_decode(trim($dat_act->nom_hb)),0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(220);
            $pdf->SetX(20);
            $pdf->Cell(31,3,utf8_decode("Cédula: "),0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(220);
            $pdf->SetX(51);
            $pdf->Cell(52,3,trim($dat_act->ced_hb),0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(240);
            $pdf->SetX(20);
            $pdf->Cell(31,3,'Firma:',0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',12);
            $pdf->SetY(200);
            $pdf->SetX(108);
            $pdf->Cell(85,3,'Por el CONTRATISTA:',0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(210);
            $pdf->SetX(106);
            $pdf->MultiCell(31,4,'Nombre y Apellido: ',0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(210);
            $pdf->SetX(137);
            $pdf->MultiCell(52,4,utf8_decode(trim($dat_act->nom_con)),0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(220);
            $pdf->SetX(106);
            $pdf->Cell(31,3,utf8_decode('Cédula: '),0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(220);
            $pdf->SetX(137);
            $pdf->Cell(52,3,trim($dat_act->ced_con),0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(225);
            $pdf->SetX(106);
            $pdf->MultiCell(31,4,'Empresa: ',0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(225);
            $pdf->SetX(137);
            $pdf->MultiCell(52,4,utf8_decode(trim($request->fk_rif_con_desc)),0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(240);
            $pdf->SetX(106);
            $pdf->Cell(31,3,'Firma:',0,0,'L',1);

            $direccion = $dat_emp->nombre . " " . $dat_emp->rif . " - " . $dat_emp->rif . ". ";
            $direccion .= $dat_emp->direccion . " " . $dat_emp->telefono . " - " . $dat_emp->fax . ".";
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','I',6);
            $pdf->SetY(250);
            $pdf->SetX(19);
            $pdf->MultiCell(180,3,$direccion,0,'C',1);

        //    $pdf->Line(10, 150, 192, 252);

           $pdf->Line(20, 249, 196, 249);



            // header("Content-type: application/pdf");
            $pdf->Output();
            exit();
        }

    }
    public function reimprimirservicio(EncNotaEntrega $actacontratobraserv,$tipo)
    {

        if ($tipo == "A")
            $dat_act    = $actacontratobraserv->acta;
        if  ($tipo == "I")
            $dat_act    = $actacontratobraserv->actai;
        if  ($tipo == "T")
            $dat_act    = $actacontratobraserv->actat;

       if ($actacontratobraserv)
        {
        $dat_emp = DatosEmpresa::where('cod_empresa','01')
                                ->get();

            switch (substr($dat_act->fec_act, 5, 2)){
                case 1: $mes = "Enero"; break;
                case 2: $mes = "Febrero"; break;
                case 3: $mes = "Marzo"; break;
                case 4: $mes = "Abril"; break;
                case 5: $mes = "Mayo"; break;
                case 6: $mes = "Junio"; break;
                case 7: $mes = "Julio"; break;
                case 8: $mes = "Agosto"; break;
                case 9: $mes = "Septiembre"; break;
                case 10: $mes = "Octubre"; break;
                case 11: $mes = "Noviembre"; break;
                case 12: $mes = "Diciembre"; break;
            };
            switch ($dat_act->acta){
                case "I":
                    $titulo_sol     = "ACTA DE INICIO ";
                    $titulo_sol2    = "DE SERVICIOS";
                    $membrete1      = utf8_decode("Código: F-AF-054");
                    $membrete2      = "Vigencia: 23/01/2018";
                    $membrete3      = utf8_decode("Revisión: 0");
                    $texto          = "En fecha {$dat_act->fec_act}, se hace constar que se iniciaron los";
                    $texto          .= " trabajos correspondientes al proceso Nro. HBGL-O".trim($dat_act->grupo);
                    $texto          .= "-".substr($dat_act->fec_ord, 5, 2).substr($actacontratobraserv->fec_ord, 2, 2)."/".$actacontratobraserv->fk_nro_ord;
                    $texto          .= ", referente al servicio de: ".utf8_decode(trim($dat_act->jus_sol));
                    $texto          .= " a cargo de la Empresa/Cooperativa: ".utf8_decode(trim($actacontratobraserv->beneficiario->nom_ben)).".";
                    break;
                case "T":
                    $titulo_sol     =  utf8_decode("ACTA DE TERMINACIÓN");
                    $titulo_sol2    = "DE SERVICIOS";
                    $membrete1      = utf8_decode("Código: F-AF-056");
                    $membrete2      = "Vigencia: 23/01/2018";
                    $membrete3      = utf8_decode("Revisión: 0");
                    $texto          = "En fecha {$dat_act->fec_act2}, se hace constar que se culminaron satisfactoriamente los ";
                    $texto          .= "trabajos correspondientes al proceso Nro. HBGL-O".trim($dat_act->grupo);
                    $texto          .= "-".substr($actacontratobraserv->fec_ord, 5, 2).substr($actacontratobraserv->fec_ord, 2, 2)."/".$actacontratobraserv->fk_nro_ord;
                    $texto          .= ", referente al servicio de: ".trim($dat_act->jus_sol);
                    $texto          .= " a cargo de la Empresa/Cooperativa: ".utf8_decode(trim($actacontratobraserv->beneficiario->nom_ben)).".";
                    break;
                case "A":
                    $titulo_sol = utf8_decode("ACTA DE ACEPTACIÓN");
                    if ($dat_act->definitiva == '1'){
                        $titulo_sol2 = "DEFINITIVA DE SERVICIOS";
                    }else{
                        $titulo_sol2 = "DE SERVICIOS";
                    }
                    $membrete1      = utf8_decode("Código: F-AF-063");
                    $membrete2      = "Vigencia: 23/01/2018";
                    $membrete3      = utf8_decode("Revisión: 0");
                    $texto          = "El ".trim(substr($dat_act->fec_act, 8, 2))." del mes de ".$mes." del ".trim(substr($dat_act->fec_act, 0, 4));
                    $texto          .= utf8_decode(", se hace constar según Entrega ");
                    if ($dat_act->tipo_orden=='1'){
                        $texto .= "Abierta ";
                    }
                    $texto          .= $dat_act->grupo."-".$dat_act->nro_ent;
                    $texto          .= ", y reunidos en: ".utf8_decode(trim($dat_act->lug_reunion))
                            ." en la presencia del ciudadano(a): "
                            .trim($dat_act->nom_hb).""
                            .utf8_decode(", portador(a) de la Cédula de Identidad Nro. ").number_format($dat_act->ced_hb,0,",",".")
                            ."; quien funge como ".trim($dat_act->cargo_hb)
                            .", adscrito a la ".trim($dat_act->des_ger)
                            .utf8_decode(" de Hidrobolivar, C.A., se efectuó revisión Nro. ").$dat_act->revision
                            .", de los trabajos correspondientes al proceso Nro. HBGL-O".trim($dat_act->grupo)
                            ."-".substr($actacontratobraserv->fec_ord, 5, 2).substr($actacontratobraserv->fec_ord, 2, 2)."/".$actacontratobraserv->fk_nro_ord
                            .". Referente al servicio de: ".utf8_decode(trim($dat_act->jus_sol))
                            ." a cargo de la Empresa/Cooperativa: ".utf8_decode(trim($actacontratobraserv->beneficiario->nom_ben)).".";
                    $texto2         = utf8_decode("Análisis del Servicio: A objeto de verificar la ejecución y terminación de la ");
                    $texto2         .= utf8_decode("prestación del servicio; se procede a suscribir la presente Acta, para dejar ");
                    $texto2         .= "constancia de los siguientes hechos y observaciones ".utf8_decode(trim($dat_act->observacion)).".";
                    $texto3         = utf8_decode("Recomendación: ").utf8_decode(trim($dat_act->recomen)).".";
                    $texto4         = utf8_decode("Es todo, se leyó y las partes suscriben dos ejemplares, en conformidad del acto realizado.");
                    break;
            };


            $pdf = new Fpdf('p','mm','letter','true');
            $pdf->SetLeftMargin(2);
            $pdf->SetRightMargin(2);
            $pdf->AddPage("P");

            /*imagenes para reportes verticales*/
            /*Imagenes cabecera*/
            $pdf->Image('img/logo_superior_izquierdo.png', 10,7,40,16,'PNG');
            $pdf->Image('img/logo_superior_derecho.png', 185,7,13,13,'PNG');
            $pdf->Image('img/logo_superior_centro.png', 80,7,60,8,'PNG');
            /*Imagenes pie*/
            $pdf->Image('img/logo_inferior_izquierdo.png', 10,262,12,15,'PNG');
            $pdf->Image('img/logo_inferior_centro.png', 80,258,30,8,'PNG');
            $pdf->Image('img/logo_inferior_derecho.png', 200,262,10,15,'PNG');



            $pdf->SetFont('Arial','',6);
            $pdf->SetY(8);
            $pdf->SetX(160);
            $pdf->Cell(17,3,$membrete1,0,'L',1);

            $pdf->SetFont('Arial','',6);
            $pdf->SetY(11);
            $pdf->SetX(160);
            $pdf->Cell(20,3,$membrete2,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','B',14);
            $pdf->SetY(45);
            $pdf->SetX(20);
            $pdf->Cell(175,5,$titulo_sol,0,0,'C',0);

            $pdf->SetFont('Arial','B',14);
            $pdf->SetY(50);
            $pdf->SetX(20);
            $pdf->Cell(175,5,$titulo_sol2,0,1,'C',1);

            if ( ($dat_act->acta == "I") || ($dat_act->acta == "T") ){
                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(70);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto,0,'J',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','B',11);
                $pdf->SetY(150);
                $pdf->SetX(20);
                $pdf->Cell(175,4,'OBSERVACIONES',0,0,'L',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(160);
                $pdf->SetX(20);
                $pdf->MultiCell(175,4,utf8_decode(trim($dat_act->observacion)),0,'J',1);

                $pdf->SetFont('Arial','',10);
                $pdf->SetY(225);
                $pdf->SetX(20);
                $pdf->MultiCell(31,4,'Empresa: ',0,'L',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',10);
                $pdf->SetY(225);
                $pdf->SetX(51);
                $pdf->MultiCell(52,4,'Hidrobolivar, C.A.',0,'L',1);

                $pdf->SetFont('Arial','',6);
                $pdf->SetY(14);
                $pdf->SetX(160);
                $pdf->Cell(11,3,$membrete3,0,'L',1);

            }else{
                $pdf->SetFont('Arial','',6);
                $pdf->SetY(14);
                $pdf->SetX(160);
                $pdf->Cell(11,3,$membrete3,0,'L',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(70);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto,0,'J',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(130);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto2,0,'J',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(155);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto3,0,'J',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',11);
                $pdf->SetY(170);
                $pdf->SetX(20);
                $pdf->MultiCell(175,6,$texto4,0,'J',1);

                $pdf->Line(20, 188, 196, 188);//linea horizontal 1

                $pdf->Line(20, 195, 196, 195);//linea horizontal 2

                $pdf->Line(108, 188, 108, 240);//linea vertical

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',10);
                $pdf->SetY(225);
                $pdf->SetX(20);
                $pdf->MultiCell(31,4,'Gerencia: ',0,'L',1);

                $pdf->SetFillColor(255,255,255);
                $pdf->SetFont('Arial','',10);
                $pdf->SetY(225);
                $pdf->SetX(51);
                $pdf->MultiCell(52,4,utf8_decode(trim($dat_act->des_ger)),0,'L',1);
            }

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',12);
            $pdf->SetY(190);
            $pdf->SetX(20);
            $pdf->Cell(23,3,'Por HIDROBOLIVAR, C.A.:',0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(200);
            $pdf->SetX(20);
            $pdf->MultiCell(31,4,'Nombre y Apellido: ',0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(200);
            $pdf->SetX(51);
            $pdf->MultiCell(52,4,utf8_decode(trim($dat_act->nom_hb)),0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(210);
            $pdf->SetX(20);
            $pdf->Cell(31,3,utf8_decode("Cédula: "),0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(210);
            $pdf->SetX(51);
            $pdf->Cell(52,3,number_format($dat_act->ced_hb,0,",","."),0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(220);
            $pdf->SetX(20);
            $pdf->Cell(31,3,'Firma:',0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',12);
            $pdf->SetY(190);
            $pdf->SetX(110);
            $pdf->Cell(85,3,'Por el CONTRATISTA:',0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(200);
            $pdf->SetX(110);
            $pdf->MultiCell(31,4,'Nombre y Apellido: ',0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(200);
            $pdf->SetX(142);
            $pdf->MultiCell(52,4,utf8_decode(trim($dat_act->nom_con)),0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(210);
            $pdf->SetX(110);
            $pdf->Cell(31,3,utf8_decode('Cédula: '),0,0,'L',1);



            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(210);
            $pdf->SetX(142);
            if (is_numeric($dat_act->ced_con)){
                $pdf->Cell(52,3,number_format($dat_act->ced_con,0,",","."),0,0,'L',1);
            }else{
                $pdf->Cell(52,3,$dat_act->ced_con,0,0,'L',1);
            }

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(225);
            $pdf->SetX(110);
            $pdf->MultiCell(31,4,'Empresa: ',0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(225);
            $pdf->SetX(142);
            $pdf->MultiCell(52,4,utf8_decode(trim($actacontratobraserv->beneficiario->nom_ben)),0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(220);
            $pdf->SetX(110);
            $pdf->Cell(31,3,'Firma:',0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(230);
            $pdf->SetX(110);
            $pdf->Cell(31,3,'Fecha:',0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(237);
            $pdf->SetX(142);
            $pdf->MultiCell(52,4,$dat_act->fecha2,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(230);
            $pdf->SetX(20);
            $pdf->Cell(31,3,'Fecha:',0,0,'L',1);

            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','',10);
            $pdf->SetY(237);
            $pdf->SetX(51);
            $pdf->MultiCell(52,4,$dat_act->fecha2,0,'L',1);

            $direccion = $dat_emp[0]->nombre." ".$dat_emp[0]->rif." - ".$dat_emp[0]->rif.". ";
            $direccion .= $dat_emp[0]->direccion." ".$dat_emp[0]->telefono." - ".$dat_emp[0]->fax.".";
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont('Arial','I',4);
            $pdf->SetY(250);
            $pdf->SetX(45);
            $pdf->MultiCell(100,3,utf8_decode($direccion),0,'C',1);

            $pdf->Line(20, 250, 196, 250);

            header("Content-type: application/pdf");
            $pdf->Output();
            exit();
        }
        else
        {
            $html='<script language="javascript">alert("El Acta no Existe, Verifique que este Creada."); window.close();</script>';
            echo $html;
        }
    }

}
