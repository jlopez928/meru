<?php

namespace App\Models\Administrativo\Meru_Administrativo\Tesoreria;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{

    use HasFactory;
    protected $table = 'tes_beneficiarios';

    protected $guarded = [];

    protected $primaryKey = 'rif_ben';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;



    // protected $casts = [
    //     'sta_reg' => Estado::class
    // ];

}
