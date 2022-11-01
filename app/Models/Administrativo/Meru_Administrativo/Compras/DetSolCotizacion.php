<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetSolCotizacion extends Model
{
    use HasFactory;

    protected $table        = 'com_detsolcotizacion';

    protected $dateFormat   = 'd/m/Y H:i:s';


    public static function getCotizaciones($ano_pro, $grupo, $nro_req)
    {
        return DetSolCotizacion::query()
                                ->join('com_solcotizacion as b', function($join){
                                        $join->on('b.ano_pro', 'com_detsolcotizacion.ano_pro')
                                            ->on('b.grupo', 'com_detsolcotizacion.grupo')
                                            ->on('b.nro_sol', 'com_detsolcotizacion.nro_sol');
                                })
                                ->selectRaw("distinct com_detsolcotizacion.grupo||'-'||com_detsolcotizacion.nro_sol||'-'||com_detsolcotizacion.ano_pro as cotizacion")
                                ->where('com_detsolcotizacion.ano_pro', $ano_pro)
                                ->where('com_detsolcotizacion.grupo', $grupo)
                                ->where('com_detsolcotizacion.nro_req', $nro_req)
                                ->whereNotIn('b.sta_sol', ['4'])
                                ->orderBy('cotizacion')
                                ->get();
    }

}
