
    <br>    <br>
    <div class="row col-12">
        <x-field class="text-center col-4 offset-2">
            <x-label for="motivo">Motivo</x-label>
            <textarea readonly   id="motivo" name="motivo" class="form-control {{ $errors->has('motivo') ? 'is-invalid' : '' }}" rows="3">
                {{ $opsolservicio->motivo}}
            </textarea>
            <div class="invalid-feedback">
                @error('motivo') {{ $message }} @enderror
            </div>
        </x-field>

        <x-field class="text-center col-4 ">
            <x-label for="observaciones">Observaciones</x-label>
            <textarea readonly  id="observaciones" name="observaciones" class="form-control {{ $errors->has('observaciones') ? 'is-invalid' : '' }}" rows="3">
                {{ $opsolservicio->observaciones}}
            </textarea>
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
        <table  class="table table-bordered table-sm text-center " >
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
                @forelse ($opsolservicio->opdetsolservicio as $index => $detalle)
                    <tr>
                        <td>{{ $detalle['cod_prod'] }}</td>
                        <td>{{ $detalle->opconceptos->des_con }}</td>
                        <td>{{ $detalle['por_iva'] }}</td>
                        <td>{{ $detalle['cantidad'] }}</td>
                        <td>{{ $detalle['cos_uni'] }}</td>
                        <td>{{ $detalle['base_excenta'] }}</td>
                        <td>{{ $detalle['cos_tot'] }}</td>
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
        <h5 class="card-title text-secondary text-bold">Estructura de Gasto</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    <div class="mb-2">
        <input id="listadoDetalle" type="hidden" wire:model.defer ="listadoDetalle"   name="listadoDetalle">
        <table  class="table table-bordered table-sm text-center " >
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
                    <th style="width:90px">Cod. Cuenta</th>
                </tr>
            </thead >
            <tbody>
                @forelse ($opsolservicio->opdetgastossolservicio as $index => $detallegasto)
                <tr>
                    <td class="text-center">
                        {{ $detallegasto['gasto']=='1'?'Si':'No' }}
                    </td>
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
                        {{  $detallegasto->partidapresupuestaria->des_con}}
                    </td>
                    <td class="text-center">
                        {{  $detallegasto['mto_tra'] }}
                    </td>
                    <td class="text-center">
                        {{  $detallegasto['cod_cta'] }}
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
        <div class="text-center  form-group col-2 ">
            <x-label for="deposito_garantia"> Deposito en Garantia</x-label>
            <x-select style="{{ $activardeposito =='true' ? '' : 'pointer-events: none' }}" id="deposito_garantia" name="deposito_garantia" class="form-control-sm {{ $errors->has('deposito_garantia') ? 'is-invalid' : '' }}">
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\EstadoSiNo::cases() as $estado)
                    <option value="{{ $estado->value }}" @selected(old('deposito_garantia', $opsolservicio->deposito_garantia) === $estado->value)>
                        {{ $estado->name }}
                    </option>
                @endforeach
            </x-select>
            <div class="invalid-feedback">
                @error('deposito_garantia') {{ $message }} @enderror
            </div>
         </div>
    </div>
    <div class="row col-12">
        <div class="text-center  form-group col-2 offset-3">
            <x-label for="base_imponible">Base Imponible</x-label>
            <x-input  x-mask:dynamic="$money($input, ',')"  value="{{ $opsolservicio->base_imponible}}"  name="base_imponible" id="base_imponible"  readonly  class="text-center form-control-sm {{ $errors->has('base_imponible') ? 'is-invalid' : '' }}"  />
            <div class="invalid-feedback">
                @error('base_imponible') {{ $message }} @enderror
            </div>
         </div>
         <div class="text-center  form-group col-2">
            <x-label for="base_exenta">Base Exenta</x-label>
            <x-input x-mask:dynamic="$money($input, ',')"  value="{{ $opsolservicio->base_exenta}}"  name="base_exenta" id="base_exenta" readonly  class="text-center form-control-sm {{ $errors->has('base_exenta') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('base_exenta') {{ $message }} @enderror
            </div>
         </div>
         <div class="text-center  form-group col-2">
            <x-label for="monto_neto">Monto Neto</x-label>
            <x-input x-mask:dynamic="$money($input, ',')"   value="{{ $opsolservicio->monto_neto}}"  name="monto_neto" id="monto_neto" readonly  class="text-center form-control-sm {{ $errors->has('monto_neto') ? 'is-invalid' : '' }}"  />
            <div class="invalid-feedback">
                @error('monto_neto') {{ $message }} @enderror
            </div>
         </div>
    </div>
    <div class="row col-12">
        <div class="text-center  form-group col-2 offset-3">
            <x-label for="monto_iva">Monto Iva</x-label>
            <x-input x-mask:dynamic="$money($input, ',')"  value="{{ $opsolservicio->monto_iva}}"  name="monto_iva" id="monto_iva"  readonly class="text-center form-control-sm {{ $errors->has('monto_iva') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('monto_iva') {{ $message }} @enderror
            </div>
         </div>
         <div class="text-center  form-group col-2">
            <x-label for="monto_total">Monto Total</x-label>
            <x-input x-mask:dynamic="$money($input, ',')"  value="{{ $opsolservicio->monto_total}}"   name="monto_total" id="monto_total" readonly  class="text-center form-control-sm {{ $errors->has('monto_total') ? 'is-invalid' : '' }}"  />
            <div class="invalid-feedback">
                @error('monto_total') {{ $message }} @enderror
            </div>
         </div>
         <div class="text-center  form-group col-2">
            <x-label for="por_iva">% Iva</x-label>
            <x-input x-mask:dynamic="$money($input, ',')"  value="{{ $opsolservicio->por_iva}}"   name="por_iva" id="por_iva" readonly class="text-center form-control-sm {{ $errors->has('por_iva') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('por_iva') {{ $message }} @enderror
            </div>
         </div>
    </div>



