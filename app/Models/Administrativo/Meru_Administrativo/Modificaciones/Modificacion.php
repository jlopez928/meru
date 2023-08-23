<?php

namespace App\Models\Administrativo\Meru_Administrativo\Modificaciones;

use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\EstadoModificacion;
use App\Enums\Administrativo\Meru_Administrativo\Modificaciones\TipoModificacion;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Modificacion extends Model
{
    use HasFactory;

    protected $table   = 'modificaciones';
    public $timestamps = false;

    protected $fillable = [
        'ano_pro',
        'num_mes',
        'tip_ope',
        'nro_mod',
        'xnro_mod',
        'tip_doc',
        'num_doc',
        'fec_pos',
        'fec_tra',
        'concepto',
        'justificacion',
        'fec_rev',
        'sta_reg',
        'fec_sta',
        'usuario',
        'fecha',
        'esteje',
        'usu_sta',
        'referencia',
        'id',
        'user_id',
        'user_id_status',
    ];

    protected $casts = [
        'tip_ope' => TipoModificacion::class,
        'sta_reg' => EstadoModificacion::class
    ];

    ////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// ACCESORS Y MUTATORS /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
    
	public function concepto(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	public function justificacion(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// RELACIONES //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

    public function partidasCedentes()
    {
        return $this->hasMany(PartidaCedente::class, 'xnro_mod', 'xnro_mod');
    }

    public function partidasReceptoras()
    {
        return $this->hasMany(PartidaReceptora::class, 'xnro_mod', 'xnro_mod');
    }

    public function solicitudTraspaso()
    {
        return $this->belongsTo(SolicitudTraspaso::class, 'num_doc', 'nro_sol')
            ->where('ano_pro', $this->ano_pro)
            ->withDefault();
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////// MÉTODOS PROPIOS ///////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Obtener estructuras
     * 
     * @param string $tipo
     * 
     * @return array
     */
    private function _obtenerEstructuras($tipo = 'c'): array
    {
        $estructuras = [];
        $detalle     = $tipo == 'c' ? $this->partidasCedentes : $this->partidasReceptoras;

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

    /**
     * Obtener estructuras cedentes
     * 
     * @return array
     */
    public function estructurasCedentes(): array
    {
        return $this->_obtenerEstructuras();
    }

    /**
     * Obtener estructuras receptoras
     * 
     * @return array
     */
    public function estructurasReceptoras(): array
    {
        return $this->_obtenerEstructuras('r');
    }

    /**
     * Verificar si una modificación es multicentro
     * 
     * @return bool
     */
    public function esMulticentro(): bool
    {
        $ctroCed = '';
        $ctroRec = '';
        $partidasCedentes   = $this->partidasCedentes;
        $partidasReceptoras = $this->partidasReceptoras;

        // $item = reset($partidasCedentes);

        foreach($partidasCedentes as $cedente)
        {
            $ceco = CentroCosto::generarCodCentroCosto($cedente->tip_cod, $cedente->cod_pryacc, $cedente->cod_obj, $cedente->gerencia, $cedente->unidad);

            if ($ctroCed == '') {
                $ctroCed = $ceco;
            } else {
                if ($ctroCed != $ceco) {
                    return true;
                }
            }
        }

        foreach($partidasReceptoras as $receptora)
        {
            $ceco = CentroCosto::generarCodCentroCosto($receptora->tip_cod, $receptora->cod_pryacc, $receptora->cod_obj, $receptora->gerencia, $receptora->unidad);

            if ($ctroRec == '') {
                $ctroRec = $ceco;
            } else {
                if ($ctroRec != $ceco) {
                    return true;
                }
            }
        }

        if ($ctroCed != '' && $ctroRec != '' && $ctroCed != $ctroRec) {
            return true;
        }

        return false;
    }

    
    /**
     * Calcular el monto total en estructuras
     * 
     * @param string $tipo
     * 
     * @return float
     */
    private function _totalPartidas($tipo = 'c'): float
    {
        if ($tipo == 'c') {
            $total = $this->partidasCedentes()->sum('mto_tra');
        } else {
            $total = $this->partidasReceptoras()->sum('mto_tra');
        }

        return $total;
    }

    /**
     * Calcular el monto total en estructuras cedentes
     * 
     * @return float
     */
    public function totalCedentes(): float
    {
        return $this->_totalPartidas();
    }

    /**
     * Calcular el monto total en estructuras receptoras
     * 
     * @return float
     */
    public function totalReceptoras(): float
    {
        return $this->_totalPartidas('r');
    }
}