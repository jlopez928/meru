<?php

namespace App\Models\Administrativo\Meru_Administrativo\Formulacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;

class CentroCosto extends Model
{
	use HasFactory;

	protected $table    = 'pre_centrocosto';
	protected $fillable = [
		'ano_pro',
		'tip_cod',
		'cod_pryacc',
		'cod_obj',
		'gerencia',
		'unidad',
		'des_con',
		'sta_reg',
		'usuario',
		'fecha',
		'cod_cencosto',
		'ajust_ctrocosto',
		'cre_adi',
		'nivel',
	];

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// ACCESORS Y MUTATORS /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function desCon(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	public function staReg(): Attribute
	{
		return new Attribute(
			get: fn ($value) => $value == '1' ? 'ACTIVO' : 'INACTIVO',
			set: fn ($value) => $value,
		);
	}

	public function creAdi(): Attribute
	{
		return new Attribute(
			get: fn ($value) => $value == '1' ? 'SI' : 'NO',
			set: fn ($value) => $value,
		);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// RELACIONES //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function gerencias()
	{
		return $this->hasMany(Gerencia::class, 'centro_costo', 'cod_cencosto');
	}

	public function estructuraPresupuestaria()
	{
		return $this->hasMany(MaestroLey::class, 'centro_costo_id', 'id');
	}

	public function gerenciasId()
	{
		return $this->hasMany(Gerencia::class, 'centro_costo_id', 'id');
	}

	public function solicitudTraspaso()
	{
		return $this->hasMany(SolicitudTraspaso::class, 'centro_costo_id', 'id');
	}

	////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////// MÉTODOS PROPIOS ///////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function cecoDescri()
	{
		return $this->cod_cencosto . ' - ' . $this->des_con;
	}

	public static function generarCodCentroCosto($tipCod, $codPryacc, $codObj, $gerencia, $unidad)
	{
		return implode('.', [
			\Str::padLeft($tipCod, 2, '0'),
			\Str::padLeft($codPryacc, 2, '0'),
			\Str::padLeft($codObj, 2, '0'),
			\Str::padLeft($gerencia, 2, '0'),
			\Str::padLeft($unidad, 2, '0')
		]);
	}

	public static function getNivel($cenCos)
	{
		$nivel = 0;
		$parts = explode('.', $cenCos);

		switch(true) {
			case !empty((integer)$parts[4]):
				$nivel = 5;
				break;
			case !empty((integer)$parts[3]):
				$nivel = 4;
				break;
			case !empty((integer)$parts[2]):
				$nivel = 3;
				break;
			case !empty((integer)$parts[1]):
				$nivel = 2;
				break;
			case !empty((integer)$parts[0]):
				$nivel = 1;
				break;
		}

		return $nivel;
	}

	public static function getPadre($cenCos)
	{
		$cenCosPadre = '';
		$nivel       = self::getNivel($cenCos);
		$parts       = explode('.', $cenCos);

		for ($i = 1; $i < $nivel; $i++) {
			$cenCosPadre .= $parts[$i - 1] . '.';
		}

		return $nivel > 1 ? \Str::padRight(\Str::substr($cenCosPadre, 0, \Str::length($cenCosPadre) - 1), 14, '.00') : null;
	}

	public static function existe($anoPro, $cenCos)
	{
		return CentroCosto::where('ano_pro', $anoPro)
			->where('cod_cencosto', $cenCos)
			->get()
			->count() > 0;
	}
}