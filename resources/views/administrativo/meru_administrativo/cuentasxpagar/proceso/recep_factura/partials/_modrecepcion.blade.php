    <!-- Divisor Recepción-->
    <div class="row col-12">
        <x-label for="tipo">&nbsp</x-label>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos del Registro</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 ">
        <div class="form-group col-1 offset-2">
            <x-label for="nro_reng">Nro. Registro</x-label>
            <x-input name="nro_reng" class="form-control-sm text-sm-center" value="{{ $recepfactura->nro_reng }}"  readonly/>
        </div>

        <div class="form-group col-5">
            <x-label for="lsta_fac">Estado</x-label>
            <x-input name="sta_fac2"   class="form-control-sm"  style="font-size: 7mm" value="{{ $recepfactura->getEstFac($recepfactura->sta_fac) }}"  readonly/>
        </div>
        <x-input name="sta_fac" wire:model.defer="sta_fac"  class="form-control-sm"  style="visibility:hidden" <i class="fas fa-do-not-enter"></i>;" value="{{ $recepfactura->sta_fac }}"  />
    </div>

    <!-- Divisor Datos del documento-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos del Documento</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2">
        <div class="row col-12 ">
            <div class="form-group col-1">
                <x-label for="rif_prov">Proveedor</x-label>
                <x-input class="form-control-sm text-center" name="rif_prov" value="{{ $recepfactura->rif_prov }}" readonly/>
            </div>
            <div class="form-group col-5 ">
                <x-label for="fk_rif_con_desc">&nbsp</x-label>
                <x-input class="form-control-sm" name="fk_rif_con_desc" value="{{ $recepfactura->proveedor->nom_prov }}" readonly/>
            </div>
        </div>

        <div class="form-group col-2">
            <x-label for="ldocumento">Documento</x-label>
            <x-select  id="tipo_doc" name="tipo_doc"  wire:model.defer="tipo_doc"  class="form-control text-center form-control-sm" readonly>
                <option value="">-- Seleccione Documento--</option>
                @foreach ($cxptipodocumento as $cxptipodocumentoItem)
                    <option value="{{ $cxptipodocumentoItem->cod_tipo }}"  @if($cxptipodocumentoItem->cod_tipo== $this->tipo_doc) selected @endif > {{$cxptipodocumentoItem->descripcion_doc}}</option>
                @endforeach
            </x-select>
        </div>

        <div class="form-group col-2">
            <x-label for="lnro_doc">Número</x-label>
            <x-input name="nro_doc" wire:model.defer="nro_doc"  class="form-control-sm text-sm-center {{ $errors->has('nro_doc') ? 'is-invalid' : '' }}"   value="{{ $recepfactura->nro_doc }}" readonly />
            <div class="invalid-feedback">
                @error('nro_doc') {{ $message }} @enderror
            </div>
        </div>

        <div class="form-group col-2">
            <x-label for="lano_sol">Año Doc.</x-label>
            <x-input name="ano_sol" wire:model.defer="ano_sol"  class="form-control-sm text-sm-center {{ $errors->has('ano_sol') ? 'is-invalid' : '' }}"   value="{{ $recepfactura->ano_sol }}" readonly />
            <div class="invalid-feedback">
                @error('ano_sol') {{ $message }} @enderror
            </div>
        </div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-6 ">
            <x-label for="concepto">Concepto</x-label>
            <textarea  id="concepto" name="concepto"   class="form-control {{ $errors->has('concepto') ? 'is-invalid' : '' }}" rows="3" readonly>{{ $recepfactura->concepto }}</textarea>
            <div class="invalid-feedback">
                @error('concepto') {{ $message }} @enderror
            </div>
        </div>
    </div>

    <!-- Divisor Datos de la factura-->
    <div class="row col-12">
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos de la Factura</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="lano_pro">Año Recep. Factura</x-label>
            <x-input name="ano_pro" class="form-control-sm text-sm-center"   value="{{$ano_pro}}"  readonly/>
        </div>

        <div class="form-group col-2">
            <x-label for="num_fac">Factura</x-label>
            <x-input name="num_fac" class="form-control-sm text-sm-center  {{ $errors->has('num_fac') ? 'is-invalid' : '' }}"   value="{{ $recepfactura->num_fac }}"  readonly />
            <div class="invalid-feedback">
                @error('num_fac') {{ $message }} @enderror
            </div>
        </div>

        <div class="form-group col-2">
            <x-label for="lrecibo">Tipo</x-label>
            <x-select  id="recibo" name="recibo"  wire:model.defer="recibo" class="form-control text-center form-control-sm {{ $errors->has('recibo') ? 'is-invalid' : '' }}" readonly>
                <option value="">-- Seleccione Beneficiario --</option>
                <option value="{{'F'}}"  @if($recepfactura->recibo== $this->recibo) selected @endif > {{'Factura'}}</option>
                <option value="{{'R'}}"  @if($recepfactura->recibo== $this->recibo) selected @endif > {{'Recibo'}}</option>
            </x-select>
            <div class="invalid-feedback">
                @error('recibo') {{ $message }} @enderror
            </div>
        </div>
    </div>

    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="fec_fac">Fecha de Factura</x-label>
            <x-input name="fec_fac"  wire:model.defer="fec_fac" class="form-control-sm text-sm-center" type="date"  value=""  readonly/>
            <div class="invalid-feedback">
                @error('fec_fac') {{ $message }} @enderror
            </div>
        </div>

        <div class="form-group col-2">
            <x-label for="lmto_fac">Monto de Factura</x-label>
            <x-input name="mto_fac" wire:model.defer="mto_fac" class="form-control-sm text-sm-right {{ $errors->has('mto_fac') ? 'is-invalid' : '' }}" type="text"  value="{{ $recepfactura->mto_fac}}" readonly/>
            <div class="invalid-feedback">
                @error('mto_fac') {{ $message }} @enderror
            </div>
        </div>

        <div class="form-group col-2">
            <x-label for="lfec_rec">Recepción</x-label>
            <x-input name="fec_rec"  wire:model.defer="fec_rec"  class="form-control-sm text-sm-center" type="date"  value="" readonly />
            <div class="invalid-feedback">
                @error('fec_fac') {{ $message }} @enderror
            </div>
        </div>
    </div>
    <div class="row col-12 offset-2">
        <div class="form-group col-2">
            <x-label for="lfec_entrega">Fecha de Entrega</x-label>
            <x-input name="fec_entrega" wire:model.defer="fec_entrega"  class="form-control-sm text-sm-center"  type="date" value="{{ $recepfactura->fec_entrega}}"  readonly/>
        </div>

        <div class="form-group col-4">
            <x-label for="usu_rec">Recepcionado Por:</x-label>
            <x-input name="usu_rec" class="form-control-sm"  style="font-size: 7mm" value="{{$recepfactura->usuario ? $recepfactura->usuario.' - '.$recepfactura->usuariorec->nombre : '' }}"  readonly/>
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
