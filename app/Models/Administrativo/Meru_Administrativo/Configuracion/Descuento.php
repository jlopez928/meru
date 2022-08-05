<?php

namespace App\Models\Administrativo\Meru_Administrativo\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\TipoMonto;

class Descuento extends Model
{
    use HasFactory;

    protected $table = 'adm_descuentos';


    public $timestamps = false;

    protected $fillable = [
            'cod_des',
            'des_des',
            'tip_mto',
            'cla_desc',
            'por_islr',
            'usuario',
            'fecha',
            'residente',
            'cod_elec',
            'tip_fianza',
            'id_des',
            'status',
            'estado',
            'tipo_montos_id',
            'adm_retencion_id',
            'adm_residencia_id',
            ];



    public function tipomontos()
    {
        return $this->belongsTo(TipoMonto::class,'tipo_montos_id','id');
    }

    public function adm_retencions()
    {
        return $this->belongsTo(Retencion::class,'adm_retencion_id','id');
    }

    public function adm_residencias()
    {
        return $this->belongsTo(Residencia::class,'adm_residencia_id','id');
    }
}
