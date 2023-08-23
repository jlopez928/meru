<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Administrativo\Meru_Administrativo\Compras\GrupoEncNotaEntrega;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\Compra\ComprobanteSopenDetNe;



class EncNotaEntrega extends Model
{
    use HasFactory;

    protected $table = 'com_encnotaentrega';

    protected $fillable = [
                'grupo',
                'nro_ent',
                'fk_nro_ord',
                'fk_ano_pro',
                'xnro_ent',
                'fec_pos',
                'fec_ent',
                'fk_tip_ord',
                'ano_causado',
                'fec_ord',
                'mto_ord',
                'fk_rif_con',
                'tip_ent',
                'num_fac',
                'nota_entrega',
                'fec_notaentrega',
                'mto_siniva',
                'mto_iva',
                'mto_ent',
                'observacion',
                'sta_ent',
                'fec_sta',
                'sta_ant',
                'fec_ant',
                'usuario',
                'proveedor',
                'jus_sol',
                'antc_amort',
                'porc_ant',
                'mto_anticipo',
                'base_imponible',
                'base_exenta',
                'ano_ord_com',
                'stat_causacion',
                'tipo_orden',
                'fondos',
                'cuenta_contable',
                'fec_com',
                'xnro_ord',
                'definitiva',
                'usu_sta'
                ];

    public $timestamps = false;

    protected $guarded = [];


	public function detnotaentrega()
	{
		return $this->hasMany(DetNotaEntrega::class, 'encnotaentrega_id', 'id');
	}

    public function detgastosnotaentrega()
	{
		return $this->hasMany(DetGastosNotaEntrega::class, 'encnotaentrega_id', 'id');
	}

    public function comprobantesopendetne()
	{
		return $this->hasMany(ComprobanteSopenDetNe::class, 'encnotaentrega_id', 'id')->orderBy('con_com');
	}


    public function acta()
	{
	  return $this->hasOne(Acta::class, 'encnotaentrega_id', 'id')->where('acta','A');
	}

    public function actai()
	{
	  return $this->hasOne(Acta::class, 'encnotaentrega_id', 'id')->where('acta','I');
	}

    public function actat()
	{
	  return $this->hasOne(Acta::class, 'encnotaentrega_id', 'id')->where('acta','T');
	}


    public function actatodas()
	{
	  return $this->hasOne(Acta::class, 'encnotaentrega_id', 'id');
	}

    public function beneficiario()
	{
	  return $this->hasOne(Beneficiario::class, 'rif_ben', 'fk_rif_con')
                  ->whereIn('tipo',['P','E','O'])
                  ->where('sta_reg','1');
	}



    //casts = ['grupo' => GrupoEncNotaEntrega::class ];


}
