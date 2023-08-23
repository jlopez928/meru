<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Administrativo\Meru_Administrativo\Estado;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Usuario;

class Comprador extends Model
{
    use HasFactory;

    protected $table        = 'com_compradores';

    protected $dateFormat   = 'd/m/Y H:i:s';

    protected $fillable     =   [
                                    'cod_com',
                                    'usu_com',
                                    'fec_hor',
                                    'usuario',
                                    'sta_reg'
                                ];

    protected $primaryKey   = 'cod_com';

    public $incrementing    = false;

    public $timestamps      = false;

    protected $casts        =   [
                                    'sta_reg' => Estado::class,
                                ];

    ////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////// ACCESORS Y MUTATORS /////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////



	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// RELACIONES //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
    public function usuariot()
    {
        return $this->belongsTo(Usuario::class, 'usu_com', 'usuario');
    }

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// QUERY SCOPES ////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
	public function scopeActivo($query)
    {
        return $query->where('status', Estado::Activo);
    }

	public function scopeNotComprador($query)
    {
        return $query->whereNotIn('usuario', Comprador::query()->pluck('usu_com'));
    }

}