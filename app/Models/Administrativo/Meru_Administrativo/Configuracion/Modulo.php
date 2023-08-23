<?php

namespace App\Models\Administrativo\Meru_Administrativo\Configuracion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Permiso;


class Modulo extends Model
{  use HasFactory;


    protected $table = 'modulos';

    protected $fillable = [
                            'nombre',
                            'codigo',
                            'status'];

    public function permiso()
    {
        return $this->hasMany(Permiso::class, 'modulo_id', 'id')->orderBy('name', 'ASC');
    }

}
