<?php

namespace App\Models\Administrativo\Meru_Administrativo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TesCorrelativo extends Model
{
    use HasFactory;
    protected $table = 'tes_correlativos';

    protected $guarded = [];

    protected $primaryKey = 'ano_pro';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;


}
