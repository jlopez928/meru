<?php
namespace App\Models\Administrativo\Meru_Administrativo\OtrosPagos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Administrativo\Meru_Administrativo\TipoPago;
use App\Enums\Administrativo\Meru_Administrativo\EstadoSiNo;
use App\Models\Administrativo\Meru_Administrativo\BaseModel;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpDetsolservicio;
use App\Enums\Administrativo\Meru_Administrativo\OtrosPagos\EstadoCertificacion;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpDetgastossolservicio;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;


class OpSolservicio extends BaseModel
{
    use HasFactory;
    protected $table = 'op_solservicio';

    protected $dates = [
        'fec_emi', 'fec_pto', 'fec_serv','fecha'
    ];

    public $timestamps = false;

    protected $fillable = [
		        'ano_pro' ,
                'xnro_sol',
                'nro_sol',
                'fec_emi',
                'cod_ger',
                'rif_prov',
                'cod_con',
                'fec_serv',
                'lugar_serv',
                'tip_pag',
                'factura',
                'pto_cta',
                'fec_pto',
                'motivo',
                'observaciones',
                'sta_sol' ,
                'fec_sta' ,
                'usuario' ,
                'fecha' ,
                'usua_apr',
                'fec_apr' ,
                'usua_anu',
                'fec_anu' ,
                'usua_comp',
                'fec_comp',
                'num_contrato',
                'fec_contrato',
                'por_anticipo',
                'tip_solicitud',
                'mto_ant',
                'monto_iva',
                'por_iva',
                'monto_neto',
                'monto_total',
                'base_exenta',
                'base_imponible',
                'grupo' ,
                'fech_reverso_a',
                'usu_reverso_a',
                'fech_reverso_c',
                'usuario_reverso_c',
                'tiempo_entrega',
                'certificados' ,
                'lugar_entrega',
                'forma_pago',
                'flete' ,
                'fecha_imp' ,
                'usua_imp',
                'ano_sol_pago' ,
                'nro_sol_pago',
                'fecha_com_original',
                'fecha_cau_original',
                'mig_iva' ,
                'mod',
                'causado_gasto',
                'causado_iva' ,
                'mod_cau' ,
                'fondo' ,
                'tip_contrat',
                'pago_manual',
                'cuenta_contable',
                'deposito_garantia',
                'ant_old' ,
                'ult_sol' ,
                'referencia',
                'provision' ,
                'cau_anu',
                'base_imponible_anticipo',
                'monto_islr' ,
                'monto_def_anticipo',
                'por_islr' ,
                'monto_donacion',
                'monto_terceros',
                'numero_factura',
                'monto_de_anticipo',
                'usuario_cerro',
                'fecha_cierre',
                'cont_fis'
                    ];
    protected $casts = [
            'sta_sol' => EstadoCertificacion::class,
            'provision' => EstadoSiNo::class,
            'tip_pag' => TipoPago::class,
    ];
    ////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////// RELACIONES //////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////

	public function opdetsolservicio()
	{
		return $this->hasMany(OpDetsolservicio::class, 'ano_pro', 'ano_pro')
                                              ->where('xnro_sol', $this->xnro_sol)
                                              ->where('nro_sol', $this->nro_sol);
	}

	public function opdetgastossolservicio()
	{
		return $this->hasMany(OpDetgastossolservicio::class,'ano_pro', 'ano_pro')
                                            ->where('xnro_sol', $this->xnro_sol)
                                            ->orderBy('cod_gen','asc');
	}
    public function gerencias()
	{
		return $this->belongsTo(Gerencia::class, 'cod_ger', 'cod_ger')->withDefault();
	}
    public function proveedor()
	{
		return $this->belongsTo(Proveedor::class, 'rif_prov', 'rif_prov')->withDefault();

	}public function beneficiario()
	{
		return $this->belongsTo(Beneficiario::class, 'rif_prov','rif_ben')->withDefault();
	}
    public function formatNumber($attr) {
		return number_format($this->{$attr}, 2, ',', '.');
	}


    public static function getEstSol($est_sol)
	{
		$desc = '';

		switch($est_sol) {
			case '1':
				$desc = 'Anulada';
				break;
			case '2':
				$desc = 'Aprobada por Gerente de la Unidad Solicitante';
				break;
			case '3':
				$desc = 'Reversada por Gerente de la Unidad Solicitante';
				break;
			case '4':
				$desc = 'Comprometida Presupuestariamente';
				break;
			case '5':
				$desc = 'Reversada Presupuestariamente';
				break;
            case '6':
                $desc = 'Con Orden Impresa';
                break;
		}
		return $desc;
	}

}

