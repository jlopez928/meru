<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Proveedores;

enum TipoEmpresa : string
{
    case CA = 'C';
    case SA = 'S';
    case SRL = 'R';
    case CRL = 'L';
    case FIRMA_PERSONAL = 'F';
    case COOPERATIVA = 'P';
    case PERSONA_NATURAL = 'N';
    case OTRA = 'O';
}