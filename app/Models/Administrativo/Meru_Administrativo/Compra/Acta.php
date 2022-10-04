<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta extends Model
{
    use HasFactory;

    protected $table    = 'com_actas';
    protected $primaryKey = 'nro_act';

	protected $fillable = [
	    'acta',
        'grupo',
        'nro_ent',
        'fk_ano_pro',
        'jus_sol',
        'nom_hb',
        'ced_hb',
        'nom_con',
        'ced_con',
        'observacion',
        'fecha',
        'usuario',
        'recomen',
        'cargo_hb',
        'gerencia',
        'lug_reunion',
        'revision',
        'ano_ord',
        'xnro_ord',
        'nro_act',
        'fec_act',
        'usu_mod',
        'fec_mod',
        'encnotaentrega_id'
	];
    public $timestamps = false;

    public function encnotaentrega()
	{
		return $this->belongsTo(EncNotaEntrega::class, 'id', 'encnotaentrega_id');
	}

}
