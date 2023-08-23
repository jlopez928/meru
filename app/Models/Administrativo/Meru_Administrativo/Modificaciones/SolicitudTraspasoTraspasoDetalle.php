<?php

namespace App\Models\Administrativo\Meru_Administrativo\Modificaciones;

use App\Models\Administrativo\Meru_Administrativo\Formulacion\MaestroLey;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudTraspasoTraspasoDetalle extends Model
{
    use HasFactory;

    protected $table = 'mod_detsoltraspasos';

    public $timestamps = false;

	protected $fillable = [
        'ano_pro',
        'nro_sol',
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
        // Nuevos
        'solicitud_traspaso_id',
        'mestroley_id',
	];

    ////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// RELACIONES //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

    public function solicitudTraspaso()
    {
        return $this->belongsTo(SolicitudTraspaso::class, 'solicitud_traspaso_id', 'id');
    }

    public function maestroLey()
	{
		return $this->belongsTo(MaestroLey::class, 'maestro_ley_id', 'id')->withDefault();
	}
}
