
        <br> <br>
        <div class="row col-12">
            <div class="text-center  form-group col-2 offset-2">
                <x-label for="recibo"> Factura / Recibo</x-label>
                    <x-select  readonly   style="pointer-events:none" id="recibo" name="recibo" class="form-control-sm {{ $errors->has('recibo') ? 'is-invalid' : '' }}">
                     <option value="{{ $solicititudpago->recibo}}">
                        {{ $solicititudpago->recibo=='F'?'Factura':'Recibo'}}
                            </option>

                </x-select>
                <div class="invalid-feedback">
                    @error('recibo') {{ $message }} @enderror
                </div>
            </div>
            <div class="text-center  form-group col-2 ">
                <x-label for="deposito_garantia"> Deposito en Garantia</x-label>
                    <x-select  readonly   style="pointer-events:none"  id="dep_garantia" name="dep_garantia" class="form-control-sm {{ $errors->has('dep_garantia') ? 'is-invalid' : '' }}">
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\EstadoSiNo::cases() as $estado)
                            <option value="{{ $estado->value }}" @selected(old('dep_garantia', $solicititudpago->dep_garantia) === $estado->value)>
                                {{ $estado->name }}
                            </option>
                        @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('dep_garantia') {{ $message }} @enderror
                </div>
            </div>
            <x-field class="text-center col-2">
                <x-label for="ano_pro">{{ __('Año Factura') }}</x-label>
                <x-select  readonly style="pointer-events:none"  id="ano_factura" name="ano_factura" class="form-control-sm {{ $errors->has('ano_factura') ? 'is-invalid' : '' }}">
                    @foreach($registrocontrol as $valor)
                    <option value="{{ $valor->ano_pro }}"  @selected(old('ano_factura', $solicititudpago->ano_factura) === $valor->ano_factura) >
                                   {{ $valor->ano_pro }}</option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('ano_pro') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="row col-12">
            <x-field class="text-center col-3 offset-2">
                <x-label for="ano_pro">{{ __('Año Factura/Solicitud') }}</x-label>
                <x-select    readonly   style="pointer-events:none" id="ano_pro" name="ano_pro" class="form-control-sm {{ $errors->has('ano_pro') ? 'is-invalid' : '' }}">
                    @foreach($registrocontrol as $valor)
                    <option value="{{ $valor->ano_pro }}"  @selected(old('ano_pro', $solicititudpago->ano_pro) === $valor->ano_pro) >
                                   {{ $valor->ano_pro }}</option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('ano_pro') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="ord_pag">Orden de Pago</x-label>
                <x-input value="{{$solicititudpago->ord_pag? $solicititudpago->ord_pag:''}}" readonly    id="ord_pag" name="ord_pag" class="text-center form-control-sm  {{ $errors->has('ord_pag') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('ord_pag') {{ $message }} @enderror
                </div>
             </x-field>
            <x-field class="text-center col-4">
                <x-label for="sta_sol">Estado</x-label>
                     <x-select   readonly   style="pointer-events:none"  id="sta_sol" name="sta_sol" class="form-control-sm {{ $errors->has('sta_sol') ? 'is-invalid' : '' }}">
                           <option value="{{ $solicititudpago->sta_sol->value }}"  >
                                {{ $solicititudpago->sta_sol->name  }}
                            </option>

                    </x-select>
                    <div class="invalid-feedback">
                        @error('sta_sol') {{ $message }} @enderror
                    </div>
            </x-field>
        </div>
        <div class="row col-12">
            <x-field class="text-center col-2 offset-2">
                <x-label for="num_fac">Número de Factura</x-label>
                <x-input value="{{$solicititudpago->num_fac? $solicititudpago->num_fac:''}}" readonly    id="num_fac" name="num_fac" class="text-center form-control-sm  {{ $errors->has('num_fac') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('num_fac') {{ $message }} @enderror
                </div>
             </x-field>
             <x-field class="text-center col-2">
                <x-label for="num_ctrl">Número de Control</x-label>
                <x-input value="{{$solicititudpago->num_ctrl? $solicititudpago->num_ctrl:''}}" readonly    id="num_ctrl" name="num_ctrl" class="text-center form-control-sm  {{ $errors->has('num_ctrl') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('num_ctrl') {{ $message }} @enderror
                </div>
             </x-field>
             <x-field class="text-center col-2">
                <x-label for="fec_fac">Fecha de Factura</x-label>
                <x-input  readonly value="{{ $solicititudpago->fec_fac->format('d/m/Y')}}"   id="fec_fac"  name="fec_fac" type="text" class="text-center form-control-sm  {{ $errors->has('fec_fac') ? 'is-invalid' : '' }}"  />
                <div class="invalid-feedback">
                    @error('fec_fac') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="row col-6 offset-2">
            <x-label for="lrif_ben">Beneficiario</x-label>
            <x-select   readonly disabled id="cesion" name="cesion" class="form-control select2bs4 text-center form-control-sm {{ $errors->has('cesion') ? 'is-invalid' : '' }}">
                @foreach($beneficiario as $valor)
                    <option value="{{ $valor->rif_ben }}"  @selected(old('cesion', $solicititudpago->cesion) === $valor->rif_ben) >
                                   {{ $valor->rif_ben }}-{{ $valor->nom_ben }}</option>
                @endforeach

            </x-select>
            <div class="invalid-feedback">
                @error('cesion') {{ $message }} @enderror
            </div>
        </div>
        <div class="row col-12 offset-2">
            <x-field class="text-center col-4 ">
                <x-label for="tipo_doc">{{ __('Tipo de Documento') }}</x-label>
                <x-select   readonly id="tipo_doc" name="tipo_doc" class="form-control-sm {{ $errors->has('ano_docsop') ? 'is-invalid' : '' }}">
                    @foreach($cxptipodoc as $valor)
                             <option value="{{ $valor->siglas }}"  @selected(old('tipo_doc', $solicititudpago->tipo_doc) === $valor->siglas) >
                                    {{ $valor->descripcion_doc }}</option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('tipo_doc') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="text-center col-2 ">
                <x-label for="ano_pro">{{ __('Año Doc') }}</x-label>
                <x-select   readonly id="ano_docsop" name="ano_docsop" class="form-control-sm {{ $errors->has('ano_docsop') ? 'is-invalid' : '' }}">
                    @foreach($registrocontrol as $valor)
                    <option value="{{ $valor->ano_pro }}"  @selected(old('ano_docsop', $solicititudpago->ano_docsop) === $valor->ano_docsop) >
                                   {{ $valor->ano_pro }}</option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('ano_pro') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="doc_sop">Nro. Doc</x-label>
                <x-input value="{{$solicititudpago->doc_sop? $solicititudpago->doc_sop:''}}" readonly    id="doc_sop" name="doc_sop" class="text-center form-control-sm  {{ $errors->has('ord_pag') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('doc_sop') {{ $message }} @enderror
                </div>
             </x-field>
        </div>
        <div class="row col-12">
            <x-field class="text-center col-2 offset-2">
                <x-label for="mto_neto_contrato">Monto Neto Contrato</x-label>
                <x-input value="{{$solicititudpago->mto_neto_contrato? $solicititudpago->mto_neto_contrato:''}}" readonly    id="mto_neto_contrato" name="mto_neto_contrato" class="text-center form-control-sm  {{ $errors->has('mto_neto_contrato') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('mto_neto_contrato') {{ $message }} @enderror
                </div>
             </x-field>
             <x-field class="text-center col-2">
                <x-label for="cesion">Cesion de Credito</x-label>
                     <x-select  readonly   id="cesion" name="cesion" class="form-control-sm {{ $errors->has('cesion') ? 'is-invalid' : '' }}">
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\EstadoSiNo::cases() as $estado)
                            <option value="{{ $estado->value }}" @selected(old('cesion', $solicititudpago->cesion) === $estado->value) >
                                {{ $estado->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cesion') {{ $message }} @enderror
                    </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="rec_ext_ont">Recurso Externo ONT</x-label>
                     <x-select  readonly   id="rec_ext_ont" name="rec_ext_ont" class="form-control-sm {{ $errors->has('rec_ext_ont') ? 'is-invalid' : '' }}">
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\EstadoSiNo::cases() as $estado)
                            <option value="{{ $estado->value }}" @selected(old('rec_ext_ont', $solicititudpago->rec_ext_ont) === $estado->value) >
                                {{ $estado->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('rec_ext_ont') {{ $message }} @enderror
                    </div>
            </x-field>
        </div>
        <div class="row col-6 offset-2">
            <x-label for="lrif_ben">Rif Cesionario</x-label>
            <x-select   readonly disabled id="cesion" name="cesion" class="form-control select2bs4 text-center form-control-sm {{ $errors->has('cesion') ? 'is-invalid' : '' }}">
                <option value="{{ $solicititudpago->rif_ces }}" >
                        {{ $solicititudpago->nom_ces }}</option>
            </x-select>
            <div class="invalid-feedback">
                @error('cesion') {{ $message }} @enderror
            </div>
        </div>
        <div class="row col-12">
            <x-field class="text-center col-4 offset-2">
                <x-label for="concepto">Concepto</x-label>
                <textarea readonly   id="concepto" name="concepto" class="form-control {{ $errors->has('concepto') ? 'is-invalid' : '' }}" rows="3">
                    {{ $solicititudpago->concepto}}
                </textarea>
                <div class="invalid-feedback">
                    @error('concepto') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="text-center col-4 ">
                <x-label for="observaciones">Observaciones</x-label>
                <textarea readonly  id="observaciones" name="observaciones" class="form-control {{ $errors->has('observaciones') ? 'is-invalid' : '' }}" rows="3">
                    {{ $solicititudpago->observaciones}}
                </textarea>
                <div class="invalid-feedback">
                    @error('observaciones') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="row col-12 offset-2">
            <x-field class="text-center col-2">
                <x-label for="fecha">Fecha de Creación</x-label>
                <x-input  readonly value="{{ $solicititudpago->fecha->format('d/m/Y')}}"   id="fecha"  name="fecha" type="text" class="text-center form-control-sm  {{ $errors->has('fecha') ? 'is-invalid' : '' }}"  />
                <div class="invalid-feedback">
                    @error('fecha') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="fec_apr">	Fecha de Aprobación</x-label>
                <x-input  readonly value="{{ $solicititudpago->fec_apr->format('d/m/Y')}}"   id="fec_apr"  name="fec_apr" type="text" class="text-center form-control-sm  {{ $errors->has('fec_apr') ? 'is-invalid' : '' }}"  />
                <div class="invalid-feedback">
                    @error('fec_apr') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
