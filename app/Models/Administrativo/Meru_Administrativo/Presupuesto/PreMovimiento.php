<?php

namespace App\Models\Administrativo\Meru_Administrativo\Presupuesto;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PreMovimiento extends Model
{
	use HasFactory;
    public $timestamps = false;
	protected $table = 'pre_movimientos';
    protected $primaryKey = 'num_reg';
	protected $fillable = [
                'num_reg',
                'ano_pro',
                'num_mes',
                'tip_ope',
                'sol_tip',
                'num_doc',
                'fec_tra',
                'tip_cod',
                'cod_pryacc',
                'objetivo',
                'gerencia',
                'unidad',
                'cod_par',
                'cod_gen',
                'cod_esp',
                'cod_sub',
                'cod_com',
                'ced_ben',
                'concepto',
                'mto_tra',
                'sdo_mod',
                'sdo_apa',
                'sdo_pre',
                'sdo_com',
                'sdo_cau',
                'sdo_dis',
                'sdo_pag',
                'nro_enl',
                'sta_reg',
                'usuario',
                'fecha',
                'usua_anu',
                'fec_anu',
                'ano_doc',
                'nota_entrega',
                'num_fac',
                'mto_transaccion',
                'cierre',
                'manual',
                'feha_auditoria',
                'referencia',
	];

	}
