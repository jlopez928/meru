<?php

namespace  App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxpDetGastosSolpago;
use App\Enums\Administrativo\Meru_Administrativo\CuentasxPagar\EstadoSolicitudPago;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxpDetDescuentosSolpago;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxpDetContableSolpago;
use  App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxpDetComproFacturas;
use App\Models\Administrativo\Meru_Administrativo\Contabilidad\ComprobantesRetencion;
class Solpago extends Model
{


    protected $table = 'solpago';
    protected $guarded = [];
    protected $keyType = 'string';
    public $timestamps = false;
    protected $dates = [
        'fecha','fec_fac','fec_apr'
    ];

	public function cxpdetgastosolpago()
	{   return $this->hasMany(CxpDetGastosSolpago::class,'ano_pro', 'ano_pro')
        ->where('ord_pag', $this->ord_pag);

	}
    public function cxpdetdescuentosolpago()
	{   return $this->hasMany(CxpDetDescuentosSolpago::class,'ano_pro', 'ano_pro')
        ->where('ord_pag', $this->ord_pag);

	}
    public function cxpdetcontablesolpago()
	{   return $this->hasMany(CxpDetContableSolpago::class,'ano_pro', 'ano_pro')
        ->where('ord_pag', $this->ord_pag)
        ->groupBy('cod_cta','tipo')
        ->select('cod_cta', 'tipo')
        ->selectRaw('SUM(monto) AS monto')
        ->orderBy('tipo','desc');

	}
    public function cxpdetcontablesolpago1()
	{   return $this->hasMany(CxpDetContableSolpago::class,'ano_pro', 'ano_pro')
        ->where('ord_pag', $this->ord_pag)
        ->where('gasto', '1')
        ->orderBy('nro_ren','asc');

	}
    public function cxpdetcontablesolpago0()
	{   return $this->hasMany(CxpDetContableSolpago::class,'ano_pro', 'ano_pro')
        ->where('ord_pag', $this->ord_pag)
        ->where('gasto', '0')
        ->orderBy('nro_ren','asc');


	}
    public function cxpdetcontablesolpago2()
	{   return $this->hasMany(CxpDetContableSolpago::class,'ano_pro', 'ano_pro')
        ->where('ord_pag', $this->ord_pag)
        ->where('gasto', '2')
        ->orderBy('nro_ren','asc');

	}
    public function cxpdetcomprofacturas()
	{   return $this->hasMany(CxpDetComproFacturas::class,'ano_sol_pago', 'ano_pro')
        ->where('nro_sol_orden', $this->ord_pag)
        ->where('nc', '0')
        ->orderBy('nro_ren','asc');


	}
    public function cxpdetcomprofacturasnc()
	{   return $this->hasMany(CxpDetComproFacturas::class,'ano_sol_pago', 'ano_pro')
        ->where('nro_sol_orden', $this->ord_pag)
        ->where('nc', '1')
        ->orderBy('nro_ren','asc');
	}

    public function comprobantesretencion()
	{   return $this->hasMany(ComprobantesRetencion::class,'ano_origen', 'ano_pro')
        ->where('nro_origen', $this->ord_pag)
        ->where('status','!=', '2')
        ->where('cod_ret', 'V1');
	}
    public function comprobantesretenciontc()
	{   return $this->hasMany(ComprobantesRetencion::class,'ano_origen', 'ano_pro')
        ->where('nro_origen', $this->ord_pag)
        ->where('status','!=', '2')
        ->where('cod_ret', 'TC');
	}
    public function comprobantesretencionISLR()
	{   return $this->hasMany(ComprobantesRetencion::class,'ano_origen', 'ano_pro')
        ->where('nro_origen', $this->ord_pag)
        ->where('status','!=', '2')
        ->whereIn('cod_ret', ['I1', 'I2']);
	}
    public function comprobantesretencionCOSC()
	{   return $this->hasMany(ComprobantesRetencion::class,'ano_origen', 'ano_pro')
        ->where('nro_origen', $this->ord_pag)
        ->where('status','!=', '2')
        ->whereIn('cod_ret', ['CS']);
	}
    public function comprobantesretencionUNOXMIL()
	{   return $this->hasMany(ComprobantesRetencion::class,'ano_origen', 'ano_pro')
        ->where('nro_origen', $this->ord_pag)
        ->where('status','!=', '2')
        ->whereIn('cod_ret', ['TM']);
	}
    public function beneficiario()
	{
		return $this->hasOne(Beneficiario::class, 'rif_ben', 'ced_ben')->withDefault();
	}

    protected $casts = [
        'sta_sol' => EstadoSolicitudPago::class

    ];
    public function formatNumber($attr) {
		return number_format($this->{$attr}, 2, ',', '.');
	}
    public static function getEstSol($est_sol)
	{
		$desc = '';

		switch($est_sol) {
            case '0':  $desc  = 'Solo Transcrita'; break;
			case '1':  $desc  = 'Asientos de IVA Aprobados'; break;
			case '2':  $desc  = 'Aprobada por Contabilidad'; break;
			case '3':  $desc  = 'Reversada por Contabilidad'; break;
			case '4':  $desc  = 'Reversada por Asientos de Retenciones'; break;
			case '5':  $desc  = 'Incluida en Programacion Semanal'; break;
			case '6':  $desc  = 'Con Generación de Pago'; break;
			case '7':  $desc  = 'Asientos de Nota de Crédito Aprobados'; break;
			case '8':  $desc  = 'Reversada por Asientos de Nota de Crédito'; break;
			case '9':  $desc  = 'Solicitud de pago con Pago Manual'; break;
			case '10': $desc =  'Solicitud de pago con Comprobante de Retencion de Iva Declarado'; break;
			case '11': $desc =  'Anulada por Contabilidad'; break;
			case '12': $desc =  'Anulada por Presupuesto'; break;
		}
		return $desc;
	}

}
