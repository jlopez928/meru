<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CxPDetGastosFactura extends Model
{
    use HasFactory;
    protected $table = 'cxp_detgastosfactura';
    protected $primaryKey = 'num_fac';
    protected $dates = [

    ];
    public $timestamps = false;
    protected $fillable = [
    ];
}
