<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\cxpdetnotasfacturas;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPDetGastosFactura;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPDetComproFacturas;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = [
        'fec_fac','fecha'
    ];
    protected $fillable = [
        'ano_pro' ,
        'rif_prov',
        'num_fac' ,
        'num_ctrl',
        'fec_fac',
        'tipo_doc' ,
        'tipo_pago' ,
        'nro_doc' ,
        'base_imponible' ,
        'base_excenta' ,
        'mto_nto' ,
        'mto_iva' ,
        'mto_fac' ,
        'por_anticipo' ,
        'mto_anticipo',
        'mto_amortizacion',
        'ncr_sn' ,
        'nro_ncr' ,
        'mto_ncr' ,
        'iva_ncr' ,
        'tot_ncr' ,
        'usuario' ,
        'fecha' ,
        'usua_apr' ,
        'fec_apr' ,
        'usua_anu' ,
        'fec_anu' ,
        'sta_fac',
        'fec_sta',
        'sol_pag',
        'usua_pago' ,
        'fec_pago' ,
        'monto_original' ,
        'porcentaje_iva' ,
        'num_nc' ,
        'ano_sol',
        'recibo',
        'mod_fac',
        'descuentos' ,
        'monto_descuento',
        'cuenta_contable' ,
        'fondo' ,
        'pago_manual',
        'deposito_garantia' ,
        'deuda' ,
        'tipo_nota',
        'ano_nota' ,
        'base_imponible_nd' ,
        'base_exenta_nd' ,
        'observacion' ,
        'sta_rep' ,
        'referencia' ,
        'provisionada',
        'servicio',
        'monto_contrato' ,
        'nro_reng',
        'id'
];

public $timestamps = false;

protected $guarded = [];

public function opsolserviciofactura()
    {
    return $this->hasOne(OpSolservicio::class, 'rif_prov', 'rif_prov')
                                        ->where('ano_sol','ano_pro')
                                        ->where('nro_doc','xnro_sol');
    }
    public static function getEstFac($sta_fac)
	{
		$desc = '';

		switch($sta_fac) {
			case '0':
				$desc = 'Con Expediente Registrado en Control del Gasto';
				break;
			case '1':
				$desc = 'Aprobada Presupuestariamente';
				break;
			case '2':
				$desc = 'Reversada Presupuestariamente';
				break;
			case '3':
				$desc = 'Aprobada Contablemente';
				break;
            case '4':
                $desc = 'Reversar Asiento Contable';
                break;
            case '5':
                $desc = 'Con Deducciones y Retenciones';
                break;
            case '6':
                $desc = 'En Cronograma de Pago';
                break;
            case '8':
                $desc = 'Con Cheque Impreso';
                break;
            case '9':
                $desc = 'Con Pago Parcial';
                break;
		}
		return $desc;
	}

    public function cxptipodocumento()
	{
		return $this->hasOne(CxPTipoDocumento::class,'cod_tipo', 'tipo_doc')
                                              ->where('status','1');
	}

    public function cxpdetnotasfacturas()
    {
        return $this->hasMany(CxPDetNotaFactura::class, 'ano_pro','ano_pro')
                                            ->where('rif_prov',$this->rif_prov)
                                            ->where('num_fac', $this->num_fac);

    }
    public function cxpdetgastosfactura()
    {
        return $this->hasMany(CxPDetGastosFactura::class, 'ano_pro','ano_pro')
                                                ->where('rif_prov',$this->rif_prov)
                                                ->where('num_fac', $this->num_fac);

    }
    public function cxpdetcomprofacturas()
    {
        return $this->hasMany(CxPDetComproFacturas::class, 'ano_pro','ano_pro')
                                            ->where('rif_prov',$this->rif_prov)
                                            ->where('num_fac', $this->num_fac);

    }

}
