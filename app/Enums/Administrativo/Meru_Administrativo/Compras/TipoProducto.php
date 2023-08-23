<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Compras;

enum TipoProducto : string
{
    case BIEN                   = 'B';
    case MATERIAL               = 'P';
    case SERVICIO_GENERAL       = 'G';
    case SERVICIO_A_VEHICULO    = 'V';
    case OBRA                   = 'O';
}
