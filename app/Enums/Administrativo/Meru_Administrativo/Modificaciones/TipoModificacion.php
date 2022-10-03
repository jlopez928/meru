<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Modificaciones;

enum TipoModificacion : string
{
    case Traspaso          = '1';
    case Credito_Adicional = '3';
    case Disminucion       = '4';
    case Insubsistencia    = '5';
}