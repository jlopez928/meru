<?php

namespace  App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Model;
class LineasAereas extends Model
{

    protected $table = 'lineas_aereas';

    protected $guarded = [];

    protected $primaryKey = 'rif_aerolinea';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;


}
