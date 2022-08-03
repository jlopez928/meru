<?php

namespace App\Models\Administrativo\Meru_Administrativo\Proveedores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamoProveedor extends Model
{
    use HasFactory;

    protected $table = 'pro_ramosproveedores';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;
}
