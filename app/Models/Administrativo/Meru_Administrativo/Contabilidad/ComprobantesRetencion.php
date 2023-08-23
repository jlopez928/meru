<?php

namespace App\Models\Administrativo\Meru_Administrativo\Contabilidad;

use App\Models\Administrativo\Meru_Administrativo\Contabilidad\ComprobantesDetRetencion;
use Illuminate\Database\Eloquent\Model;

class ComprobantesRetencion extends Model
{
    protected $table = 'comprobantes_retencion';
    public $timestamps = false;
    protected $guarded = [];
    public function comprobantesdetretencion()
	{
		return $this->belongsTo(ComprobantesDetRetencion::class, 'nro_comprobante', 'nro_comprobante')->withDefault();
	}
    public function comprobantesdetretencionND()
	{
		return $this->belongsTo(ComprobantesDetRetencion::class, 'nro_comprobante', 'nro_comprobante')
                                 ->where('nro_literal', 'ND')->withDefault();

	}

    public function ComprobantesDetRetencionNC()
	{   return $this->belongsTo(ComprobantesDetRetencion::class,'nro_comprobante', 'nro_comprobante')
        ->whereIn('cod_ret', ['I1', 'I2'])
        ->whereIn('nro_literal', ['NC', 'ND'])
        ->orderBy('cod_ret','asc');


	}
}
