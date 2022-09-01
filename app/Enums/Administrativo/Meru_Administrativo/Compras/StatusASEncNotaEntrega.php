<?php

namespace App\Enums\Administrativo\Meru_Administrativo\Compras;

enum StatusASEncNotaEntrega : string
{
    case Transcrita        = '0';
    case Acta_Inicio       = '1';
    case Acta_Terminación  = '2';
    case Acta_Aceptación   = '3';
    case Con_Factura       = '4';
    case Reverso           = '5';
    case Causada           = '6';
    case Causada2          = '7';
    case Anulado           = '8';

}


