<?php

namespace App\Models\Administrativo\Meru_Administrativo\OtrosPagos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use Illuminate\Database\Eloquent\Model;

class OpDetgastossolservicio extends Model
{   use HasFactory;
    protected $table = 'op_detgastossolservicio';
    protected $primaryKey = 'xnro_sol';

    public $timestamps = false;
	protected $fillable = [
                            'ano_pro' ,
                            'xnro_sol' ,
                            'nro_sol' ,
                            'tip_cod',
                            'cod_pryacc',
                            'cod_obj',
                            'gerencia',
                            'unidad',
                            'cod_par',
                            'cod_gen',
                            'cod_esp',
                            'cod_sub',
                            'cod_com',
                            'mto_tra',
                            'sal_cau',
                            'grupo',
                            'gasto',
                            'monto_causado',
                            'causado_acumulado',
                            'saldo',
                            'nro_ren',
                            'cod_cta',
                            'op_solservicio_id',
                            'partida_presupuestaria_id'
                                    ];

    public function partidapresupuestaria()
    {
        return $this->belongsTo(PartidaPresupuestaria::class, 'partida_presupuestaria_id', 'id')
                ->withDefault();
    }

    }
