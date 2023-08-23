<?php

namespace App\Models\Administrativo\Meru_Administrativo\General;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'unidad';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    public static function obtenerUnidades($cod_ger) {
        return Unidad::query()
                        ->where('status', 1)
                        ->where('cod_ger', $cod_ger)
                        ->orderBy('des_uni')
                        ->pluck('des_uni','cod_uni');
    }
}
