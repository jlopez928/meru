<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>

            <div class="row d-flex justify-content-between">
                <x-field class="col-3">
                    <x-label for="inscrito_rnc">Inscrito R.N.C.</x-label>
                    <x-select name="inscrito_rnc" class="form-control-sm {{ $errors->has('inscrito_rnc') ? 'is-invalid' : 'is-valid' }}">
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
                        class="form-control-sm {{ $errors->has('nro_rnc') ? 'is-invalid' : 'is-valid' }}"
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
                        class="form-control-sm {{ $errors->has('fec_susp') ? 'is-invalid' : 'is-valid' }}"
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
                    <x-label for="nro_sunacoop">Nro. SUNACOOP</x-label>
                    <x-input
                        class="form-control-sm {{ $errors->has('nro_sunacoop') ? 'is-invalid' : 'is-valid' }}"
                        name="nro_sunacoop"
                        value="{{ old('nro_sunacoop', $proveedor->nro_sunacoop) }}"
                        maxlength="30"
                        x-bind:readonly="tip_emp !== 'P'"
                    />
                    <div class="invalid-feedback">
                        @error('nro_sunacoop') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <div class="row d-flex justify-content-between">
                <x-field class="col-6">
                    <x-label for="objetivo">Actividad Comercial</x-label>
                    <textarea
                        style="text-transform: uppercase"
                        class="form-control {{ $errors->has('objetivo') ? 'is-invalid' : 'is-valid' }}"
                        name="objetivo"
                        maxlength="500"
                        cols="50"
                        rows="5"
                    >{{ old('objetivo', $proveedor->objetivo) }}</textarea>
                    <div class="invalid-feedback">
                        @error('objetivo') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-6">
                    <x-label for="objetivo_gral">Objetivo General</x-label>
                    <textarea
                        style="text-transform: uppercase"
                        class="form-control {{ $errors->has('objetivo_gral') ? 'is-invalid' : 'is-valid' }}"
                        name="objetivo_gral"
                        maxlength="300"
                        cols="50"
                        rows="5"
                    >{{ old('objetivo_gral', $proveedor->objetivo_gral) }}</textarea>
                    <div class="invalid-feedback">
                        @error('objetivo_gral') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <hr>
            <h5 class="text-bold">Representante</h5>
            <hr>

            <div class="row d-flex justify-content-between">
                <x-field class="col-2">
                    <x-label for="ced_res">Cédula</x-label>
                    <div x-data>
                        <x-input
                            class="form-control-sm {{ $errors->has('ced_res') ? 'is-invalid' : 'is-valid' }}"
                            name="ced_res"
                            x-mask="99999999"
                            value="{{ old('ced_res', $proveedor->ced_res) }}"
                        />
                        <div class="invalid-feedback">
                            @error('ced_res') {{ $message }} @enderror
                        </div>
                    </div>
                </x-field>
                <x-field class="col-4">
                    <x-label for="nom_res">Nombre</x-label>
                    <x-input
                        style="text-transform: uppercase"
                        class="form-control-sm {{ $errors->has('nom_res') ? 'is-invalid' : 'is-valid' }}"
                        name="nom_res"
                        value="{{ old('nom_res', $proveedor->nom_res) }}"
                    />
                    <div class="invalid-feedback">
                        @error('nom_res') {{ $message }} @enderror
                    </div>
                </x-field>
                <x-field class="col-4">
                    <x-label for="car_res">Cargo</x-label>
                    <x-input
                        style="text-transform: uppercase"
                        class="form-control-sm {{ $errors->has('car_res') ? 'is-invalid' : 'is-valid' }}"
                        name="car_res"
                        value="{{ old('car_res', $proveedor->car_res) }}"
                    />
                    <div class="invalid-feedback">
                        @error('car_res') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>

            <hr>
            <h5 class="text-bold">Ubicación</h5>
            <hr>

            <div class="row d-flex justify-content-between" x-data="otros()">
                <x-field class="col-3">
                    <x-label for="ubi_pro">Ubicación</x-label>
                    <x-select 
                        name="ubi_pro" 
                        class="form-control-sm {{ $errors->has('ubi_pro') ? 'is-invalid' : 'is-valid' }}"
                        x-model="selectedUbicacion"
                        x-on:change="updateUbicacion"
                    >
                        <option value="">Seleccione...</option>
                        @foreach (\App\Enums\Administrativo\Meru_Administrativo\Proveedores\UbicacionProveedor::cases() as $ubicacionProveedor)
                            <option value="{{ $ubicacionProveedor->value }}" @selected(old('ubi_pro', $proveedor->ubi_pro?->value) === $ubicacionProveedor->value) >
                                {{ $ubicacionProveedor->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <div class="invalid-feedback">
                        @error('ubi_pro') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-3">
                    <x-label for="selectedEstado">Estado</x-label>
                    <x-select
                        x-model="selectedEstado"
                        x-on:change="updateEstado"
                        name="cod_edo"
                        class="form-control-sm {{ $errors->has('cod_edo') ? 'is-invalid' : 'is-valid' }}"
                    >
                        <option value="">Seleccione</option>
                        <template x-for="estado in estados">
                            <option
                                :key="estado.cod_edo"
                                :value="estado.cod_edo"
                                x-text="estado.des_ubi"
                                x-bind:selected="estado.cod_edo === selectedEstado">
                            </option>
                        </template>
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cod_edo') {{ $message }} @enderror
                    </div>
                </x-field>

                <x-field class="col-4">
                    <x-label for="selectedMunicipio">Municipios</x-label>
                    <x-select
                        x-model="selectedMunicipio"
                        name="cod_mun"
                        class="form-control-sm {{ $errors->has('cod_mun') ? 'is-invalid' : 'is-valid' }}"
                    >
                        <option value="">Seleccione</option>
                        <template x-for="municipio in municipios">
                            <option
                                :key="municipio.cod_mun"
                                :value="municipio.cod_mun"
                                x-text="municipio.des_ubi"
                                x-bind:selected="municipio.cod_mun === selectedMunicipio"
                                >
                            </option>
                        </template>
                    </x-select>
                    <div class="invalid-feedback">
                        @error('cod_mun') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
    </x-slot>
</x-card>

@push('scripts')
    <script>
        function otros(){
            return {
                selectedEstado:'',
                selectedUbicacion:'',
                estados: [],
                selectedMunicipio:'',
                municipios: [],

                getMunicipios(){
                    this.selectedMunicipio = ''
                    fetch('{{ env('APP_URL') }}/api/proveedores/municipios/'+ this.selectedEstado)
                    .then(response => response.json())
                    .then(data => {
                        this.municipios = data
                    })
                },
                getEstados(){
                    this.selectedEstado = ''
                    fetch('{{ env('APP_URL') }}/api/proveedores/estados/'+ this.selectedUbicacion)
                    .then(response => response.json())
                    .then(data => {
                        this.estados = data
                    })
                },
                updateEstado(){
                    this.getMunicipios()
                },
                updateUbicacion(){
                    this.selectedMunicipio = ''
                    this.getEstados()
                }
            }
        }
    </script>
@endpush
