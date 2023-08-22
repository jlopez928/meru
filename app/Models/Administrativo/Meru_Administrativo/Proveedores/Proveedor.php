<?php

namespace  App\Models\Administrativo\Meru_Administrativo\Proveedores;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;
use App\Enums\Administrativo\Meru_Administrativo\Proveedores\TipoEmpresa;
use App\Enums\Administrativo\Meru_Administrativo\Proveedores\EstadoProveedor;
use App\Enums\Administrativo\Meru_Administrativo\Proveedores\RegistroProveedor;
use App\Enums\Administrativo\Meru_Administrativo\Proveedores\UbicacionProveedor;
use App\Enums\Administrativo\Meru_Administrativo\Proveedores\ClasificacionProveedor;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $guarded = [];

    protected $primaryKey = 'rif_prov';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'tip_emp' => TipoEmpresa::class,
        'sta_con' => EstadoProveedor::class,
        'tip_reg' => RegistroProveedor::class,
        'sta_emp' => ClasificacionProveedor::class,
        'ubi_pro' => UbicacionProveedor::class,
    ];


    public function ramos(){
        return $this->belongsToMany(Ramo::class, 'pro_ramosproveedores','rif_prov', 'cod_ram')
                                    ->withPivot(['usuario']);
    }

    protected function capital() : Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, ',', '.'),
            set: fn($value) => str_replace(",", ".", str_replace(".", "", $value)),
        );
    }

    public static function  formatear($valor,$cantidad) {
        return \Str::padLeft($valor, $cantidad, '0');
    }
}
