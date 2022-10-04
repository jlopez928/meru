<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncOrdenCompra extends Model
{
    use HasFactory;
    protected $table        = 'com_encordencompra';

    protected $dateFormat   = 'd/m/Y H:i:s';
}
