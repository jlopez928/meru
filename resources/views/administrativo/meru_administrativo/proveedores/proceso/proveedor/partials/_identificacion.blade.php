<x-card class="card-secondary col-12 mt-3">
    {{--  <x-slot:header>
        <h3 class="card-title text-bold" >Identificación de la Empresa</h3>
    </x-slot>  --}}
    <x-slot:body>
            <h5 class="text-bold" >Identificación de la Empresa</h5>
            <hr>

            <div class="row d-flex justify-content-between">
                <x-field class="col-4">
                    <x-label for="tip_emp">Tipo</x-label>
                    <x-select x-model="tip_emp" name="tip_emp" class="form-control-sm ml-1 {{ $errors->has('tip_emp') ? 'is-invalid' : 'is-valid' }}">
                        <option value="">Seleccione...</option>
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\Proveedores\TipoEmpresa::cases() as $tipoEmpresa)
                            <option 
                                value="{{ $tipoEmpresa->value }}" 
                            >
                                ({{ $tipoEmpresa->value }}) {{ $tipoEmpresa->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('tip_emp') {{ $message }} @enderror
                    </div>
                </x-field>
                    
                <x-field class="col-3">
                    <x-label for="rif_prov">R.I.F.</x-label>
                    <div x-data>
                        <x-input 
                            style="text-transform: uppercase" 
                            class="form-control-sm {{ $errors->has('rif_prov') ? 'is-invalid' : 'is-valid' }}" 
                            name="rif_prov" 
                            x-mask="a-99999999-9" 
                            placeholder="J-99999999-9"
                            value="{{ old('rif_prov', $proveedor->rif_prov) }}"
                        />
                        <div class="invalid-feedback">
                            @error('rif_prov') {{ $message }} @enderror
                        </div>
                    </div>
                </x-field>
            </div>
            
            <div class="row d-flex justify-content-between">
                <x-field class="col-3">
                    <x-label for="cod_prov">Código Prov.</x-label>
                    <x-input 
                        class="form-control-sm {{ $errors->has('cod_prov') ? 'is-invalid' : 'is-valid' }}" 
                        name="cod_prov" 
                        value="{{ $proveedor->cod_prov }}"
                        disabled
                    />
                </x-field>

                <x-field class="col-3">
                    <x-label for="tip_reg">Registro</x-label>
                    <x-select x-model="tip_reg" name="tip_reg" class="form-control-sm {{ $errors->has('tip_reg') ? 'is-invalid' : 'is-valid' }}">
                        <option value="">Seleccione...</option>
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\Proveedores\RegistroProveedor::cases() as $registroProveedor)
                            <option value="{{ $registroProveedor->value }}">
                                {{ $registroProveedor->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('tip_reg') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="row d-flex justify-content-between">
                <x-field class="col-4">
                    <x-label for="nom_prov">Nombre</x-label>
                    <x-input 
                        style="text-transform: uppercase" 
                        class="form-control-sm {{ $errors->has('nom_prov') ? 'is-invalid' : 'is-valid' }}" 
                        name="nom_prov" 
                        value="{{ old('nom_prov', $proveedor->nom_prov) }}"
                        maxlength="90"
                    />
                    <div class="invalid-feedback">
                        @error('nom_prov') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-3">
                    <x-label for="sig_prov">Siglas</x-label>
                    <x-input 
                        style="text-transform: uppercase" 
                        class="form-control-sm {{ $errors->has('sig_prov') ? 'is-invalid' : 'is-valid' }}" 
                        name="sig_prov" 
                        value="{{ old('sig_prov', $proveedor->sig_prov) }}"
                        maxlength="30"
                    />
                    <div class="invalid-feedback">
                        @error('sig_prov') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
           
            <div class="row d-flex justify-content-between">
                <x-field class="col-4">
                    <x-label for="email">Correo Electrónico</x-label>
                    <x-input 
                        class="form-control-sm {{ $errors->has('email') ? 'is-invalid' : 'is-valid' }}" 
                        name="email" 
                        value="{{ old('email', $proveedor->email) }}"
                        maxlength="100"
                    />
                    <div class="invalid-feedback">
                        @error('email') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-5">
                    <x-label for="dir_prov">Dirección</x-label>
                    <textarea 
                        style="text-transform: uppercase" 
                        class="form-control {{ $errors->has('dir_prov') ? 'is-invalid' : 'is-valid' }}" 
                        name="dir_prov" 
                        maxlength="200"
                        cols="40"
                        rows="2"
                    >{{ old('dir_prov', $proveedor->dir_prov) }}</textarea>
                    <div class="invalid-feedback">
                        @error('dir_prov') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
          
            <div class="row d-flex justify-content-between">
                <x-field class="col-2">
                    <x-label for="tlf_prov1">Teléfono 1</x-label>
                    <div x-data>
                        <x-input 
                            class="form-control-sm {{ $errors->has('tlf_prov1') ? 'is-invalid' : 'is-valid' }}" 
                            name="tlf_prov1" 
                            x-mask="9999-9999999"
                            value="{{ old('tlf_prov1', $proveedor->tlf_prov1) }}"
                        />
                        <div class="invalid-feedback">
                            @error('tlf_prov1') {{ $message }} @enderror
                        </div>
                    </div>
                </x-field>

                <x-field class="col-3">
                    <x-label for="tlf_prov2">Teléfono 2</x-label>
                    <div x-data>
                        <x-input 
                            class="form-control-sm {{ $errors->has('tlf_prov2') ? 'is-invalid' : 'is-valid' }}" 
                            name="tlf_prov2" 
                            x-mask="9999-9999999"
                            value="{{ old('tlf_prov2', $proveedor->tlf_prov2) }}"
                        />
                        <div class="invalid-feedback">
                            @error('tlf_prov2') {{ $message }} @enderror
                        </div>
                    </div>
                </x-field>
            </div>

            <div class="row d-flex justify-content-between">
                <x-field class="col-2">
                    <x-label for="fax">Fax</x-label>
                    <div x-data>
                        <x-input 
                            class="form-control-sm {{ $errors->has('fax') ? 'is-invalid' : 'is-valid' }}" 
                            name="fax" 
                            x-mask="9999-9999999"
                            value="{{ old('fax', $proveedor->fax) }}"
                        />
                        <div class="invalid-feedback">
                            @error('fax') {{ $message }} @enderror
                        </div>
                    </div>
                </x-field>

                <x-field class="col-3">
                    <x-label for="sta_emp">Clasificación</x-label>
                    <x-select x-model="sta_emp" name="sta_emp" class="form-control-sm {{ $errors->has('sta_emp') ? 'is-invalid' : 'is-valid' }}">
                        <option value="">Seleccione...</option>
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\Proveedores\ClasificacionProveedor::cases() as $clasificacionProveedor)
                            {{--  <option value="{{ $clasificacionProveedor->value }}" @selected(old('sta_emp', $proveedor->sta_emp?->value) === $clasificacionProveedor->value) >  --}}
                            <option value="{{ $clasificacionProveedor->value }}">
                                {{ $clasificacionProveedor->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('sta_emp') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
           
            <div class="row">
                <x-field class="col-3">
                    <x-label for="cuenta_hid">Cuenta Hidrológica</x-label>
                    <div x-data>
                        <x-input 
                            name="cuenta_hid" 
                            class="form-control-sm {{ $errors->has('cuenta_hid') ? 'is-invalid' : 'is-valid' }}" 
                            style="text-transform: uppercase" 
                            value="{{ old('cuenta_hid', $proveedor->cuenta_hid) }}"
                            maxlength="25"
                        />
                        <div class="invalid-feedback">
                            @error('cuenta_hid') {{ $message }} @enderror
                        </div>
                    </div>
                </x-field>
            </div>

            <div class="row">
                <x-field class="col-3">
                    <x-label for="sta_con">Estado</x-label>
                    <x-select x-model="sta_con" name="sta_con" class="form-control-sm" disabled>
                        <option value="">Seleccione...</option>
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\Proveedores\EstadoProveedor::cases() as $estadoProveedor)
                            <option value="{{ $estadoProveedor->value }}">
                                {{ $estadoProveedor->name }}
                            </option>
                        @endforeach
                    </x-select>
                </x-field>
            </div>
    </x-slot>
</x-card>