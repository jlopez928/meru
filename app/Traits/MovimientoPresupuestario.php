<?php

namespace App\Traits;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;

trait MovimientoPresupuestario
{
  function ActualizarPreMaestro($anopro,$codcom,$tipo_operacion,$mtotra){
        $maestroley=MaestroLey::query()->where('ano_pro',$anopro)
                                    ->where('cod_com',$codcom)->first();
        switch ($tipo_operacion) {
            case "8": //Precompromiso
                         $maestroley->update([
                            'mto_pre '             => $maestroley->mto_pre - $mtotra,
                            'mto_dis'              => $maestroley->mto_dis + $mtotra
                        ]);
                        break;
            case "9": //Reverso de Precompromiso
                       $maestroley->update([
                            'mto_pre '             => $maestroley->mto_pre - $mtotra,
                            'mto_dis'              => $maestroley->mto_dis + $mtotra
                        ]);
                        break;
            case "10": //Compromiso Por Flujos Diferentes a OC/OS
                            $maestroley->update([
                                'mto_dis'   => $maestroley->mto_dis - $mtotra,
                                'mto_com'   => $maestroley->mto_com + $mtotra
                            ]);
                            break;
            case "20": // Reverso Compromiso  Por Flujos Diferentes a OC/OS
                            $maestroley->update([
                                            'mto_dis'   => $maestroley->mto_dis + $mtotra,
                                            'mto_com'   => $maestroley->mto_com - $mtotra
                                        ]);
                                        break;
            case "30":// Causado Directo
                                $maestroley->update([
                                    'mto_dis'   => $maestroley->mto_dis - $mtotra,
                                    'mto_com'   => $maestroley->mto_com + $mtotra,
                                    'mto_cau'   => $maestroley->mto_cau + $mtotra
                                ]);
            case "40"://  Reverso Causado Directo
                                    $maestroley->update([
                                        'mto_dis'   => $maestroley->mto_dis + $mtotra,
                                        'mto_com'   => $maestroley->mto_com - $mtotra,
                                        'mto_cau'   => $maestroley->mto_cau - $mtotra
                                    ]);
                                    break;

        }
        return true;
  }

}
