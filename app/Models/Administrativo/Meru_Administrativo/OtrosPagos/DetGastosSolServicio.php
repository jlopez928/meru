<?php

namespace App\Models\Administrativo\Meru_Administrativo\OtrosPagos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetGastosSolServicio extends Model
{
    use HasFactory;

    protected $table = 'op_detgastossolservicio';

    protected $fillable = [];

    public $timestamps = false;
}
