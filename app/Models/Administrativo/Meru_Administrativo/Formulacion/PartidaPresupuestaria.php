<?php

namespace App\Models\Administrativo\Meru_Administrativo\Formulacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartidaPresupuestaria extends Model
{
	use HasFactory;

	protected $table    = 'pre_partidasgastos';

	protected $fillable = [
		'tip_cod',
		'cod_par',
		'cod_gen',
		'cod_esp',
		'cod_sub',
		'cod_cta',
		'des_con',
		'sta_reg',
		'usuario',
		'user_id',
		'fecha',
		'cta_activo',
		'cta_gasto',
		'cta_x_pagar',
		'part_asociada',
		'cta_x_pagar_activo',
		'usu_sta',
		'fec_sta',
		'cta_provision',
	];

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// ACCESORS Y MUTATORS /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	/*
	public function desCon(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value)
		);
	}
	*/

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// RELACIONES //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function partidaAsociada()
	{
		return $this->belongsTo(PartidaPresupuestaria::class, 'part_asociada', 'cod_cta')->withDefault();
	}

/*
	public function estructuraPresupuestaria()
	{
		return $this->hasMany(MaestroLey::class, 'centro_costo_id', 'id');
	}
*/

	////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////// MÉTODOS PROPIOS ///////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public static function generarCodPartida($codPar, $codGen, $codEsp, $codSub)
	{
		return implode('.', [
			\Str::padLeft($codPar, 2, '0'),
			\Str::padLeft($codGen, 2, '0'),
			\Str::padLeft($codEsp, 2, '0'),
			\Str::padLeft($codSub, 2, '0')
		]);
	}
}
