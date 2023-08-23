<?php

namespace App\Models\Administrativo\Meru_Administrativo\Formulacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ControlPresupuesto extends Model
{
	use HasFactory;
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'cod_com';
	protected $table = 'controlpresupuesto';

	protected $fillable = [
        'ano_pro' ,
        'cod_com',
        'usuario'
	];

}
