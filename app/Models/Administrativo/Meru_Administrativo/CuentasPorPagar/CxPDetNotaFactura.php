<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CxPDetNotaFactura extends Model
{
    use HasFactory;
    protected $table = 'cxp_detnotasfacturas';
    protected $primaryKey = 'num_fac';
    protected $dates = [

    ];
    public $timestamps = false;
    protected $fillable = [
    ];
}
