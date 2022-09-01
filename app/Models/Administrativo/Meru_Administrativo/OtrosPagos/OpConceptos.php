<?php

namespace App\Models\Administrativo\Meru_Administrativo\OtrosPagos;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpConceptosDet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpConceptos extends Model
{
    use HasFactory;
    protected $table = 'op_conceptos';
	protected $fillable = [
                            'cod_con',
                            'des_con',
                            'sta_reg' ,
                            'usuario',
                            'fecha','id'];
    public function opconceptosdet()
    {
        return $this->hasMany(OpConceptosDet::class, 'op_conceptos_id', 'id');
    }
}
