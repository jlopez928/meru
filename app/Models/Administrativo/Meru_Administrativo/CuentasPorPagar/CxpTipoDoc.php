<?php

namespace  App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Model;


class CxpTipoDoc extends Model
{


    protected $table = 'cxp_tipo_doc';

    protected $guarded = [];

    protected $primaryKey = 'cod_tipo';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;



}
