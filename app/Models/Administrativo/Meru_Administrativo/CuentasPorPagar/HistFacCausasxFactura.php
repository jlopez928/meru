<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistFacCausasxFactura extends Model
{
    use HasFactory;
    protected $table = 'hist_fac_causas_x_factura';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'ano_pro',
        'nro_reng',
        'num_fac',
        'cod_dev',
        'rif_prov',
        'usuario',
        'fecha'
    ];

}
