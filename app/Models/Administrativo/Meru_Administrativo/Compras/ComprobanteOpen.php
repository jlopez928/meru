<?php

namespace App\Models\Administrativo\Meru_Administrativo\Compras;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Administrativo\Meru_Administrativo\Compras\ComprobanteOpenDet;
use Illuminate\Database\Eloquent\Model;

class ComprobanteOpen extends Model
{ use HasFactory;
    protected $table = 'comprobantesopen';
    public $timestamps = false;
    protected $guarded = [];


    public function opdetcomprobante()
	{
		return $this->hasMany(ComprobanteOpenDet::class, 'comprobantesopen_id', 'id');
	}
}





