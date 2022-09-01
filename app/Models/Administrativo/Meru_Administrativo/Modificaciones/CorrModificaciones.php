<?php

namespace App\Models\Administrativo\Meru_Administrativo\Modificaciones;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrModificaciones extends Model
{
    use HasFactory;

    protected $table = 'mod_corrmodificaciones';

    protected $primaryKey = 'ano_pro';

    public $timestamps = false;
    
    protected $fillable = [
        'ano_pro',
        'num_reg',
        'num_sol',
	];
}
