<?php
namespace App\Traits;

trait  convertirLetrasMonto{
    function convertirLetrasMonto($numero,$puntodecimal='.',$conCentimos=true)
    {
        $snumero="".$numero; // Convertir a String

        $partes = preg_split("/".$puntodecimal."/",$numero);

        /********************************/
        $tt = round($numero,2);
        $numero = intval($tt);
        $dec = $tt - intval($tt);
        //Hay que redondear los decimales
        $decimales = round($dec * 100) / 100;
        //file_put_contents (dirname(__FILE__)."/revision1.html",print_r($decimales,true));
        //file_put_contents (dirname(__FILE__)."/revision2.html",print_r(strpos($decimales,'.')+1,true));
        //file_put_contents (dirname(__FILE__)."/revision3.html",print_r(substr($decimales,strpos($decimales,'.')+1,3),true));
        $decimales2 = substr($decimales,strpos($decimales,'.')+1,2);//*(100);
        $decimales3 = substr($decimales,strpos($decimales,'.')+1,3);//*(100);
        //file_put_contents (dirname(__FILE__)."/revision4.html",print_r($decimales2,true));
        if (!$conCentimos){
            $val= $this->milmillon($numero);
        }
        else{
            if(!$decimales) { // No requiere moneda
                $val=$this->milmillon($numero). " CON 00/100 CENTIMOS";
            } else { // requiere 00/100
                //$val=$this->milmillon($numero)." ".((count($partes)==1)?" 00/100":("CON ". formatear($partes[1],2)."/100 CENTIMOS"));
                //file_put_contents (dirname(__FILE__)."/revision6.html",print_r(substr($decimales2,0,1),true));
                if(substr($decimales2,0,1) == "0") { // MENOR A DECENA
                    $val= $this->milmillon($numero). " CON CERO ". $this->unidad($decimales3). " CENTIMOS";
                } else {
                    //file_put_contents (dirname(__FILE__)."/revision7a.html",print_r($decimales,true));
                    $decimales = $decimales * 100;
                    // file_put_contents (dirname(__FILE__)."/revision6.html",print_r($decimales3,true));
                    //file_put_contents (dirname(__FILE__)."/revision7.html",print_r($decimales,true));
                    $val= $this->milmillon($numero). " CON ". $this->decena($decimales). " CENTIMOS";
                }
            }
        }
        return $val;
    }

    function milmillon($nummierod) {
        $num_letrammd=""; // Valor a Devolver
        if ($nummierod >= 1000000000 && $nummierod <2000000000) {
            $num_letrammd = "MIL ".($this->cienmillon($nummierod%1000000000));
        } elseif ($nummierod >= 2000000000 && $nummierod <10000000000) {
            $num_letrammd = $this->unidad(Floor($nummierod/1000000000))." MIL ".($this->cienmillon($nummierod%1000000000));
        } elseif ($nummierod < 1000000000) {
            $num_letrammd = $this->cienmillon($nummierod);
        }
        return $num_letrammd;
    }

    /**
     * Funcion que devuelve valor para decenas
     * @param $numdero variable de tipo integer que contiene el n&uacute;mero que va a ser convertido
     * @return devuelve el n&uacute;mero de la decena y la unidad en letras
     */
    function decena($numdero) {
        $numd="";
        if ($numdero >= 90 && $numdero <= 99) {
        $numd = "NOVENTA ";
        if ($numdero == 91)
            $numd = $numd."Y UN";
        if ($numdero > 91) $numd = $numd."Y ".($this->unidad($numdero - 90));
        }
        elseif ($numdero >= 80 && $numdero <= 89) {
        $numd = "OCHENTA ";
        if ($numdero == 81)
            $numd = $numd."Y UN";
        if ($numdero > 81) $numd = $numd."Y ".($this->unidad($numdero - 80));
        }
        elseif ($numdero >= 70 && $numdero <= 79) {
        $numd = "SETENTA ";
        if ($numdero == 71)
            $numd = $numd."Y UN";
        if ($numdero > 71) $numd = $numd."Y ".($this->unidad($numdero - 70));
        }
        elseif ($numdero >= 60 && $numdero <= 69) {
        $numd = "SESENTA ";
        if ($numdero == 61)
            $numd = $numd."Y UN";
        if ($numdero > 61) $numd = $numd."Y ".($this->unidad($numdero - 60));
        }
        elseif ($numdero >= 50 && $numdero <= 59) {
        $numd = "CINCUENTA ";
        if ($numdero == 51)
            $numd = $numd."Y UN";
        if ($numdero > 51) $numd = $numd."Y ".($this->unidad($numdero - 50));
        }
        elseif ($numdero >= 40 && $numdero <= 49) {
        $numd = "CUARENTA ";
        if ($numdero == 41)
            $numd = $numd."Y UN";
        if ($numdero > 41) $numd = $numd."Y ".($this->unidad($numdero - 40));
        }
        elseif ($numdero >= 30 && $numdero <= 39) {
        $numd = "TREINTA ";
        if ($numdero == 31)
            $numd = $numd."Y UN";
        if ($numdero > 31) $numd = $numd."Y ".($this->unidad($numdero - 30));
        }
        elseif ($numdero >= 20 && $numdero <= 29) {
        if ($numdero == 20)
            $numd = "VEINTE ";
        else
            $numd = "VEINTI ";
            if ($numdero == 21)
                $numd = $numd."UN";
            if ($numdero > 21) $numd = $numd." ".($this->unidad($numdero - 20));
        } elseif ($numdero >= 10 && $numdero <= 19) {
            // file_put_contents (dirname(__FILE__)."/revision7.html",print_r($numdero,true));
            $valor= substr($numdero,0,2);
            //  file_put_contents (dirname(__FILE__)."/revision75.html",print_r($valor,true));
            if  ($valor=='10') {$numd = "DIEZ ";}// break;
            elseif  ($valor=='11') {$numd = "ONCE ";}// break;
                elseif  ($valor=='12') {$numd = "DOCE ";}// break;
                    elseif  ($valor=='13') {$numd = "TRECE ";}// break;
                        elseif  ($valor =='14') {$numd = "CATORCE ";}// break;
                            elseif  ($valor=='15') {$numd = "QUINCE ";}// break;
                                elseif  ($valor=='16') {$numd = "DIECISEIS ";}// break;
                                    elseif  ($valor=='17') {$numd = "DIECISIETE ";}// break;
                                        elseif  ($valor=='18') {$numd = "DIECIOCHO ";}// break;
                                            elseif ($valor=='19'){$numd = "DIECINUEVE ";}

        //file_put_contents (dirname(__FILE__)."/revision8.html",print_r($numd.'-*--'.$numdero.'--',true));
        }
        else {
        $numd = $this->unidad($numdero);
        }
        return $numd;
    }



    /**
     * Funcion que devuelve Valor para Unidad
     * @param $numero variable de tipo integer que contiene el n&uacute;mero de la unidad a convertir
     * @return devuelve el valor en letras del n&uacute;mero de la unidad dado
    */
    function unidad($numuero)
    {
        $numu = "";
        $numuero = trim($numuero);
        switch ($numuero){
            case 9: $numu = "NUEVE";	break;
            case 8: $numu = "OCHO";		break;
            case 7: $numu = "SIETE";	break;
            case 6: $numu = "SEIS";		break;
            case 5: $numu = "CINCO";	break;
            case 4: $numu = "CUATRO";	break;
            case 3: $numu = "TRES";		break;
            case 2: $numu = "DOS";		break;
            case 1: $numu = "UNO";		break;
        }
        //file_put_contents (dirname(__FILE__)."/numero.html",print_r($numu,true));
        return $numu;
    }

    function cienmillon($numcmeros) {
        $num_letracms=""; // Valor a devolver
        if ($numcmeros == 100000000) {
          $num_letracms = "CIEN MILLONES";
        } elseif ($numcmeros >= 100000000 && $numcmeros <1000000000) {
                $num_letracms = $this->centena(Floor($numcmeros/1000000));
                if (substr($num_letracms,strlen($num_letracms)-3,3)=="UNO"){
                  $num_letracms = substr($num_letracms,0,strlen($num_letracms)-3)."* UN MILLONES ".($this->millon($numcmeros%1000000));
                }else{
                //file_put_contents (dirname(__FILE__)."/a.html",print_r($numcmeros,true));
                //file_put_contents (dirname(__FILE__)."/ab.html",print_r($numcmeros/1000,true));
                //file_put_contents (dirname(__FILE__)."/ab2.html",print_r(Floor($numcmeros/1000),true));
                  $num_letracms = $this->centena(Floor($numcmeros/1000000))." MILLONES ".($this->millon($numcmeros%1000000));
                }
        } elseif ($numcmeros < 100000000) {
          $num_letracms = $this->decmillon($numcmeros);
        }
        return $num_letracms;
    }



    /**
     * Funciones de convierte Decenas de Millon
     * @param $numerodm variable de tipo integer que contiene el n&uacute;mero que se va a convertir
     *
     * @return devuelve el n&uacute;mero en letras de la cantidad dada
     */
    function decmillon($numerodm) {
        $num_letradmm=""; // Valor a devolver
        if ($numerodm == 10000000) {
        $num_letradmm = "DIEZ MILLONES";
        } elseif ($numerodm > 10000000 && $numerodm <20000000) {
        $num_letradmm = $this->decena(Floor($numerodm/1000000))."MILLONES ".($this->cienmiles($numerodm%1000000));
        } elseif ($numerodm >= 20000000 && $numerodm <100000000) {
        $num_letradmm = $this->decena(Floor($numerodm/1000000))." MILLONES ".($this->millon($numerodm%1000000));
        } elseif ($numerodm < 10000000) {
        $num_letradmm = $this->millon($numerodm);
        }
        return $num_letradmm;
    }


    /**
     * Funcion para Millones
     * @param $nummiero variable de tipo integer que contiene el n&uacute;mero que se desea convertir
     *
     * @return devuelve el n&uacute;mero en letras de la cantidad dada
     */
    function millon($nummiero) {
        $num_letramm=""; //Valor a Devolver
        if ($nummiero >= 1000000 && $nummiero <2000000) {
        $num_letramm = "UN MILLON ".($this->cienmiles($nummiero%1000000));
        } elseif ($nummiero >= 2000000 && $nummiero <10000000) {
        $num_letramm = $this->unidad(Floor($nummiero/1000000))." MILLONES ".($this->cienmiles($nummiero%1000000));
        } elseif ($nummiero < 1000000) {
        $num_letramm = $this->cienmiles($nummiero);
        }
        return $num_letramm;
    }

        /**
     * Funcion que devuelve el valor para centenes de mil
     * @param $numcmero variable de tipo integer que contiene el n&uacute;mero a convertir
     *
     * @return devuelve el n&uacute;mero en letras de la cantidad dada
     */
    function cienmiles($numcmero) {
        $num_letracm="";
        if ($numcmero == 100000) {
        $num_letracm = "CIEN MIL";
        } elseif ($numcmero >= 100000 && $numcmero <1000000) {
                $num_letracm = $this->centena(Floor($numcmero/1000));
                if (substr($num_letracm,strlen($num_letracm)-3,3)=="UNO"){
                $num_letracm = substr($num_letracm,0,strlen($num_letracm)-3)." UN MIL ".($this->centena($numcmero%1000));
                }else{
                $num_letracm = $this->centena(Floor($numcmero/1000))." MIL ".($this->centena($numcmero%1000));
                }
        } elseif ($numcmero < 100000) {
        $num_letracm = $this->decmiles($numcmero);
        }
        return $num_letracm;
    }
    /**
     * Funcion que devuelve valor para decenas de Mil
     * @param $numdmero variable de tipo integer que contiene el n&uacute;mero que se desea convertir
     *
     * @return devuelve el n&uacute;mero en letras de la cantidad dada
     */
    function decmiles($numdmero) {
        $numde=""; //Valor a Devolver
        if ($numdmero == 10000) {
        $numde = "DIEZ MIL";
        } elseif ($numdmero > 10000 && $numdmero <20000) {
        $numde = $this->decena(Floor($numdmero/1000))."MIL ".($this->centena($numdmero%1000));
        } elseif ($numdmero >= 20000 && $numdmero <100000) {
        $numde = $this->decena(Floor($numdmero/1000));
        if (substr($numde,strlen($numde)-3,3)=="UNO"){
                $numde = substr($numde,0,strlen($numde)-3)." UN MIL ".($this->miles($numdmero%1000));
        }else{
                $numde = $this->decena(Floor($numdmero/1000))." MIL ".($this->miles($numdmero%1000));
        }
        } elseif ($numdmero < 10000) {
        $numde =$this->miles($numdmero);
        }
        return $numde;
    }
    /**
     * Función que devuelve valor para miles
     * @param $nummero variable de tipo integer que contiene el n&uacute;mero a convertir
     *
     * @return devuelve el n&uacute;mero en letras de un n&uacute;mero dado
     */
    function miles($nummero) {
        $numm=""; // valor a devolver
        if ($nummero >= 1000 && $nummero < 2000) {
            $numm = "MIL ".($this->centena($nummero%1000));
        } elseif ($nummero >= 2000 && $nummero <10000) {
            $numm = $this->unidad(Floor($nummero/1000))." MIL ".($this->centena($nummero%1000));
        } elseif ($nummero < 1000) {
            $numm = $this->centena($nummero);
        }
        return $numm;
    }
    /**
    * Función que Obtiene valor para Centenas
    * @param $numc variable de tipo integer que contiene el n&uacute;mero que se desea convertir
    *
    * @return devuelve el valor en letras del n&uacute;mero informado
    */
   function centena($numc) {
       $numce=""; // Valor a devolver
       //file_put_contents (dirname(__FILE__)."/c.html",print_r($numc,true));
       if ($numc >= 100) {
         if ($numc >= 900 && $numc <= 999) {
            $numce = "NOVECIENTOS ";
            if ($numc > 900) $numce = $numce.($this->decena($numc - 900));
         } elseif ($numc >= 800 && $numc <= 899) {
            $numce = "OCHOCIENTOS ";
            if ($numc > 800) $numce = $numce.($this->decena($numc - 800));
         } elseif ($numc >= 700 && $numc <= 799) {
            $numce = "SETECIENTOS ";
            if ($numc > 700) $numce = $numce.($this->decena($numc - 700));
         } elseif ($numc >= 600 && $numc <= 699) {
            $numce = "SEISCIENTOS ";
            if ($numc > 600) $numce = $numce.($this->decena($numc - 600));
         } elseif ($numc >= 500 && $numc <= 599) {
            $numce = "QUINIENTOS ";
            if ($numc > 500) $numce = $numce.($this->decena($numc - 500));
         } elseif ($numc >= 400 && $numc <= 499) {
            $numce = "CUATROCIENTOS ";
            if ($numc > 400) $numce = $numce.($this->decena($numc - 400));
         } elseif ($numc >= 300 && $numc <= 399) {
            $numce = "TRESCIENTOS ";
            if ($numc > 300) $numce = $numce.($this->decena($numc - 300));
         } elseif ($numc >= 200 && $numc <= 299) {
            $numce = "DOSCIENTOS ";
            if ($numc > 200) $numce = $numce.($this->decena($numc - 200));
         } elseif ($numc >= 100 && $numc <= 199) {
            if ($numc == 100)
               $numce = "CIEN ";
            else
               $numce = "CIENTO ".($this->decena($numc - 100));
         }
      } else {
         $numce = $this->decena($numc);
      }
      return $numce;
   }
}
?>
