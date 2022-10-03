<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Modificaciones;

enum EstadoModificacion : string
{
    case Creado             = '0';
    case Apartado           = '1';
    case Aprobado           = '2';
    case Reverso_Aprobacion = '3';
    case Reverso_Apartado   = '4';
    case Modificado         = '5';
    case Anulado            = '6';
}