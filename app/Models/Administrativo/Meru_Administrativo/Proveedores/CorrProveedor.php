<?php

namespace App\Models\Administrativo\Meru_Administrativo\Proveedores;


use Illuminate\Database\Eloquent\Model;

class CorrProveedor extends Model
{
    protected $table = 'prov_corrproveedor';

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];


    public static function getCorrProveedor($ano_pro)
    {
        return CorrProveedor::query()
                                ->where('ano_pro', $ano_pro)
                                ->max('num_reg');
    }

    public static function incCorrProveedor($ano_pro, $valor)
    {
        return CorrProveedor::query()
                                ->where('ano_pro', $ano_pro)
                                ->update(['num_reg' => $valor]);
    }
}
