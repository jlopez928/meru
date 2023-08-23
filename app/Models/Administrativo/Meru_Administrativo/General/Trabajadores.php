<?php

namespace App\Models\Administrativo\Meru_Administrativo\General;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrativo\Meru_Administrativo\General\Cargos;


class Trabajadores extends Model
{
    use HasFactory;
    protected $table= 'trajadores_v';


    public function cargos()
	{
		return $this->hasMany(Cargos::class, 'codcar', 'codcar');
	}
}
