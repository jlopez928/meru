<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioBien extends Model
{
    use HasFactory, Compoships;

    protected $table        = 'com_servicioabienes';

    protected $guarded      = [];

    public $incrementing    = false;

    public $timestamps      = false;


    public function bien()
    {
        return $this->belongsTo(Bien::class, 'cod_corr','cod_corr');
    }
}
