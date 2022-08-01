<?php

namespace App\Models\Administrativo\Meru_Administrativo\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMonto extends Model
{
    use HasFactory;

    protected $table = 'adm_tipo_montos';

    protected $fillable = [
        'codigos',
        'descripcion',
        'usuario',
        'fecha',
        'estado'
        ];

    public $timestamps = false;

    //Relacion con almacenes 1:m
    public function descuento()
    {
        return $this->hasMany(Descuento::class,'id','tipo_montos_id');
    }


}
