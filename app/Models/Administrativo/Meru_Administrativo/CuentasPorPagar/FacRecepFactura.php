<?php

namespace App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administrativo\Meru_Administrativo\Proveedores\Proveedor;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPTipoDocumento;
use App\Models\Administrativo\Meru_Administrativo\General\Usuario;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use App\Models\Administrativo\Meru_Administrativo\OtrosPagos\OpSolservicio;
use App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar\CxPDetNotaFactura;

class FacRecepFactura extends Model
{
    use HasFactory;
    protected $table = 'fac_recepfacturas';
    protected $primaryKey = 'id';
    protected $dates = [
        'fec_fac','fec_rec','fec_entrega','fec_dev'
    ];
    public $timestamps = false;
    protected $fillable = [
        'id',
        'rif_prov',
        'concepto',
        'num_fac' ,
        'nro_reng',
        'mto_fac' ,
        'fec_fac' ,
        'fec_rec' ,
        'tipo_doc',
        'nro_doc' ,
        'ano_pro',
        'usuario',
        'hor_rec',
        'sta_fac',
        'fec_sta',
        'ano_sol',
        'recibo',
    ];

    public function proveedor()
	{
		return $this->belongsTo(Proveedor::class, 'rif_prov', 'rif_prov')->withDefault();
	}

    public function beneficiario()
	{
		return $this->hasOne(Beneficiario::class, 'rif_ben', 'rif_prov')->withDefault();
	}

    public function cxptipodocumento()
	{
		return $this->hasOne(CxPTipoDocumento::class,'cod_tipo', 'tipo_doc')
                                              ->where('status','1')
                                              ->where('recp_factura','1');

	}
    public function usuariorec()
	{
		return $this->hasOne(Usuario::class,'usuario', 'usuario');

	}

    public static function getEstFac($sta_fac)
	{
		$desc = '';

		switch($sta_fac) {
			case '0':
				$desc = 'Recepcionada';
				break;
			case '1':
				$desc = 'Expediente Registrado en Control del Gasto.';
				break;
			case '2':
				$desc = 'Expediente Devuelto';
				break;
			case '3':
				$desc = 'Expediente Entregado';
				break;
		}
		return $desc;
	}

    public static function getRecibo($recibo)
	{
		$desc = '';

		switch($recibo) {
			case 'F':
				$desc = 'Factura';
				break;
			case 'R':
				$desc = 'Recibo';
				break;
		}
		return $desc;
	}

    public function faccausaxfactura()
	{
		return $this->hasMany(FacCausaxFactura::class,'ano_pro', 'ano_pro')
                                               ->where('nro_reng', $this->nro_reng)
                                               ->where('rif_prov', $this->rif_prov)
                                               ->where('num_fac', $this->num_fac);

	}
    public function gerencia()
	{
		return $this->hasOne(Gerencia::class,'cod_ger', 'resp_dev')->withDefault();

	}

    public function opsolservicio()
    {
    return $this->hasOne(OpSolservicio::class, 'ano_pro','ano_pro')
                                      ->where('xnro_sol',$this->nro_doc)
                                      ->where('rif_prov', $this->rif_prov);
    }

    public function cxpdetgastosfactura()
    {
    return $this->hasMany(CxPDetGastosFactura::class, 'ano_pro','ano_pro')
                                           ->where('rif_prov',$this->rif_prov)
                                           ->where('num_fac', $this->num_fac);

    }

    public function cxpdetnotafacturas()
    {
    return $this->hasMany(CxPDetNotaFactura::class, 'ano_pro','ano_pro')
                                           ->where('rif_prov',$this->rif_prov)
                                           ->where('num_fac', $this->num_fac);

    }


}
