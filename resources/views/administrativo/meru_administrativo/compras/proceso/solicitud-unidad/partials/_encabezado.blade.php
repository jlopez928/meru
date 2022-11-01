<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>
        <h5 class="text-bold" >Identificación de la Solicitud</h5>
        <hr>

        <div class="row d-flex justify-content-between">
            <x-field class="col-2">
                <x-label for="ano_pro">Año</x-label>
                <x-select
                    name="ano_pro"
                    class="form-control-sm ml-1 {{ $errors->has('ano_pro') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-model="ano_pro"
                    x-bind:readonly="{{ $accion !== 'editar' }}"
                >
                    <option value="">Seleccione...</option>
                    @foreach ( $years as $year)
                        <option
                            value="{{ $year }}"
                        >
                            {{ $year }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('ano_pro') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-3">
                <x-label for="grupo">Grupo</x-label>
                <x-select
                    name="grupo"
                    class="form-control-sm {{ $errors->has('grupo') ? 'is-invalid' : 'is-valid' }}"
                    x-model="grupo"
                    x-on:change="updateGrupo"
                    style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione...</option>
                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\GrupoSolicitud::cases() as $grupoSolicitud)
                        <option value="{{ $grupoSolicitud->value }}">
                            ({{ $grupoSolicitud->value }}) {{ $grupoSolicitud->name }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('grupo') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-3">
                <x-label for="cla_sol">Clase</x-label>
                <x-select
                    name="cla_sol"
                    class="form-control-sm {{ $errors->has('cla_sol') ? 'is-invalid' : 'is-valid' }}"
                    x-model="cla_sol"
                    style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione...</option>
                    <template x-for="clase in clases">
                        <option
                            :key="clase.cod_cla"
                            :value="clase.cod_cla"
                            x-text="clase.des_cla"
                            x-bind:selected="clase.cod_cla === cla_sol">
                        </option>
                    </template>
                </x-select>
                <div class="invalid-feedback">
                    @error('cla_sol') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-2">
                <x-label for="nro_req">Solicitud</x-label>
                <x-input
                    class="form-control-sm"
                    name="nro_req"
                    x-model="nro_req"
                    readonly
                />
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-8">
                <x-label for="jus_sol">Justificación</x-label>
                <textarea
                    style="text-transform: uppercase"
                    title="Indique la Justificación de la Solicitud"
                    class="form-control {{ $errors->has('jus_sol') ? 'is-invalid' : 'is-valid' }}"
                    name="jus_sol"
                    maxlength="1000"
                    cols="50"
                    rows="5"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >{{ old('jus_sol', $solicitudUnidad->jus_sol) }}</textarea>
                <div class="invalid-feedback">
                    @error('jus_sol') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row d-flex justify-content-between">
            <x-field class="col-4">
                <x-label for="fec_emi">Emisión</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_emi') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_emi"
                    value="{{ $accion !== 'nuevo' ? old('fec_emi', $solicitudUnidad->fec_emi) : date('Y-m-d') }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo'"
                />
                <div class="invalid-feedback">
                    @error('fec_emi') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="col-4">
                <x-label for="fec_rec">Rec. Compras</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_rec') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_rec"
                    value="{{ old('fec_rec', $solicitudUnidad->fec_rec) }}"
                    readonly
                />
                <div class="invalid-feedback">
                    @error('fec_rec') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="col-4">
                <x-label for="fec_com_cont">Asig. del Comprador Contrataciones</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_com_cont') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_com_cont"
                    value="{{ old('fec_com_cont', $solicitudUnidad->fec_com_cont) }}"
                    readonly
                />
                <div class="invalid-feedback">
                    @error('fec_com_cont') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row d-flex justify-content-between">
            <x-field class="col-4">
                <x-label for="fec_anu">Anulación</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_anu') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_anu"
                    value="{{ $accion !== 'anular' && $accion !== 'reversar' ? old('fec_anu', $solicitudUnidad->fec_anu) : date('Y-m-d') }}"
                    style="{{ $accion == 'anular' || $accion == 'reversar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'anular' && '{{ $accion }}' !== 'reversar'"
                />
                <div class="invalid-feedback">
                    @error('fec_anu') {{ $message }} @enderror
                </div>

            </x-field>
            <x-field class="col-4">
                <x-label for="fec_rec_cont">Rec. Contrataciones</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_rec_cont') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_rec_cont"
                    value="{{ old('fec_rec_cont', $solicitudUnidad->fec_rec_cont) }}"
                    readonly
                />
                <div class="invalid-feedback">
                    @error('fec_rec_cont') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="col-4">
                <x-label for="fec_dev_com">Dev. Compras</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_dev_com') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_dev_com"
                    value="{{ old('fec_dev_com', $solicitudUnidad->fec_dev_com) }}"
                    readonly
                />
                <div class="invalid-feedback">
                    @error('fec_dev_com') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row d-flex justify-content-between">
            <x-field class="col-4">
                <x-label for="fec_pcom">Aprobar</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_pcom') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_pcom"
                    value="{{ $accion !== 'precomprometer' ? old('fec_pcom', $solicitudUnidad->fec_pcom) : date('Y-m-d') }}"
                    style="{{ $accion == 'precomprometer' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'precomprometer'"
                />
                <div class="invalid-feedback">
                    @error('fec_pcom') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="col-4">
                <x-label for="fec_com">Asig. del Comprador</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_com') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_com"
                    value="{{ old('fec_com', $solicitudUnidad->fec_com) }}"
                    readonly
                />
                <div class="invalid-feedback">
                    @error('fec_com') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="col-4">
                <x-label for="fec_dev_cont">Dev. Contrataciones</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_dev_cont') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_dev_cont"
                    value="{{ old('fec_dev_cont', $solicitudUnidad->fec_dev_cont) }}"
                    readonly
                />
                <div class="invalid-feedback">
                    @error('fec_dev_cont') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-4">
                <x-label for="fec_reasig">Reasignación</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_reasig') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_reasig"
                    value="{{ old('fec_reasig', $solicitudUnidad->fec_reasig) }}"
                    readonly
                />
                <div class="invalid-feedback">
                    @error('fec_reasig') {{ $message }} @enderror
                </div>
            </x-field>
            <x-field class="col-4">
                <x-label for="fec_aut">Conformación Presupuestaria</x-label>
                <x-input
                    type="date"
                    class="form-control-sm {{ $errors->has('fec_aut') ? 'is-invalid' : 'is-valid' }}"
                    name="fec_aut"
                    value="{{ old('fec_aut', $solicitudUnidad->fec_aut) }}"
                    readonly
                />
                <div class="invalid-feedback">
                    @error('fec_aut') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-5">
                <x-label for="gru_ram">Grupo-Ramo</x-label>
                <x-select
                    name="gru_ram"
                    x-model="gru_ram"
                    x-on:change="updateGrupoRamo"
                    class="form-control-sm {{ $errors->has('gru_ram') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione...</option>
                    @foreach ($ramos as $index => $ramo)
                        <option value="{{ $index }}">
                            ({{ $index }}) {{  ($ramo) }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('gru_ram') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-5">
                <x-label for="fk_cod_ger">Gerencia Solicitante</x-label>
                <x-select
                    name="fk_cod_ger"
                    class="form-control-sm {{ $errors->has('fk_cod_ger') ? 'is-invalid' : 'is-valid' }}"
                    x-model="fk_cod_ger"
                    x-on:change="updateGerencia"
                    style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione...</option>
                    @foreach ($gerencias as $index => $gerencia)
                        <option value="{{ $index }}">
                            ({{ $index }}) {{  ($gerencia) }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('fk_cod_ger') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-3">
                <x-label for="cod_uni">Unidad Adscrita</x-label>
                <x-select
                    name="cod_uni"
                    class="form-control-sm {{ $errors->has('cod_uni') ? 'is-invalid' : 'is-valid' }}"
                    x-model="cod_uni"
                    style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione</option>
                    <template x-for="unidad in unidades">
                        <option
                            :key="unidad.cod_uni"
                            :value="unidad.cod_uni"
                            x-text="unidad.des_uni"
                            x-bind:selected="unidad.cod_uni == cod_uni">
                        </option>
                    </template>
                </x-select>
                <div class="invalid-feedback">
                    @error('cod_uni') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-2">
                <x-label for="pri_sol">Prioridad</x-label>
                <x-select
                    name="pri_sol"
                    class="form-control-sm {{ $errors->has('pri_sol') ? 'is-invalid' : 'is-valid' }}"
                    x-model="pri_sol"
                    style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione...</option>
                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\PrioridadSolicitud::cases() as $prioridad)
                        <option value="{{ $prioridad->value }}">
                            {{ $prioridad->name }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('pri_sol') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-2">
                <x-label for="aplica_pre">Se Precompromete</x-label>
                <x-select
                    name="aplica_pre"
                    class="form-control-sm {{ $errors->has('aplica_pre') ? 'is-invalid' : 'is-valid' }}"
                    x-model="aplica_pre"
                    style="pointer-events: none"
                    readonly
                    >
                    <option value="0">
                        NO
                    </option>
                    <option value="1">
                        SI
                    </option>
                </x-select>
                <div class="invalid-feedback">
                    @error('aplica_pre') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-2">
                <x-label for="cierre">Posee Cierre</x-label>
                <x-select
                    name="cierre"
                    class="form-control-sm {{ $errors->has('cierre') ? 'is-invalid' : 'is-valid' }}"
                    x-model="cierre"
                    style="pointer-events: none"
                    readonly
                >
                    <option value="0">
                        SIN CIERRE
                    </option>
                    <option value="1">
                        CON CIERRE
                    </option>
                </x-select>
                <div class="invalid-feedback">
                    @error('cierre') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-8">
                <x-label for="anexos">Anexos/Observaciones</x-label>
                <textarea
                    name="anexos"
                    class="form-control {{ $errors->has('anexos') ? 'is-invalid' : 'is-valid' }}"
                    style="text-transform: uppercase"
                    title="Indique los Anexos de la Solicitud"
                    maxlength="500"
                    cols="50"
                    rows="5"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar' && '{{ $accion }}' !== 'editar_anexos'"
                >{{ old('anexos', $solicitudUnidad->anexos) }}</textarea>
                <div class="invalid-feedback">
                    @error('anexos') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <x-field class="col-5">
            <x-label>Estatus</x-label>
            <x-input
                class="form-control-sm"
                value="{{ $solicitudUnidad->estado->descripcion ?? '' }}"
                readonly
            />
        </x-field>
    </x-slot>
</x-card>

