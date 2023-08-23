<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacCausaDevolucion extends Model
{
    use HasFactory;
    protected $table = 'fac_causasdevolucion';
	protected $primaryKey = 'cod_dev';
}
