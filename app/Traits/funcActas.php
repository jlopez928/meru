<?php

    namespace App\Traits;


    use App\Models\Administrativo\Meru_Administrativo\Presupuesto\RegistroControl;
    use App\Models\Administrativo\Meru_Administrativo\Compra\DetNotaEntrega;
    use App\Models\Administrativo\Meru_Administrativo\Presupuesto\CentroCosto;


trait funcActas
{
    //  use funcActas;

    public function FechaSistema($ano_pro, $format = 'YmdHis')
    { /// no es necesario se debe eliminar por la variable global
        date_default_timezone_set("America/Caracas");
        $ano_actual = date('Y');

        if ($ano_pro!=$ano_actual){
            $fecha1 = "$ano_pro-12-31 20:00";
            $fecha = date($format, strtotime($fecha1));
        }else{
            $fecha = date($format);
        }
        return $fecha;
    }

    function ObtenerCentroCosto($ano_pro,$tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad){

		$query = CentroCosto::where('ano_pro',$ano_pro)
                            ->where('tip_cod', $tip_cod)
                            ->where('cod_pryacc', $cod_pryacc)
                            ->where('cod_obj', $cod_obj)
                            ->where('gerencia', $gerencia)
                            ->where('unidad', $unidad)
                            ->first();

                           // dd($ano_pro.'/'.$tip_cod.'/'.$cod_pryacc.'/'.$cod_obj.'/'.$gerencia.'/'.$unidad);

		if($query){
			return $query->ajust_ctrocosto;
		}
		else{
			return false;
		}
	}

    /* Funcion que formatea un entero a unaFormatear cadena de caracteres,
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


    function armar_cod_com($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub){
        $str = $this->formatear($tip_cod,2).".".$this->formatear($cod_pryacc,2).".".$this->formatear($cod_obj,2).".".
        $this->formatear($gerencia,2).".".$this->formatear($unidad,2).".".$this->formatear($cod_par,2).".".
        $this->formatear($cod_gen,2).".".$this->formatear($cod_esp,2).".".$this->formatear($cod_sub,2);
        return $str;
    }

    /* Obtiene el cod_com de la partida de iva dado el año activo */
	function ObtenerCodComIva($ano_pro)
	{
		$cod_com = 0;

		$query =  RegistroControl::where('ano_pro', $ano_pro)
                                 ->select('cod_comi')
                                 ->first();
 		if($query){
			$cod_com = $query->cod_comi;
		}
		return $cod_com;
	}

    /**
     * Funcion que permite obtener cada uno de los codigos de la estructura de gastos
     * @param {string} $cod_com: Codigo concatenado de estructura presupuestaria
     */
    function obtener_cod_com($cod_com){

        $estructura = array() ;
        $vec = explode(".", $cod_com);

        if (count($vec) > 1 ) {
            $estructura["tip_cod"]		= (int)$vec[0];
            $estructura["cod_pryacc"]	= (int)$vec[1];
            $estructura["cod_obj"]		= (int)$vec[2];
            $estructura["gerencia"]		= (int)$vec[3];
            $estructura["unidad"]		= (int)$vec[4];
            $estructura["cod_par"]		= (int)$vec[5];
            $estructura["cod_gen"]		= (int)$vec[6];
            $estructura["cod_esp"]		= (int)$vec[7];
            $estructura["cod_sub"]		= (int)$vec[8];
        }
        return $estructura;
    }



    /* Obtiene si es Gasto o no Para una estructura presupuestaria */
	function ObtenerGasto($fk_ano_pro,$grupo,$nro_ent,$cod_com)
	{
		//require_once("utilsPHP/sacoGen.Script.php");
        $tip_cod=1;
		$estructura		= $this->obtener_cod_com($cod_com);
		$gasto			= 0;
		extract($estructura);
        //dd($estructura);
        if ($estructura){
            $query =   DetNotaEntrega::where('fk_ano_pro',$fk_ano_pro )
                                    ->where('grupo',$grupo)
                                    ->where('nro_ent',$nro_ent)
                                    ->where('tip_cod',$tip_cod)
                                    ->where('cod_pryacc',$cod_pryacc)
                                    ->where('cod_obj',$cod_obj)
                                    ->where('gerencia',$gerencia)
                                    ->where('unidad',$unidad)
                                    ->where('cod_par',$cod_par)
                                    ->where('cod_gen',$cod_gen)
                                    ->where('cod_esp',$cod_esp)
                                    ->where('cod_sub',$cod_sub)
                                    ->first();

            if($query){
                $gasto = $query->gasto;
            }
        }
		return $gasto;
	}

    function descrip_statu($valor) {
        if (!empty($valor)) {
            switch ($valor) {
                case "0":
                    return "Solo Transcrita.";
                case "1":
                    return "Con Acta de Inicio.";

                case "2":
                    return "Con Acta de Terminación.";

                case "3":
                    return "Con Acta de Aceptación.";

                case "4":
                    return "Con Factura Registrada.";

                case "5":
                    return "Reversada.";

                case "7":
                    return "Causada.";

                case "8":
                    return "Anulada.";

            }
        }
    }

    /* Función que coloca las descripcion del status Causado */
    function descrip_statu2($valor) {
        if (!empty($valor)) {
            switch ($valor) {
                case "6":
                   return "Comprobante Aprobado.";
                case "7":
                    return "Comprobante Por Aprobación.";
                default:
                    return "Sin Comprobante";
            }
        }else{
            return "Sin Comprobante";
        }

    }
    // Función que coloca las descripcion del estatus del contrato
    function EstatusContrato($valor) {
        $descrip_estado = "";
        if (!empty($valor)) {
            switch ($valor) {
                case "0":
                    $descrip_estado = "Ingresada en Sistema";;
                    break;
                case "1":
                    $descrip_estado = "Anulada";;
                    break;
                case "2":
                    $descrip_estado = "Aprobada por Gerente de la Unidad Solicitante";;
                    break;
                case "3":
                    $descrip_estado = "Reversada por Gerente de la Unidad Solicitante";;
                    break;
                case "4":
                    $descrip_estado = "Comprometida Presupuestariamente";;
                    break;
                case "5":
                    $descrip_estado = "Reversada Presupuestariamente";;
                    break;
                case "6":
                    $descrip_estado = "Con Orden Impresa";;
                    break;
            }
            return $descrip_estado;
        }else{
            return "Ingresada en Sistema";
        }
        return $descrip_estado;
    }

    /* Obtiene El Centro de Costo Original */
    function ObtenerCentroCostoViejo($ano_pro,$cod_com)
    {
        $centro = substr($cod_com, 0, 14);

        $query = CentroCosto::where('ano_pro',$ano_pro)
                            ->where('ajust_ctrocosto',$centro)
                            ->select('tip_cod', 'cod_pryacc', 'cod_obj', 'gerencia', 'unidad', 'cod_cencosto', 'ajust_ctrocosto')
                            ->get();
        if($query){
            $centro_costo = $query[0];
            return $centro_costo;
        } else{
            return false;
        }
    }

}
