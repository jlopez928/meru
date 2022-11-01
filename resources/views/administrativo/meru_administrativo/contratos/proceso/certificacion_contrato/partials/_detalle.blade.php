
    <br>    <br>
    <div class="row col-12">
        <x-field class="text-center col-4 offset-2">
            <x-label for="Ano">Motivo</x-label>
            <textarea   style="{{ $nombreRuta =='contrato'||  $habilitar==true ? '' : 'pointer-events: none' }}" wire:model.defer ="motivo"  id="motivo" name="motivo" class="form-control {{ $errors->has('motivo') ? 'is-invalid' : '' }}" rows="3"></textarea>
            <div class="invalid-feedback">
                @error('motivo') {{ $message }} @enderror
            </div>
        </x-field>

        <x-field class="text-center col-4 ">
            <x-label for="observaciones">Observaciones</x-label>
            <textarea  style="{{ $nombreRuta =='contrato'||  $habilitar==true ? '' : 'pointer-events: none' }}" wire:model.defer ="observaciones"   id="observaciones" name="observaciones" class="form-control {{ $errors->has('observaciones') ? 'is-invalid' : '' }}" rows="3"></textarea>
            <div class="invalid-feedback">
                @error('observaciones') {{ $message }} @enderror
            </div>
        </x-field>
    </div>
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">Servicios</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    <div class="mb-2">
        <table   style="{{ $nombreRuta =='contrato'||  $habilitar==true ? '' : 'pointer-events: none' }}" class="table table-bordered table-sm text-center " >
            <thead class="">
                <tr class="table-primary">
                    <th style="width:150px">Código</th>
                    <th style="width:350px">Descripción</th>
                    <th>%IVA</th>
                    <th>Cantidad</th>
                    <th>Base Imponible</th>
                    <th>Base Exenta</th>
                    <th>Monto Total</th>
                </tr>
            </thead >
            <tbody>
                    <tr>
                        <td>
                            <input class="form-control-sm border-0 text-center" name="codigo" style="width:100px" wire:model.defer="codigo" x-mask="999" wire:keydown.tab.prevent="calculaCostoTotal('codigo')"  type="text">
                        </td>
                        <td>
                            <input  readonly   class="form-control-sm border-0" wire:model.defer="des_con" name="des_con" wire:model.defer="des_con"  type="text" style="width:350px">
                        </td>
                        <td >
                            <input class="form-control-sm border-0 text-center money-mask" id="por_iva_con"  name="por_iva_con" x-mask:dynamic="$money($input, ',')" style="width:100px" wire:model.defer="por_iva_con" wire:keydown.tab.prevent="calculaCostoTotal('por_iva_con')" >
                        </td>
                        <td>
                            <input class="form-control-sm border-0 text-center" id="cantidad" name="cantidad"   style="text-align:right width:100px" wire:model.defer="cantidad" x-mask="99999" wire:keydown.tab.prevent="calculaCostoTotal('cantidad')" type="text" >
                        </td>
                        <td>
                            <input class="form-control-sm border-0 text-right money-mask" id="cos_uni" name="cos_uni"  style="width:100px" wire:model.defer="cos_uni" x-mask:dynamic="$money($input, ',')" wire:keydown.tab.prevent="calculaCostoTotal('cos_uni')" type="text" >
                        </td>
                        <td>
                            <input class="form-control-sm border-0 text-right money-mask" id="cos_excenta" name="cos_excenta"  style="width:100px" wire:model.defer="cos_excenta" x-mask:dynamic="$money($input, ',')" wire:keydown.tab.prevent="calculaCostoTotal('cos_excenta')" type="text" >
                        </td>
                        <td>
                            <input readonly class="form-control-sm border-0 text-right"  id="cos_tot" name="cos_tot" style="width:100px" wire:model.defer="cos_tot"  type="text" >
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">Estructura de Gasto</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    <div class="mb-2"  x-data="{  open: @entangle('showDropdown')}">
        <input id="hiddenDetalle" type="hidden"   name="listadoDetalle">
        <input id="habilitar" type="hidden"  wire:model.defer="habilitar"  name="habilitar">
        <table  style='pointer-events: none' class="table table-bordered table-sm text-center " >
            <thead class="">
                <tr class="table-primary">
                    <th style="width:50px">Gasto</th>
                    <th style="width:50px">Tp</th>
                    <th style="width:50px">P/A</th>
                    <th style="width:50px">Obj</th>
                    <th style="width:50px">Gcia</th>
                    <th style="width:50px">U.Ejec.</th>
                    <th style="width:50px">Pa</th>
                    <th style="width:50px">Gn</th>
                    <th style="width:50px">Esp.</th>
                    <th style="width:50px">Sub.Esp</th>
                    <th style="width:350px">Descripción</th>
                    <th style="width:50px">Monto</th>
                </tr>
            </thead >
            <tbody>
                @forelse ($this->detallegasto as $index => $detalle)
                    <tr>
                        <td  class="text-center">
                            {{ $detalle['gasto']=='1'?'Si':'No' }}     </td>
                        <td class="text-center">
                            {{ $detalle['tip_cod'] }}
                        </td>
                        <td class="text-center">
                            {{  $detalle['cod_pryacc'] }}
                        </td>
                        <td class="text-center">
                            {{  $detalle['cod_obj'] }}
                        </td>
                        <td class="text-center">
                            {{  $detalle['gerencia'] }}
                        </td>
                        <td class="text-center">
                            {{  $detalle['unidad'] }}
                        </td>
                        <td class="text-center">
                            {{  $detalle['cod_par'] }}
                        </td>
                        <td class="text-center">
                            {{  $detalle['cod_gen'] }}
                        </td>
                        <td class="text-center">
                            {{  $detalle['cod_esp'] }}
                        </td>
                        <td class="text-center">
                            {{  $detalle['cod_sub'] }}
                        </td>
                        <td class="text-center">
                            {{ $detalle['descrip'] }}
                        </td>
                        <td class="text-center">
                            {{  $detalle['mto_tra'] }}
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
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">Montos Totales</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
     <div class="row col-12">
        <div class="text-center  form-group col-2 offset-3">
            <x-label for="base_imponible">Base Imponible</x-label>
            <x-input  name="base_imponible" id="base_imponible"  readonly wire:model.defer="base_imponible" class="text-center form-control-sm {{ $errors->has('base_imponible') ? 'is-invalid' : '' }}"  />
            <div class="invalid-feedback">
                @error('base_imponible') {{ $message }} @enderror
            </div>
         </div>
         <div class="text-center  form-group col-2">
            <x-label for="base_exenta">Base Exenta</x-label>
            <x-input  name="base_exenta" id="base_exenta" readonly wire:model.defer="base_exenta" class="text-center form-control-sm {{ $errors->has('base_exenta') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('base_exenta') {{ $message }} @enderror
            </div>
         </div>
         <div class="text-center  form-group col-2">
            <x-label for="monto_neto">Monto Neto</x-label>
            <x-input  name="monto_neto" id="monto_neto" readonly wire:model.defer="monto_neto" class="text-center form-control-sm {{ $errors->has('monto_neto') ? 'is-invalid' : '' }}"  />
            <div class="invalid-feedback">
                @error('monto_neto') {{ $message }} @enderror
            </div>
         </div>
    </div>
    <div class="row col-12">
        <div class="text-center  form-group col-2 offset-3">
            <x-label for="monto_iva">Monto Iva</x-label>
            @if($nombreRuta =='contrato')
              <x-input style="{{ $factura =='N' ? 'pointer-events: none' : '' }}" wire:keydown.tab.prevent="validar_monto_Iva()" name="monto_iva" id="monto_iva"  wire:model.defer="monto_iva" x-mask:dynamic="$money($input, ',')" class="money-mask text-center form-control-sm {{ $errors->has('monto_iva') ? 'is-invalid' : '' }}" />
            @else
                <x-input readonly wire:keydown.tab.prevent="validar_monto_Iva()" name="monto_iva" id="monto_iva"  wire:model.defer="monto_iva" x-mask:dynamic="$money($input, ',')" class="money-mask text-center form-control-sm {{ $errors->has('monto_iva') ? 'is-invalid' : '' }}" />
            @endif
            <div class="invalid-feedback">
            @error('monto_iva') {{ $message }} @enderror
            </div>
         </div>
         <div class="text-center  form-group col-2">
            <x-label for="monto_total">Monto Total</x-label>
            <x-input  name="monto_total" id="monto_total" readonly wire:model.defer="monto_total" class="text-center form-control-sm {{ $errors->has('monto_total') ? 'is-invalid' : '' }}"  />
            <div class="invalid-feedback">
                @error('monto_total') {{ $message }} @enderror
            </div>
         </div>
         <div class="text-center  form-group col-2">
            <x-label for="por_iva">% Iva</x-label>
            <x-input  name="por_iva" id="por_iva" readonly wire:model.defer="por_iva"  class="text-center form-control-sm {{ $errors->has('por_iva') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('por_iva') {{ $message }} @enderror
            </div>
         </div>
    </div>



