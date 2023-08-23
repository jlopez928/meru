<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistFacRecepFacturas extends Model
{
    use HasFactory;
    protected $table = 'hist_fac_recepfacturas';

    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
                'ano_pro',
                'nro_reng',
                'rif_prov',
                'num_fac',
                'fec_fac',
                'fec_rec',
                'fec_dev',
                'resp_dev',
                'hor_rec',
                'mto_fac',
                'concepto',
                'observaciones',
                'sta_fac',
                'fec_sta',
                'usuario',
                'usuario_dev',
                'usuario_reac',
                'usuario_mod',
                'usuario_entrega',
                'fec_mod',
                'fec_reac',
                'fec_entrega',
                'tipo_doc',
                'nro_doc',
                'ano_sol',
                'recibo'
    ];
}
