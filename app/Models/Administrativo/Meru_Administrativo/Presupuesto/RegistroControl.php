<?php

namespace App\Models\Administrativo\Meru_Administrativo\Presupuesto;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroControl extends Model
{
    use HasFactory;

    protected $table      = 'registrocontrol';
    protected $primaryKey = 'ano_pro';
    public $incrementing  = false;
    protected $fillable   = [
    ];

    public static function periodoActual()
    {
        return RegistroControl::where('sta_con', 'A')->max('ano_pro');
    }
}
