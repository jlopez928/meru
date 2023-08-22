
        <br><br>
        <div class="row col-12">
            <x-field class="text-center col-1 offset-2">
                <x-label for="ano_pro">{{ __('Año') }}</x-label>
                <x-select   readonly id="ano_pro" name="ano_pro" class="form-control-sm {{ $errors->has('ano_pro') ? 'is-invalid' : '' }}">
                    @foreach($registrocontrol as $valor)
                    <option value="{{ $valor->ano_pro }}"  @selected(old('ano_pro', $opsolservicio->ano_pro) === $valor->ano_pro) >
                                   {{ $valor->ano_pro }}</option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('ano_pro') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="xnro_sol">Numero</x-label>
                <x-input value="{{$opsolservicio->xnro_sol? $opsolservicio->xnro_sol:''}}" readonly    id="xnro_sol" name="xnro_sol" class="text-center form-control-sm  {{ $errors->has('xnro_sol') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('xnro_sol') {{ $message }} @enderror
                </div>
             </x-field>
            <x-field class="text-center col-2">
                <x-label for="fec_emi">Fecha de Emisión</x-label>
                <x-input  readonly value="{{ $opsolservicio->fec_emi->format('d-m-Y')}}"   id="fec_emi"  name="fec_emi" type="text" class="text-center form-control-sm  {{ $errors->has('fec_emi') ? 'is-invalid' : '' }}"  />
                <div class="invalid-feedback">
                    @error('fec_emi') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="text-center col-2">
                <x-label for="sta_sol">Estado</x-label>
                     <x-select  readonly   id="sta_sol" name="sta_sol" class="form-control-sm {{ $errors->has('sta_sol') ? 'is-invalid' : '' }}">
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\OtrosPAgos\EstadoCertificacion::cases() as $estado)
                            <option value="{{ $estado->value }}" @selected(old('sta_sol', $opsolservicio->sta_sol) === $estado->value) >
                                {{ $estado->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('sta_sol') {{ $message }} @enderror
                    </div>
            </x-field>
        </div>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos de la Certificación</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
        <div class="row col-12">
            <div wire:ignore class="text-center  form-group col-6">
                    <x-label for="lrif_ben">Beneficiario</x-label>
                    <x-select   readonly disabled id="rif_prov" name="rif_prov" class="form-control select2bs4 text-center form-control-sm {{ $errors->has('rif_prov') ? 'is-invalid' : '' }}">
                        @foreach($beneficiario as $valor)
                            <option value="{{ $valor->rif_ben }}"  @selected(old('rif_prov', $opsolservicio->rif_prov) === $valor->rif_ben) >
                                           {{ $valor->rif_ben }}-{{ $valor->nom_ben }}</option>
                        @endforeach

                    </x-select>
                    <div class="invalid-feedback">
                        @error('rif_prov') {{ $message }} @enderror
                    </div>
            </div>
            <div wire:ignore class="text-center  form-group col-6">
                <x-label for="lcod_ger">Gerencia</x-label>
                   <x-select   readonly disabled id="cod_ger" name="cod_ger"  class="form-control select2bs4 text-center form-control-sm {{ $errors->has('cod_ger') ? 'is-invalid' : '' }}">
                    <option value="">-- Seleccione Gerencia --</option>
                        @foreach($gerencia as $valor)
                            <option value="{{ $valor->cod_ger }}"  @selected(old('cod_ger', $opsolservicio->cod_ger) === $valor->cod_ger) >
                                           {{ $valor->cod_ger }}-{{ $valor->des_ger }}</option>
                        @endforeach

               </x-select>
                <div class="invalid-feedback">
                    @error('cod_ger') {{ $message }} @enderror
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Punto de Cuenta</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
        <div class="row col-12">
            <x-field class="text-center col-2 offset-4">
                <x-label for="lpto_cta">Punto de Cuenta</x-label>
                <x-input  readonly value="{{$opsolservicio->pto_cta}}" id="pto_cta" name="pto_cta" class="form-control-sm {{ $errors->has('pto_cta') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('pto_cta') {{ $message }} @enderror
                </div>
                </x-field>
            <x-field class="text-center col-2 ">
                <x-label for="lfec_pto">Fecha del Punto</x-label>
                <x-input readonly value="{{$opsolservicio->fec_pto==''?$opsolservicio->fec_pto:$opsolservicio->fec_pto->format('d-m-Y')}}" id="fec_pto" name="fec_pto" type="text" class="form-control-sm {{ $errors->has('fec_pto') ? 'is-invalid' : '' }}"/>
                <div class="invalid-feedback">
                    @error('fec_pto') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos del Anticipo</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
        <div class="row col-12">
            <x-field class="text-center col-2 offset-4">
                <x-label for="por_anticipo">% Anticipo</x-label>
                <x-input  readonly value="{{$opsolservicio->por_anticipo}}" x-mask:dynamic="$money($input, ',')" id="por_anticipo" name="por_anticipo"  class="form-control-sm text-right {{ $errors->has('por_anticipo') ? 'is-invalid' : '' }}" />
                    <div class="invalid-feedback">
                        @error('por_anticipo') {{ $message }} @enderror
                    </div>
            </x-field>
            <x-field class="text-center col-2 ">
                <x-label for="mto_ant">Monto Anticipo</x-label>
                <x-input   readonly value="{{$opsolservicio->mto_ant}}" x-mask:dynamic="$money($input, ',')" id="mto_ant" name="mto_ant" class="form-control-sm text-right {{ $errors->has('mto_ant') ? 'is-invalid' : '' }}" />
                    <div class="invalid-feedback">
                        @error('mto_ant') {{ $message }} @enderror
                    </div>
            </x-field>
        </div>
        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos del Contrato</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
        <div class="row col-12">
            <x-field class="text-center col-2 offset-2">
                <x-label for="grupo">Gupo</x-label>
                <x-select style="pointer-events:none"  readonly  id="grupo"  name="grupo" class="form-control-sm {{ $errors->has('grupo') ? 'is-invalid' : '' }}">
                    <option value="CO">Contrato</option>
                </x-select>
                <div class="invalid-feedback">
                    @error('grupo') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2 ">
                <x-label for="grupo">Tipo Contrato</x-label>
                <x-select style="pointer-events:none"  readonly  id="tip_contrat"  name="tip_contrat" class="form-control-sm {{ $errors->has('tip_contrat') ? 'is-invalid' : '' }}">
                    <option value="{{$opsolservicio->tip_contrat=='O'?'O':'S'}}">{{$opsolservicio->tip_contrat=='O'?'Obras':'Servicios'}}</option>
                </x-select>
                <div class="invalid-feedback">
                    @error('tip_contrat') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2 -bottom-3">
                <x-label for="num_contrato">Nro. Contrato</x-label>
                <x-input  readonly value="{{$opsolservicio->num_contrato}}"  id="num_contrato" name="num_contrato"  class="form-control-sm {{ $errors->has('num_contrato') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('num_contrato') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="row col-12">
            <x-field class="text-center col-2 offset-2">
                <x-label for="tip_pag">Tipo de Pago</x-label>
                <x-select  style="pointer-events:none"  readonly   id="tip_pag"  name="tip_pag" class="form-control-sm {{ $errors->has('tip_pag') ? 'is-invalid' : '' }}">
                    <option value="{{$opsolservicio->tip_pag->value=='P'?'P':'T'}}">{{$opsolservicio->tip_pag->value=='P'?'Parcial':'Total'}}</option>
                </x-select>
                <div class="invalid-feedback">
                    @error('tip_pag') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="factura">Tiene Factura</x-label>
                <x-select  style="pointer-events:none"  readonly  id="factura" name="factura" class="form-control-sm {{ $errors->has('factura') ? 'is-invalid' : '' }}">
                    <option value="S">Si</option>
                </x-select>
                <div class="invalid-feedback">
                    @error('factura') {{ $message }} @enderror
                </div>
            </x-field>
            @if($nombreRuta =='addendum')
                <x-field class="text-center col-2 ">
                    <x-label for="mod">Tipo de Addendum</x-label>
                    <x-select style="pointer-events:none"  readonly  id="mod" name="mod" wire:model.defer="mod" class="form-control-sm {{ $errors->has('mod') ? 'is-invalid' : '' }}">
                        <option value="{{$opsolservicio->mod=='0'?'0':'S'}}">{{$opsolservicio->mod=='0'?'Aumenta':'Disminuye'}}</option>
                    </x-select>
                    <div class="invalid-feedback">
                        @error('mod') {{ $message }} @enderror
                    </div>
                </x-field>
            @endif
        </div>


