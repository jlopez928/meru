<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaBorrada extends Model
{
    use HasFactory;

    protected $table = 'facturas_borradas';
    protected $primaryKey = 'num_fac';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $dates = [
        'fec_fac','fecha'
    ];
    protected $fillable = [
        'ano_pro'            ,
        'rif_prov'           ,
        'num_fac'            ,
        'num_ctrl'           ,
        'fec_fac'            ,
        'tipo_doc'           ,
        'tipo_pago'          ,
        'nro_doc'            ,
        'base_imponible'     ,
        'base_excenta'       ,
        'mto_nto'            ,
        'mto_iva'            ,
        'mto_fac'            ,
        'por_anticipo'       ,
        'mto_anticipo'      ,
        'mto_amortizacion'  ,
        'ncr_sn'        ,
        'nro_ncr'       ,
        'mto_ncr'       ,
        'iva_ncr'       ,
        'tot_ncr'       ,
        'usuario'       ,
        'fecha'         ,
        'usua_apr'      ,
        'fec_apr'       ,
        'usua_anu'      ,
        'fec_anu'       ,
        'sta_fac'       ,
        'fec_sta'       ,
        'sol_pag'       ,
        'usua_pago'     ,
        'fec_pago'      ,
        'monto_original',
        'porcentaje_iva',
        'num_nc'  ,
        'ano_sol' ,
        'recibo'  ,
        'mod_fac' ,
        'usuario_borro' ,
        'fecha_borrada'];

}
