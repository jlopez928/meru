<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;


use Illuminate\Database\Eloquent\Model;

class CorrSolCompras extends Model
{
    protected $table = 'com_corr_solcompras';

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];


    public static function getCorrSolCompras($ano_fiscal, $grupo)
    {
        return CorrSolCompras::query()
                                ->where('ano_pro', $ano_fiscal)
                                ->where('grupo', $grupo)
                                ->max('nro_req');
    }

    public static function incCorrSolCompras($ano_fiscal, $grupo, $valor)
    {
        return CorrSolCompras::query()
                                ->where('ano_pro', $ano_fiscal)
                                ->where('grupo', $grupo)
                                ->increment('nro_req', $valor);
    }
}
