<?php

namespace App\Models\Administrativo\Meru_Administrativo\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasaCambio extends Model
{
    use HasFactory;


    protected $table = 'adm_tasacambio';

    protected $fillable = [
                'fec_tasa',
                'bs_tasa' ,
                'usuario',
                'sta_reg',
                'fecha',
                'hora',
                'estado'
                ];

    public $timestamps = false;
}
