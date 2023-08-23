<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Administrativo\Meru_Administrativo\Estado;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Ramo;
use App\Enums\Administrativo\Meru_Administrativo\Compras\TipoProducto;
use App\Models\Administrativo\Meru_Administrativo\Compras\SubGrupoProducto;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $dateFormat = 'd/m/Y H:i:s';

    protected $fillable =   [
                    'cod_prod',
                    'des_prod',
                    'cod_uni',
                    'fec_act',
                    'tip_prod',
                    'grupo',
                    'subgrupo',
                    'prod',
                    'ult_pre',
                    'por_iva',
                    'por_islr',
                    'stock',
                    'tipovida',
                    'tiporesguardo',
                    'especificaciones',
                    'cant_maxima',
                    'cant_minima',
                    'critica',
                    'almacen',
                    'uni_almacen',
                    'factor_unimed',
                    'tip_cod',
                    'cod_par',
                    'cod_gen',
                    'cod_esp',
                    'cod_sub',
                    'cod_gru',
                    'cod_sgru',
                    'cod_clasifprod',
                    'seccion',
                    'sta_reg',
                    'usuario',
                    'fecha',
                    'gru_ram'
                ];

    protected $primaryKey = 'cod_prod';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    public $dates = [
        'fec_act',
    ];

    protected $casts = [
        'sta_reg'   => Estado::class,
        'tip_prod'  => TipoProducto::class,
    ];

    public function scopeActivo($query)
    {
        return $query->where('sta_reg', Estado::Activo);
    }

    public function grupoproducto()
    {
        return $this->belongsTo(GrupoProducto::class, 'grupo');
    }

    public function subgrupoproducto()
    {
        return $this->belongsTo(SubGrupoProducto::class, 'subgrupo');
    }

    public function gruporamo()
    {
        return $this->belongsTo(Ramo::class, 'gru_ram');
    }

    public function unidadmedida()
    {
        return $this->belongsTo(UnidadMedida::class, 'cod_uni');
    }

    public static function generarCodPartida($codPar, $codGen, $codEsp, $codSub)
	{
		return implode('.', [
			\Str::padLeft($codPar, 2, '0'),
			\Str::padLeft($codGen, 2, '0'),
			\Str::padLeft($codEsp, 2, '0'),
			\Str::padLeft($codSub, 2, '0'),
		]);
	}

    public static function getProductos($grupo_ram, $grupo)
    {
        return Producto::query()
                            ->where('gru_ram', $grupo_ram)
                            ->when($grupo == 'BM', function($query){
                                $query->where(function($q) {
                                    $q->where('tip_prod', 'B')->orWhere('tip_prod', 'P');
                                });
                            })
                            ->when($grupo == 'SG', function($query){
                                $query->where(function($q) {
                                    $q->where('tip_prod', 'G')->orWhere('tip_prod', 'O');
                                });
                            })
                            ->when($grupo == 'SV', function($query){
                                $query->where(function($q) {
                                    $q->where('tip_prod', 'G')->orWhere('tip_prod', 'V');
                                });
                            })
                            ->orderBy('des_prod')
                            ->pluck('des_prod','cod_prod');
    }

    public static function getProducto($fk_cod_mat)
    {
        return Producto::query()
                            ->where('cod_prod', $fk_cod_mat)
                            ->first();
    }
}
