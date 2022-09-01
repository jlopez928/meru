<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Administrativo\Meru_Administrativo\Estado;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidadmedida';

    protected $fillable =   [
        'cod_uni',
        'des_uni',
        'sta_reg',
        'usuario',
        'fecha'
    ];

    protected $primaryKey = 'cod_uni';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'sta_reg' => Estado::class,
    ];

}