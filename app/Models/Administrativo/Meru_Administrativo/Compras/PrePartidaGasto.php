<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Administrativo\Meru_Administrativo\Estado;

class PrePartidaGasto extends Model
{
    use HasFactory;

    protected $table = 'pre_partidasgastos';

    protected $dateFormat = 'd/m/Y H:i:s';

    protected $guarded = [];

    // protected $primaryKey = 'cod_par';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'sta_reg' => Estado::class,
    ];

    public static function getPartidas()
    {
        return PrePartidaGasto::query()
                                ->where('tip_cod', 4)
                                ->where('cod_par','<>', 0)
                                ->where('cod_gen', 0)
                                ->where('cod_esp', 0)
                                ->where('cod_sub', 0)
                                ->orderBy('cod_par')
                                ->pluck('des_con', 'cod_par');
    }

    public static function getGenericas($cod_par)
    {
        return $cod_par == '' ? collect([]) : PrePartidaGasto::query()
                                ->where('tip_cod', 4)
                                ->where('cod_par', $cod_par)
                                ->where('cod_gen','<>', 0)
                                ->where('cod_esp', 0)
                                ->where('cod_sub', 0)
                                ->orderBy('cod_gen')
                                ->pluck('des_con', 'cod_gen');
    }

    public static function getEspecificas($cod_par, $cod_gen)
    {
        return $cod_gen == '' ? collect([]) : PrePartidaGasto::query()
                                ->where('tip_cod', 4)
                                ->where('cod_par', $cod_par)
                                ->where('cod_gen', $cod_gen)
                                ->where('cod_esp','<>',0)
                                ->where('cod_sub', 0)
                                ->orderBy('cod_esp')
                                ->pluck('des_con', 'cod_esp');
    }

    public static function getSubEspecificas($cod_par, $cod_gen, $cod_esp)
    {
        return $cod_esp == '' ? collect([]) : PrePartidaGasto::query()
                                ->where('tip_cod', 4)
                                ->where('cod_par', $cod_par)
                                ->where('cod_gen', $cod_gen)
                                ->where('cod_esp', $cod_esp)
                                ->where('cod_sub','<>',0)
                                ->orderBy('cod_sub')
                                ->pluck('des_con', 'cod_sub');
    }

    // public static function getPartidas($columns = ['*'])
    // {
    //     return PrePartidaGasto::query()
    //                             ->where('tip_cod', 4)
    //                             ->whereNot('cod_par', 0)
    //                             ->where('cod_gen', 0)
    //                             ->where('cod_esp', 0)
    //                             ->where('cod_sub', 0)
    //                             ->orderBy('cod_par')
    //                             ->get($columns);
    // }

    // public static function getGenericas($cod_par, $columns = ['*'])
    // {
    //     return $cod_par == '' ? collect([]) : PrePartidaGasto::query()
    //                                             ->where('tip_cod', 4)
    //                                             ->where('cod_par', $cod_par)
    //                                             ->whereNot('cod_gen', 0)
    //                                             ->where('cod_esp', 0)
    //                                             ->where('cod_sub', 0)
    //                                             ->orderBy('cod_gen')
    //                                             ->get($columns);
    // }



}
