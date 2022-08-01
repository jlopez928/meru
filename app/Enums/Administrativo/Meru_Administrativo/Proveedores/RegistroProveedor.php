<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Proveedores;

enum RegistroProveedor : string
{
    case Proveedor      = 'P';
    case Contratista    = 'C';
    case Ambos          = 'A';
}