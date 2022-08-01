<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>
        
            <div class="row d-flex justify-content-between">
                <x-field class="col-3">
                    <x-label for="inscrito_rnc">Inscrito R.N.C.</x-label>
                    <x-select name="inscrito_rnc" class="{{ $errors->has('inscrito_rnc') ? 'is-invalid' : 'is-valid' }}">
                        <option value="0" @selected(old('inscrito_rnc', $proveedor->inscrito_rnc) === '0')>
                            No
                        </option>
                        <option value="1" @selected(old('inscrito_rnc', $proveedor->inscrito_rnc) === '1')>
                            Si
                        </option>
                    </x-select>
                    <div class="invalid-feedback">
                        @error('inscrito_rnc') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-3">
                    <x-label for="nro_rnc">Número R.N.C.</x-label>
                    <x-input 
                        class="{{ $errors->has('nro_rnc') ? 'is-invalid' : 'is-valid' }}" 
                        name="nro_rnc" 
                        value="{{ old('nro_rnc', $proveedor->nro_rnc) }}"
                        maxlength="40"
                    />
                    <div class="invalid-feedback">
                        @error('nro_rnc') {{ $message }} @enderror
                    </div>
                </x-field>
                
                <x-field class="col-3">
                    <x-label for="fec_susp">Fecha Vencimiento R.N.C.</x-label>
                    <x-input 
                        type="date"
                        class="{{ $errors->has('fec_susp') ? 'is-invalid' : 'is-valid' }}" 
                        name="fec_susp" 
                        value="{{ old('fec_susp', $proveedor->fec_susp) }}"
                    />
                    <div class="invalid-feedback">
                        @error('fec_susp') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
            
            <div class="row">
                <x-field class="col-4">
                    <x-label for="nro_sunacoop">Código SUNACOOP</x-label>
                    <x-input 
                        class="{{ $errors->has('nro_sunacoop') ? 'is-invalid' : 'is-valid' }}" 
                        name="nro_sunacoop" 
                        value="{{ old('nro_sunacoop', $proveedor->nro_sunacoop) }}"
                        maxlength="30"
                        {{--  @readonly($user->isNotAdmin())  --}}
                    />
                    <div class="invalid-feedback">
                        @error('nro_sunacoop') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
    </x-slot>
</x-card>