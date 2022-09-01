<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Compras;

enum GrupoEncNotaEntrega : string
{
    case Contrato           = 'CO';
    case Bienes_Materiales  = 'BM';
    case Servicio_General   = 'SG';
    case Servicio_Vehículo  = 'SV';
}
