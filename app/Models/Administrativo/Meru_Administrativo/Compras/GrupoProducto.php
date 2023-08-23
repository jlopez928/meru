<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Administrativo\Meru_Administrativo\Estado;
use App\Enums\Administrativo\Meru_Administrativo\Compras\TipoProducto;

class GrupoProducto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gruposprod';

    protected $fillable =   [
        'grupo',
        'des_grupo',
        'sta_reg',
        'usuario',
        'fecha'
    ];

    protected $primaryKey = 'grupo';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'sta_reg' => Estado::class,
    ];

    public function subgrupoproductos(){
        return $this->hasMany(SubGrupoProducto::class, 'grupo', 'grupo');
    }

    public function scopeActivo($query)
    {
        return $query->where('sta_reg', Estado::Activo);
    }

    public static function getGrupos($tipo_producto)
    {
        return GrupoProducto::query()
                            ->when($tipo_producto === TipoProducto::BIEN->value || $tipo_producto === TipoProducto::MATERIAL->value,
                                        fn($query) => $query->whereRaw("SUBSTRING(grupo, 1,  1) = 'B'"))
                            ->when($tipo_producto === TipoProducto::SERVICIO_GENERAL->value || $tipo_producto === TipoProducto::SERVICIO_A_VEHICULO->value,
                                        fn($query) => $query->whereRaw("SUBSTRING(grupo, 1,  1) = 'S'"))
                            ->when($tipo_producto === TipoProducto::OBRA->value,
                                        fn($query) => $query->whereRaw("SUBSTRING(grupo, 1,  1) = 'O'"))
                            ->orderBy('des_grupo')
                            ->pluck('des_grupo', 'grupo');
    }

}