<?php

namespace App\Models\Administrativo\Meru_Administrativo\Modificaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoPermisoTraspaso extends Model
{
    use HasFactory;

    protected $table   = 'mod_hisaprmov';
    public $timestamps = false;

	protected $fillable = [
		'usuario',
        'maxut',
        'multicentro',
        'usu_mod',
        'activo',
        'usuario_id',
        'user_id'
	];
}
