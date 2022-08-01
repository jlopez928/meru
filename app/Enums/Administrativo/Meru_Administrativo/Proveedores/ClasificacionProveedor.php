<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Proveedores;

enum ClasificacionProveedor : string
{
    case Excelente  = 'E';
    case Buena      = 'B';
    case Regular    = 'R';
    case Mala       = 'M';
    case Nuevo      = 'N';
}