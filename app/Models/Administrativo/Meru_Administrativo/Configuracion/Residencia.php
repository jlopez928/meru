<?php

namespace App\Models\Administrativo\Meru_Administrativo\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residencia extends Model
{
    use HasFactory;
    protected $table = 'adm_residencias';

    use HasFactory;

    protected $fillable = [
        'codigo',
        'descripcion',
        'usuario',
        'fecha',
        'sta_reg',
        ];

    public $timestamps = false;

    //Relacion con almacenes 1:m
    public function residencia()
    {
        return $this->hasMany(Descuento::class,'id','adm_residencia_id');
    }
}
