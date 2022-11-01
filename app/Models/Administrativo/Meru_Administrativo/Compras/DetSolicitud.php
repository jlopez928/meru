<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetSolicitud extends Model
{
    use HasFactory, Compoships;

    protected $table        = 'com_detsolicitud';

    protected $dateFormat   = 'd/m/Y H:i:s';

    protected $guarded      = [];

    public $incrementing    = false;

    public $timestamps      = false;


    public static function getEstructurasPresupuestarias($ano_pro,$grupo,$nro_req)
    {
        return DetSolicitud::query()
                        ->join('com_encsolicitud as b', function($join){
                                $join->on('b.nro_req', 'com_detsolicitud.nro_req')
                                    ->on('b.grupo', 'com_detsolicitud.grupo')
                                    ->on('b.ano_pro', 'com_detsolicitud.ano_pro');
                        })
                        ->selectRaw('com_detsolicitud.ano_pro, com_detsolicitud.cod_com, sum(com_detsolicitud.cant_sal*com_detsolicitud.pre_ref) as mto_tra')
                        ->where('com_detsolicitud.ano_pro', $ano_pro)
                        ->where('com_detsolicitud.grupo', $grupo)
                        ->where('com_detsolicitud.nro_req', $nro_req)
                        ->where('b.aplica_pre', '1')
                        ->groupBy('com_detsolicitud.ano_pro','com_detsolicitud.cod_com')
                        ->get();
    }

    public static function  getCodCom($tip_cod,$cod_pryacc,$cod_obj,$gerencia,$unidad,$cod_par,$cod_gen,$cod_esp,$cod_sub) {
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
}
