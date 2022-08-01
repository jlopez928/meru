@extends('layouts.aplicacion')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.control.registrocontrol.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Ver Registro Control</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div>
    <x-card>
        <x-slot name="header">
            <h3 class="card-title text-bold">Datos de Registro Control</h3>
        </x-slot>
        <x-slot name="body">
            <div class="row col-12">
                <div class="text-center  form-group col-2 offset-1">
                    <x-label for="ano_pro">Año</x-label>
                    <x-input class="text-center form-control-sm" name="ano_pro" value="{{ $registrocontrol->ano_pro }}" disabled/>
                </div>

                <div class="text-center  form-group col-4">
                    <x-label for="des_emp1">Primera Línea</x-label>
                    <x-input class="text-center form-control-sm" name="centro_costo" value="{{  $registrocontrol->des_emp1 }}" disabled/>
                </div>

                <div class="text-center  form-group col-4">
                    <x-label for="con_spf">Segunda Línea</x-label>
                    <x-input class="text-center form-control-sm" name="partida_gastos" value="{{  $registrocontrol->con_spf }}" disabled/>
                </div>
            </div>
            <div class="row col-12">
                <div class="col-12">
                   <h5 class="card-title text-secondary text-bold">Control de Contabilidad</h5>
               </div>
            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
            <div class="row col-12">
                <div class="text-center form-group col-1">
                    <x-label for="ult_mes">Mes</x-label>
                    <x-input class="text-center form-control-sm" name="ult_mes" value="{{ $registrocontrol->ult_mes }}" disabled/>
                </div>
                <div class="text-center form-group col-2">
                    <x-label for="sta_con">Estado</x-label>
                    <x-input class="text-center form-control-sm" name="sta_con" value="{{ $registrocontrol->sta_con }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="con_con">Comprobantes Abiertos</x-label>
                    <x-input class="text-center form-control-sm" name="con_con" value="{{ $registrocontrol->con_con }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="ctaresultado">Cuenta de Resultado</x-label>
                    <x-input class="text-center form-control-sm" name="ctaresultado" value="{{ $registrocontrol->ctaresultado }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="ctaresulcap">Cuenta Capital</x-label>
                    <x-input class="text-center form-control-sm" name="ctaresulcap" value="{{ $registrocontrol->ctaresulcap }}" disabled/>
                </div>
            </div>
            <div class="row col-12">
                <div class="col-12">
                   <h5 class="card-title text-secondary text-bold">Control de Tesoreria y Compras</h5>
               </div>
            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
            <div class="row col-12">
                <div class="text-center  form-group col-3 offset-1">
                    <x-label for="ult_dia">Último Día</x-label>
                    <x-input class="text-center form-control-sm" name="ult_dia" value="{{ $registrocontrol->ult_dia }}" disabled/>
                </div>
                <div class="text-center form-group col-3">
                    <x-label for="con_col">Consecutivo de Colocaciones</x-label>
                    <x-input class="text-center form-control-sm" name="con_col" value="{{ $registrocontrol->con_col }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="con_col">Consecutivo O.P. Financiera</x-label>
                    <x-input class="text-center form-control-sm" name="con_col" value="{{ $registrocontrol->con_col }}" disabled/>
                </div>
            </div>
            <div class="row col-12">
                <div class="text-center  form-group col-3 offset-1">
                    <x-label for="ciudad">Ciudad (Emisión Cheque)</x-label>
                    <x-input class="text-center form-control-sm" name="ciudad" value="{{ $registrocontrol->ciudad }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="ut_max_efec">Pago Máximo Efectivo (U.T.)</x-label>
                    <x-input class="text-center form-control-sm" name="ut_max_efec" value="{{ $registrocontrol->ut_max_efec }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="nro_recep">Cons. Recepciones Compra)</x-label>
                    <x-input class="text-center form-control-sm" name="nro_recep" value="{{ $registrocontrol->nro_recep }}" disabled/>
                </div>
            </div>
            <div class="row col-12">
                <div class="col-12">
                   <h5 class="card-title text-secondary text-bold">Control de Presupuesto</h5>
               </div>
            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
            <div class="row col-12">
                <div class="text-center  form-group col-3>
                    <x-label for="mes_pre">Mes</x-label>
                    <x-input class="text-center form-control-sm" name="mes_pre" value="{{ $registrocontrol->mes_pre }}" disabled/>
                </div>
                <div class="text-center form-group col-3">
                    <x-label for="sol_pag">Consecutivo  Orden Pago</x-label>
                    <x-input class="text-center form-control-sm" name="sol_pag" value="{{ $registrocontrol->sol_pag }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="nro_apa">Consecutivo  Apartado</x-label>
                    <x-input class="text-center form-control-sm" name="nro_apa" value="{{ $registrocontrol->nro_apa }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="sta_pre">Status</x-label>
                    <x-input class="text-center form-control-sm" name="sta_pre" value="{{ $registrocontrol->sta_pre }}" disabled/>
                </div>
            </div>
            <div class="row col-12">
                <div class="col-12">
                   <h5 class="card-title text-secondary text-bold">Partida de Iva</h5>
               </div>
            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
            <div class="row col-12">
                <div class="text-center  form-group col-2">
                    <x-label for="tip_codi">Tipo</x-label>
                    <x-input class="text-center form-control-sm" name="tip_codi" value="{{ $registrocontrol->tip_codi }}" disabled/>
                </div>
                <div class="text-center form-group col-2">
                    <x-label for="cod_pryacci">Proyecto/Acción</x-label>
                    <x-input class="text-center form-control-sm" name="cod_pryacci" value="{{ $registrocontrol->cod_pryacci }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="cod_obji">Objetivo Especifico</x-label>
                    <x-input class="text-center form-control-sm" name="cod_obji" value="{{ $registrocontrol->cod_obji }}" disabled/>
                </div>
                <div class="text-center  form-group col-2">
                    <x-label for="gerenciai">Gerencia</x-label>
                    <x-input class="text-center form-control-sm" name="gerenciai" value="{{ $registrocontrol->gerenciai }}" disabled/>
                </div>
                <div class="text-center  form-group col-2">
                    <x-label for="unidadi">Unidad Ejecutora</x-label>
                    <x-input class="text-center form-control-sm" name="unidadi" value="{{ $registrocontrol->unidadi }}" disabled/>
                </div>
            </div>
            <div class="row col-12">
                <div class="text-center  form-group col-2 offset-1">
                    <x-label for="cod_pari">Partida</x-label>
                    <x-input class="text-center form-control-sm" name="cod_pari" value="{{ $registrocontrol->cod_pari }}" disabled/>
                </div>
                <div class="text-center form-group col-2">
                    <x-label for="cod_geni">Generica</x-label>
                    <x-input class="text-center form-control-sm" name="cod_geni" value="{{ $registrocontrol->cod_geni }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="cod_espi">Específica</x-label>
                    <x-input class="text-center form-control-sm" name="cod_espi" value="{{ $registrocontrol->cod_espi }}" disabled/>
                </div>
                <div class="text-center  form-group col-2">
                    <x-label for="cod_subi">Sub-Específica</x-label>
                    <x-input class="text-center form-control-sm" name="cod_subi" value="{{ $registrocontrol->cod_subi }}" disabled/>
                </div>
            </div>
            <div class="row col-12">
                <div class="col-12">
                   <h5 class="card-title text-secondary text-bold">Centro de Costo del Almacen</h5>
               </div>
            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
            <div class="row col-12">
                <div class="text-center  form-group col-2">
                    <x-label for="tip_codalm">Tipo</x-label>
                    <x-input class="text-center form-control-sm" name="tip_codalm" value="{{ $registrocontrol->tip_codalm }}" disabled/>
                </div>
                <div class="text-center form-group col-2">
                    <x-label for="cod_pryaccalm">Proyecto/Acción</x-label>
                    <x-input class="text-center form-control-sm" name="cod_pryaccalm" value="{{ $registrocontrol->cod_pryaccalm }}" disabled/>
                </div>
                <div class="text-center  form-group col-3">
                    <x-label for="cod_objalm">Objetivo Especifico</x-label>
                    <x-input class="text-center form-control-sm" name="cod_objalm" value="{{ $registrocontrol->cod_objalm }}" disabled/>
                </div>
                <div class="text-center  form-group col-2">
                    <x-label for="gerenciaalm">Gerencia</x-label>
                    <x-input class="text-center form-control-sm" name="gerenciaalm" value="{{ $registrocontrol->gerenciaalm }}" disabled/>
                </div>
                <div class="text-center  form-group col-2">
                    <x-label for="unidadalm">Unidad Ejecutora</x-label>
                    <x-input class="text-center form-control-sm" name="unidadalm" value="{{ $registrocontrol->unidadalm }}" disabled/>
                </div>
            </div>
        </x-slot>
    </x-card>
</div>
</section>
@endsection

@section('sidebar')
@include('layouts.sidebar')
@endsection



