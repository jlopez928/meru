<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use App\Models\Administrativo\Meru_Administrativo\Contabilidad\PlanContable;
use Illuminate\Database\Eloquent\Model;

class CxpDetComproFacturas extends Model
{


    protected $table = 'cxp_detcompro_facturas';



    public $timestamps = false;

    protected $guarded = [];
    public function plancontable()
	{
		return $this->belongsTo(PlanContable::class, 'cod_cta', 'cod_cta')->withDefault();

	}
}
