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

class SolicitudUnidad extends Model
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
}
