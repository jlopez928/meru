<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';
    protected $primaryKey = 'rif_prov';
    public $incrementing = false;
    protected $keyType = 'string';
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
        'nro_reng'
];

public $timestamps = false;

protected $guarded = [];

public function opsolserviciofactura()
    {
    return $this->hasOne(OpSolservicio::class, 'rif_prov', 'rif_prov')
                                        ->where('ano_sol','ano_pro')
                                        ->where('nro_doc','xnro_sol');
    }

}
