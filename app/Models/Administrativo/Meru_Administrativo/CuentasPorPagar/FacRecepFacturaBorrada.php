<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacRecepFacturaBorrada extends Model
{
    use HasFactory;
    protected $table = 'fac_recepfacturas_borradas';
    protected $primaryKey = 'num_fac';
    public $timestamps = false;

    protected $fillable = [
                'ano_pro',
                'nro_reng',
                'rif_prov',
                'num_fac',
                'fec_fac',
                'fec_rec',
                'fec_dev',
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
                'usuario_anu',
                'fecha_anu',
                'recibo'
            ];
}
