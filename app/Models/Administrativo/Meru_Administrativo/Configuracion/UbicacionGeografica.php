<?php

namespace App\Models\Administrativo\Meru_Administrativo\Configuracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UbicacionGeografica extends Model
{
	use HasFactory;

	protected $table    = 'adm_ubigeograficas';
	protected $fillable = [
		'cod_edo',
		'cod_mun',
		'cod_par',
		'des_ubi',
		'capital',
		'cod_ubi',
		'sta_reg',
		'usuario',
		'user_id',
		'fecha',
		'hora',
	];

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// ACCESORS Y MUTATORS /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function codEdo(): Attribute
	{
		return new Attribute(
			get: fn ($value) => $value,
			set: fn ($value) => is_null($value) ? 0 : $value,
		);
	}

	public function codMun(): Attribute
	{
		return new Attribute(
			get: fn ($value) => $value,
			set: fn ($value) => is_null($value) ? 0 : $value,
		);
	}

	public function codPar(): Attribute
	{
		return new Attribute(
			get: fn ($value) => $value,
			set: fn ($value) => is_null($value) ? 0 : $value,
		);
	}

	public function desUbi(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	public function capital(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	public function codUbi(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////// MÉTODOS PROPIOS ///////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public static function getEstados()
	{
		return UbicacionGeografica::where('cod_mun', '0')
			->where('cod_par', '0')
			->orderBy('des_ubi')
			->get();
	}

	public static function getMunicipios($estado)
	{
		return UbicacionGeografica::where('cod_edo', $estado)
			->where('cod_mun', '!=', 0)
			->where('cod_par', 0)
			->orderBy('des_ubi')
			->get();
	}

	public static function getParroquias($estado, $municipio)
	{
		return UbicacionGeografica::where('cod_edo', $estado)
			->where('cod_mun', $municipio)
			->where('cod_par', '!=' ,0)
			->orderBy('des_ubi')
			->get();
	}

	public function setCodigo()
	{
		if (empty($this->cod_edo)) {
			$this->cod_edo = $this->max('cod_edo') + 1;
		} else if (empty($this->cod_mun)) {
			$this->cod_mun = $this->where('cod_edo', $this->cod_edo)
								->max('cod_mun') + 1;
		} else if (empty($this->cod_par)) {
			$this->cod_par = $this->where('cod_edo', $this->cod_edo)
								->where('cod_edo', $this->cod_mun)
								->max('cod_par') + 1;
		}

		return $this;
	}
}