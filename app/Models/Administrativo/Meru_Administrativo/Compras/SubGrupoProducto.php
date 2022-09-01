<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Administrativo\Meru_Administrativo\Estado;
use App\Enums\Administrativo\Meru_Administrativo\Compras\TipoProducto;

class SubGrupoProducto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subgruposprod';

    protected $fillable =   [
        'grupo',
        'subgrupo',
        'des_subgrupo',
        'sta_reg',
        'usuario',
        'fecha'
    ];

    protected $primaryKey = 'subgrupo';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'sta_reg' => Estado::class,
    ];

    public function scopeActivo($query)
    {
        return $query->where('sta_reg', Estado::Activo);
    }

    public function productos(){
        return $this->hasMany(Producto::class, 'subgrupo', 'subgrupo');
    }

    public function grupoproducto()
    {
        return $this->belongsTo(GrupoProducto::class, 'grupo');
    }

    public static function getSubGrupos($tipo_producto, $grupo)
    {
        return SubGrupoProducto::query()
                                ->when($tipo_producto === TipoProducto::BIEN->value || $tipo_producto === TipoProducto::MATERIAL->value,
                                            fn($query) => $query->whereRaw("SUBSTRING(grupo, 1,  1) = 'B'"))
                                ->when($tipo_producto === TipoProducto::SERVICIO_GENERAL->value || $tipo_producto === TipoProducto::SERVICIO_A_VEHICULO->value,
                                            fn($query) => $query->whereRaw("SUBSTRING(grupo, 1,  1) = 'S'"))
                                ->when($tipo_producto === TipoProducto::OBRA->value,
                                            fn($query) => $query->whereRaw("SUBSTRING(grupo, 1,  1) = 'O'"))
                                ->where('grupo', $grupo)
                                ->orderBy('des_subgrupo')
                                ->pluck('des_subgrupo', 'subgrupo');
    }
}