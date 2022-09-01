<?php

namespace  App\Models\Administrativo\Meru_Administrativo\CuentasPorPagar;
use App\Models\Administrativo\Meru_Administrativo\Tesoreria\Beneficiario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PlanSolpago extends Model
{
    use HasFactory;

    protected $table = 'plan_solpago';

    protected $guarded = [];

    protected $primaryKey = 'rif_prov';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;


    public function beneficiario()
	{
		return $this->hasOne(Beneficiario::class, 'rif_ben', 'rif_ben')->withDefault();
	}
}
