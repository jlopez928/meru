<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Compras;

enum GrupoSolicitud : string
{
    case BIEN_MATERIALES        = 'BM';
    case SERVICIOS              = 'SG';
    case SERVICIOS_A_VEHICULOS  = 'SV';
}
