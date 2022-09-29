<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bien extends Model
{
    use HasFactory;

    protected $table        = 'bienes';

    protected $dateFormat   = 'd/m/Y H:i:s';

    protected $guarded = [];

    public $incrementing    = false;

    public $timestamps      = false;

}
