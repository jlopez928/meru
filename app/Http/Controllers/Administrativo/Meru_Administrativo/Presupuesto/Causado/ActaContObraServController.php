<?php

namespace App\Http\Controllers\Administrativo\Meru_Administrativo\Presupuesto\Causado;
use App\Models\Administrativo\Meru_Administrativo\Compra\EncNotaEntrega;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;
use App\Models\Administrativo\Meru_Administrativo\Compra\DetNotaEntrega;
use App\Models\Administrativo\Meru_Administrativo\Compra\DetGastosNotaEntrega;
use App\Models\Administrativo\Meru_Administrativo\Compra\ComprobanteSopenDetNe;
use App\Models\Administrativo\Meru_Administrativo\Compra\ComprobanteSopen;
use App\Models\Administrativo\Meru_Administrativo\Compra\ComprobanteSopenDet;
use App\Models\Administrativo\Meru_Administrativo\Compra\Acta;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\DetGastosSolServicio;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\ConceptoContrato;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Contabilidad\CorrComprobante;
use App\Traits\funcActas;
use App\Traits\ObtenerCentroCosto;
use Illuminate\Support\Facades\DB;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Http\Controllers\Controller;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPCabeceraFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPGastoFactura;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ActaContObraServController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use funcActas;

    public function index()
    {
        $causar=1;

        return view('administrativo.meru_administrativo.presupuesto.causado.actacontobraserv.index',compact('causar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(EncNotaEntrega $encnotaentrega)
    {
        $beneficiarios = Beneficiario::where('rif_ben',$encnotaentrega->fk_rif_con )
                                      ->where('sta_reg','1')
                                      ->first();

        $responsablehb = Beneficiario::where('rif_ben',$encnotaentrega->ced_hb )
                                     ->where('tipo','E')
                                     ->where('sta_reg','1')
                                     ->first();
        // if ($encnotaentrega->grupo == 'CO'){
            $solservicio = OpSolservicio::where('ano_pro',$encnotaentrega->ano_ord_com)
                                      ->where('xnro_sol',$encnotaentrega->xnro_ord)
                                      ->select('por_iva','monto_neto')
                                    ->first();


            $entera = $encnotaentrega->detnotaentrega[0]->fk_cod_prod;

            $des_con =ConceptoContrato::where('cod_con',DB::Raw("$entera::integer"))
                                      ->select('des_con')
                                      ->first();

        // } else {
        //     $des_con = null;
        //     $solservicio = null;
        // }

        $aprobar = 0;
        $causar = 0;
        $reversar = 0;
        $statcomprob='';
        $statusent =   $this->descrip_statu($encnotaentrega->sta_ent);
      //  dd($statcomprob);
        $statcomprob = $this->descrip_statu2($encnotaentrega->stat_causacion);
       // dd($encnotaentrega->fec_com);
       // $encnotaentrega->fec_com = $encnotaentrega->fec_com->format('d/m/Y');
      // dd($statusent);
      //dd($encnotaentrega->detnotaentrega[0]->conceptocontrato);

        return view('administrativo.meru_administrativo.presupuesto.causado.actacontobraserv.show', compact('encnotaentrega','beneficiarios','responsablehb','statusent','statcomprob','solservicio','des_con','causar','aprobar','reversar'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function causar(EncNotaEntrega $encnotaentrega)
    {
        // $ano = EncNotaEntrega::selectRaw("distinct fk_ano_pro as anos")
        //                      ->get();
        $ano = RegistroControl::selectRaw("ano_pro as anos")
                              ->get();


        $beneficiarios = Beneficiario::where('rif_ben',$encnotaentrega->fk_rif_con )
                                      ->where('sta_reg','1')
                                      ->first();

        $responsablehb = Beneficiario::where('rif_ben',$encnotaentrega->ced_hb )
                                     ->where('tipo','E')
                                     ->where('sta_reg','1')
                                     ->first();
       $aprobar     = 0;
       $causar      = 1;
       $reversar    = 0;

        $statusent =   $this->descrip_statu($encnotaentrega->sta_ent);
        $statcomprob = $this->descrip_statu2($encnotaentrega->stat_causacion);


        $encnotaentrega->fec_com = Carbon::parse($encnotaentrega->fec_com)->format('d/m/Y');

        // if ($encnotaentrega->grupo == 'CO'){
            $solservicio = OpSolservicio::where('ano_pro',$encnotaentrega->ano_ord_com)
                                      ->where('xnro_sol',$encnotaentrega->xnro_ord)
                                      ->select('por_iva','monto_neto')
                                      ->first();


            $entera = $encnotaentrega->detnotaentrega[0]->fk_cod_prod;

            $des_con =ConceptoContrato::where('cod_con',DB::Raw("$entera::integer"))
                                      ->select('des_con')
                                      ->first();

        // } else {
        //     $des_con = null;
        //     $solservicio = null;
        // }


        // $statusent =   $this->descrip_statu($encnotaentrega->sta_ent);
        // $statcomprob = $this->descrip_statu2($encnotaentrega->stat_causacion);
        //$encnotaentrega->fec_com = Carbon::parse($encnotaentrega->fec_com)->format('d/m/Y');

        //dd($encnotaentrega->detnotaentrega());

        return view('administrativo.meru_administrativo.presupuesto.causado.actacontobraserv.show', compact('encnotaentrega','beneficiarios','responsablehb','causar','aprobar','reversar','statusent','statcomprob','solservicio','des_con'));
    }


    public function causar_ejecutar(EncNotaEntrega $encnotaentrega)
    {



        //Buscar(5)

        //se valida que exista el número de entrega
        if (empty($encnotaentrega->nro_ent)) {
            $msj = 'No. de Entrega No Existe. Verifique su Información.';
            alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
            return redirect()->back()->withInput();
        }

        //dd($encnotaentrega->beneficiario->rif_ben);
        $documento = "Entrega";

        //se valida que la entrega tenga acta de aceptación
        if ( $encnotaentrega->sta_ent !=3 ){
            $msj = 'Entrega con Status INVALIDO para OPERACION.\n Por Favor Verifique que la Entrega tenga estatus Con Acta de Aceptación.';
            alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
            return redirect()->back()->withInput();
        }

        // grabar('causar')

        //se valida para fondo Externos la existencia de cuenta
        if ($encnotaentrega->fondos=='E' && $encnotaentrega->cuenta_contable == ''){
            $msj = 'Debe Introducir la Cuenta Contable si el Fondo es Externo.';
            alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
            return redirect()->back()->withInput();
        }

        //Causar($db,$conn,&$datosDetalle,$tablasDetalle,&$msj)

                //LlamadoCausar($db,$conn,$datosDetalle,$tablasDetalle,$msj);


        /*com_encnotaentrega */
        $grupo					= $encnotaentrega->grupo;
        $nro_ent				= $encnotaentrega->nro_ent;
        $fk_ano_pro				= $encnotaentrega->fk_ano_pro;
        $xnro_ord				= $encnotaentrega->xnro_ord;
        $fec_ent				= $encnotaentrega->fec_ent;
        $base_imponible			= $encnotaentrega->base_imponible;
        $base_exenta			= $encnotaentrega->base_exenta;
        $fondos					= $encnotaentrega->fondos? "I" : $encnotaentrega->fondos;
        $mto_anticipo			= $encnotaentrega->mto_anticipo;
        $porc_ant				= $encnotaentrega->porc_ant;
        $antc_amort				= $encnotaentrega->antc_amort;
        $xnro_ent				= $encnotaentrega->xnro_ent;  //$grupo."-".$nro_ent;
        $mto_iva				= $encnotaentrega->mto_iva;
        $mto_ent				= $encnotaentrega->mto_ent;
        $mto_siniva				= $encnotaentrega->mto_siniva;
        $fk_rif_con				= $encnotaentrega->fk_rif_con;
        $ano_ord_com			= $encnotaentrega->ano_ord_com;
        $tipo_orden				= $encnotaentrega->tipo_orden;
        $fk_tip_ord				= $encnotaentrega->fk_tip_ord;
        $cuenta_contable		= $encnotaentrega->cuenta_contable ? "null" : "'".$encnotaentrega->cuenta_contable."'";

        /*com_detnotaentrega*/
        $totrecep               = $encnotaentrega->detnotaentrega[0]->totrecep;
        $por_iva                = $encnotaentrega->detnotaentrega[0]->por_iva;

        /*com_detgastosnotaentrega*/
        $mto_cau                = $encnotaentrega->detgastosnotaentrega[0]->mto_cau;
        //$causar			        = $encnotaentrega->detgastosnotaentrega[0]->causar;
       //$causar			        = 'SI';

       /*otro origen*/


        $nro_entrega			= $grupo."-".$nro_ent."-".$fk_ano_pro;
        $ano_fiscal				= 2021; //ano_pro
        $vueltas				= 0;
        $vueltas2				= 0;
        $control				= 0;
        $usuario				= auth()->user()->id;
        $fecha_sistema			= $this->FechaSistema($ano_fiscal, "Y/m/d H:i:s");
        $tip_ope			   	= 50;
        $sol_tip			   	= 'CO';
        $num_doc			   	= $xnro_ord;
        $concepto				= "CAUSADO SOBRE COMPROMISO CONTRATO O/S";
        $tipo_doc               = 0;
        $monto_neto_doc         = 0;
        $cta_cont               = '';
        $cta_x_pagar            = '';
        $reverso                = 0;
        $tip_cod                = 2;
        $cod_com                = '';
        DB::beginTransaction();

        try {

            $msj = "Error Consultado Datos de Contrato.";

           //dd($ano_ord_com.'/'.$xnro_ord);
            $solservicio = OpSolservicio::where('ano_pro',$ano_ord_com)
                                ->where('xnro_sol',$xnro_ord)
                                ->select('por_iva','monto_neto')
                                ->get();

           //dd(count($solservicio));
            if (count($solservicio)>0){
                $por_iva			= $solservicio[0]->por_iva;
                $monto_neto_doc		= $solservicio[0]->monto_neto;
            }

            if ($grupo == 'CO')
				$tipo_doc = 5;


            $newcxpcabecerafactura  = new CxPCabeceraFactura;


			$newcxpcabecerafactura->ano_pro            =    $fk_ano_pro;
			$newcxpcabecerafactura->rif_prov           =    $fk_rif_con;
			$newcxpcabecerafactura->tipo_doc            =   $tipo_doc;
			$newcxpcabecerafactura->nro_doc            =    $xnro_ord;
			$newcxpcabecerafactura->ano_doc            =    $ano_ord_com;
			$newcxpcabecerafactura->doc_asociado       =    $xnro_ent;
			$newcxpcabecerafactura->ano_doc_asociado   =    $fk_ano_pro;
			$newcxpcabecerafactura->tipo_pago          =    'T';
			$newcxpcabecerafactura->fondo              =    $fondos;
			$newcxpcabecerafactura->base_imponible     =    $base_imponible;
			$newcxpcabecerafactura->base_excenta       =    $base_exenta;
            $newcxpcabecerafactura->porcentaje_iva     =    $por_iva;
			$newcxpcabecerafactura->mto_nto            =    $mto_siniva;
			$newcxpcabecerafactura->mto_iva            =    $mto_iva;
			$newcxpcabecerafactura->mto_tot            =    $mto_ent;
            $newcxpcabecerafactura->por_anticipo       =    $porc_ant;
			$newcxpcabecerafactura->monto_anticipo     =    $mto_anticipo;
            $newcxpcabecerafactura->monto_amortizacion =    $antc_amort;
			$newcxpcabecerafactura->usuario            =    $usuario;
            $newcxpcabecerafactura->fecha              =    $fecha_sistema;
			$newcxpcabecerafactura->statu_proceso      =    '1';
            $newcxpcabecerafactura->fec_sta            =    $fecha_sistema;
			$newcxpcabecerafactura->cuenta_contable    =    $cuenta_contable;
            $newcxpcabecerafactura->nota_entrega_prov  =    'N/A';
			$newcxpcabecerafactura->monto_neto_doc     =    $monto_neto_doc;

            $msj = "Error Actualizando Cabecera Factura.";

            //dd($newcxpcabecerafactura);

			$newcxpcabecerafactura->save();

            foreach($encnotaentrega->detnotaentrega as $detnotaentregaItem)
            {

                if ($detnotaentregaItem->gasto == 'Si' || $detnotaentregaItem->gasto == '1'){
                    $gasto = 1;
                }
                else{
                    $gasto = 0;
                }
                $cta_cont = $detnotaentregaItem->cta_cont;
                $cta_x_pagar = $detnotaentregaItem->cta_x_pagar;

                //dd($detnotaentregaItem->fk_cod_prod);

                $dne =  DetNotaEntrega::where('fk_ano_pro',$encnotaentrega->fk_ano_pro )
                                      ->where('grupo'       ,$encnotaentrega->grupo)
                                      ->where('nro_ent'     ,$encnotaentrega->nro_ent)
                                      ->where('fk_cod_prod' ,$detnotaentregaItem->fk_cod_prod)
                                      ->where('tip_cod'     ,$detnotaentregaItem->tip_cod)
                                      ->where('cod_pryacc'  ,$detnotaentregaItem->cod_pryacc)
                                      ->where('cod_obj'     ,$detnotaentregaItem->cod_obj)
                                      ->where('gerencia'    ,$detnotaentregaItem->gerencia)
                                      ->where('unidad'      ,$detnotaentregaItem->unidad)
                                      ->where('cod_par'     ,$detnotaentregaItem->cod_par)
                                      ->where('cod_gen'     ,$detnotaentregaItem->cod_gen)
                                      ->where('cod_esp'     ,$detnotaentregaItem->cod_esp)
                                      ->where('cod_sub'     ,$detnotaentregaItem->cod_sub)
                                      ->first();

                $dne->gasto       = $gasto;
                $dne->cta_cont    = $cta_cont;
                $dne->cta_x_pagar = $cta_x_pagar;

                $msj = "Error Actualizando en el detalle de la Nota de Entrega";

                $dne->save();

                if ($ano_fiscal != $encnotaentrega->fk_ano_pro){
                    $centro = $this->ObtenerCentroCosto($detnotaentregaItem->fk_ano_pro,$detnotaentregaItem->tip_cod,$detnotaentregaItem->cod_pryacc,$detnotaentregaItem->cod_obj,$detnotaentregaItem->gerencia,$detnotaentregaItem->unidad);

                    if (!$centro){
                        $msj = "Error al Intentar Obtener Centro de Costo Nuevo. \n";
                        return false;
                    }
                    else{
                        extract($centro); // $tip_cod, $cod_pryacc, $cod_obj, $gerencia, $unidad
                    }
                }
                //dd($msj);

				$premaestroley =  MaestroLey::where('ano_pro',$ano_fiscal)
                                            ->where('cod_com',$cod_com)
                                            ->get();
                //dd($premaestroley);
                if (count($premaestroley) != 0){

                    $cod_com = $this->armar_cod_com($premaestroley->tip_cod,$premaestroley->cod_pryacc,$premaestroley->cod_obj,$premaestroley->gerencia,$premaestroley->unidad,$detnotaentregaItem->cod_par,$detnotaentregaItem->cod_gen,$detnotaentregaItem->cod_esp,$detnotaentregaItem->cod_sub);
                    //dd($cod_com);
                    $saldo = ($detnotaentregaItem->totrecep - $encnotaentrega->antc_amort);
                    //dd($saldo);
                    if ($saldo > 0){
                        /*MOvimiento contable*/
                        $control = 0;
                        if ($premaestroley->tip_cod < 10){$tip_co = '0'.$premaestroley->tip_cod;}else{$tip_co = $premaestroley->tip_cod;};
                        if ($premaestroley->cod_pryacc < 10){$proy_ac = '0'.$premaestroley->cod_pryacc;}else{$proy_ac = $premaestroley->cod_pryacc;};
                        if ($premaestroley->cod_obj < 10){$cod_obje = '0'.$premaestroley->cod_obj;}else{$cod_obje = $premaestroley->cod_obj;};
                        if ($premaestroley->gerencia < 10){$gerenc = '0'.$premaestroley->gerencia;}else{$gerenc = $premaestroley->gerencia;};
                        if ($premaestroley->unidad < 10){$unid = '0'.$premaestroley->unidad;}else{$unid = $premaestroley->unidad;};

                        $mto_tra1 =  DetNotaEntrega::where('fk_ano_pro', $encnotaentrega->fk_ano_pro )
                                                    ->where('grupo',     $encnotaentrega->grupo)
                                                    ->where('nro_ent',   $encnotaentrega->nro_ent)
                                                    ->where('cta_cont',  $detnotaentregaItem->cta_cont)
                                                    ->sum('totrecep');
                                                    // ->first();
                         //dd( $mto_tra1);
                        if ($mto_tra1 == 0){
                            if ($detnotaentregaItem->totrecep > 0){
                                $vueltas += 1;
                            }
                        }

                        $mto_tra2 =  DetNotaEntrega::where('fk_ano_pro',   $encnotaentrega->fk_ano_pro )
                                                   ->where('grupo',        $encnotaentrega->grupo)
                                                   ->where('nro_ent',      $encnotaentrega->nro_ent)
                                                   ->where('cta_x_pagar',  $detnotaentregaItem->cta_x_pagar)
                                                   ->selectRaw("sum(totrecep- $encnotaentrega->antc_amort)as total")
                                                   ->first();
                                                //    ->get();
                        //dd( $mto_tra2);
                        if ($mto_tra2->total == 0){
                            if ($detnotaentregaItem->totrecep > 0){
                                $vueltas2 += 1;
                            }
                        }

                    }
                   // dd( $mto_tra2);
                }else{
                    $msj = "Error. La Partida $cod_com No esta formulada en Pre-MaestroLey. \n";
                }
            }

            foreach($encnotaentrega->detgastosnotaentrega as $detgastosnotaentregaItem)
            {
                $premaestroley =  MaestroLey::where('ano_pro',$ano_fiscal)
                                            ->where('cod_com',$detgastosnotaentregaItem->cod_com)
                                            ->get();
               // dd($premaestroley);
                if (count($premaestroley) != 0)
                     $cod_com_viejo = $this->armar_cod_com($premaestroley[0]->tip_cod,$premaestroley[0]->cod_pryacc,$premaestroley[0]->cod_obj,$premaestroley[0]->gerencia,$premaestroley[0]->unidad,$detgastosnotaentregaItem->cod_par,$detgastosnotaentregaItem->cod_gen,$detgastosnotaentregaItem->cod_esp,$detgastosnotaentregaItem->cod_sub);

                    //dd($cod_com_viejo);
                if ($ano_fiscal != $fk_ano_pro){
                    $centro = $this->ObtenerCentroCosto($detnotaentregaItem->fk_ano_pro,$detnotaentregaItem->tip_cod,$detnotaentregaItem->cod_pryacc,$detnotaentregaItem->cod_obj,$detnotaentregaItem->gerencia,$detnotaentregaItem->unidad);

                    //dd($centro);
                    if (!$centro){
                        $msj = "Error al Intentar Obtener Centro de Costo Nuevo. \n";
                        return false;
                    }
                    else{
                        extract($centro);// $tip_cod, $cod_pryacc, $cod_obj, $gerencia, $unidad
                       // dd($centro);
                    }
                }

                $cod_com = $this->armar_cod_com($premaestroley[0]->tip_cod,$premaestroley[0]->cod_pryacc,$premaestroley[0]->cod_obj,$premaestroley[0]->gerencia,$premaestroley[0]->unidad,$detgastosnotaentregaItem->cod_par,$detgastosnotaentregaItem->cod_gen,$detgastosnotaentregaItem->cod_esp,$detgastosnotaentregaItem->cod_sub);

                if ($detgastosnotaentregaItem->causar == 1){

                    $movpre = DB::select("SELECT * FROM movimientopresupuestario('$ano_fiscal', '$cod_com', '$sol_tip',
                    '$tip_ope', '$num_doc', '$detgastosnotaentregaItem->mto_cau', '', '$concepto', '$reverso',
                    '$usuario', '$ano_ord_com', '$nro_entrega', '', '0', '$fecha_sistema')");

                    if(!$movpre){
                        $msj = 'Ocurrio el siguiento error al ejecutar Movimiento Presupuestario:\n'.
                                '\t'.$error.'\n'.
                                'Comuniquese con su Administrador de sistema.';
                        //return false;
                    }
                }

                if ($detgastosnotaentregaItem->causar == 1)
                     $causar = 'SI';
                else
                    $causar = 'NO';

                //Detallle de Gasto de la Factura.
                if ($causar == 'SI'){
                    $causar2 = 0;
                }else{
                    $causar2 = 1;
                }

                $cod_com_iva = $this->ObtenerCodComIva($encnotaentrega->fk_ano_pro);

                //dd( $cod_com_iva.'/'.$cod_com);
                if ($cod_com!=$cod_com_iva){
                    $gasto = $this->ObtenerGasto($encnotaentrega->fk_ano_pro,$encnotaentrega->grupo,$encnotaentrega->nro_ent,
                                                 $cod_com_viejo);
                }else{
                    $gasto = 0;
                }
                //dd($gasto);
                $newcxpgastofactura  = new CxPGastoFactura;

                $newcxpgastofactura->ano_pro           = $encnotaentrega->fk_ano_pro;
                $newcxpgastofactura->rif_prov          = $encnotaentrega->fk_rif_con;
                $newcxpgastofactura->ano_doc_asociado  = $encnotaentrega->fk_ano_pro;
                $newcxpgastofactura->doc_asociado      = $encnotaentrega->xnro_ent;
                $newcxpgastofactura->tip_cod           = $detgastosnotaentregaItem->tip_cod;
                $newcxpgastofactura->cod_pryacc        = $detgastosnotaentregaItem->cod_pryacc;
                $newcxpgastofactura->cod_obj           = $detgastosnotaentregaItem->cod_obj;
                $newcxpgastofactura->gerencia          = $detgastosnotaentregaItem->gerencia;
                $newcxpgastofactura->unidad            = $detgastosnotaentregaItem->unidad;
                $newcxpgastofactura->cod_par           = $detgastosnotaentregaItem->cod_par;
                $newcxpgastofactura->cod_gen           = $detgastosnotaentregaItem->cod_gen;
                $newcxpgastofactura->cod_esp           = $detgastosnotaentregaItem->cod_esp;
                $newcxpgastofactura->cod_sub           = $detgastosnotaentregaItem->cod_sub;
                $newcxpgastofactura->cod_com           = $detgastosnotaentregaItem->cod_com;
                $newcxpgastofactura->gasto             = $gasto;
                $newcxpgastofactura->mto_tra           = $detgastosnotaentregaItem->$mto_cau;
                $newcxpgastofactura->causar            = $causar2;

                $msj = "Error Actualizando Gasto de Factura.";

                $newcxpgastofactura->save();

               // Actualizo partidas en caso del cambio del iva
                 //dd($detgastosnotaentregaItem->mto_cau);
                $detgasnotent =  DetGastosNotaEntrega::where('ano_pro', $encnotaentrega->fk_ano_pro )
                                                     ->where('grupo',   $encnotaentrega->grupo)
                                                     ->where('nro_ent', $encnotaentrega->nro_ent)
                                                     ->where('cod_com', $cod_com_viejo)
                                                     ->update(['mto_cau' => $detgastosnotaentregaItem->mto_cau]);


                $msj = "Error al Actualizar en el Encabezado del Gasto.";


            }  //fin detgastosnotaentrega foreach

            $ctaxcobrar = Beneficiario::where('rif_ben',$encnotaentrega->fk_rif_con)
                                      ->select('cta_x_cobrar')
                                      ->get();

            if(!$ctaxcobrar){
                $msj = 'Error Buscando Cuenta por Coobrar del tc_amortBeneficiario';
                return false;
            }else{
                $cta_x_cobrar = $ctaxcobrar[0]->cta_x_cobrar;
            }

            if ($encnotaentrega->antc_amort > 0){
				if($cta_x_cobrar==''){
					$msj = 'Error. No existe la Cuenta por Cobrar del Beneficiario';
					return false;
				}
		    }

            if ($fondos=='I'){
				$con_com=1;
				//$n=count($lgastos); // Filas reportadas en Detalle de Gastos

                //Debito
                foreach($encnotaentrega->detnotaentrega as $detnotentItem)
                {
                    //dd($detnotentItem->cta_cont);
                    $cta_cont		= empty($detnotentItem->cta_cont) ? "null" : "'$detnotentItem->cta_cont'";
					$ctro_costo		= empty($detnotentItem->ctro_costo) ? "null" : "'$cod_com_viejo'";

                    $con_doc		= "Para Realizar el Asiento Correspondiente a Causado de CO $nro_entrega";

                    $compsopendetne  = new ComprobanteSopenDetNe;

                    // DEBITOS
                    $compsopendetne->ano_pro     = $detnotentItem->fk_ano_pro;
                    $compsopendetne->con_com     = $con_com;
                    $compsopendetne->cod_cta     = $detnotentItem->cta_cont;
                    $compsopendetne->tip_mto     = 'DB';
                    $compsopendetne->mto_doc     = $detgastosnotaentregaItem->mto_tra;
				    $compsopendetne->status      = '1';
                    $compsopendetne->grupo       = $detnotentItem->grupo;
                    $compsopendetne->nro_ent     = $detnotentItem->nro_ent;
                    $compsopendetne->cod_aux     = $fk_rif_con;
                    $compsopendetne->tip_doc     ='12';
					$compsopendetne->nro_doc     = $nro_entrega;
                    $compsopendetne->fec_doc     = $encnotaentrega->fec_ent;
                    $compsopendetne->con_doc     = $con_doc;
                    $compsopendetne->ctro_costo  = $ctro_costo;
                    $compsopendetne->encnotaentrega_id  = $encnotaentrega->id;

                    $compsopendetne->save();
                }

            	//SI HAY ANTICIPO
				if ($encnotaentrega->antc_amort > 0){
					$con_doc		= "Para Realizar el Asiento Correspondiente a Causado de CO $nro_entrega";

                    $compsopendetne2 = new ComprobanteSopenDetNe;

                    // DEBITOS
                    $compsopendetne2->ano_pro     = $detnotentItem->fk_ano_pro;
                    $compsopendetne2->con_com     = 2;
                    $compsopendetne2->cod_cta     = $detnotentItem->cta_x_pagar;
                    $compsopendetne2->tip_mto     = 'CR';
                    $compsopendetne2->mto_doc     = $encnotaentrega->antc_amort;
				    $compsopendetne2->status      = '1';
                    $compsopendetne2->grupo       = $detnotentItem->grupo;
                    $compsopendetne2->nro_ent     = $detnotentItem->nro_ent;
                    $compsopendetne2->cod_aux     = $fk_rif_con;
                    $compsopendetne2->tip_doc     ='12';
					$compsopendetne2->nro_doc     = $nro_entrega;
                    $compsopendetne2->fec_doc     = $encnotaentrega->fec_ent;
                    $compsopendetne2->con_doc     = $dcon_doc;
                    $compsopendetne2->ctro_costo  = $ctro_costo;
                    $compsopendetne2->encnotaentrega_id  = $encnotaentrega->id;

                    $msj = "Error Insertando Comprobante Revision2";

                    $detgasnotent->save();  // Insertar Detalle de Comprobante

					$con_com++;
				}

                // CREDITOS
                foreach($encnotaentrega->detgastosnotaentrega as $detgasnotentItem)
                {
                     // dd($ctro_costo);
                     $ctro_costo = $this->ObtenerCentroCosto($encnotaentrega->fk_ano_pro,$detgasnotentItem->tip_cod,$detgasnotentItem->cod_pryacc,$detgasnotentItem->cod_obj,$detgasnotentItem->gerencia,$detgasnotentItem->unidad);
                     //dd($ctro_costo);

                   if ($detgasnotentItem->causar != 1) {

                        $compsopendetne3= new ComprobanteSopenDetNe;
                        //dd($detgasnotentItem->mto_cau.'/'. $con_com.'/'.$ctro_costo);
                        $compsopendetne3->ano_pro     = $detgasnotentItem->ano_pro;
                        $compsopendetne3->con_com     = $con_com;
                        $compsopendetne3->cod_cta     = $detnotentItem->cta_x_pagar;
                        $compsopendetne3->tip_mto     = 'CR';
                        $compsopendetne3->mto_doc     = $detgasnotentItem->mto_cau;
                        $compsopendetne3->status      = '1';
                        $compsopendetne3->grupo       = $detnotentItem->grupo;
                        $compsopendetne3->nro_ent     = $detnotentItem->nro_ent;
                        $compsopendetne3->cod_aux     = $fk_rif_con;
                        $compsopendetne3->tip_doc     ='12';
                        $compsopendetne3->nro_doc     = $nro_entrega;
                        $compsopendetne3->fec_doc     = $encnotaentrega->fec_ent;
                        $compsopendetne3->con_doc     = $con_doc;
                        $compsopendetne3->ctro_costo  = $ctro_costo;
                        $compsopendetne3->encnotaentrega_id  = $encnotaentrega->id;

                        $msj = "Error Insertando Comprobante Revision3";
                        //dd( $con_com);
                        $compsopendetne3->save();
                   }else{
                        $compsopendetne->ano_pro     = $detnotentItem->fk_ano_pro;
                        $compsopendetne->con_com     = 1;
                        $compsopendetne->cod_cta     = $detnotentItem->cta_cont;
                        $compsopendetne->tip_mto     = 'DB';
                        $compsopendetne->mto_doc     = $detgasnotentItem->mto_cau;
                        $compsopendetne->status      = '1';
                        $compsopendetne->grupo       = $detnotentItem->grupo;
                        $compsopendetne->nro_ent     = $detnotentItem->nro_ent;
                        $compsopendetne->cod_aux     = $fk_rif_con;
                        $compsopendetne->tip_doc     ='12';
                        $compsopendetne->nro_doc     = $nro_entrega;
                        $compsopendetne->fec_doc     = $encnotaentrega->fec_ent;
                        $compsopendetne->con_doc     = $con_doc;
                        $compsopendetne->ctro_costo  = $ctro_costo;
                        $compsopendetne->encnotaentrega_id  = $encnotaentrega->id;

                        $msj = "Error Insertando Comprobante Fondo Externo 1";

                        $compsopendetne->save();
                   }
                    $con_com++;
                }
			}else { //$fondos!='I'

				//SI HAY ANTICIPO
				if ($antc_amort > 0){
					$ctro_costo = empty($ctro_costo) ? "null" : "'$ctro_costo'";
					$con_doc = "Para Realizar el Asiento Correspondiente a Causado de CO $nro_entrega";

                    $compsopendetne= new ComprobanteSopenDetNe;

                    // DEBITOS
                    $compsopendetne->ano_pro     = $detnotentItem->fk_ano_pro;
                    $compsopendetne->con_com     = 1;
                    $compsopendetne->cod_cta     = $detnotentItem->cta_cont;
                    $compsopendetne->tip_mto     = 'DB';
                    $compsopendetne->mto_doc     = $detgasnotentItem->antc_amort;
				    $compsopendetne->status      = '1';
                    $compsopendetne->grupo       = $detnotentItem->grupo;
                    $compsopendetne->nro_ent     = $detnotentItem->nro_ent;
                    $compsopendetne->cod_aux     = $fk_rif_con;
                    $compsopendetne->tip_doc     ='12';
					$compsopendetne->nro_doc     = $nro_entrega;
                    $compsopendetne->fec_doc     = $encnotaentrega->fec_ent;
                    $compsopendetne->con_doc     = $con_doc;
                    $compsopendetne->ctro_costo  = $ctro_costo;
                    $compsopendetne->encnotaentrega_id  = $encnotaentrega->id;

                    $msj = "Error Insertando Comprobante Fondo Externo 1";

                    $compsopendetne->save();

                    $con_com++;

                    $compsopendetne = new ComprobanteSopenDetNe;


                    $compsopendetne->ano_pro     = $detnotentItem->fk_ano_pro;
                    $compsopendetne->con_com     = 2;
                    $compsopendetne->cod_cta     = $detgasnotentItem->cta_x_cobrar;
                    $compsopendetne->tip_mto     = 'CR';
                    $compsopendetne->mto_doc     = $encnotaentrega->antc_amort;
				    $compsopendetne->status      = '1';
                    $compsopendetne->grupo       = $detnotentItem->grupo;
                    $compsopendetne->nro_ent     = $detnotentItem->nro_ent;
                    $compsopendetne->cod_aux     = $fk_rif_con;
                    $compsopendetne->tip_doc     ='12';
					$compsopendetne->nro_doc     = $nro_entrega;
                    $compsopendetne->fec_doc     = $encnotaentrega->fec_ent;
                    $compsopendetne->con_doc     = $con_doc;
                    $compsopendetne->ctro_costo  = $ctro_costo;
                    $compsopendetne->encnotaentrega_id  = $encnotaentrega->id;

                    $msj = "Error Insertando Comprobante Fondo Externo 2";

                    $compsopendetne->save();

                    $con_com++;
				}
			}

            $fecha_comprobante = '';

			// ACTUALIZAR STATUS DE NOTA DE ENTREGA
			$sta_ent = '7';
			if ($fondos=='I'){
				$stat_causacion			= '7';
			}
			else{
				if ($encnotaentrega->antc_amort > 0){
					$stat_causacion		= '7';
				}
				else{
					$stat_causacion		= '6';
					$fecha_comprobante	= " fec_com='$fecha_sistema', ";
				}
			}

            $encnotent = EncNotaEntrega::where('fk_ano_pro',$fk_ano_pro)
                                        ->where('grupo',$grupo)
                                        ->where('nro_ent',$nro_ent)
                                        ->first();

            $encnotent->fec_ant			= $fecha_sistema;
            $encnotent->fec_sta			= $fecha_sistema;
            $encnotent->fec_com			= $fecha_sistema;
            $encnotent->sta_ent			= $sta_ent;
            $encnotent->stat_causacion	= $stat_causacion;
            $encnotent->mto_iva			= $mto_iva;
            $encnotent->mto_ent			= $mto_ent;
            $encnotent->sta_ant			= $sta_ent;
            $encnotent->ano_causado		= $ano_fiscal;
            $encnotent->fondos			= $fondos;
            $encnotent->cuenta_contable	= $cuenta_contable;
            $encnotent->usu_sta			= $usuario;

            $encnotent->save();


            DB::commit();

            alert()->Success('Acta causada exitosamente<br><b>' . $encnotaentrega->fk_ano_pro . ' - ' . $encnotaentrega->grupo . ' - ' . $encnotaentrega->nro_ent. '</b>');
            return redirect()->route('presupuesto.causado.actacontobraserv.index');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            $msj = 'A ocurrido un ERROR en la transaccion.\\n Por Favor Intente de Nuevo.';

            alert()->Error('Transacci&oacute;n Fallida<br>'.$msj.' '. $e->getMessage());
            return redirect()->back()->withInput();
        }

    }


    public function aprobar(EncNotaEntrega $encnotaentrega)
    {
        // $ano = EncNotaEntrega::selectRaw("distinct fk_ano_pro as anos")
        //                      ->get();
        $ano = RegistroControl::selectRaw("ano_pro as anos")
                              ->get();


        $beneficiarios = Beneficiario::where('rif_ben',$encnotaentrega->fk_rif_con )
                                      ->where('sta_reg','1')
                                      ->first();

        $responsablehb = Beneficiario::where('rif_ben',$encnotaentrega->ced_hb )
                                     ->where('tipo','E')
                                     ->where('sta_reg','1')
                                     ->first();
        $aprobar    = 1;
        $causar     = 0;
        $reversar   = 0;
        if ($encnotaentrega->grupo == 'CO'){
            $solservicio = OpSolservicio::where('ano_pro',$encnotaentrega->ano_ord_com)
                                      ->where('xnro_sol',$encnotaentrega->xnro_ord)
                                      ->select('por_iva','monto_neto')
                                    ->first();


            $entera = $encnotaentrega->detnotaentrega[0]->fk_cod_prod;

            $des_con =ConceptoContrato::where('cod_con',DB::Raw("$entera::integer"))
                                      ->select('des_con')
                                      ->first();

        } else {
            $des_con = null;
            $solservicio = null;
        }


        $statusent =   $this->descrip_statu($encnotaentrega->sta_ent);
        $statcomprob = $this->descrip_statu2($encnotaentrega->stat_causacion);
        //$encnotaentrega->fec_com = Carbon::parse($encnotaentrega->fec_com)->format('d/m/Y');

        return view('administrativo.meru_administrativo.presupuesto.causado.actacontobraserv.show', compact('encnotaentrega','beneficiarios','responsablehb','causar','aprobar','reversar','statusent','statcomprob','solservicio','des_con'));
    }



    public function aprobar_ejecutar(EncNotaEntrega $encnotaentrega)
    {
        DB::beginTransaction();

        try
		{
            $fk_ano_pro				= $encnotaentrega->fk_ano_pro;
            $ano_ord_com			= $encnotaentrega->ano_ord_com;
            $grupo					= $encnotaentrega->grupo;
            $nro_ent				= $encnotaentrega->nro_ent;
            $porc_ant				= $encnotaentrega->porc_ant;
            $antc_amort				= $encnotaentrega->antc_amort;
            $fk_rif_con				= $encnotaentrega->fk_rif_con;
            $fec_ent				= $encnotaentrega->fec_ent;
            $base_imponible			= $encnotaentrega->base_imponible;
            $base_exenta			= $encnotaentrega->base_exenta;
            $mto_siniva				= $encnotaentrega->mto_siniva;
            $mto_iva				= $encnotaentrega->mto_iva;
            $mto_ent				= $encnotaentrega->mto_ent;
            $mto_anticipo			= $encnotaentrega->mto_anticipo;
            $num_fac				= $encnotaentrega->num_fac;
            $fec_com				= $encnotaentrega->fec_com;
            $ano_fiscal				= 2021; //ano_pro
            $mes					= date("m");
            $xnro_ent				= $grupo.'-'.$nro_ent;
            $stat_causacion			= 6;
            $usuario				= auth()->user()->id;
            $fecha_sistema			= $this->FechaSistema($ano_fiscal, "Y/m/d H:i:s");

            /* Se valida que el estatus de entrada sea  4 o 7 para poder aprobar*/
            if (($encnotaentrega->sta_ent != '4') && ($encnotaentrega->sta_ent != '7')) {
                $msj = 'Entrega con Status INVALIDO para OPERACION.\n Por Favor Verifique que la Entrega tenga estatus Causada o Facturada.';
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                return redirect()->back()->withInput();
            }

            $msj = "Error Consultando Balance del Comprobante";

            /* Verifico que el asiento contable este balanceado */
            $queryCR = ComprobanteSopenDetNe::where('ano_pro',$fk_ano_pro)
                                        ->where('grupo',$grupo)
                                        ->where('nro_ent',$nro_ent)
                                        ->where('tip_mto','CR')
                                        ->selectRaw("sum(mto_doc) as mto_doc")
                                        ->first();


            $queryDB = ComprobanteSopenDetNe::where('ano_pro',$fk_ano_pro)
                                        ->where('grupo',$grupo)
                                        ->where('nro_ent',$nro_ent)
                                        ->where('tip_mto','DB')
                                        ->selectRaw("sum(mto_doc)*-1 as mto_doc")
                                        ->first();

            $monto = $queryCR->mto_doc + $queryDB->mto_doc;

            //dd($monto);
            // dd($queryCR->mto_doc.'/'.$queryDB->mto_doc);


            if($monto != 0){
                $msj ="Error. El Comprobante Contable no esta Balanceado, Contacte a su Administrador de Sistema.";
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                return redirect()->back()->withInput();
            }

            $msj = "Error Consultando Plan Contable";

            /* Verifico que todas las cuenta contables esten en el plan contable */
            $query = ComprobanteSopenDetNe::where('ano_pro',$fk_ano_pro)
                                          ->where('grupo',$grupo)
                                          ->where('nro_ent',$nro_ent)
                                          ->whereRaw("ano_pro||'-'||cod_cta not in (SELECT ano_cta||'-'||cod_cta from plancontable)")
                                          ->selectRaw("count(ano_pro||'-'||cod_cta) as total")
                                          ->first();

                                    // dd($query);
                                    //   PlanContable::whereRaw(" ano_cta||'-'||cod_cta")

            if ($query->total != 0){
                $msj ="Error. Una de las Cuentas Contables no se encuentra en el Plan Contable.";
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                return redirect()->back()->withInput();
            }

            $msj = "Error Consultando el Comprobante de Revision";

            /* Se consulta el comprobante creado en el causado */
            $query = ComprobanteSopenDetNe::where('ano_pro',$fk_ano_pro)
                                          ->where('grupo',$grupo)
                                          ->where('nro_ent',$nro_ent)
                                          ->where('status','1')
                                          ->select('ano_pro', 'con_com', 'cod_cta', 'tip_mto', 'mto_doc', 'status', 'grupo', 'nro_ent','cod_aux', 'tip_doc',
                                                   'nro_doc', 'fec_doc', 'con_doc', 'ctro_costo')
                                          ->get();

            if($query){

                $msj = "Error Consultando el Correlativo de Comprobante";

                $corr_comp2 = CorrComprobante::where('ano_pro',$ano_fiscal)
                                            ->SelectRaw("corr_compro as correlativo")
                                            ->first();

                $nro=1;
                if($corr_comp2){
                    $nro	= $corr_comp2->correlativo + 1;
                }

                $msj = "Error Actualizando el Correlativo de Comprobante";

                /**********Actualizo Correlativo*****************/
                $corr_comp = CorrComprobante::where('ano_pro',$ano_fiscal)
                                            ->update(['corr_compro' => $nro]);

                $fecpos	= $this->FechaSistema($ano_fiscal, "Y/m/d H:i:s");

                $msj = "Error Insertando Cabecera de Comprobante";

                $compopen = new ComprobanteSopen;

                $compopen->nro_com = $corr_comp2->correlativo;
                $compopen->num_mes = $mes;
                $compopen->tip_com = 'GA';
                $compopen->fec_pos = $fecpos;
                $compopen->usuario = $usuario;
                $compopen->nro_sol = $xnro_ent;
                $compopen->origen  = 'CO';
                $compopen->ano_sol = $fk_ano_pro;
                $compopen->ano_pro = $ano_fiscal;
                $compopen->fec_com = $fec_com;

                $compopen->save();

                //$n=count($resultcomp);

                foreach($encnotaentrega->comprobantesopendetne as $comprobantesopendetneItem)
                {
                    $comproopendet = new ComprobanteSopenDet;

                    $msj = "Error Insertando Detalle de Comprobante";

                    $comproopendet->nro_com         =  $corr_comp2->correlativo;
                    $comproopendet->con_com         =  $comprobantesopendetneItem->con_com;
                    $comproopendet->cod_cta         =  $comprobantesopendetneItem->cod_cta;
                    $comproopendet->cod_aux         =  $comprobantesopendetneItem->cod_aux;
                    $comproopendet->tip_doc         =  $comprobantesopendetneItem->tip_doc;
                    $comproopendet->nro_doc         =  $comprobantesopendetneItem->nro_doc;
                    $comproopendet->fec_doc         =  $fecha_sistema;
                    $comproopendet->con_doc         =  $comprobantesopendetneItem->con_doc;
                    $comproopendet->ctro_costo      =  $comprobantesopendetneItem->ctro_costo;
                    $comproopendet->tip_mto         =  $comprobantesopendetneItem->tip_mto;
                    $comproopendet->mto_doc         =  $comprobantesopendetneItem->mto_doc;
                    $comproopendet->ano_pro         =  $ano_fiscal;
                    $comproopendet->nro_factura     =  $num_fac;

                    $comproopendet->save();

                }

                $encnotent = EncNotaEntrega::where('fk_ano_pro',$fk_ano_pro)
                                           ->where('grupo',$grupo)
                                           ->where('nro_ent',$nro_ent)
                                           ->update(['stat_causacion' => $stat_causacion,'fec_com' => $fecha_sistema,'usu_sta' => $usuario ]);




                DB::commit();

                alert()->Success('EL COMPROBANTE FUE APROBADO EXITOSAMENTE<br><b>' . '</b>');
                return redirect()->route('presupuesto.causado.actacontobraserv.index');

            }else{
                $msj = "Error al Consultar Revision de Comprobante Contable";
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                return redirect()->back()->withInput();
            }

        } catch (\Illuminate\Database\QueryException $e) {
            // dd($msj);
             DB::rollBack();
             $msj = 'A ocurrido un ERROR en la transaccion.\\n Por Favor Intente de Nuevo.';
             //dd($e->getMessage());
             alert()->Error('Transacci&oacute;n Fallida<br>'.$msj.' '. $e->getMessage());
             return redirect()->back()->withInput();
         }
    }

    public function reversar(EncNotaEntrega $encnotaentrega)
    {
        // $ano = EncNotaEntrega::selectRaw("distinct fk_ano_pro as anos")
        //                      ->get();
        $ano = RegistroControl::selectRaw("ano_pro as anos")
                              ->get();


        $beneficiarios = Beneficiario::where('rif_ben',$encnotaentrega->fk_rif_con )
                                      ->where('sta_reg','1')
                                      ->first();

        $responsablehb = Beneficiario::where('rif_ben',$encnotaentrega->ced_hb )
                                     ->where('tipo','E')
                                     ->where('sta_reg','1')
                                     ->first();
        $aprobar    = 0;
        $causar     = 0;
        $reversar   = 1;

        // if ($encnotaentrega->grupo == 'CO'){
            $solservicio = OpSolservicio::where('ano_pro',$encnotaentrega->ano_ord_com)
                                      ->where('xnro_sol',$encnotaentrega->xnro_ord)
                                      ->select('por_iva','monto_neto')
                                    ->first();


            $entera = $encnotaentrega->detnotaentrega[0]->fk_cod_prod;

            $des_con =ConceptoContrato::where('cod_con',DB::Raw("$entera::integer"))
                                      ->select('des_con')
                                      ->first();

        $statusent =   $this->descrip_statu($encnotaentrega->sta_ent);
        $statcomprob = $this->descrip_statu2($encnotaentrega->stat_causacion);
        //$encnotaentrega->fec_com = Carbon::parse($encnotaentrega->fec_com)->format('d/m/Y');

        return view('administrativo.meru_administrativo.presupuesto.causado.actacontobraserv.show', compact('encnotaentrega','beneficiarios','responsablehb','causar','aprobar','reversar','statusent','statcomprob','solservicio','des_con'));
    }


    public function reversar_ejecutar(EncNotaEntrega $encnotaentrega)
    {

        DB::beginTransaction();

        try
		{
            $fk_ano_pro				= $encnotaentrega->fk_ano_pro;
            $ano_ord_com			= $encnotaentrega->ano_ord_com;
            $grupo					= $encnotaentrega->grupo;
            $xnro_ord				= $encnotaentrega->xnro_ord;
            $var					= explode("-", $xnro_ord);
            $fk_nro_ord				= $var[1];
            $nro_ent				= $encnotaentrega->nro_ent;
            $porc_ant				= $encnotaentrega->porc_ant;
            $antc_amort				= $encnotaentrega->antc_amort;
            $fk_rif_con				= $encnotaentrega->fk_rif_con;
            $fec_ent				= $encnotaentrega->fec_ent;
            $base_imponible			= $encnotaentrega->base_imponible;
            $base_exenta			= $encnotaentrega->base_exenta;
            $mto_siniva				= $encnotaentrega->mto_siniva;
            $mto_iva				= $encnotaentrega->mto_iva;
            $mto_ent				= $encnotaentrega->mto_ent;
            $mto_anticipo			= $encnotaentrega->mto_anticipo;
            $tipo_orden				= $encnotaentrega->tipo_orden;
            $fondos					= $encnotaentrega->fondos;
            $cuenta_contable		= $encnotaentrega->cuenta_contable;
            $nro_entrega			= $encnotaentrega->grupo."-".$encnotaentrega->nro_ent."-".$encnotaentrega->fk_ano_pro;
            $fec_com				= $encnotaentrega->fec_com;
            $ano_fiscal				= 2021; //ano_pro
            $xnro_ent				= $grupo.'-'.$nro_ent;
            $usuario				= auth()->user()->id;
            $fecha_sistema			= $this->FechaSistema($ano_fiscal, "Y/m/d H:i:s");

            $tip_ope				= 60;
			$sol_tip				= 'CO';
			$num_doc				= $xnro_ord;
			$concepto				= "REVERSO DE CAUSADO SOBRE COMPROMISO CONTRATO O/S";
			$reverso				= '0';

            $msj = "Error Consultado Datos de Orden Compra/Servicio.";

            /* Consulto orden para obtener cierto datos */
            $solservicio = OpSolservicio::where('ano_pro',$encnotaentrega->ano_ord_com)
                                      ->where('xnro_sol',$encnotaentrega->xnro_ord)
                                      ->select('por_iva','monto_neto')
                                      ->first();

            $por_iva			= $solservicio->por_iva;
            $monto_neto_doc		= $solservicio->monto_neto;

            //dd($solservicio->por_iva.'/'. $monto_neto_doc	);
            $msj = "Error Actualizando Encabezado de Factura.";

            $newcxpcabecerafactura  =  CxPCabeceraFactura::where('rif_prov',$fk_rif_con)
                                                         ->where('doc_asociado',$xnro_ent)
                                                         ->where('ano_doc_asociado',$fk_ano_pro)
                                                         ->update(['statu_proceso' => '2','fec_sta' => $fecha_sistema,'usuario' => $usuario]);

            //dd($newcxpcabecerafactura);
            $msj = "Error CONSULTANDO STATUS en Nota Entrega";

            $encnotent = EncNotaEntrega::query()
                                       ->where('fk_ano_pro',$fk_ano_pro)
                                       ->where('grupo',$grupo)
                                       ->where('nro_ent',$nro_ent)
                                       ->select('stat_causacion','ano_causado','fec_sta','sta_ent')
                                       ->first();

            $causacion      = $encnotaentrega->stat_causacion;
            $ano_causado    = $encnotaentrega->ano_causado;

            //dd($causacion.'/'.$ano_causado);

            //verificando año causado y año actual
            if($ano_causado!=$ano_fiscal){
                $msj = "Error NO SE PUEDE REVERSAR UN CAUSADO DE UN AÑO ANTERIOR";
                alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                return redirect()->back()->withInput();
            }

            $msj = "Error Actualizando Encabezado de la Nota de Entrega. \n\t";

            //actualizo a estatus 5 Reversada
            $encnotent = EncNotaEntrega::where('fk_ano_pro',$fk_ano_pro)
                                       ->where('grupo',$grupo)
                                       ->where('nro_ent',$nro_ent)
                                       ->update(['fec_ant' => $encnotent->fec_sta,'fec_sta' => $fecha_sistema,'sta_ent' => '5','sta_ant'=>$encnotent->sta_ent,'usuario' => $usuario]);
            //dd( $encnotent );
            //dd($encnotaentrega->detnotaentrega);

            foreach($encnotaentrega->detnotaentrega as $detnotentItem)
            {
                $cod_com = $this->armar_cod_com($detnotentItem->tip_cod,
                                                $detnotentItem->cod_pryacc,
                                                $detnotentItem->cod_obj,
                                                $detnotentItem->gerencia,
                                                $detnotentItem->unidad,
                                                $detnotentItem->cod_par,
                                                $detnotentItem->cod_gen,
                                                $detnotentItem->cod_esp,
                                                $detnotentItem->cod_sub);

                 //dd($cod_com);
                //dd($ano_ord_com.'/'.$fk_ano_pro);

                if ($ano_ord_com!=$fk_ano_pro){
                    $centro = $this->ObtenerCentroCostoViejo($ano_ord_com,$cod_com);
                    if (!$centro){
                        $msj = "Error al Intentar Obtener Centro de Costo Original.";
                        alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                        return redirect()->back()->withInput();
                    }else{
                        extract($centro);// $tip_cod, $cod_pryacc, $cod_obj, $gerencia, $unidad
                        //$cod_com = $this->armar_cod_com($premaestroley[0]->tip_cod,$premaestroley[0]->cod_pryacc,$premaestroley[0]->cod_obj,$premaestroley[0]->gerencia,$premaestroley[0]->unidad,$detgastosnotaentregaItem->cod_par,$detgastosnotaentregaItem->cod_gen,$detgastosnotaentregaItem->cod_esp,$detgastosnotaentregaItem->cod_sub);
                        $cod_com = $this->armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$detnotentItem->cod_par,$detnotentItem->cod_gen,$detnotentItem->cod_esp,$detnotentItem->cod_sub);
                    }
                }

                $msj = "Error Actualizando en el detalle de la Solicitud de Servicio";

                $detgassolser = DetGastosSolServicio::where('ano_pro',$ano_ord_com)
                                                    ->where('grupo',$grupo)
                                                    ->where('nro_sol',$fk_nro_ord)
                                                    ->where('cod_com',$cod_com)
                                                    ->where('nro_ren',$detnotentItem->nro_ren)
                                                    ->update(['saldo' => DB::Raw("saldo + $detnotentItem->totrecep")]);

                //dd($detgassolser);
                //dd($encnotaentrega->detgastosnotaentrega);

                foreach($encnotaentrega->detgastosnotaentrega as $detgasnotentItem)
                {
                    if ($detgasnotentItem->causar == 1){
                        if ($ano_fiscal!=$fk_ano_pro){
                            $centro = $this->ObtenerCentroCosto($detgasnotentItem->fk_ano_pro,$detgasnotentItem->tip_cod,$detgasnotentItem->cod_pryacc,$detgasnotentItem->cod_obj,$detgasnotentItem->gerencia,$detgasnotentItem->unidad);
                            if (!$centro){
                                $msj = "Error al Intentar Obtener Centro de Costo Nuevo.";
                                return false;
                            }
                            else{
                                extract($centro); // $tip_cod, $cod_pryacc, $cod_obj, $gerencia, $unidad
                            }
                        }

                        $cod_com = $this->armar_cod_com($detnotentItem->tip_cod,
                                                $detnotentItem->cod_pryacc,
                                                $detnotentItem->cod_obj,
                                                $detnotentItem->gerencia,
                                                $detnotentItem->unidad,
                                                $detnotentItem->cod_par,
                                                $detnotentItem->cod_gen,
                                                $detnotentItem->cod_esp,
                                                $detnotentItem->cod_sub);

                        //dd($cod_com);

                        $msj = 'Ocurrio el siguiento error al ejecutar Movimiento Presupuestario:\n Comuniquese con su Administrador de sistema.';


                       //dd($detgasnotentItem->mto_cau);

                        $movpre = DB::select("SELECT * FROM movimientopresupuestario('$ano_fiscal', '$cod_com', '$sol_tip',
                                            '$tip_ope', '$num_doc', '$detgasnotentItem->mto_cau', '', '$concepto', '$reverso',
                                            '$usuario', '$ano_ord_com', '$nro_entrega', '', '0', '$fecha_sistema')");

                        //dd($movpre);

                    }//fin causar == 1


                }//fin foreach detgasnotentItem

            }//fin detnotaentrega

            if (($grupo!="BM") && ($causacion=="6")){

				if (($fondos =='I') || ($fondos =='E' && $antc_amort > 0))
				{
                    $msj = "Error Consultando el Comprobante aprobado";

                    $comprosopendet = ComprobanteSopenDet::query()
                                                        ->where('tip_doc','12')
                                                        ->where('nro_doc',$nro_entrega)
                                                        ->where('cod_aux',$fk_rif_con)
                                                        ->select('nro_com','con_com','cod_cta','tip_mto','mto_doc','cod_aux', 'tip_doc', 'nro_doc', 'fec_doc','con_doc', 'ctro_costo')
                                                        ->orderBy('con_com')
                                                        ->get();

                    //dd( $comprosopendet);

					if($comprosopendet){
						$mes	= date("m");

                        $msj = "Error Consultando el Correlativo de Comprobante";

                        $corr_comp = CorrComprobante::where('ano_pro',$ano_fiscal)
                                                    ->SelectRaw("corr_compro as correlativo")
                                                    ->first();

                        $nro=1;
                        if($corr_comp){
                           $nro = $corr_comp->correlativo;
                        }

						/**********Actualizo Correlativo*****************/
                        $msj = "Error Actualizando el Correlativo de Comprobante";

                        $corr_comp2 = CorrComprobante::where('ano_pro',$ano_fiscal)
                                                     ->update(['corr_compro' => DB::Raw("corr_compro + 1")]);

                        //dd( $corr_comp2);
                        // $corr_comp2 = CorrComprobante::where('ano_pro',$ano_fiscal)
                        //                              ->select('corr_compro')
                        //                              ->first();

                        //dd($corr_comp2->corr_compro);

                        $fecpos	= $this->FechaSistema($ano_fiscal, "Y/m/d H:i:s");

                        //dd($fecpos);

                        $compsopen = new ComprobanteSopen;

                        $msj = "Error Insertando Cabecera de Comprobante";

                        $compsopen->nro_com = $nro;
                        $compsopen->num_mes = $mes;
                        $compsopen->tip_com = 'GA';
                        $compsopen->fec_com = $fecha_sistema;
                        $compsopen->fec_pos = $fecpos;
                        $compsopen->usuario = $usuario;
                        $compsopen->nro_sol = $xnro_ent;
                        $compsopen->origen  = 'CO';
                        $compsopen->ano_sol = $fk_ano_pro;
                        $compsopen->ano_pro = $ano_fiscal;

                        //dd($corr_comp->correlativo);

                        $compsopen->save();


                        foreach($comprosopendet as $comprosopendetItem)
                        {
                            //dd($comprosopendetItem);
                            $query = new ComprobanteSopenDet;

                            $msj = "Error Insertando Detalle de Comprobante";


                           // dd($comprosopendetItem->mto_doc);
                            $query->nro_com     =  $nro;
                            $query->con_com     = $comprosopendetItem->con_com;
                            $query->cod_cta     = $comprosopendetItem->cod_cta;
                            $query->cod_aux     = $comprosopendetItem->cod_aux;
                            $query->tip_doc     = $comprosopendetItem->tip_doc;
                            $query->nro_doc     = $comprosopendetItem->nro_doc;
                            $query->fec_doc     = $fecha_sistema;
                            $query->con_doc     = $comprosopendetItem->con_doc;
                            $query->ctro_costo  = $comprosopendetItem->ctro_costo;
                            $query->tip_mto    = ($comprosopendetItem->tip_mto=='DB') ? 'CR' : 'DB';
                            $query->mto_doc     = $comprosopendetItem->mto_doc;
                            $query->ano_pro     = $ano_fiscal;

                            $query->save();

                            //dd($comprosopendet);

                        }
					}
					else
					{
						$msj = "Error al Consultar Revision de Comprobante Contable";
                        alert()->Error('Transacci&oacute;n Fallida<br>'.$msj);
                        return redirect()->back()->withInput();
					}
				}
			}

            DB::commit();

            alert()->Success('EL COMPROBANTE FUE REVERSADO EXITOSAMENTE<br><b>' . '</b>');
            return redirect()->route('presupuesto.causado.actacontobraserv.index');

        } catch (\Illuminate\Database\QueryException $e) {
             dd($msj.'/'.$e);
             DB::rollBack();
             $msj = 'A ocurrido un ERROR en la transaccion.\\n Por Favor Intente de Nuevo.';
             //dd($e->getMessage());
             alert()->Error('Transacci&oacute;n Fallida<br>'.$msj.' '. $e->getMessage());
             return redirect()->back()->withInput();
         }
    }
}
