<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Compras;

enum TipoActa : string
{
    case INICIO                 = 'I';
    case TERMINACION            = 'T';
    case ACEPTADA               = 'A';
}
