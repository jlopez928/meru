<?php

namespace App\Models\Administrativo\Meru_Administrativo\OtrosPagos;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpConceptos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpDetsolservicio extends Model
{
    use HasFactory;
    protected $table = 'op_detsolservicio';
    protected $primaryKey = 'xnro_sol';
    public $timestamps = false;
	protected $fillable = [
                        'ano_pro' ,
                        'xnro_sol',
                        'nro_sol',
                        'cod_prod',
                        'por_iva' ,
                        'cantidad' ,
                        'cos_uni',
                        'cos_tot' ,
                        'grupo' ,
                        'base_excenta',
                        'op_solservicio_id'
                                    ];
    public function opconceptos()
	{
		return $this->belongsTo(OpConceptos::class,  'cod_prod','cod_con')->withDefault();
	}
}
