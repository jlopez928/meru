<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoCompra extends Model
{
    use HasFactory;

    protected $table = 'com_tipocompra';

    protected $primaryKey = 'cod_tipocompra';

    public $incrementing = false;

    public $timestamps = false;


    public function scopeRangos($query, $value)
    {
        return $query->where('cod_tipocompra', $value)->whereNot('cod_tipocompra','0')->orderBy('cod_tipocompra')->limit(1);
    }

}
