<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>

        <div class="row d-flex justify-content-between">
            <x-field class="col-3">
                <x-label for="capital">Capital</x-label>
                <x-input
                    class="form-control-sm {{ $errors->has('capital') ? 'is-invalid' : 'is-valid' }}"
                    id="decimal-input"
                    name="capital"
                    value="{{ old('capital', $proveedor->capital) }}"
                    {{--  maxlength="23"  --}}
                    maxlength="11"
                />
                <div class="invalid-feedback">
                    @error('capital') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-3">
                <x-label for="nivel_cont">Nivel de Contratación</x-label>
                <x-input
                    name="nivel_cont"
                    class="form-control-sm {{ $errors->has('nivel_cont') ? 'is-invalid' : 'is-valid' }}"
                    style="text-transform: uppercase"
                    value="{{ old('nivel_cont', $proveedor->nivel_cont) }}"
                    maxlength="10"
                />
                <div class="invalid-feedback">
                    @error('nivel_cont') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="row d-flex justify-content-between">
            <x-field class="col-3">
                <x-label for="num_fem">Nro. Mujeres</x-label>
                <x-input
                    data-inputmask="'mask': '9', 'repeat': 5"
                    class="form-control-sm {{ $errors->has('num_fem') ? 'is-invalid' : 'is-valid' }}"
                    style="position: rtl"
                    name="num_fem"
                    value="{{ old('num_fem', $proveedor->num_fem ?? 0) }}"
                    dir="rtl"
                />
                <div class="invalid-feedback">
                    @error('num_fem') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-3">
                <x-label for="num_mas">Nro. Hombres</x-label>
                <x-input
                    data-inputmask="'mask': '9', 'repeat': 5"
                    class="form-control-sm {{ $errors->has('num_mas') ? 'is-invalid' : 'is-valid' }}"
                    name="num_mas"
                    value="{{ old('num_mas', $proveedor->num_mas ?? 0) }}"
                    dir="rtl"
                />
                <div class="invalid-feedback">
                    @error('num_mas') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <hr>
        <h5 class="text-bold">Solvencias</h5>
        <hr>

        <div class="row col-12 d-flex justify-content-between">
            <div class="col-6 d-flex justify-content-between">
                <x-field class="col-6">
                    <x-label for="sol_ivss">I.V.S.S</x-label>
                    <x-input
                        class="form-control-sm {{ $errors->has('sol_ivss') ? 'is-invalid' : 'is-valid' }}"
                        style="text-transform: uppercase"
                        name="sol_ivss"
                        value="{{ old('sol_ivss', $proveedor->sol_ivss) }}"
                        maxlength="10"
                    />
                    <div class="invalid-feedback">
                        @error('sol_ivss') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-6">
                    <x-label for="fec_ivss">Fecha Emisión</x-label>
                    <x-input
                        type="date"
                        name="fec_ivss"
                        class="form-control-sm {{ $errors->has('fec_ivss') ? 'is-invalid' : 'is-valid' }}"
                        value="{{ old('fec_ivss', $proveedor->fec_ivss) }}"
                    />
                    <div class="invalid-feedback">
                        @error('fec_ivss') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="col-6 d-flex justify-content-between">
                <x-field class="col-6">
                    <x-label for="sol_ince">I.N.C.E</x-label>
                    <x-input
                        class="form-control-sm {{ $errors->has('sol_ince') ? 'is-invalid' : 'is-valid' }}"
                        style="text-transform: uppercase"
                        name="sol_ince"
                        value="{{ old('sol_ince', $proveedor->sol_ince) }}"
                        maxlength="10"
                    />
                    <div class="invalid-feedback">
                        @error('sol_ince') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-6">
                    <x-label for="fec_ince">Fecha Emisión</x-label>
                    <x-input
                        type="date"
                        name="fec_ince"
                        class="form-control-sm {{ $errors->has('fec_ince') ? 'is-invalid' : 'is-valid' }}"
                        value="{{ old('fec_ince', $proveedor->fec_ince) }}"
                    />
                    <div class="invalid-feedback">
                        @error('fec_ince') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
        </div>

        <div class="row col-12 d-flex justify-content-between">
            <div class="col-6 d-flex justify-content-between">
                <x-field class="col-6">
                    <x-label for="sol_laboral">Laboral</x-label>
                    <x-input
                        class="form-control-sm {{ $errors->has('sol_laboral') ? 'is-invalid' : 'is-valid' }}"
                        style="text-transform: uppercase"
                        name="sol_laboral"
                        value="{{ old('sol_laboral', $proveedor->sol_laboral) }}"
                        maxlength="10"
                    />
                    <div class="invalid-feedback">
                        @error('sol_laboral') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-6">
                    <x-label for="fec_laboral">Fecha Emisión</x-label>
                    <x-input
                        type="date"
                        name="fec_laboral"
                        class="form-control-sm {{ $errors->has('fec_laboral') ? 'is-invalid' : 'is-valid' }}"
                        value="{{ old('fec_laboral', $proveedor->fec_laboral) }}"
                    />
                    <div class="invalid-feedback">
                        @error('fec_laboral') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="col-6 d-flex justify-content-between">
                <x-field class="col-6">
                    <x-label for="sol_agua">Agua</x-label>
                    <x-input
                        class="form-control-sm {{ $errors->has('sol_agua') ? 'is-invalid' : 'is-valid' }}"
                        style="text-transform: uppercase"
                        name="sol_agua"
                        value="{{ old('sol_agua', $proveedor->sol_agua) }}"
                        maxlength="10"
                    />
                    <div class="invalid-feedback">
                        @error('sol_agua') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-6">
                    <x-label for="fec_agua">Fecha Emisión</x-label>
                    <x-input
                        type="date"
                        name="fec_agua"
                        class="form-control-sm {{ $errors->has('fec_agua') ? 'is-invalid' : 'is-valid' }}"
                        value="{{ old('fec_agua', $proveedor->fec_agua) }}"
                    />
                    <div class="invalid-feedback">
                        @error('fec_agua') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
        </div>

        <hr>
        <h5 class="text-bold">Acumulados</h5>
        <hr>

        <div class="row d-flex justify-content-between">
            <x-field class="col-3">
                <x-label for="num_con">Nro. Contratos</x-label>
                <x-input
                    class="form-control-sm"
                    name="num_con"
                    value="{{ $proveedor->num_con ?? ''}}"
                    readonly
                />
            </x-field>
            <x-field class="col-3">
                <x-label for="mon_acu">Monto Contratos</x-label>
                <x-input
                    class="form-control-sm"
                    name="mon_acu"
                    value="{{ $proveedor->mon_acu ?? ''}}"
                    readonly
                />
            </x-field>
            <x-field class="col-3">
                <x-label for="nro_oc">Nro. OC</x-label>
                <x-input
                    class="form-control-sm"
                    name="nro_oc"
                    value="{{ $proveedor->nro_oc ?? ''}}"
                    readonly
                />
            </x-field>
            <x-field class="col-3">
                <x-label for="mon_oc">Monto OC</x-label>
                <x-input
                    class="form-control-sm"
                    name="mon_oc"
                    value="{{ $proveedor->mon_oc ?? ''}}"
                    readonly
                />
            </x-field>
        </div>
    </x-slot>
</x-card>

@push('scripts')
    <script>
        $('#decimal-input').inputmask({
            alias: 'decimal',
            integerDigits: 5,
            digits: 2,
            numericInput: true,
            radixPoint: ',',
            placeholder: '0,00',
            defaultValue: '0,00',
            removeMaskOnSubmit: true
        });
    </script>
@endpush
