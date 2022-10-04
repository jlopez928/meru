
        <br> <br>
        <div class="row col-12">
            <x-field class="text-center col-1 offset-2">
                <x-label for="ano_pro">{{ __('Año') }}</x-label>
                <x-select  style="pointer-events:none" readonly wire:model.defer="ano_pro" id="ano_pro" name="ano_pro" class="form-control-sm {{ $errors->has('ano_pro') ? 'is-invalid' : '' }}">
                    @foreach ($this->RegistroControl as $index => $ano_pros)
                    <option value="{{ $ano_pros }}" @selected(old('ano_pro', $ano_pro) === $ano_pros)>
                        {{ ($ano_pros) }}
                    </option>
                @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('ano_pro') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="xnro_sol">Numero</x-label>
                <x-input  readonly   wire:model.defer="xnro_sol"  id="xnro_sol" name="xnro_sol" class="text-center form-control-sm  {{ $errors->has('xnro_sol') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('xnro_sol') {{ $message }} @enderror
                </div>
             </x-field>
            <x-field class="text-center col-2">
                <x-label for="fec_emi">Fecha de Emisión</x-label>
               <x-input  readonly  wire:model.defer="fec_emi"  id="fec_emi"  name="fec_emi" type="date" class="text-center form-control-sm  {{ $errors->has('fec_emi') ? 'is-invalid' : '' }}"  />
                <div class="invalid-feedback">
                    @error('fec_emi') {{ $message }} @enderror
                </div>
            </x-field>
             <x-field class="text-center col-2">
                <x-label for="provision">{{ __('Provisión') }}</x-label>
                <x-select  style="pointer-events:none" readonly wire:model.defer="provision" id="provision" name="provision" class="form-control-sm {{ $errors->has('provision') ? 'is-invalid' : '' }}">
                    <option value={{ $actprovision=='nuevo'?'N':'S'}}>{{ $actprovision=='nuevo'?'No':'Si'}}</option>
               </x-select>
                <div class="invalid-feedback">
                    @error('provision') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="sta_sol">Estado</x-label>
                     <x-select style="pointer-events:none" readonly  wire:model.defer="sta_sol" id="sta_sol" name="sta_sol" class="form-control-sm {{ $errors->has('sta_sol') ? 'is-invalid' : '' }}">
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\OtrosPAgos\EstadoCertificacion::cases() as $estado)
                            <option value="{{ $estado->value }}" @selected(old('sta_sol', $sta_sol) === $estado->value) >
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
                <x-label for="lcod_ger">Beneficiario</x-label>
                   <x-select  wire:model.defer="rif_prov"  id="rif_prov" name="rif_prov"  class="form-control select2bs4 text-center form-control-sm {{ $errors->has('rif_prov') ? 'is-invalid' : '' }}">
                    <option value="">-- Seleccione Beneficiario --</option>
                    @foreach ($this->Beneficiario as $index => $beneficiario)
                        <option value="{{ $index }}"  @selected(old('rif_prov', $rif_prov) === $index) >
                            {{ ($beneficiario) }}
                        </option>
                    @endforeach
               </x-select>
                <div class="invalid-feedback">
                    @error('rif_prov') {{ $message }} @enderror
                </div>
            </div>
            <div wire:ignore class="text-center  form-group col-6">
                <x-label for="lcod_ger">Gerencia</x-label>
                   <x-select  wire:model.defer="cod_ger"  id="cod_ger" name="cod_ger"  class="form-control select2bs4 text-center form-control-sm {{ $errors->has('cod_ger') ? 'is-invalid' : '' }}">
                    <option value="">-- Seleccione Gerencia --</option>
                    @foreach ($this->Gerencia as $index => $Gerencia)
                        <option value="{{ $index }}"  @selected(old('cod_ger', $cod_ger) === $index) >
                            {{ ($Gerencia) }}
                        </option>
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
                <x-input   wire:model.defer="pto_cta" id="pto_cta" name="pto_cta" class="form-control-sm {{ $errors->has('pto_cta') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('pto_cta') {{ $message }} @enderror
                </div>
                </x-field>
            <x-field class="text-center col-2 ">
                <x-label for="lfec_pto">Fecha del Punto</x-label>
                 <x-input  wire:model.defer="fec_pto" id="fec_pto" name="fec_pto" type="date" class="form-control-sm {{ $errors->has('fec_pto') ? 'is-invalid' : '' }}"/>
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
                <x-input   wire:model.defer="por_anticipo" wire:keydown.tab.prevent="calcularAnticipo('#identificacion-tab')" x-mask:dynamic="$money($input, ',')" id="por_anticipo" name="por_anticipo"  class="form-control-sm text-right {{ $errors->has('por_anticipo') ? 'is-invalid' : '' }}" />
                    <div class="invalid-feedback">
                        @error('por_anticipo') {{ $message }} @enderror
                    </div>
            </x-field>
            <x-field class="text-center col-2 ">
                <x-label for="mto_ant">Monto Anticipo</x-label>
                <x-input  wire:model.defer="mto_ant" readonly  x-mask:dynamic="$money($input, ',')" id="mto_ant" name="mto_ant"  class="form-control-sm text-right {{ $errors->has('mto_ant') ? 'is-invalid' : '' }}" />
                    <div class="invalid-feedback">
                        @error('mto_ant') {{ $message }} @enderror
                    </div>
            </x-field>
        </div>

        <div class="col-12">
            <h5 class="card-title text-secondary text-bold">Datos de La Certificación</h5>
        </div>
        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
        <div class="row col-12">
            <x-field class="text-center col-2 offset-2">
                <x-label for="grupo">Gupo</x-label>
                <x-select style="pointer-events:none"  readonly wire:model.defer="grupo" id="grupo"  name="grupo" class="form-control-sm {{ $errors->has('grupo') ? 'is-invalid' : '' }}">
                    <option value="PD">Certificación</option>
                </x-select>
                <div class="invalid-feedback">
                    @error('grupo') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2 -bottom-3">
                <x-label for="num_contrato">Nro. Contrato</x-label>
                <x-input readonly wire:model.defer="num_contrato" id="num_contrato" name="num_contrato"  class="form-control-sm {{ $errors->has('num_contrato') ? 'is-invalid' : '' }}" />
                <div class="invalid-feedback">
                    @error('num_contrato') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="tip_pag">Tipo de Pago</x-label>
                <x-select    wire:model.defer="tip_pag" id="tip_pag"  name="tip_pag" class="form-control-sm {{ $errors->has('tip_pag') ? 'is-invalid' : '' }}">
                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\TipoPago::cases() as $estado)
                        <option value="{{ $estado->value }}"@selected(old('tip_pag', $tip_pag) === $estado->value) >
                            {{ $estado->name }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('tip_pag') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="factura">Tiene Factura</x-label>
                <x-select style="{{ $actprovision =='provision' ? 'pointer-events: none' : '' }}"   wire:model.defer="factura" wire:change="limpiar()"  id="factura" name="factura" class="form-control-sm {{ $errors->has('factura') ? 'is-invalid' : '' }}">
                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\Factura::cases() as $estado)
                        <option value="{{ $estado->value }}"  @selected(old('factura',$factura ) === $estado->value)  >
                            {{ $estado->name }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('factura') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2 offset-2">
                <x-label for="tip_contrat">Tipo de Certificación</x-label>
                <x-select  id="tip_contrat" name="tip_contrat" wire:model.defer="tip_contrat" class="form-control-sm {{ $errors->has('tip_contrat') ? 'is-invalid' : '' }}">
                <option value="N">Normal</option>
                <option value="S">Servicio</option tion>
                </x-select>
                <div class="invalid-feedback">
                    @error('tip_contrat') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-4 ">
                <x-label for="lugar_serv">Lugar del Servicio</x-label>
                <x-input  name="lugar_serv" id="lugar_serv" wire:model.defer="lugar_serv" class="form-control-sm {{ $errors->has('lugar_serv') ? 'is-invalid' : '' }}"  />
                <div class="invalid-feedback">
                    @error('lugar_serv') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="text-center col-2">
                <x-label for="fec_serv">Fecha del Servicio</x-label>
                 <x-input   id="fec_serv" name="fec_serv" wire:model.defer="fec_serv" type="date" class="text-center form-control-sm {{ $errors->has('fec_serv') ? 'is-invalid' : '' }}" />
                    <div class="invalid-feedback">
                        @error('fec_serv') {{ $message }} @enderror
                    </div>
            </x-field>
        </div>


