<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudUnidad extends Model
{
    use HasFactory;

    protected $table        = 'com_encsolicitud';

    protected $dateFormat   = 'd/m/Y H:i:s';

    protected $guarded = [];

    // protected $fillable     =   [
    //                                 'cod_com',
    //                                 'usu_com',
    //                                 'fec_hor',
    //                                 'usuario',
    //                                 'sta_reg'
    //                             ];

    public $incrementing    = false;

    public $timestamps      = false;

}
