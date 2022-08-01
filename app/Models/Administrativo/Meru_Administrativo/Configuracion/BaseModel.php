<?php


namespace App\Models\Administrativo\Meru_Administraivo\Configuracion;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use SoftDeletes;
    protected $connection = 'public';
    protected $dateFormat = 'd/m/Y H:i:s';
}
