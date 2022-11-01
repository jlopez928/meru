<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetOrdenCompra extends Model
{
    use HasFactory;

    protected $table        = 'com_detordencompra';

    protected $dateFormat   = 'd/m/Y H:i:s';


    public static function getOrdenes($ano_pro, $grupo, $nro_req)
    {
        return DetOrdenCompra::query()
                                ->join('com_encordencompra as b', function($join){
                                        $join->on('b.ano_pro', 'com_detordencompra.ano_pro')
                                            ->on('b.xnro_ord', 'com_detordencompra.xnro_ord');
                                })
                                ->selectRaw("distinct com_detordencompra.xnro_ord ||'-'||com_detordencompra.ano_pro as orden")
                                ->where('com_detordencompra.ano_pro', $ano_pro)
                                ->where('com_detordencompra.grupo', $grupo)
                                ->where('com_detordencompra.nro_req', $nro_req)
                                ->whereNotIn('b.sta_ord', ['A','3'])
                                ->orderBy('orden')
                                ->get();
    }

}
