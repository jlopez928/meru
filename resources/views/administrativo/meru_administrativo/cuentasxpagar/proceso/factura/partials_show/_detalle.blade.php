
<br>
<!-- Divisor Montos Facturas/Recibos -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos de la Amortización</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="lbase_imponible">Base Imponible</x-label>
            <x-input name="base_imponible" wire:model.defer="base_imponible" class="form-control-sm text-sm-center {{ $errors->has('base_imponible') ? 'is-invalid' : '' }}"   value="{{ $factura->base_imponible }}"  readonly/>
            <div class="invalid-feedback">
                @error('base_imponible') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="base_excenta">Base exenta</x-label>
            <x-input name="base_excenta" wire:model.defer="base_excenta" class="form-control-sm text-sm-center {{ $errors->has('base_excenta') ? 'is-invalid' : '' }}"   value="{{ $factura->base_excenta }}" readonly />
            <div class="invalid-feedback">
                @error('base_excenta') {{ $message }} @enderror
            </div>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="mto_nto">Monto Neto</x-label>
            <x-input name="mto_nto" wire:model.defer="mto_nto" class="form-control-sm text-sm-center {{ $errors->has('mto_nto') ? 'is-invalid' : '' }}"   value="{{ $factura->mto_nto }}"  readonly/>
            <div class="invalid-feedback">
                @error('mto_nto') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="mto_iva">Monto I.V.A.</x-label>
            <x-input name="mto_iva" wire:model.defer="mto_iva" class="form-control-sm text-sm-center {{ $errors->has('mto_iva') ? 'is-invalid' : '' }}"   value="{{ $factura->mto_iva }}"  readonly/>
            <div class="invalid-feedback">
                @error('mto_iva') {{ $message }} @enderror
            </div>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="mto_fac">Monto de Factura</x-label>
            <x-input name="mto_fac" wire:model.defer="mto_fac" class="form-control-sm text-sm-center {{ $errors->has('mto_fac') ? 'is-invalid' : '' }}"   value="{{ $factura->mto_fac }}"  readonly/>
            <div class="invalid-feedback">
                @error('mto_fac') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="monto_original">Monto Original</x-label>
            <x-input name="monto_original" wire:model.defer="monto_original" class="form-control-sm text-sm-center {{ $errors->has('monto_original') ? 'is-invalid' : '' }}"   value="{{ $factura->monto_original }}"  readonly/>
            <div class="invalid-feedback">
                @error('monto_original') {{ $message }} @enderror
            </div>
        </div>
    </div>
    <!-- Divisor Montos Facturas/Recibos -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos Nota Crédito</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="lncr_sn">Nota de Crédito</x-label>
            <x-select  name="ncr_sn" id="ncr_sn" wire:model.defer="ncr_sn" style="pointer-events:none" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\EstadoSiNo::cases() as $tipo)
                    <option value="{{ $tipo->value }}"  @selected(old('ncr_sn',$factura->ncr_sn) == $tipo->value)>
                        {{ $tipo->name }}
                    </option>
                @endforeach
            </x-select>
            <div class="invalid-feedback">
                @error('ncr_sn') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="ltot_ncr">Monto Total NC</x-label>
            <x-input name="tot_ncr" wire:model.defer="tot_ncr" class="form-control-sm text-sm-center {{ $errors->has('tot_ncr') ? 'is-invalid' : '' }}"   value="{{ $factura->tot_ncr }}"  readonly/>
            <div class="invalid-feedback">
                @error('tot_ncr') {{ $message }} @enderror
            </div>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="lmto_ncr">Monto de NC</x-label>
            <x-input name="mto_ncr" wire:model.defer="mto_ncr" class="form-control-sm text-sm-center {{ $errors->has('mto_ncr') ? 'is-invalid' : '' }}"   value="{{ $factura->mto_ncr }}" readonly />
            <div class="invalid-feedback">
                @error('mto_ncr') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="liva_ncr">Monto de NC</x-label>
            <x-input name="iva_ncr" wire:model.defer="iva_ncr" class="form-control-sm text-sm-center {{ $errors->has('iva_ncr') ? 'is-invalid' : '' }}"   value="{{ $factura->iva_ncr }}" readonly />
            <div class="invalid-feedback">
                @error('iva_ncr') {{ $message }} @enderror
            </div>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="lnum_nc">Número NC Sistema</x-label>
            <x-input name="num_nc" wire:model.defer="num_nc" class="form-control-sm text-sm-center {{ $errors->has('num_nc') ? 'is-invalid' : '' }}"   value="{{ $factura->num_nc }}" readonly />
            <div class="invalid-feedback">
                @error('num_nc') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="lnro_ncr">Prov NC Prov.</x-label>
            <x-input name="nro_ncr" wire:model.defer="nro_ncr" class="form-control-sm text-sm-center {{ $errors->has('nro_ncr') ? 'is-invalid' : '' }}"   value="{{ $factura->nro_ncr }}" readonly />
            <div class="invalid-feedback">
                @error('nro_ncr') {{ $message }} @enderror
            </div>
        </div>
    </div>
    <!-- Divisor DNotas de Entrge, Actas de Aceptación-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Notas de Entrge, Actas de Aceptación</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>
    <div class="row col-12 offset-2">
        {{--  <input id="hiddenDetalle" type="hidden" wire:model.defer ="listadoDetalle" name="listadoDetalle">
        <input id="hiddengastos"  type="hidden" wire:model.defer ="listadoGastos"  name="listadoGastos">  --}}
        <table  class="table table-bordered table-striped table-hover text-nowrap table-responsive">
            <thead>
                <tr align="center" style="background: rgba(128, 255, 0, 0.3); border: 1px solid rgba(100, 200, 0, 0.3);">
                    <th>Marcar</th>
                    <th>Año</th>
                    <th>NE Sistema</th>
                    <th>NE Proveedor</th>
                    <th>Imponible</th>
                    <th>Exenta</th>
                    <th>Monto IVA/th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($factura->cxpdetnotasfacturas as $index => $detalle )
                <tr>
                    <td class="text-center">
                        {{ 'Si' }}
                    </td>
                    <td class="text-center">
                        {{ $detalle['ano_nota_entrega'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['nro_ent'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['nota_entrega'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['base_imponible'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['base_excenta'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['mto_iva'] }}
                    </td>
                    <td class="text-center">
                        {{  $detalle['mto_ord'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

