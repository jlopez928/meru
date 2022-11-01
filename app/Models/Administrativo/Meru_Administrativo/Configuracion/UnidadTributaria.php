<?php

namespace App\Models\Administrativo\Meru_administrativo\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadTributaria extends Model
{
    use HasFactory;
    protected $table = 'adm_unitributaria';

    protected $fillable =   [
                                'fec_ut',
                                'bs_ut',
                                'bs_ucau',
                                'vigente',
                                'usuario',
                                'estado'
                            ];

    public $timestamps = false;

    public static function getUltimaUnidadTributaria()
    {
        return UnidadTributaria::query()->orderByDesc('fec_ut')->first(['fec_ut','bs_ut']);
    }

}
