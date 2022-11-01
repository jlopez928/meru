<?php

namespace App\Enums\Administrativo\Meru_Administrativo\CuentasxPagar;

enum EstadoSolicitudPago : string
{
    case Solo_Transcrita           ='0';
    case Asientos_de_IVA_Aprobados ='1';
    case Aprobada_por_Contabilidad='2';
    case Reversada_por_Contabilidad='3';
    case Reversada_por_Asientos_de_Retenciones='4';
    case Incluida_en_Programacion_Semanal='5';
    case Con_Generación_de_Pago='6';
    case Asientos_de_Nota_de_Crédito_Aprobados='7';
    case Reversada_por_Asientos_de_Nota_de_Crédito='8';
    case Con_Pago_Manual='9';
    case Con_Comprobante_de_Retencion_de_Iva_Declarado='10';
    case Anulada_por_Contabilidad='11';
    case Anulada_por_Presupuesto='12';
}



