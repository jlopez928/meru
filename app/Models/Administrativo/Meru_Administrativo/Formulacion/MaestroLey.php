<?php

namespace App\Models\Administrativo\Meru_Administrativo\Formulacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;

class MaestroLey extends Model
{
	use HasFactory;

	protected $table = 'pre_maestroley';
	protected $fillable = [
		'ano_pro',
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
		'ley_for',
		'mto_ley',
		'mto_mod',
		'mto_apa',
		'mto_pre',
		'mto_com',
		'mto_cau',
		'mto_dis',
		'mto_pag',
		'sta_reg',
		'usuario',
		'user_id',
		'fecha',
		'mto_cnc',
		'mto_pre_anterior',
		'mto_com_anterior',
		'mto_cau_anterior',
		'exc_pag',
		/*
		'created_at',
		'updated_at',
		'deleted_at',
		*/
		'centro_costo_id',
		'partida_presupuestaria_id',
	];

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// ACCESORS Y MUTATORS /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function excPag(): Attribute
	{
		return new Attribute(
			get: fn ($value) => $value == '1' ? 'SI' : 'NO',
			set: fn ($value) => $value,
		);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// RELACIONES //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function centroCosto()
	{
		return $this->belongsTo(CentroCosto::class, 'centro_costo_id', 'id')->withDefault();
	}

	public function partidaPresupuestaria()
	{
		return $this->belongsTo(PartidaPresupuestaria::class, 'partida_presupuestaria_id', 'id')->withDefault();
	}

	////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////// M??TODOS PROPIOS ///////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function formatNumber($attr) {
		return number_format($this->{$attr}, 2, ',', '.');
	}
}
