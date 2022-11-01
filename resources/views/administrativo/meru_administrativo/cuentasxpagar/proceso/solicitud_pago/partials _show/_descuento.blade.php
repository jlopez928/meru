
    <br>    <br>
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">NOTAS DE CRÉDITO/DÉBITO Y ANTICIPOS</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    <div class="row col-12">
        <x-field class="text-center col-2 offset-2">
            <x-label for="por_ant">% de Anticipo</x-label>
            <x-input value="{{$solicititudpago->por_ant? $solicititudpago->por_ant:''}}" readonly    id="por_ant" name="por_ant" class="text-center form-control-sm  {{ $errors->has('por_ant') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('por_ant') {{ $message }} @enderror
            </div>
         </x-field>
         <x-field class="text-center col-2">
            <x-label for="mto_anticipo">Total Anticipo 	</x-label>
            <x-input value="{{$solicititudpago->mto_anticipo? $solicititudpago->mto_anticipo:''}}" readonly    id="mto_anticipo" name="mto_anticipo" class="text-center form-control-sm  {{ $errors->has('mto_anticipo') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('mto_anticipo') {{ $message }} @enderror
            </div>
         </x-field>
         <x-field class="text-center col-2">
            <x-label for="mto_amortizacion">Amortización</x-label>
            <x-input value="{{$solicititudpago->mto_amortizacion? $solicititudpago->mto_amortizacion:''}}" readonly    id="mto_amortizacion" name="mto_amortizacion" class="text-center form-control-sm  {{ $errors->has('mto_amortizacion') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('mto_amortizacion') {{ $message }} @enderror
            </div>
         </x-field>
    </div>
    <div class="row col-12">
        <x-field class="text-center col-2 offset-2">
            <x-label for="mto_ncr">Monto de NC</x-label>
            <x-input value="{{$solicititudpago->mto_ncr? $solicititudpago->mto_ncr:''}}" readonly    id="mto_ncr" name="mto_ncr" class="text-center form-control-sm  {{ $errors->has('mto_ncr') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('mto_ncr') {{ $message }} @enderror
            </div>
         </x-field>
         <x-field class="text-center col-2">
            <x-label for="iva_ncr">I.V.A de NC</x-label>
            <x-input value="{{$solicititudpago->iva_ncr? $solicititudpago->iva_ncr:''}}" readonly    id="iva_ncr" name="iva_ncr" class="text-center form-control-sm  {{ $errors->has('iva_ncr') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('iva_ncr') {{ $message }} @enderror
            </div>
         </x-field>
         <x-field class="text-center col-2">
            <x-label for="tot_ncr">Total de NC</x-label>
            <x-input value="{{$solicititudpago->tot_ncr? $solicititudpago->tot_ncr:''}}" readonly    id="tot_ncr" name="tot_ncr" class="text-center form-control-sm  {{ $errors->has('tot_ncr') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('tot_ncr') {{ $message }} @enderror
            </div>
         </x-field>
    </div>
    <div class="row col-12">
        <x-field class="text-center col-2 offset-2">
            <x-label for="num_ctrl">Monto de ND</x-label>
            <x-input value="{{$solicititudpago->mto_ndb? $solicititudpago->mto_ndb:''}}" readonly    id="mto_ndb" name="mto_ndb" class="text-center form-control-sm  {{ $errors->has('mto_ndb') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('mto_ndb') {{ $message }} @enderror
            </div>
         </x-field>
         <x-field class="text-center col-2">
            <x-label for="iva_ndb">	I.V.A de ND</x-label>
            <x-input value="{{$solicititudpago->iva_ndb? $solicititudpago->iva_ndb:''}}" readonly    id="iva_ndb" name="iva_ndb" class="text-center form-control-sm  {{ $errors->has('iva_ndb') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('iva_ndb') {{ $message }} @enderror
            </div>
         </x-field>
         <x-field class="text-center col-2">
            <x-label for="tot_ndb">Total de ND</x-label>
            <x-input value="{{$solicititudpago->tot_ndb? $solicititudpago->tot_ndb:''}}" readonly    id="tot_ndb" name="tot_ndb" class="text-center form-control-sm  {{ $errors->has('tot_ndb') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('tot_ndb') {{ $message }} @enderror
            </div>
         </x-field>
    </div>
    <br>
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">RETENCIONES ESPECIALES</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    <div class="row col-12">
        <x-field class="text-center col-2 offset-2">
            <x-label for="retencion_terceros"> Retencion Especial</x-label>
            <x-select readonly style="pointer-events:none"  id="retencion_terceros" name="retencion_terceros" class="form-control-sm {{ $errors->has('retencion_terceros') ? 'is-invalid' : '' }}">
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\CuentasxPagar\RetencionTerceros::cases() as $estado)
                    <option value="{{ $estado->value }}" @selected(old('retencion_terceros', $solicititudpago->retencion_terceros) === $estado->value)>
                        {{ $estado->name }}
                    </option>
                @endforeach
            </x-select>
        </x-field>
        <div class="invalid-feedback">
            @error('retencion_terceros') {{ $message }} @enderror
         </div>
        <x-field class="text-center col-5">
            <x-label for="rif_aerolinea">{{ __('Aerolinea') }}</x-label>
            <x-select   readonly  style="pointer-events:none" id="rif_aerolinea" name="rif_aerolinea" class="form-control-sm {{ $errors->has('rif_aerolinea') ? 'is-invalid' : '' }}">
                @if ($solicititudpago->rif_aerolinea!='NA')
                    @foreach($lineasaereas as $valor)
                    <option value="{{ $valor->rif_aerolinea }}"  @selected(old('rif_aerolinea', $solicititudpago->rif_aerolinea) === $valor->rif_aerolinea) >
                                {{ $valor->nom_aerolinea }}</option>
                    @endforeach
                @endif
            </x-select>
            <div class="invalid-feedback">
                @error('rif_aerolinea') {{ $message }} @enderror
            </div>
        </x-field>
    </div>
    <div class="row col-12">
        <x-field class="text-center col-2 offset-2">
        <x-label for="base_islr_terceros">Base Imponible:</x-label>
        <x-input value="{{$solicititudpago->base_islr_terceros? $solicititudpago->base_islr_terceros:''}}" readonly    id="base_islr_terceros" name="base_islr_terceros" class="text-center form-control-sm  {{ $errors->has('base_islr_terceros') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('base_islr_terceros') {{ $message }} @enderror
        </div>
        </x-field>
        <x-field class="text-center col-2">
        <x-label for="base_iva_internacional">Base IVA</x-label>
        <x-input value="{{$solicititudpago->base_iva_internacional? $solicititudpago->base_iva_internacional:''}}" readonly    id="base_iva_internacional" name="base_iva_internacional" class="text-center form-control-sm  {{ $errors->has('base_iva_internacional') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('base_iva_internacional') {{ $message }} @enderror
        </div>
        </x-field>
    </div>
    <br>
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">RETENCIONES TERCEROS</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    <div class="row col-12">
        <x-field class="text-center col-2 offset-2">
            <x-label for="retencionrif_terceros">{{ __('Retencion Tercero') }}</x-label>
            <x-select  readonly disabled id="retencionrif_terceros" name="retencionrif_terceros" class="form-control-sm {{ $errors->has('retencionrif_terceros') ? 'is-invalid' : '' }}">
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\EstadoSiNo::cases() as $estado)
                    <option value="{{ $estado->value }}" @selected(old('retencionrif_terceros', $solicititudpago->retencionrif_terceros) === $estado->value)>
                        {{ $estado->name }}
                    </option>
                @endforeach
            </x-select>
            <div class="invalid-feedback">
                @error('retencionrif_terceros') {{ $message }} @enderror
            </div>
        </x-field>
        <x-field class="text-center col-5">
            <x-label for="rif_ret">Beneficiario</x-label>
            <x-select   readonly disabled id="rif_ret" name="rif_ret" class="form-control select2bs4 text-center form-control-sm {{ $errors->has('rif_ret') ? 'is-invalid' : '' }}">
                @if ($solicititudpago->rif_ret!='')
                    @foreach($beneficiario as $valor)
                        <option value="{{ $valor->rif_ben }}"  @selected(old('rif_ret', $solicititudpago->rif_ret) === $valor->rif_ben) >
                                    {{ $valor->rif_ben }}-{{ $valor->nom_ben }}</option>
                    @endforeach
                @endif
            </x-select>
            <div class="invalid-feedback">
                @error('rif_ret') {{ $message }} @enderror
            </div>
        </x-field>
    </div>
    <br>
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">DESCUENTOS ESPECIALES</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    <div class="row col-12">
        <x-field class="text-center col-2 offset-2">
            <x-label for="monto_descuento">Monto Descuento</x-label>
            <x-input value="{{$solicititudpago->monto_descuento? $solicititudpago->monto_descuento:''}}" readonly    id="monto_descuento" name="monto_descuento" class="text-center form-control-sm  {{ $errors->has('monto_descuento') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('monto_descuento') {{ $message }} @enderror
            </div>
         </x-field>
         <x-field class="text-center col-2">
            <x-label for="documento_descuento">Doc. descuento</x-label>
            <x-input value="{{$solicititudpago->documento_descuento? $solicititudpago->documento_descuento:''}}" readonly    id="documento_descuento" name="documento_descuento" class="text-center form-control-sm  {{ $errors->has('documento_descuento') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('documento_descuento') {{ $message }} @enderror
            </div>
         </x-field>
         <x-field class="text-center col-4">
            <x-label for="cta_descuento">Cta. Contable Descuento</x-label>
            <x-input value="{{$solicititudpago->cta_descuento? $solicititudpago->cta_descuento:''}}" readonly    id="cta_descuento" name="cta_descuento" class="text-center form-control-sm  {{ $errors->has('cta_descuento') ? 'is-invalid' : '' }}" />
            <div class="invalid-feedback">
                @error('cta_descuento') {{ $message }} @enderror
            </div>
         </x-field>

    </div>
    <div class="row col-12">
        <x-field class="text-center col-4 offset-2">
            <x-label for="motivo_descuento">Motivo del Descuento</x-label>
            <textarea readonly  id="motivo_descuento" name="motivo_descuento" class="form-control {{ $errors->has('motivo_descuento') ? 'is-invalid' : '' }}" rows="3">
                {{ $solicititudpago->motivo_descuento}}
            </textarea>
            <div class="invalid-feedback">
                @error('motivo_descuento') {{ $message }} @enderror
            </div>
        </x-field>

    </div>
    <div class="col-12">
        <h5 class="card-title text-secondary text-bold">OPCIONES ADICIONALES</h5>
    </div>
    <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    <div class="row col-12">
        <div class="form-check">
            <label><input  disabled type="checkbox" id="cbox1" value="first_checkbox"> ¿Si es persona Juridica, Aplica Retencion como Persona Natural ?</label><br>
            <label><input  disabled type="checkbox" id="cbox1" value="first_checkbox"> ¿Es pago de Condominio/Compra persona Natural?</label><br>
            <label><input  disabled type="checkbox" id="cbox1" value="first_checkbox"> ¿Si es Persona Natural, Aplica Retención? 	</label><br>
        </div>
    </div>
