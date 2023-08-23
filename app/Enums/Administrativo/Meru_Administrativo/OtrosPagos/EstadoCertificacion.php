<?php

namespace App\Enums\Administrativo\Meru_Administrativo\OtrosPagos;

enum EstadoCertificacion : string
{
    case Ingresada='0';
    case Anulada= '1' ;
    case Aprobada='2'  ;
    case Reversada='3' ;
    case Comprometida='4' ;
    case Reverso_Compromiso='5';
    case Orden_Impresa='6';
    case Cerrada='7';
    case Cierre_Prespuestario='C';
}



