<?php

namespace App\Models\Administrativo\Meru_Administrativo\Configuracion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Formulacion\PartidaPresupuestaria;
use App\Models\Administrativo\Meru_Administrativo\Modificaciones\SolicitudTraspaso;


class Gerencia extends Model
{
	use HasFactory;

	protected $table      = 'gerencias';
	protected $primaryKey = 'cod_ger';
	protected $fillable   = [
		'des_ger',
		'nom_jefe',
		'car_jefe',
		'usuario',
		'fec_hor',
		'nomenclatura',
		'part_gastos',
		'part_gastos_vinternac',
		'centro_costo',
		'status',
		'centro_costo_anterior',
		'correo_jefe',
		'aplica_pre'
	];

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// ACCESORS Y MUTATORS /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function desGer(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	public function nomenclatura(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	public function nomJefe(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	public function carJefe(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	public function correoJefe(): Attribute
	{
		return new Attribute(
			get: fn ($value) => \Str::upper($value),
			set: fn ($value) => \Str::upper($value),
		);
	}

	public function status(): Attribute
	{
		return new Attribute(
			get: fn ($value) => $value == '1' ? 'ACTIVA' : 'INACTIVA',
			set: fn ($value) => $value,
		);
	}

	public function aplicaPre(): Attribute
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
		return $this->belongsTo(CentroCosto::class, 'centro_costo', 'cod_cencosto')->withDefault();
	}

	public function centroCostoId()
	{
		return $this->belongsTo(CentroCosto::class, 'centro_costo_id', 'id')->withDefault();
	}

	public function partidaGastoViNac()
	{
		return $this->belongsTo(PartidaPresupuestaria::class, 'part_gasto_id', 'id')->withDefault();
	}

	public function partidaGastoVinternac()
	{
		return $this->belongsTo(PartidaPresupuestaria::class, 'part_gasto_vinternac_id', 'id')->withDefault();
	}

	public function solicitudTraspaso()
	{
		return $this->hasMany(SolicitudTraspaso::class, 'cod_ger', 'cod_ger');
	}
}
