<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CxPTipoDocumento extends Model
{
    use HasFactory;
    protected $table = 'cxp_tipo_doc';
    protected $primaryKey = 'cod_tipo';

}
