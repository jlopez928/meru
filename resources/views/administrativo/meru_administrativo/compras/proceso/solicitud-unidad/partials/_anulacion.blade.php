<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>
        <div class="row">
            <x-field class="col-4">
                <x-label for="fk_cod_cau">Causa Anulación</x-label>
                <x-select
                    name="fk_cod_cau"
                    class="form-control-sm {{ $errors->has('fk_cod_cau') ? 'is-invalid' : 'is-valid' }}"
                    style="{{ $accion !== 'anular' && $accion !== 'reversar' ? 'pointer-events: none' : '' }}"
                    x-model="fk_cod_cau"
                    x-bind:readonly="'{{ $accion }}' !== 'anular' && '{{ $accion }}' !== 'reversar'"
                >
                    <option value=""></option>
                    @foreach ( $causaAnulacion as $index => $causa)
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
                    name="cau_dev"
                    x-model="cau_dev"
                    maxlength="500"
                    cols="50"
                    rows="5"
                    x-bind:readonly="'{{ $accion }}' !== 'devolucion'"
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
                    name="cau_reasig"
                    x-model="cau_reasig"
                    maxlength="200"
                    cols="50"
                    rows="5"
                    x-bind:readonly="'{{ $accion }}' !== 'reasignacion'"
                ></textarea>
                <div class="invalid-feedback">
                    @error('cau_reasig') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
    </x-slot>
</x-card>
