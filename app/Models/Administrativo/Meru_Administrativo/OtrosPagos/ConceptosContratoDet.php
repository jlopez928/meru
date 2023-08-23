<?php

namespace App\Models\Administrativo\Meru_Administrativo\OtrosPagos;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\ConceptoContrato;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptosContratoDet extends Model
{
    use HasFactory;
    protected $table = 'op_conceptos_contrato_det';
	protected $fillable = [
                            'cod_con',
                            'cod_par',
                            'cod_gen',
                            'cod_esp',
                            'cod_sub',
                            'cod_com',
                            'partida_presupuestaria_id',
                            'op_conceptos_id' ];

    protected $primaryKey = 'cod_con';

    protected $keyType = 'string';

    // public $incrementing = false;

    public $timestamps = false;

    public function conceptocontrato()
    {
        return $this->belongsTo(ConceptoContrato::class, 'id','op_conceptos_id')->withDefault();
    }
    public function gerencia()
    {
    return $this->hasOne(Gerencia::class, 'cod_ger', 'cod_ger');
    }
    public function partidapresupuestaria()
	{
		return $this->belongsTo(PartidaPresupuestaria::class, 'partida_presupuestaria_id','id')->withDefault();
	}
}
