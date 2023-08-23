<?php

namespace App\Models\Administrativo\Meru_Administrativo\Modificaciones;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoSolicitudTraspaso;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudTraspaso extends Model
{
    use HasFactory;

    protected $table = 'mod_soltraspasos';

	protected $fillable = [
        'ano_pro',
        'nro_sol',
        'num_sop',
        'cod_ger',
        'concepto',
        'justificacion',
        'centro_costo',
        'nro_ext',
        'fec_sol',
        'sta_reg',
        'usuario',
        'total',
        'fec_apr',
        'usu_apr',
        'fec_sta',
        'usu_sta',
        'cau_anu',
        'referencia',
        // Nuevos
        'user_id',
        'user_id_apr',
        'user_id_status',
        'centro_costo_id'
	];

    protected $casts = [
        'sta_reg' => EstadoSolicitudTraspaso::class,
        //'fec_sol' => 'datetime:d/m/Y',
    ];

    ////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// RELACIONES //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

    public function gerencia()
	{
		return $this->belongsTo(Gerencia::class, 'cod_ger', 'cod_ger')->withDefault();
	}

    public function centroCosto()
	{
		return $this->belongsTo(CentroCosto::class, 'centro_costo_id', 'id')->withDefault();
	}

    public function detalleSolicitudTraspaso()
    {
        return $this->hasMany(SolicitudTraspasoTraspasoDetalle::class, 'solicitud_traspaso_id', 'id');
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////// MÉTODOS PROPIOS ///////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////

    public function obtenerEstructuras()
    {
        $estructuras = []; 
        $detalle     = $this->detalleSolicitudTraspaso;

        foreach ($detalle as $row) {
            $partida = PartidaPresupuestaria::where('cod_par', $row->cod_par)
                        ->where('cod_gen', $row->cod_gen)
                        ->where('cod_esp', $row->cod_esp)
                        ->where('cod_sub', $row->cod_sub)
                        ->first();

            $mtoDis = MaestroLey::where('ano_pro', $this->ano_pro)
                ->where('cod_com', $row->cod_com)
                ->pluck('mto_dis')
                ->first();

            $estructuras[$row->cod_com] = [
                'cod_com'    => $row->cod_com,
                'tip_cod'    => $row->tip_cod,
                'cod_pryacc' => $row->cod_pryacc,
                'cod_obj'    => $row->cod_obj,
                'gerencia'   => $row->gerencia,
                'unidad'     => $row->unidad,
                'cod_par'    => $row->cod_par,
                'cod_gen'    => $row->cod_gen,
                'cod_esp'    => $row->cod_esp,
                'cod_sub'    => $row->cod_sub,
                'descrip'    => $partida->des_con,
                'mto_dis'    => $mtoDis,
                'mto_tra'    => $row->mto_tra,
            ];
        }

        return $estructuras;
    }

    public function formatNumber($attr) {
		return number_format($this->{$attr}, 2, ',', '.');
	}
}