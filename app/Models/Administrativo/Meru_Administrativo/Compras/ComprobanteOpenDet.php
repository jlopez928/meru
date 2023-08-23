<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprobanteOpenDet extends Model
{
    use HasFactory;

    protected $table = 'comprobantesopendet';

    public $timestamps = false;

    protected $guarded = [];
}
