<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetOrdenCompra extends Model
{
    use HasFactory;

     protected $table        = 'com_detordencompra';

    protected $dateFormat   = 'd/m/Y H:i:s';

}
