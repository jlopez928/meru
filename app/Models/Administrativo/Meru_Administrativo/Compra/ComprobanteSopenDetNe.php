<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteSopenDetNe extends Model
{
    use HasFactory;

    protected $table = 'comprobantesopendetne';

    protected $fillable = [
            'nro_com',
            'con_com',
            'cod_cta',
            'cod_aux',
            'tip_doc',
            'nro_doc',
            'fec_doc',
            'con_doc',
            'ctro_costo',
            'tip_mto',
            'mto_doc',
            'ano_pro',
            'nro_factura'
        ];

    public $timestamps = false;

    protected $guarded = [];

}
