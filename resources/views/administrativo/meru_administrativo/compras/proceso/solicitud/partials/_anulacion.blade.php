<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>
        <div class="row">
            <x-field class="col-4">
                <x-label for="fk_cod_cau">Causa Anulación</x-label>
                <x-select
                    class="form-control-sm {{ $errors->has('fk_cod_cau') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'anular' && $accion !== 'reversar' && $accion !== 'presupuesto_reversar' ? 'pointer-events: none' : '' }}"
                    wire:model.lazy="fk_cod_cau"
                    x-bind:readonly="'{{ $accion }}' !== 'anular' && '{{ $accion }}' !== 'reversar' && '{{ $accion }}' !== 'presupuesto_reversar'"
                >
                    <option value="">Seleccione...</option>
                    @foreach ( $this->causaanulacion as $index => $causa)
                        <option
                            value="{{ $index }}"
                        >
                            ({{ $index }}) {{ $causa }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('fk_cod_cau') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-8">
                <x-label for="cau_dev">Causa de la Devolución</x-label>
                <textarea
                    style="text-transform: uppercase"
                    title="Indique la causa de la devolución"
                    class="form-control {{ $errors->has('cau_dev') ? 'is-invalid' : 'is-valid' }}"
                    wire:model.lazy="cau_dev"
                    maxlength="500"
                    cols="50"
                    rows="5"
                    x-bind:readonly="'{{ $accion }}' !== 'devolver' && '{{ $accion }}' !== 'contratacion_devolver'"
                ></textarea>
                <div class="invalid-feedback">
                    @error('cau_dev') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-8">
                <x-label for="cau_reasig">Causa de la Reasignación</x-label>
                <textarea
                    style="text-transform: uppercase"
                    title="Indique la causa de reasignación"
                    class="form-control {{ $errors->has('cau_reasig') ? 'is-invalid' : 'is-valid' }}"
                    wire:model.lazy="cau_reasig"
                    maxlength="200"
                    cols="50"
                    rows="5"
                    x-bind:readonly="'{{ $accion }}' !== 'reasignar' && '{{ $accion }}' !== 'contratacion_reasignar'"
                ></textarea>
                <div class="invalid-feedback">
                    @error('cau_reasig') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
    </x-slot>
</x-card>
