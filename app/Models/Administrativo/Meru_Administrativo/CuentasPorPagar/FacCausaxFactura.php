<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacCausaxFactura extends Model
{
    use HasFactory;

    protected $table = 'fac_causas_x_factura';
    protected $primaryKey = 'num_fac';
    public $timestamps = false;

    protected $dates = [
        'fecha'
    ];
    protected $fillable = [
        'ano_pro',
        'nro_reng',
        'num_fac',
        'cod_dev',
        'rif_prov',
        'usuario',
        'fecha',
        'status',
        'ano_sol',
    ];

    public function faccausadevolucion()
	{
		return $this->hasOne(FacCausaDevolucion::class,'cod_dev', 'cod_dev');

	}
}
