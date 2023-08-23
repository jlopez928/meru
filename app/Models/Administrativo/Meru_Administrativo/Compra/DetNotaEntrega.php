<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\conceptocontrato;
use Illuminate\Support\Facades\DB;

class DetNotaEntrega extends Model
{
    use HasFactory;

    protected $table = 'com_detnotaentrega';

    protected $primaryKey = 'nro_ent';

    protected $fillable = [
        'fk_ano_pro',
        'grupo',
        'nro_ent',
        'nro_ren',
        'fk_cod_prod',
        'xnro_ent',
        'nro_sol',
        'cantidad',
        'totrecep',
        'pre_uni',
        'por_iva',
        'mon_iva',
        'tot_ren',
        'tip_cod',
        'cod_pryacc',
        'cod_obj',
        'gerencia',
        'unidad',
        'cod_par',
        'cod_gen',
        'cod_esp',
        'cod_sub',
        'saldo',
        'des_bien',
        'cta_cont',
        'cta_x_pagar',
        'gasto',
        'encnotaentrega_id'
    ];

    public $timestamps = false;

    protected $guarded = [];

    // public function conceptocontrato()
	// {
   	//   return $this->hasOne(ConceptoContrato::class, 'cod_con', \DB::raw('fk_cod_prod::integer'));

	// }


}
