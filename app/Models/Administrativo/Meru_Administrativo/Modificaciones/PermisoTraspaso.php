<?php

namespace App\Models\Administrativo\Meru_Administrativo\Modificaciones;

use App\Models\Administrativo\Meru_administrativo\Configuracion\UnidadTributaria;
use App\Models\User;
use App\Observers\Administrativo\Meru_Administrativo\Modificaciones\PermisoTraspasoObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PermisoTraspaso extends Model
{
    use HasFactory;

    protected $table      = 'mod_aprmov';
    protected $primaryKey = 'usuario_id';
    public $incrementing  = false;
    protected $keyType    = 'integer';

	protected $fillable = [
		'usuario',
        'maxut',
        'multicentro',
		'usuario_id',
        'user_id'
	];

	protected static function boot()
    {
        parent::boot();
		PermisoTraspaso::observe(PermisoTraspasoObserver::class);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// ACCESORS Y MUTATORS /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function maxut(): Attribute
	{
		return new Attribute(
			get: fn ($value) => (int)$value,
			set: fn ($value) => $value,
		);
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// RELACIONES //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

    public function usuario()
	{
		return $this->belongsTo(User::class, 'usuario_id', 'id')->withDefault();
	}

    ////////////////////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////// MÉTODOS PROPIOS ///////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Verificar si un usuario puede aprobar un traspaso de determinado monto
	 * 
	 * @param string $usuario
	 * @param float $monto
	 * 
	 * @return bool
	 */
	public static function puedeAprobarMonto(string $usuario, float $monto): bool
	{
		$bsUt 	 = UnidadTributaria::where('vigente', 1)->pluck('bs_ut')->first();
		$permiso = PermisoTraspaso::where('usuario', $usuario)->first();

		if (is_null($permiso)) {
			return false;
		} else if ($permiso->maxut * $bsUt < $monto) {
			return false;
		}

		return true;
	}

	/**
	 * Verificar si un usuario puede aprobar un traspaso multicentro
	 * 
	 * @param string $usuario
	 * 
	 * @return bool
	 */
	public static function puedeAprobarMulticentro(string $usuario): bool
	{
		$permiso = PermisoTraspaso::where('usuario', $usuario)
			->where('multicentro', true)
			->first();

		return !is_null($permiso);
	}
}