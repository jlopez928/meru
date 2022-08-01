<x-card class="card-secondary col-12 mt-3">
    <x-slot:header>
        <h3 class="card-title text-bold" >Identificación de la Empresa</h3>
    </x-slot>
    <x-slot:body>
            <div class="row d-flex justify-content-between">
                <x-field class="col-4">
                    <x-label for="tip_emp">Tipo</x-label>
                    <div class="d-flex" x-data="{ tip_emp: '{{ old('tip_emp', $proveedor->tip_emp?->value) }}' }">
                        <x-input 
                            class="col-1" 
                            x-bind:value="tip_emp ? tip_emp : ''"
                            disabled
                        />
                        <div>
                            <x-select x-model="tip_emp" name="tip_emp" class="ml-1 {{ $errors->has('tip_emp') ? 'is-invalid' : 'is-valid' }}">
                                <option value="">Seleccione...</option>
                                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Proveedores\TipoEmpresa::cases() as $tipoEmpresa)
                                    <option 
                                        value="{{ $tipoEmpresa->value }}" 
                                    >
                                        {{ $tipoEmpresa->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            <div class="invalid-feedback">
                                @error('tip_emp') {{ $message }} @enderror
                            </div>
                        </div>
                    </div>
                </x-field>
                    
                <x-field class="col-3">
                    <x-label for="rif_prov">R.I.F.</x-label>
                    <div x-data>
                        <x-input 
                            style="text-transform: uppercase" 
                            class="{{ $errors->has('rif_prov') ? 'is-invalid' : 'is-valid' }}" 
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
                        class="{{ $errors->has('cod_prov') ? 'is-invalid' : 'is-valid' }}" 
                        name="cod_prov" 
                        value="{{ $proveedor->cod_prov }}"
                        disabled
                    />
                </x-field>

                <x-field class="col-3">
                    <x-label for="tip_reg">Registro</x-label>
                    <x-select name="tip_reg" class="{{ $errors->has('tip_reg') ? 'is-invalid' : 'is-valid' }}">
                        <option value="">Seleccione...</option>
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\Proveedores\RegistroProveedor::cases() as $registroProveedor)
                            <option value="{{ $registroProveedor->value }}" @selected(old('tip_reg', $proveedor->tip_reg?->value) === $registroProveedor->value) >
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
                <x-field class="col-3">
                    <x-label for="nom_prov">Nombre</x-label>
                    <x-input 
                        style="text-transform: uppercase" 
                        class="{{ $errors->has('nom_prov') ? 'is-invalid' : 'is-valid' }}" 
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
                        class="{{ $errors->has('sig_prov') ? 'is-invalid' : 'is-valid' }}" 
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
                <x-field class="col-3">
                    <x-label for="email">Correo Electrónico</x-label>
                    <x-input 
                        class="{{ $errors->has('email') ? 'is-invalid' : 'is-valid' }}" 
                        name="email" 
                        value="{{ old('email', $proveedor->email) }}"
                        maxlength="100"
                    />
                    <div class="invalid-feedback">
                        @error('email') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-3">
                    <x-label for="dir_prov">Dirección</x-label>
                    <x-input 
                        style="text-transform: uppercase" 
                        class="{{ $errors->has('dir_prov') ? 'is-invalid' : 'is-valid' }}" 
                        name="dir_prov" 
                        value="{{ old('dir_prov', $proveedor->dir_prov) }}"
                        maxlength="200"
                    />
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
                            class="{{ $errors->has('tlf_prov1') ? 'is-invalid' : 'is-valid' }}" 
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
                            class="{{ $errors->has('tlf_prov2') ? 'is-invalid' : 'is-valid' }}" 
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
                            class="{{ $errors->has('fax') ? 'is-invalid' : 'is-valid' }}" 
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
                    <x-select name="sta_emp" class="{{ $errors->has('sta_emp') ? 'is-invalid' : 'is-valid' }}">
                        <option value="">Seleccione...</option>
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\Proveedores\ClasificacionProveedor::cases() as $clasificacionProveedor)
                            <option value="{{ $clasificacionProveedor->value }}" @selected(old('sta_emp', $proveedor->sta_emp?->value) === $clasificacionProveedor->value) >
                                {{ $clasificacionProveedor->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('sta_emp') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
           
            <div class="row d-flex justify-content-between">
                <x-field class="col-3">
                    <x-label for="cuenta_hid">Cuenta Hidrológica</x-label>
                    <div x-data>
                        <x-input 
                            class="{{ $errors->has('cuenta_hid') ? 'is-invalid' : 'is-valid' }}" 
                            name="cuenta_hid" 
                            x-mask="9999999999999999999999999"
                            value="{{ old('cuenta_hid', $proveedor->cuenta_hid) }}"
                        />
                        <div class="invalid-feedback">
                            @error('cuenta_hid') {{ $message }} @enderror
                        </div>
                    </div>
                </x-field>
            </div>

    </x-slot>
</x-card>