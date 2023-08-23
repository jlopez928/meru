<?php

namespace App\Models\Administrativo\Meru_Administrativo\OtrosPagos;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\ConceptosContratoDet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoContrato extends Model
{
    use HasFactory;

    protected $table = 'op_conceptos_contrato';

    protected $fillable = [
        'cod_con',
        'des_con',
        'sta_reg',
        'usuario',
        'fecha',
        'cont_fis'
    ];

    public $timestamps = false;

    protected $guarded = [];

    public function conceptoscontratodet()
    {
        return $this->hasMany(ConceptosContratoDet::class, 'op_conceptos_id', 'id');
    }
}
