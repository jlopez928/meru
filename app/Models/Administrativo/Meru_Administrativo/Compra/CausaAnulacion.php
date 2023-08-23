<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CausaAnulacion extends Model
{
    use HasFactory;

    protected $table = 'com_causasanulacion';

    public $timestamps = false;

    protected $fillable = [
            'cod_cau',
            'des_cau',
            'sta_reg',
            'usuario',
            'fec_hor',
            'id',
            ];
}

