<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CxPGastoFactura extends Model
{
    use HasFactory;

    protected $table = 'cxp_gasto_factura';

    protected $fillable = [
        'ano_pro',
        'rif_prov',
        'ano_doc_asociado',
        'doc_asociado',
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
        'gasto',
        'causar'
    ];

    public $timestamps = false;

    protected $guarded = [];

}
