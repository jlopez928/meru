<?php

namespace App\Models\Administrativo\Meru_Administrativo\Proveedores;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Administrativo\Meru_Administrativo\Estado;

class Ramo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pro_ramos';

    protected $fillable =   [
        'cod_ram',
        'des_ram',
        'usuario',
        'sta_reg',
        'id'
    ];

    protected $primaryKey = 'cod_ram';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'sta_reg' => Estado::class,
    ];


    public function ramoproveedores(){
        return $this->hasMany(RamoProveedor::class, 'cod_ram', 'cod_ram');
    }


}
