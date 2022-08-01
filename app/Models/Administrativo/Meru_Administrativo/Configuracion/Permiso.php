<?php

namespace App\Models\Administrativo\Meru_Administrativo\Configuracion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permiso extends SpatiePermission
{
    use HasFactory;
    public function modulo()
    {
        return $this->hasOne(Modulo::class, 'id', 'modulo_id');
    }
}

