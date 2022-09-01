<?php

namespace App\Models\Administrativo\Meru_Administrativo\Modificaciones;

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
}
