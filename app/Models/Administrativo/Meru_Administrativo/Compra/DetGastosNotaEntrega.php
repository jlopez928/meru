<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetGastosNotaEntrega extends Model
{
    use HasFactory;

    protected $table = 'com_detgastosnotaentrega';
    protected $primaryKey = 'nro_ent';

    protected $fillable = [
            'ano_pro',
            'grupo',
            'nro_ent',
            'tip_cod',
            'cod_pryacc',
            'cod_obj',
            'gerencia',
            'unidad',
            'cod_par',
            'cod_gen',
            'cod_esp',
            'cod_sub',
            'cod_com',
            'mto_tra',
            'mto_cau',
            'causar',
            'encnotaentrega_id'
    ];
    public $timestamps = false;

    protected $guarded = [];

}
