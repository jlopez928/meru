<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Modificaciones;

enum EstadoSolicitudTraspaso : string
{
    case Creada     = '0';
    case Aprobada   = '1';
    case Modificada = '2';
    case Anulada    = '3';
    case Rechazada  = '4';
    case Procesada  = '5';
}