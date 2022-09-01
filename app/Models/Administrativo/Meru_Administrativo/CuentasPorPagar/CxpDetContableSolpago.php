<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CxpDetContableSolpago extends Model
{
    use HasFactory;

    protected $table = 'cxp_detcontablesolpago';

    protected $fillable = [
        'ano_pro',
        'ord_pag',
        'nro_ren',
        'cod_cta',
        'tipo',
        'monto'    ];

    public $timestamps = false;

    protected $guarded = [];

}
