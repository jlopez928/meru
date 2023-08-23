<?php

namespace App\Traits;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\PreMovimiento;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\ControlPresupuesto;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;

trait PreMovimientos
{
    function parse_fecha($fecha)
    {
        if ($fecha!=''){
            $fecha=str_replace ('-','/',$fecha);
            $afecha=explode('/',$fecha);
            $fecha="{$afecha[2]}/{$afecha[1]}/{$afecha[0]}";
        }
        return $fecha;
    }
    function formatea($valor,$n){
        $str = '';
        $n = $n-strlen($valor);
        for($i=0;$i<$n;$i++){
            $str .='';
        }
        $str .=$valor;
        return $str;
    }
    function formatNumber($num,$dec,$sepmiles,$puntodecimal,$neg) {
        $sepmiles=($puntodecimal=='.')?",":".";
        $f = number_format($num,$dec,$puntodecimal,$sepmiles);
        if(strstr($f,"-") && $neg=="(") {
            $f=str_replace("-","(",$f);
            $f.=")"; // Agregar Parentesis al Final
        }
        return $f;
    }
    /**
 * Funcion que formatea un entero a una cadena de caracteres,
 * ejm: entero = 100, si se necesita de 6 caracteres queda: 000100
 * @param {string} $valor: Valor de cadena sin formatear
 * @param {integer} $n: Numero de ceros(0) concatenados a la izquierda
 * @return $str -> Cadena formateada
 */
    function formatear($valor,$n){
        $str = '';
        $n = $n-strlen($valor);
        for($i=0;$i<$n;$i++){
            $str .='0';
        }
        $str .=$valor;
        return $str;
    }
    function verificarDisponibilidad($ano_pro,$cod_com){
        $maestroley=MaestroLey::query()->select('mto_dis')->where('ano_pro',$ano_pro)
                     ->where('cod_com',$cod_com)->first();
        return $maestroley->mto_dis;
    }
    /**
 * Funcion que inserta en tabla de control de los movimientos presupuestarios
 * @param {integer} $ano_pro: Año en proceso
 * @param {string} $cod_com: Codigo compuesto de la estructura de gastos
 */
function insert_cod($ano_pro,$cod_com)
{
    ControlPresupuesto::create([
        'ano_pro'          => $ano_pro,
        'cod_com'          => $cod_com,
        'usuario'          => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),

        ]);
        return true;
}
/**
 * Funcion que elimina en tabla de control de los movimientos presupuestarios
 * @param {integer} $ano_pro: Año en proceso
 * @param {string} $cod_com: Codigo compuesto de la estructura de gastos
 */
function delete_cod($ano_pro,$cod_com)
{      $control=ControlPresupuesto::query()->where('ano_pro',$ano_pro)
                                           ->where('cod_com',$cod_com)->first();
       $control->delete();
	return true;
}
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
function insert_preMovimientos($datos){
    $registrocontrol=RegistroControl::query()->where('ano_pro',$datos["ano_pro"])->first();
    $datos["num_mes"] = $registrocontrol->mes_pre;
    $sw=false;
    switch ($datos["tip_ope"]) {
        case "20":
                    if(($datos["sol_tip"] == 'OC') || ($datos["sol_tip"] == 'CO')){
                        $tipo_operacion = 10;
                        $cod_com =$datos["cod_com_viejo"];
                        $sw=true;
                    }
                    break;
        case "9":

                    $tipo_operacion = 8;
                    $cod_com =$datos["cod_com_viejo"];
                    $sw=true;
                    break;
        case "60":
                    if($datos["sol_tip"] == 'AN'){
                        $tipo_operacion = 8;
                        $cod_com =$datos["cod_com_viejo"];
                        $sw=true;
                    }
                    break;
        case "50":
                    if($datos["sol_tip"] == 'AN'){
                        $tipo_operacion = 60;
                        $cod_com =$datos["cod_com_viejo"];
                        $sw=true;
                    }
                    break;
        case "62":
                    $tipo_operacion = 61;
                    $cod_com =$datos["cod_com"];
                    $sw=true;
                    break;
        case "40":
                        $tipo_operacion = 30;
                        $cod_com =$datos["cod_com"];
                        $sw=true;
                        break;
    }
    if ($sw){
        $pre_movimientos=PreMovimiento::query()->where('ano_pro',$datos["ano_doc"])
                                                ->where('sta_reg','1')
                                                ->where('sol_tip',$datos["ano_doc"])
                                                ->where('ano_pro',$datos["sol_tip"])
                                                ->where('tip_ope',$tipo_operacion )
                                                //trim(num_doc) = '{$datos["num_doc"]}' and
                                                ->where('num_doc',$datos["num_doc"])
                                                ->where('cod_com',$cod_com);
        $datos["num_mes"] =$registrocontrol->mes;
        $datos["nro_enl"] = $pre_movimientos->num_reg;
        $datos["sta_ant"] = $pre_movimientos->sta_reg;
        $datos["fec_ant"] = $pre_movimientos->fec_sta;
    }

	if(($datos["tip_ope"] == 20) && (($datos["sol_tip"] == 'OC') || ($datos["sol_tip"] == 'CO'))){
       $pre_movimientos->update(['sta_reg' => '2','usua_anu' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),'fec_anu' =>  now()->format('Y-m-d') ]);
	}
	if(($datos["tip_ope"] == 9)){
        $pre_movimientos->update(['sta_reg' => '2','usua_anu' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),'fec_anu' =>  now()->format('Y-m-d') ]);
	}
    if(($datos["tip_ope"] == 60) && ($datos["sol_tip"] == 'AN')){
        $pre_movimientos->update(['sta_reg' => '2','usua_anu' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),'fec_anu' =>  now()->format('Y-m-d') ]);
	}
    if(($datos["tip_ope"] == 50) && ($datos["sol_tip"] == 'AN')){
        $pre_movimientos->update(['sta_reg' => '2','usua_anu' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),'fec_anu' =>  now()->format('Y-m-d') ]);
    }

    //-----------------------------------------------------------------
    //						Pagado Directo
    //-------------------------------------------------------------------
    if(($datos["tip_ope"] == 62)){
        $pre_movimientos->update(['sta_reg' => '2','usua_anu' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),'fec_anu' =>  now()->format('Y-m-d') ]);
    }
    //-----------------------------------------------------------------
    //						Causado Directo
    //-------------------------------------------------------------------
    if($datos["tip_ope"] == 40){
        $pre_movimientos->update(['sta_reg' => '2','usua_anu' => \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email),'fec_anu' =>  now()->format('Y-m-d') ]);
    }

    //Leer Saldos Actuales en Maestro de Ley
    $maestroley=MaestroLey::query()->where('ano_pro',$datos["ano_pro"])
                                    ->where('cod_com',$datos["cod_com"])->first();
    $datos["sdo_mod"] =  $maestroley->mto_mod;
    $datos["sdo_apa"] =  $maestroley->mto_apa;
    $datos["sdo_pre"] =  $maestroley->mto_pre;
    $datos["sdo_com"] =  $maestroley->mto_com;
    $datos["sdo_cau"] =  $maestroley->mto_cau;
    $datos["sdo_dis"] =  $maestroley->mto_dis;
    $datos["sdo_pag"] =  $maestroley->mto_pag;
    if(empty($datos["nro_enl"])){
        $datos["nro_enl"] = "";
    }
	if(empty($datos["fecha"])){
			$datos["fecha"] =  now()->format('Y-m-d');
	}
	if(!isset($datos['cierre'])){
	    $datos['cierre'] = '1';
	}
 //-------------------------------------------------------------------------------------
//Validar estos Campos
	$datos["usuario"] =  \Str::replace('@hidrobolivar.com.ve', '', auth()->user()->email);
	// Ingresar Registro en pre_movimientos
    PreMovimiento::create([
        'num_mes'=>$datos["num_mes"] ,
        'ano_pro'=>$datos["ano_pro"],
        'tip_ope'=>$datos["tip_ope"],
        'mto_tra'=>$datos["mto_tra"],
        'usuario'=>$datos["usuario"],
        'sol_tip'=>$datos["sol_tip"],
        'num_doc'=>$datos["num_doc"],
        'fec_tra'=>$datos["fec_tra"],
        'tip_cod'=>$datos["tip_cod"],
        'cod_pryacc'=>$datos["cod_pryacc"],
        'objetivo'=>$datos["cod_obj"],
        'gerencia'=>$datos["gerencia"],
        'unidad'=>$datos["unidad"],
        'cod_par'=>$datos["cod_par"],
        'cod_gen'=>$datos["cod_gen"],
        'cod_esp'=>$datos["cod_esp"],
        'cod_sub'=>$datos["cod_sub"],
        'cod_com'=>$datos["cod_com"],
        'ced_ben'=>!empty($datos["ced_ben"])?$datos["ced_ben"]:'',
        'concepto'=>$datos["concepto"],
        'sdo_mod'=>$datos["sdo_mod"],
        'sdo_apa'=>$datos["sdo_apa"],
        'sdo_pre'=>$datos["sdo_pre"],
        'sdo_com'=>$datos["sdo_com"],
        'sdo_cau'=>$datos["sdo_cau"],
        'sdo_dis'=>$datos["sdo_dis"],
        'sdo_pag'=>$datos["sdo_pag"],
        'nro_enl'=>$datos["nro_enl"],
        'sta_reg'=>$datos["sta_reg"],
        'fecha'   =>$datos["fecha"],
        'usua_anu'=>!empty($datos["usua_anu"])?$datos["ced_ben"]:'',
        'ano_doc' =>$datos["ano_doc"],
        'nota_entrega'=>!empty($datos["nota_entrega"])?$datos["nota_entrega"]:'',
        'num_fac'=>!empty($datos["num_fac"])?$datos["num_fac"]:'',
        'cierre'=>$datos["cierre"],
        'manual'=>!empty($datos["manual"])?$datos["manual"]:'',
        'mto_transaccion'=>!empty($datos["mto_transaccion"])?$datos["mto_transaccion"]:0
        ]);
	return true;
}

}
