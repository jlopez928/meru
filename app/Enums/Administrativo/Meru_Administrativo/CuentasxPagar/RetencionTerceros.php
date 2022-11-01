<?php

namespace App\Enums\Administrativo\Meru_Administrativo\CuentasxPagar;

enum RetencionTerceros : string
{
    case Vuelo_Nacional         ='VN';
    case Vuelo_Internacional    ='VI';
    case Servicio_de_Clinicas   ='RC';
    case CS_Contrato            ='CS';
    case Otros                  ='OT';
    case No_Aplica              ='NA';

}
