
    <div class="row col-12 offset-2">
        <div class="row col-12 ">
            <div class="form-group col-6">
                <x-label for="lrif_prov">Proveedor</x-label>
                <x-select  id="rif_prov" name="rif_prov"  style="pointer-events:none" class="form-control text-center form-control-sm {{ $errors->has('rif_prov') ? 'is-invalid' : '' }}" readonly>
                    <option value="">-- Seleccione Beneficiario --</option>
                    @foreach ($proveedores as $proveedor)
                        <option value="{{ $proveedor->rif_ben }}" @if ($factura->rif_prov ==  $proveedor->rif_ben) selected @endif> {{  $proveedor->rif_ben.' - '.$proveedor->nom_ben }}</option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('rif_prov') {{ $message }} @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-3">
            <x-label for="num_fac">Nro. Factura/Rec</x-label>
            <x-input name="num_fac" class="form-control-sm text-sm-center  {{ $errors->has('num_fac') ? 'is-invalid' : '' }}"   value="{{ $factura->num_fac }}"  readonly/>
            <div class="invalid-feedback">
                @error('num_fac') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-3">
            <x-label for="ano_pro">Año Doc.</x-label>
            <x-input name="ano_pro" class="form-control-sm text-sm-center {{ $errors->has('ano_pro') ? 'is-invalid' : '' }}"   value="{{ $factura->ano_pro }}"  readonly/>
            <div class="invalid-feedback">
                @error('ano_pro') {{ $message }} @enderror
            </div>
        </div>
    </div>

    <!-- Divisor Datos de la factura/recibos -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos Facturas/Recibos</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-2 ">
            <x-label for="fondo">Tipo Entrada</x-label>
            <x-select  name="fondo" style="pointer-events:none" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\FondoEncNotaEntrega::cases() as $tipo)
                     <option value="{{ $tipo->value }}"  @selected(old('fondo',$factura->fondo) == $tipo->value)>
                         {{ $tipo->name }}
                    </option>
                @endforeach
            </x-select>
        </div>
        <div class="form-group col-3">
            <x-label for="lcuenta_contable">Cuenta Contable</x-label>
            <x-input name="cuenta_contable" class="form-control-sm text-sm-center {{ $errors->has('cuenta_contable') ? 'is-invalid' : '' }}"   value="{{ $factura->cuenta_contable }}" readonly />
            <div class="invalid-feedback">
                @error('cuenta_contable') {{ $message }} @enderror
            </div>
        </div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="fec_fac">Fecha de Factura</x-label>
            <x-input name="fec_fac" class="form-control-sm text-sm-center {{ $errors->has('fec_fac') ? 'is-invalid' : '' }}" type="date"  value="{{$factura->fec_fac ? $factura->fec_fac->format('Y-m-d') : ''}}" readonly />
            <div class="invalid-feedback">
                @error('fec_fac') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-3">
            <x-label for="lnum_ctrl">Nro. Control</x-label>
            <x-input name="num_ctrl" class="form-control-sm text-sm-center {{ $errors->has('num_ctrl') ? 'is-invalid' : '' }}"   value="{{ $factura->num_ctrl }}" readonly />
            <div class="invalid-feedback">
                @error('num_ctrl') {{ $message }} @enderror
            </div>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="fecha">Fecha Ingreso RCG</x-label>
            <x-input name="fecha" class="form-control-sm text-sm-center {{ $errors->has('fecha') ? 'is-invalid' : '' }}" type="date"  value="{{$factura->fecha ? $factura->fecha->format('Y-m-d') : ''}}"  readonly/>
            <div class="invalid-feedback">
                @error('fecha') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="recibo">Tipo</x-label>
            <x-select  id="recibo" name="recibo" style="pointer-events:none" class="form-control text-center form-control-sm {{ $errors->has('recibo') ? 'is-invalid' : '' }}" readonly>
                <option value="">-- Seleccione Beneficiario --</option>
                <option value="{{'F'}}" @if($factura->recibo=='F') selected @endif> {{'Factura'}}</option>
                <option value="{{'R'}}" @if($factura->recibo=='F') selected @endif> {{'Recibo'}}</option>
            </x-select>
            <div class="invalid-feedback">
                @error('recibo') {{ $message }} @enderror
            </div>
        </div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-5">
            <x-label for="sta_fac">Estado</x-label>
            <x-input name="sta_fac" class="form-control-sm"  style="font-size: 7mm" value="{{ $factura->getEstFac($factura->sta_fac) }}"  readonly/>
        </div>
    </div>

    <!-- Divisor Datos de la factura/recibos -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Identificación de Documentos</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="ldocumento">Documento</x-label>
            <x-select  id="tipo_doc" name="tipo_doc" style="pointer-events:none" class="form-control   text-center form-control-sm {{ $errors->has('tipo_doc') ? 'is-invalid' : '' }}" readonly>
                <option value="">-- Seleccione Tipo Documento --</option>
                @foreach ($cxptipodocumento as $cxptipodocumentoItem)
                    <option value="{{ $cxptipodocumentoItem->cod_tipo }}" @if($factura->tipo_doc == $cxptipodocumentoItem->cod_tipo) selected @endif > {{ $cxptipodocumentoItem->descripcion_doc }}</option>
                @endforeach
            </x-select>
            <div class="invalid-feedback">
                @error('tipo_doc') {{ $message }} @enderror
            </div>
        </div>

    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-3">
            <x-label for="ldeposito_garantia">Depósito en Garantia</x-label>
            <x-select  name="deposito_garantia" style="pointer-events:none" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\EstadoSiNo::cases() as $tipo)
                     <option value="{{ $tipo->value }}"  @selected(old('deposito_garantia',$factura->deposito_garantia) == $tipo->value)>
                         {{ $tipo->name }}
                    </option>
                @endforeach
            </x-select>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="lano_sol">Año Doc.</x-label>
            <x-input name="ano_sol" class="form-control-sm text-sm-center {{ $errors->has('ano_sol') ? 'is-invalid' : '' }}"   value="{{ $factura->ano_sol }}" readonly />
            <div class="invalid-feedback">
                @error('ano_sol') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="lnro_doc">Nro. Doc</x-label>
            <x-input name="nro_doc" class="form-control-sm text-sm-center {{ $errors->has('nro_doc') ? 'is-invalid' : '' }}"   value="{{ $factura->nro_doc }}"  readonly />
            <div class="invalid-feedback">
                @error('nro_doc') {{ $message }} @enderror
            </div>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-3">
            <x-label for="ltipo_pago">Depósito en Garantia</x-label>
            <x-select  name="tipo_pago" style="pointer-events:none" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\compras\TipoEntEncNotaEntrega::cases() as $tipo)
                     <option value="{{ $tipo->value }}"  @selected(old('tipo_pago',$factura->tipo_pago) == $tipo->value)>
                         {{ $tipo->name }}
                    </option>
                @endforeach
            </x-select>
        </div>
        <div class="form-group col-2">
            <x-label for="mto_iva">% Iva</x-label>
            <x-input name="mto_iva" class="form-control-sm text-sm-center {{ $errors->has('mto_iva') ? 'is-invalid' : '' }}"   value="{{ $factura->mto_iva }}" readonly />
            <div class="invalid-feedback">
                @error('mto_iva') {{ $message }} @enderror
            </div>
        </div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-3">
            <x-label for="provisionada">Provisionada</x-label>
            <x-select  name="provisionada" style="pointer-events:none" class="form-control-sm text-center " readonly>
                <option value="">Seleccione...</option>
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\EstadoSiNo::cases() as $tipo)
                    <option value="{{ $tipo->value }}"  @selected(old('provisionada',$factura->provisionada) == $tipo->value)>
                        {{ $tipo->name }}
                    </option>
                @endforeach
            </x-select>
        </div>
    </div>

    <!-- Divisor Datos de la factura/recibos -->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos de la Amortización</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="por_anticipo">% de Anticipo</x-label>
            <x-input name="por_anticipo" class="form-control-sm text-sm-center {{ $errors->has('por_anticipo') ? 'is-invalid' : '' }}"   value="{{ $factura->por_anticipo }}" readonly />
            <div class="invalid-feedback">
                @error('por_anticipo') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="mto_anticipo">Monto de Anticipo</x-label>
            <x-input name="mto_anticipo" class="form-control-sm text-sm-center {{ $errors->has('mto_anticipo') ? 'is-invalid' : '' }}"   value="{{ $factura->mto_anticipo }}" readonly />
            <div class="invalid-feedback">
                @error('mto_anticipo') {{ $message }} @enderror
            </div>
        </div>
        <div class="form-group col-2">
            <x-label for="mto_amortizacion">Monto de Amortización</x-label>
            <x-input name="mto_amortizacion" class="form-control-sm text-sm-center {{ $errors->has('mto_amortizacion') ? 'is-invalid' : '' }}"   value="{{ $factura->mto_amortizacion }}"  readonly />
            <div class="invalid-feedback">
                @error('mto_amortizacion') {{ $message }} @enderror
            </div>
        </div>
    </div>


    @push('scripts')
    <script type="text/javascript">

        $(function () {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
                minimumInputLength: 2,
                language: {
                    noResults: function() {
                        return 'No se encontraron resultados';
                    },
                    inputTooShort: function() {
                        return 'Ingrese al menos dos letras';
                    }
                }
            }).on('change', function(event){
                Livewire.emit('changeSelect', $(this).val(), event.target.id)
            });
        })

    </script>
@endpush
