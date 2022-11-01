<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetOfertaPro extends Model
{
    use HasFactory;

    protected $table        = 'com_detofertaspro';

    protected $dateFormat   = 'd/m/Y H:i:s';


    public static function getOfertas($ano_pro, $grupo, $nro_req)
    {
        return DetOfertaPro::query()
                                ->join('com_ofertaproveedor as b', function($join){
                                        $join->on('b.ano_pro', 'com_detofertaspro.ano_pro')
                                            ->on('b.grupo', 'com_detofertaspro.grupo')
                                            ->on('b.nro_sol', 'com_detofertaspro.nro_sol');
                                })
                                ->selectRaw("distinct b.num_oferta||'-'||b.ano_pro as ofertas")
                                ->where('com_detofertaspro.ano_pro', $ano_pro)
                                ->where('com_detofertaspro.grupo', $grupo)
                                ->where('com_detofertaspro.nro_req', $nro_req)
                                ->whereNotIn('b.status', ['2'])
                                ->orderBy('ofertas')
                                ->get();
    }

}
