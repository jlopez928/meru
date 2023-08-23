<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidadmedida';

    public $timestamps = false;

    protected $fillable = [
            'cod_uni',
            'des_uni',
            'sta_reg',
            'usuario',
            'fecha',
            'id',
            ];
}
