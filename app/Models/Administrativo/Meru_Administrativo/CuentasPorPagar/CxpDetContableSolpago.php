<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use App\Models\Administrativo\Meru_Administrativo\Contabilidad\PlanContable;
use Illuminate\Database\Eloquent\Model;

class CxpDetContableSolpago extends Model
{


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
    public function plancontable()
	{
		return $this->belongsTo(PlanContable::class, 'cod_cta', 'cod_cta')->withDefault();

	}
}
