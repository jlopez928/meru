<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetSolicitudDet extends Model
{
    use HasFactory, Compoships;

    protected $table        = 'com_detsolicitud_det';

    protected $dateFormat   = 'd/m/Y H:i:s';

    protected $guarded      = [];

    public $incrementing    = false;

    public $timestamps      = false;
}
