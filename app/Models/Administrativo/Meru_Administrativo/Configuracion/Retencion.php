<?php

namespace App\Models\Administrativo\Meru_Administrativo\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retencion extends Model
{
    use HasFactory;
    protected $table = 'adm_retenciones';


    protected $fillable = [
            'cod_ret' ,
            'des_ret' ,
            'cod_cta' ,
            'cod_cta_otra' ,
            'afec_ctaprov'
        ];

    public $timestamps = false;

    //Relacion con almacenes 1:m
    public function descuentos()
    {
        return $this->hasMany(Descuento::class,'id','retencion_id');
    }
    public function retenciones()
    {
        return $this->hasMany(Retencion::class,'id','adm_retencion_id');
    }
}
