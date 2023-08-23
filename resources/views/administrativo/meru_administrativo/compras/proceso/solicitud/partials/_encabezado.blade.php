<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>
        <h5 class="text-bold" >Identificación de la Solicitud</h5>
        <hr>

        <div class="row d-flex justify-content-between">
            <x-field class="col-2">
                <x-label for="ano_pro">Año</x-label>
                <x-select
                    wire:model.lazy="ano_pro"
                    class="form-control-sm ml-1 {{ $errors->has('ano_pro') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione...</option>
                    @foreach ($this->years as $year)
                        <option
                            value="{{ $year }}"
                            @selected($ano_pro == $year)
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
                    wire:model.lazy="grupo"
                    class="form-control-sm {{ $errors->has('grupo') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione...</option>
                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\GrupoSolicitud::cases() as $grupoSolicitud)
                        <option
                            value="{{ $grupoSolicitud->value }}"
                        >
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
                    wire:model.defer="cla_sol"
                    class="form-control-sm {{ $errors->has('cla_sol') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'nuevo' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo'"
                >
                    <option value="">Seleccione...</option>
                    @foreach ($clases as $clase)
                        <option
                            value="{{ $clase['cod_cla'] }}"
                        >
                            {{ $clase['des_cla'] }}
                        </option>
                    @endforeach

                </x-select>
                <div class="invalid-feedback">
                    @error('cla_sol') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-2">
                <x-label for="nro_req">Solicitud</x-label>
                <x-input
                    class="form-control-sm"
                    wire:model.defer="nro_req"
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
                    wire:model.lazy="jus_sol"
                    maxlength="1000"
                    cols="50"
                    rows="5"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                ></textarea>
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
                    wire:model.defer="fec_emi"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                    style="{{ $accion == 'nuevo' || $accion == 'editar' ? 'pointer-events: none' : '' }}"
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
                    wire:model.defer="fec_rec"
                    x-bind:readonly="'{{ $accion }}' !== 'recepcionar'"
                    style="{{ $accion == 'recepcionar' ? 'pointer-events: none' : '' }}"
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
                    wire:model.defer="fec_com_cont"
                    x-bind:readonly="'{{ $accion }}' !== 'contratacion_comprador'"
                    style="{{ $accion == 'contratacion_comprador' ? 'pointer-events: none' : '' }}"
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
                    wire:model.defer="fec_anu"
                    style="{{ $accion == 'anular' || $accion == 'reversar' || $accion == 'presupuesto_reversar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'anular' && '{{ $accion }}' !== 'reversar' && '{{ $accion }}' !== 'presupuesto_reversar'"
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
                    wire:model.defer="fec_rec_cont"
                    style="{{ $accion == 'contratacion_recepcionar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'contratacion_recepcionar'"
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
                    wire:model.defer="fec_dev_com"
                    style="{{ $accion == 'devolver' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'devolver'"
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
                    wire:model.defer="fec_pcom"
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
                    wire:model.defer="fec_com"
                    style="{{ $accion == 'compra_comprador' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'compra_comprador'"
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
                    wire:model.defer="fec_dev_cont"
                    style="{{ $accion == 'contratacion_devolver' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'contratacion_devolver'"
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
                    wire:model.defer="fec_reasig"
                    style="{{ $accion == 'reasignar' || $accion == 'contratacion_reasignar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'reasignar' && '{{ $accion }}' !== 'contratacion_reasignar'"
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
                    wire:model.defer="fec_aut"
                    style="{{ $accion == 'presupuesto_aprobar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'presupuesto_aprobar'"
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
                    wire:model.lazy="gru_ram"
                    class="form-control-sm {{ $errors->has('gru_ram') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'nuevo' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo'"
                >
                    <option value="">Seleccione...</option>
                    @foreach ($this->ramos as $index => $ramo)
                        <option
                            value="{{ $index }}"
                        >
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
                    wire:model.lazy="fk_cod_ger"
                    class="form-control-sm {{ $errors->has('fk_cod_ger') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'nuevo' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo'"
                >
                    <option value="">Seleccione...</option>
                    @foreach ($this->gerencias as $index => $gerencia)
                        <option
                            value="{{ $index }}"
                        >
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
                    wire:model.defer="cod_uni"
                    class="form-control-sm {{ $errors->has('cod_uni') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione</option>
                    @foreach ($unidades as $index => $unidad)
                        <option
                            value="{{ $index }}"
                        >
                            ({{ $index }}) {{  $unidad }}
                        </option>
                    @endforeach
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
                    wire:model.defer="pri_sol"
                    class="form-control-sm {{ $errors->has('pri_sol') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                >
                    <option value="">Seleccione...</option>
                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\PrioridadSolicitud::cases() as $prioridad)
                        <option
                            value="{{ $prioridad->value }}"
                        >
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
                    wire:model.defer="aplica_pre"
                    class="form-control-sm {{ $errors->has('aplica_pre') ? 'is-invalid' : 'is-valid' }}"
                    style="pointer-events: none"
                    readonly
                    >
                    <option
                        value="0"
                    >
                        NO
                    </option>
                    <option
                        value="1"
                    >
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
                    wire:model.defer="cierre"
                    class="form-control-sm {{ $errors->has('cierre') ? 'is-invalid' : 'is-valid' }}"
                    style="pointer-events: none"
                    readonly
                >
                    <option
                        value="0"
                    >
                        SIN CIERRE
                    </option>
                    <option
                        value="1"
                    >
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
                    wire:model.lazy="anexos"
                    class="form-control {{ $errors->has('anexos') ? 'is-invalid' : 'is-valid' }}"
                    style="text-transform: uppercase"
                    title="Indique los Anexos de la Solicitud"
                    maxlength="500"
                    cols="50"
                    rows="5"
                    x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar' && '{{ $accion }}' !== 'editar_anexos'"
                ></textarea>
                <div class="invalid-feedback">
                    @error('anexos') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        @if ($modulo == 'unidad_donante')
            <x-field class="col-2">
                <x-label for="donacion">Donación</x-label>
                <x-select
                    wire:model.defer="donacion"
                    class="form-control-sm {{ $errors->has('donacion') ? 'is-invalid' : 'is-valid' }}"
                    style="pointer-events: none"
                    readonly
                >
                    <option
                        value="S"
                    >
                        Si
                    </option>
                    <option
                        value="N"
                    >
                        No
                    </option>
                </x-select>
                <div class="invalid-feedback">
                    @error('donacion') {{ $message }} @enderror
                </div>
            </x-field>
        @endif

        <x-field class="col-5">
            <x-label>Estatus</x-label>
            <x-input
                wire:model.defer="estatus"
                class="form-control-sm"
                readonly
            />
        </x-field>
    </x-slot>
</x-card>

