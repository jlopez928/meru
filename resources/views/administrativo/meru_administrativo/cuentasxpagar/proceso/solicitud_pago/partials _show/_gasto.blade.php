<br>    <br>
<div class="col-12">
    <h5 class="card-title text-secondary text-bold">MONTOS DE SOLICITUD</h5>
</div>
<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
<div class="row col-12">
    <x-field class="text-center col-2 offset-2">
        <x-label for="base_imponible">Base Imponible</x-label>
        <x-input value="{{$solicititudpago->base_imponible? $solicititudpago->base_imponible:''}}" readonly    id="base_imponible" name="base_imponible" class="text-center form-control-sm  {{ $errors->has('base_imponible') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('base_imponible') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-2">
        <x-label for="base_excenta">Monto Base Exenta</x-label>
        <x-input value="{{$solicititudpago->base_excenta? $solicititudpago->base_excenta:''}}" readonly    id="base_excenta" name="base_excenta" class="text-center form-control-sm  {{ $errors->has('base_excenta') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('base_excenta') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-2">
        <x-label for="por_iva">Porcentaje de Iva</x-label>
        <x-input value="{{$solicititudpago->por_iva? $solicititudpago->por_iva:''}}" readonly    id="por_iva" name="por_iva" class="text-center form-control-sm  {{ $errors->has('por_iva') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('por_iva') {{ $message }} @enderror
        </div>
     </x-field>
</div>
<div class="row col-12">
    <x-field class="text-center col-2 offset-2">
        <x-label for="mto_bru">Monto Neto</x-label>
        <x-input value="{{$solicititudpago->mto_bru? $solicititudpago->mto_bru:''}}" readonly    id="mto_bru" name="mto_bru" class="text-center form-control-sm  {{ $errors->has('mto_bru') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('mto_bru') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-2">
        <x-label for="mto_iva">Monto I.V.A</x-label>
        <x-input value="{{$solicititudpago->mto_iva? $solicititudpago->mto_iva:''}}" readonly    id="mto_iva" name="mto_iva" class="text-center form-control-sm  {{ $errors->has('mto_iva') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('mto_iva') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-2">
        <x-label for="monto">Monto Factura</x-label>
        <x-input value="{{$solicititudpago->monto? $solicititudpago->monto:''}}" readonly    id="monto" name="monto" class="text-center form-control-sm  {{ $errors->has('monto') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('monto') {{ $message }} @enderror
        </div>
     </x-field>
</div>
<div class="row col-12">
    <x-field class="text-center col-2 offset-2">
        <x-label for="tot_des_iva">Retenciones Iva</x-label>
        <x-input value="{{$solicititudpago->tot_des_iva? $solicititudpago->tot_des_iva:''}}" readonly    id="tot_des_iva" name="tot_des_iva" class="text-center form-control-sm  {{ $errors->has('tot_des_iva') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('tot_des_iva') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-2">
        <x-label for="tot_des_islr">Retenciones I.S.L.R:</x-label>
        <x-input value="{{$solicititudpago->tot_des_islr? $solicititudpago->tot_des_islr:''}}" readonly    id="tot_des_islr" name="tot_des_islr" class="text-center form-control-sm  {{ $errors->has('tot_des_islr') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('tot_des_islr') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-2">
        <x-label for="retencion_esp">Retención Esp</x-label>
        <x-input value="{{$solicititudpago->retencion_esp? $solicititudpago->retencion_esp:''}}" readonly    id="retencion_esp" name="retencion_esp" class="text-center form-control-sm  {{ $errors->has('retencion_esp') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('retencion_esp') {{ $message }} @enderror
        </div>
     </x-field>
</div>
<div class="row col-12">
    <x-field class="text-center col-2 offset-2">
        <x-label for="saldo">Saldo</x-label>
        <x-input value="{{$solicititudpago->saldo? $solicititudpago->saldo:''}}" readonly    id="saldo" name="saldo" class="text-center form-control-sm  {{ $errors->has('saldo') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('saldo') {{ $message }} @enderror
        </div>
     </x-field>
</div>
<br>
<div class="col-12">
    <h5 class="card-title text-secondary text-bold">RENGLONES DE GASTOS PRESUPUESTARIOS</h5>
</div>
<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
<div class="mb-2">
    <input id="listadoDetalle" type="hidden" wire:model.defer ="listadoDetalle"   name="listadoDetalle">
    <table  class="table table-bordered table-sm text-center " >
        <thead class="">
            <tr class="table-primary">
                <th style="width:50px">Tp</th>
                <th style="width:50px">P/A</th>
                <th style="width:50px">Obj</th>
                <th style="width:50px">Gcia</th>
                <th style="width:50px">U.Ejec.</th>
                <th style="width:50px">Pa</th>
                <th style="width:50px">Gn</th>
                <th style="width:50px">Esp.</th>
                <th style="width:50px">Sub.Esp</th>
                <th style="width:50px">Monto</th>
                <th style="width:350px">Causado Factura</th>

            </tr>
        </thead >
        <tbody>
         @forelse ($solicititudpago->cxpdetgastosolpago as $index => $detallegasto)
            <tr>
                <td class="text-center">
                    {{  $detallegasto['cod_pryacc'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['cod_pryacc'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['cod_obj'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['gerencia'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['unidad'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['cod_par'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['cod_gen'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['cod_esp'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['cod_sub'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['mto_tra'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['mto_sdo'] }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="13" class="text-center"></td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="row col-12">
    <x-field class="text-center col-2 offset-2">
        <x-label for="pagos_mes">Total Pagos del Mes</x-label>
        <x-input value="{{$solicititudpago->monto_retenido}}" readonly    id="pagos_mes" name="pagos_mes" class="text-center form-control-sm  {{ $errors->has('pagos_mes') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('pagos_mes') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-4 ">
        <x-label for="neto_facturas">Monto Neto de facturas</x-label>
        <x-input value="{{$solicititudpago->neto_factura}}" readonly    id="neto_facturas" name="neto_facturas" class="text-center form-control-sm  {{ $errors->has('neto_facturas') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('neto_facturas') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-2 ">
        <x-label for="sustraendo">Sustraendo</x-label>
        <x-input value="{{$solicititudpago->sustraendo? $solicititudpago->sustraendo:''}}" readonly    id="sustraendo" name="sustraendo" class="text-center form-control-sm  {{ $errors->has('sustraendo') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('sustraendo') {{ $message }} @enderror
        </div>
     </x-field>
</div>
<br>
<div class="col-12">
    <h5 class="card-title text-secondary text-bold">DESCUENTOS/RETENCIONES (Presione Ctrl + Alt + b para visualizar listado de Literales)</h5>
</div>
<div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
<div class="row col-12">
</div>
<div class="mb-2">
    <input id="listadoDetalle" type="hidden" wire:model.defer ="listadoDetalle"   name="listadoDetalle">
    <table  class="table table-bordered table-sm text-center " >
        <thead class="">
            <tr class="table-primary">
                <th style="width:50px">Literal</th>
                <th style="width:50px"></th>
                <th style="width:50px">Porcentaje (%)</th>
                <th style="width:50px">Impuesto Retenido</th>
                <th style="width:50px">Sustraendo</th>
                <th style="width:50px">Toral Retención</th>
            </tr>
        </thead >
        <tbody>
            @forelse ($solicititudpago->cxpdetdescuentosolpago as $index => $detallegasto)
            <tr>
                <td class="text-center">
                    {{ $detallegasto['cod_des']}}
                </td>
                <td class="text-center">
                    {{  $detallegasto['des_des'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['mon_ori'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['mon_des'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['mon_ded'] }}
                </td>
                <td class="text-center">
                    {{  $detallegasto['total'] }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="13" class="text-center"></td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
