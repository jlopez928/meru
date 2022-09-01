<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CxpDetGastosSolpago extends Model
{
    use HasFactory;

    protected $table = 'cxp_detgastossolpago';

    protected $fillable = [
        'ano_pro',
        'ord_pag',
        'tip_cod',
        'cod_pryacc',
        'cod_obj',
        'gerencia',
        'unidad',
        'cod_par',
        'cod_gen',
        'cod_esp',
        'cod_sub',
        'cod_com',
        'mto_tra',
        'mto_sdo',
        'status_presu'
    ];

    public $timestamps = false;

    protected $guarded = [];

}
