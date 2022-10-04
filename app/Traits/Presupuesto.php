<?php

namespace App\Traits;

use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;

trait Presupuesto
{


//-----------------------------------------------------------------------------
//          Funcion que valida si existe disponibilidad presupuestaria
//                           para comprometer o causar
//-----------------------------------------------------------------------------
function Validar_Monto_a_Procesar($ano_fiscal,$monto,$cod_com,$accion){
    $query_Monto=MaestroLey::query()->select($accion)
                                   ->where('ano_pro',$ano_fiscal)
                                   ->where('cod_com',$cod_com)->first();

    if( $query_Monto->$accion>=0){
        $monto_procesado =$query_Monto->$accion;
        $monto_procesado = $monto_procesado - $monto;
        if ($monto_procesado >= 0){
            return true;
        }else{
            $accion='mto_dis'?  $descripcion='Comprometer':$descripcion='Causar';
            alert()->error("Error No Existe disponibilidad Presupuestaria para ".$descripcion." la partida [".$cod_com."].    Favor Verifique...");
            return false;
        }
    }else{
        alert()->error( "Error Validando Patida Presupuestaria [".$cod_com."].Comuniquese con su Administrados de Sistema");
        return false;
    }
 }
 //-------------------------------------------------
// Validar que el causado no supere el compromiso
//------------------------------------------------
function Validar_Causado($ano_fiscal,$monto,$cod_com){
    $query_Monto=MaestroLey::query()->select('mto_cau','mto_com' )
                                   ->where('ano_pro',$ano_fiscal)
                                   ->where('cod_com',$cod_com)->first();
    if( $query_Monto->mto_cau>0){
        if( ($query_Monto->mto_com+$monto)>= $query_Monto->mto_cau){
           return true;
        }else{
            alert()->error( "Error el Compromiso es Insuficiente para Causar la Partida [".$cod_com."].\\n Favor Verifique...");
            return false;
        }
   }else{
        alert()->error("Error al buscar Monto de Compromiso en Pre Maestro Ley para la partida['".$cod_com."'].\\nComuniquese con su Administrador de sistema.");
        return false;
    }
 }
//--------------------------------------------------------------------------------------------------------
// Funcion que arma el centro de costo validando que si cambio le concatena el nuevo centro de costo
//                        asociado a la gerencia
//-------------------------------------------------------------------------------------------------------
public function generarCentroCosto($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$codPar,$codGen, $codEsp, $codSub,$ano_doc)
{

    // Concatenar el centro de Costo que trae la orden o la certificacion
    $cod_centro    = CentroCosto::generarCodCentroCosto($tip_cod,$cod_pryacc,$cod_obj, $gerencia,$unidad);
    // partida presupuestaria que trae la orden o la certificacion
    $partida=      PartidaPresupuestaria::generarCodPartida($codPar, $codGen, $codEsp, $codSub);
    //Busca el centro de costo actual

    $centro_costo=$this->BuscarCentroCostoActual($cod_centro,$partida,$this->anoPro,$ano_doc);


    //Validar Centro de Costo
    if($centro_costo!=''){
            //  $cod_centro==$centro_costo  si se cumple la condición
            //  La Estrucutra presupuestaria queda tal cual como viene
            // de lo contrario se debe cambiar el centro de costo por el que posee actualmente la gerencia
            $cod_centro==$centro_costo?$cod_com=$cod_centro.".".$partida:$cod_com=$centro_costo.".".$partida;
    }else{
       $cod_com='';
    }
    return $cod_com;
}


//--------------------------------------------------------------------------------------------------------
// Funcion que arma el centro de costo actual validando que si cambio le concatena el n
//                    uevo centro de costo  asociado a la gerencia
//-------------------------------------------------------------------------------------------------------

function BuscarCentroCostoActual($centro_viejo,$partida,$ano_fiscal,$ano_doc)
{
    $centro_costo= CentroCosto::query()->select('ajust_ctrocosto')
                                       ->where('ano_pro',$ano_doc)
                                       ->where('cod_cencosto', $centro_viejo)->first();
    if(!empty($centro_costo->ajust_ctrocosto)){

         //----------------------------------------------------------------------------
         //Validar que el centro de costo exista en pre_maestro_ley para el año fiscal
         //----------------------------------------------------------------------------
         $query_partida= MaestroLey::query()
                                    ->where('ano_pro',$ano_fiscal)
                                    ->where('cod_com', $centro_costo->ajust_ctrocosto.".".$partida)->first();
        if(empty($query_partida)){
             alert()->error("Partida Presupuestaria [".$centro_costo->ajust_ctrocosto.".".$partida ." ] No Existe en Pre Maestro Ley ");
            return  $centro_costo='';
        }
    }else{
        $centro_costo= CentroCosto::query()->select('ajust_ctrocosto')
                                    ->where('ano_pro',$ano_fiscal)
                                    ->where('cod_cencosto', $centro_viejo)->first();
        if(!empty($centro_costo->ajust_ctrocosto)){
            //----------------------------------------------------------------------------
            //Validar que el centro de costo exista en pre_maestro_ley para el año fiscal
            //----------------------------------------------------------------------------
            $query_partida= MaestroLey::query()
                                        ->where('ano_pro',$ano_fiscal)
                                        ->where('cod_com', $centro_costo.".".$partida)->first();
            if(empty($query_partida)){
                alert()->error("Partida Presupuestaria [".$centro_costo.".".$partida ." ] No Existe en Pre Maestro Ley ");
                return  $centro_costo='';
            }
        }else{
            alert()->error("Error... Gerencia no tiene Asociado Centro de Costo.\\n Comuniquese con su Administrados de Sistema.");
            return  $centro_costo='';
        }

    }
    return  $centro_costo->ajust_ctrocosto;
}

//--------------------------------------------------------------------------------------------------------
//                         Funcion que arma un arreglo de la partida presupuestaria
//-------------------------------------------------------------------------------------------------------
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
//--------------------------------------------------------------------------------
//             funcion para armar cod
//---------------------------------------------------------------------------------
public function  varmarCodcom($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub) {
    return implode('.', [
        \Str::padLeft($tip_cod, 2, '0'),
        \Str::padLeft($cod_pryacc, 2, '0'),
        \Str::padLeft($cod_obj, 2, '0'),
        \Str::padLeft($gerencia, 2, '0'),
        \Str::padLeft($unidad, 2, '0'),
        \Str::padLeft($cod_par, 2, '0'),
        \Str::padLeft($cod_gen, 2, '0'),
        \Str::padLeft($cod_esp, 2, '0'),
        \Str::padLeft($cod_sub, 2, '0'),
    ]);
}
//--------------------------------------------------------------------------------
//             funcion para armar cod
//---------------------------------------------------------------------------------
public function  varmarpartida($cod_par,$cod_gen,$cod_esp,$cod_sub) {
    return implode('.', [
        \Str::padLeft($cod_par, 2, '0'),
        \Str::padLeft($cod_gen, 2, '0'),
        \Str::padLeft($cod_esp, 2, '0'),
        \Str::padLeft($cod_sub, 2, '0'),
    ]);
}
//--------------------------------------------------------------------------------
//             funcion para ObtenerIdpartida
//---------------------------------------------------------------------------------
public function  ObtenerIdpartida($cod_par,$cod_gen,$cod_esp,$cod_sub) {

   return PartidaPresupuestaria::query()
                                   ->where('cod_par', $cod_par)
                                   ->where('cod_gen', $cod_gen)
                                   ->where('cod_esp', $cod_esp)
                                   ->where('cod_sub', $cod_sub)->first()->id;
}
//--------------------------------------------------------------------------------
//---------------------------------------------------------------------------------
public function  ObtenerDatosPartida($cod_par,$cod_gen,$cod_esp,$cod_sub) {

    return PartidaPresupuestaria::query()
                                    ->where('cod_par', $cod_par)
                                    ->where('cod_gen', $cod_gen)
                                    ->where('cod_esp', $cod_esp)
                                    ->where('cod_sub', $cod_sub)->first();
 }

}
