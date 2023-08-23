<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;

use Carbon\Carbon;
use Awobaz\Compoships\Compoships;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\Compras\DetSolicitud;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\Gerencia;
use App\Models\Administrativo\Meru_Administrativo\Presupuesto\CentroCosto;
use App\Models\Administrativo\Meru_Administrativo\Configuracion\RegistroControl;

class EncSolicitud extends Model
{
    use HasFactory, Compoships;

    protected $table        = 'com_encsolicitud';

    protected $guarded      = [];

    public $incrementing    = false;

    public $timestamps      = false;

    public function estado()
    {
        return $this->belongsTo(Estatus::class, 'sta_sol','siglas')->where('modulo', 'solicitud');
    }

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class, 'fk_cod_ger','cod_ger');
    }

    public function productos()
    {
        return $this->hasMany(DetSolicitud::class, ['ano_pro','nro_req','grupo'], ['ano_pro','nro_req','grupo'])->orderBy('nro_ren');
    }

    public function detalles()
    {
        return $this->hasMany(DetSolicitudDet::class, ['ano_pro','nro_req','grupo'], ['ano_pro','nro_req','grupo'])->orderBy('nro_ren');
    }

    public function vehiculos()
    {
        return $this->hasMany(ServicioBien::class, ['ano_pro','nro_req','grupo'], ['ano_pro','nro_req','grupo'])->orderBy('cod_corr');
    }

    protected function montoTot() : Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, ',', '.'),
            set: fn($value) => str_replace(",", ".", str_replace(".", "", $value)),
        );
    }

    protected function fecEmi() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecRec() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecComCont() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecAnu() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecRecCont() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecDevCom() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecPcom() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecCom() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecDevCont() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecReasig() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecAut() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null,
        );
    }

    protected function fecSta() : Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::createFromFormat('d/m/Y H:i:s', $value)->format('Y-m-d H:i:s') : null,
        );
    }

    public static function obtenerStatusRenglon($estatus) {
        return match($estatus) {
                        "0" => "Producto seleccionado Correctamente",
                        "1" => "Producto con Orden de Compra Asignada",
                        "2" => "Producto con Nota de Entrega Asignada",
                        "D" => "La Est. de Gas. del Producto no tiene Disponibilidad",
                        "E" => "La Est. de Gas. del Producto no Existe",
                        "I" => "El Producto esta repetido"
                    };
    }

    public static function estructuraGastoSolicitudSeleccionada($ano_pro, $grupo, $nro_req) {

        if ($ano_pro != '' && $grupo != '' && $nro_req != '')
        {
            return DB::connection('pgsql')->select("    SELECT a.mto_dis, x.sum_tot_ref, (a.mto_dis - x.sum_tot_ref) as dif_dis_tot, a.cod_com
                                                        FROM pre_maestroley a
                                                        INNER JOIN
                                                            (SELECT b.ano_pro, b.cod_com, a.aplica_pre,
                                                                    case when a.aplica_pre='1' then sum(b.cantidad*b.pre_ref)
                                                                    else 0 end as sum_tot_ref
                                                            FROM com_encsolicitud a
                                                            INNER JOIN com_detsolicitud b
                                                            ON a.nro_req=b.nro_req AND a.grupo=b.grupo AND a.ano_pro=b.ano_pro
                                                            WHERE
                                                                a.ano_pro = $ano_pro AND a.grupo = '$grupo' AND a.nro_req = $nro_req
                                                            GROUP BY 1,2,3) as x
                                                        ON x.ano_pro=a.ano_pro AND x.cod_com=a.cod_com;");
        }
    }

    public static function obtenerUnidadContratante($grupo, $total_ut) {
        return match($grupo) {
                        "BM"      => $total_ut >= 5000 ? 'C' : 'L',
                        "SV","SG" => $total_ut >= 10000 ? 'C' : 'L',
                    };
    }

    public static function obtenerClases($grupo) {
        return match($grupo) {
                    'BM'        => [['cod_cla' => 'C', 'des_cla' => 'COMPRA'],['cod_cla' => 'A', 'des_cla' => 'ALMACEN']],
                    'SV', 'SG'  => [['cod_cla' => 'S', 'des_cla' => 'SERVICIO']],
                    default     => []
                };
    }

    public static function obtenerCentroCosto($cod_ger = null) {
        if($cod_ger){
            $centroCosto = Gerencia::query()->where('cod_ger', $cod_ger)->first('centro_costo');

            return str($centroCosto->centro_costo)->explode('.');
        }
    }

    public static function obtenerCentroCostoUnidades($anopro)
    {
        if($anopro){
            return  CentroCosto::query()
                                        ->join('public.gerencias as b', function($q) {
                                            $q->on('b.centro_costo','cod_cencosto')->where('b.status', 1);
                                        })
                                        ->where('ano_pro', $anopro)
                                        ->where('sta_reg', '1')
                                        ->orderBy('b.cod_ger')
                                        ->pluck('cre_adi','b.cod_ger');
        }
    }

    public static function find($ano_pro, $grupo, $nro_req)
	{

        // TODO Revisar Query jefe_contrataciones (multiples registros)
		return DB::connection('pgsql')->select("	SELECT
						a.*,
						CASE a.grupo
							WHEN 'BM' THEN 'BIENES/MATERIALES'
							WHEN 'SG' THEN 'SERVICIOS GENERALES'
							WHEN 'SV' THEN 'SERVICIOS A VEHICULOS'
						END as grupo2,
						TO_CHAR(a.fec_emi,'dd/mm/yyyy') AS fec_emi2,
						TO_CHAR(a.fec_dev_com,'dd/mm/yyyy') AS fec_dev_com2,
						TO_CHAR(a.fec_dev_pre,'dd/mm/yyyy') AS fec_dev_pre2,
						TO_CHAR(a.fec_rec_cont,'dd/mm/yyyy') AS fec_rec_cont2,
						TO_CHAR(a.fec_dev_cont,'dd/mm/yyyy') AS fec_dev_cont2,
						TO_CHAR(a.fec_com_cont,'dd/mm/yyyy') AS fec_com_cont2,
						CASE a.cla_sol
								WHEN 'C' THEN 'COMPRAS'
								WHEN 'A' THEN 'ALMACEN'
								WHEN 'S' THEN 'SERVICIOS'
						END as cla_sol,
						CASE a.pri_sol
							WHEN 'N' THEN 'NORMAL'
							WHEN 'U' THEN 'URGENTE'
						END as pri_sol,
						f.descripcion as sta_sol2,
						CASE a.donacion
							WHEN 'N' THEN 'NO APLICA'
							WHEN 'S' THEN 'PARA DONACION'
						END as donacion2,
						usu.nombre,
						usu.ficha,
						usu.telf,
						gr.des_ram,
						ger.des_ger,
						uni.cod_uni,
						uni.des_uni,
						ger.nomenclatura,
						b.des_cau,
						(SELECT COALESCE(c.nomemp) as nom_jefe
						FROM adm_confunidades a
			            INNER JOIN nom_estorg b ON a.idunidadgciaadministracion = b.idunidadestructura
					    LEFT JOIN vis_jefesxunidad c ON b.idunidadestructura = c.idunidadestructura) as jefe_administracion,
						(SELECT COALESCE(c.nomemp) as nom_jefe
						    FROM adm_confunidades a
					        INNER JOIN nom_estorg b ON a.idunidadcompras = b.idunidadestructura
					        LEFT JOIN vis_jefesxunidad c ON b.idunidadestructura = c.idunidadestructura) as jefe_logistica
						/* (SELECT COALESCE(c.nomemp) as nom_jefe
						    FROM adm_confunidades a
					        INNER JOIN nom_estorg b ON a.idunidadcontrataciones = b.idunidadestructura
					        LEFT JOIN vis_jefesxunidad c ON b.idunidadestructura = c.idunidadestructura) as jefe_contrataciones */
				FROM com_encsolicitud a
					INNER JOIN gerencias ger ON a.fk_cod_ger = ger.cod_ger
					LEFT JOIN unidad uni ON uni.cod_ger = a.fk_cod_ger and uni.cod_uni = a.cod_uni
					LEFT JOIN usuarios_v usu ON a.usuario=usu.usuario
					INNER JOIN pro_ramos gr ON gr.cod_ram=a.gru_ram
					INNER JOIN com_estatus f on f.modulo='solicitud' and f.siglas=a.sta_sol
					LEFT JOIN com_causasanulacion b on b.cod_cau=a.fk_cod_cau
				WHERE a.ano_pro=$ano_pro and a.grupo='$grupo' and a.nro_req=$nro_req;");
	}
}
