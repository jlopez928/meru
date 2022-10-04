<div>
    <div class="row d-flex justify-content-between">
        <x-field class="col-5">
            <x-label for="cod_prod">Producto</x-label>
            <x-select
                class="form-control-sm {{ $errors->has('cod_prod') ? 'is-invalid' : 'is-valid' }}"
                wire:model="cod_prod"
            >
                <option value="">Seleccione...</option>
                @foreach ($productos as $index => $producto)
                    <option value="{{ $index }}">
                        ({{ $index }}) {{ $producto }}
                    </option>
                @endforeach
            </x-select>
            <div class="invalid-feedback">
                @error('cod_prod') {{ $message }} @enderror
            </div>
        </x-field>

        <x-field class="col-7">
            <x-label>Descripción</x-label>
            <textarea
                class="form-control {{ $errors->has('des_prod') ? 'is-invalid' : 'is-valid' }}"
                wire:model.defer="des_prod"
                style="text-transform: uppercase"
                title="Indique la descripción"
                maxlength="500"
                cols="40"
                rows="2"
            ></textarea>
            <div class="invalid-feedback">
                @error('des_prod') {{ $message }} @enderror
            </div>
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-2">
            <x-label for="cod_uni">Cod. Unidad</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="cod_uni"
                readonly
            />
        </x-field>

        <x-field class="col-7">
            <x-label>Unidad</x-label>
            <textarea
                class="form-control {{ $errors->has('des_uni') ? 'is-invalid' : 'is-valid' }}"
                wire:model.defer="des_uni"
                style="text-transform: uppercase"
                title="Indique la descripción de la unidad de medida"
                maxlength="60"
                cols="50"
                rows="2"
                readonly
            ></textarea>
            <div class="invalid-feedback">
                @error('des_uni') {{ $message }} @enderror
            </div>
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-2">
            <x-label for="cantidad">Cantidad</x-label>
            <x-input
                class="form-control-sm"
                title="Indique la cantidad"
                wire:model.debounce.250ms="cantidad"
            />
        </x-field>

        <x-field class="col-2">
            <x-label for="ult_pre">Precio</x-label>
            <x-input
                class="form-control-sm"
                title="Indique el Precio Negociado"
                wire:model.debounce.250ms="ult_pre"
            />
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-2">
            <x-label for="mon_sub_tot">Total</x-label>
            <x-input
                class="form-control-sm"
                title="Indique el Total Negociado"
                wire:model.lazy="mon_sub_tot"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label for="cod_par">Pa</x-label>
            <x-input
                class="form-control-sm"
                title="Indique Código de Partida de Gastos"
                wire:model.lazy="cod_par"
                readonly
            />
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-2">
            <x-label for="cod_gen">Gn</x-label>
            <x-input
                class="form-control-sm"
                title="Indique Código de Genérica de Gastos"
                wire:model.lazy="cod_gen"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label for="cod_esp">Esp</x-label>
            <x-input
                class="form-control-sm"
                title="Indique Código de Especifica de Gastos"
                wire:model.lazy="cod_esp"
                readonly
            />
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-2">
            <x-label for="cod_sub">Sub</x-label>
            <x-input
                class="form-control-sm"
                title="Indique Código de Especifica de Gastos"
                wire:model.lazy="cod_sub"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label for="cod_status">Status</x-label>
            <x-input
                class="form-control-sm"
                wire:model.lazy="cod_status"
                readonly
            />
        </x-field>
    </div>

    <div class="row">
        <x-field class="col-2">
            <x-label for="renglon">Reng. Det.</x-label>
            <x-input
                class="form-control-sm"
                wire:model.lazy="renglon"
                title="Indique Renglón"
                readonly
            />
        </x-field>
    </div>

    <div class="row col-12 d-flex justify-content-end">
        <button class="btn-primary btn-sm" wire:click.prevent="agregarRenglon">Agregar Renglon</button>
    </div>

    <hr>

    {{--  Detalle de la Tabla  --}}
    <div class="mt-4">
        <table class="table table-bordered table-sm text-center">
            <thead>
                <tr class="table-success">
                    <th style="width:10%">Producto</th>
                    <th style="width:60%">Descripción</th>
                    <th style="width:10%">Cod. Unidad</th>
                    <th style="width:20%">Unidad</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($detalle_productos as $index => $detalle)
                    <tr>
                        <td>{{ $detalle['cod_prod'] }}</td>
                        <td>{{ $detalle['des_prod'] }}</td>
                        <td>{{ $detalle['cod_uni'] }}</td>
                        <td>{{ $detalle['des_uni'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center"></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
