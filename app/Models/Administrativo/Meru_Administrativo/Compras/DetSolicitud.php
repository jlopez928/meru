<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetSolicitud extends Model
{
    use HasFactory;

    protected $table        = 'com_detsolicitud';

    protected $dateFormat   = 'd/m/Y H:i:s';
}
