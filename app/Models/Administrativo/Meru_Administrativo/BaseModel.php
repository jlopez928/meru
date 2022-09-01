<?php


namespace App\Models\Administrativo\Meru_Administrativo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    //use SoftDeletes;
    protected $connection = 'pgsql';
    protected $dateFormat = 'd/m/Y H:i:s';
}
