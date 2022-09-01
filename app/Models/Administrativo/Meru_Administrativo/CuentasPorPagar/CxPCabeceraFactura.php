<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CxPCabeceraFactura extends Model
{
    use HasFactory;

    protected $table = 'cxp_cabecera_facturas';

    protected $fillable = [
        'ano_pro',
        'rif_prov',
        'tipo_doc',
        'nro_doc',
        'ano_doc',
        'doc_asociado',
        'ano_doc_asociado',
        'tipo_pago',
        'fondo',
        'base_imponible',
        'base_excenta',
        'porcentaje_iva',
        'mto_nto',
        'mto_iva',
        'mto_tot',
        'por_anticipo',
        'monto_anticipo',
        'monto_amortizacion',
        'usuario',
        'fecha',
        'statu_proceso',
        'fec_sta',
        'pago_manual',
        'cuenta_contable',
        'nota_entrega_prov',
        'monto_neto_doc',
        'num_fac',
        'deposito_garantia'
];

public $timestamps = false;

protected $guarded = [];

}
