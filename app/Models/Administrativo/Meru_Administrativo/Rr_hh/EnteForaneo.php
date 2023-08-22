<?php

namespace  App\Models\Administrativo\Meru_Administrativo\Rr_hh;

use Illuminate\Database\Eloquent\Model;

class EnteForaneo extends Model
{
    protected $connection = 'pgsql_rrhh';

    protected $table = 'adm_entesforaneos';

    protected $guarded = [];

    protected $primaryKey = 'identeforaneo';

    protected $keyType = 'int';

    public $incrementing = false;

    public $timestamps = false;
}
