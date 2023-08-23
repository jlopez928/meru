<?php

namespace App\Models\Administrativo\Meru_Administrativo\Modificaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidaReceptora extends Model
{
    use HasFactory;

    protected $table      = 'mod_partidasreceptoras';
    protected $primaryKey = 'xnro_sol';
    public $incrementing  = false;
    protected $keyType    = 'string';
    public $timestamps    = false;

    protected $fillable = [
        'xnro_mod',
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
        'sdo_mod',
        'sdo_apa',
        'sdo_pre',
        'sdo_com',
        'sdo_cau',
        'sdo_dis',
        'sdo_pag',
        'esteje',
    ];
}
