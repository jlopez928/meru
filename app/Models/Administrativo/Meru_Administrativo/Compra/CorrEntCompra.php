<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrEntCompra extends Model
{
    use HasFactory;
    protected $table    = 'com_corr_entcompras';
    public $timestamps = false;
    protected $guarded = [];

}
