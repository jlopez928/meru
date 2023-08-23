<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>
        <div class="row">
            <x-field class="col-4">
                <x-label for="contratante">Unidad Contratante</x-label>
                <x-select
                    class="form-control-sm"
                    {{--  style="{{ $accion !== 'anular' ? 'pointer-events: none' : '' }}"  --}}
                    {{--  x-bind:readonly="'{{ $accion }}' !== 'anular'"  --}}
                    wire:model="contratante"
                    style="pointer-events: none"
                    readonly
                >
                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\Compras\UnidadContratante::cases() as $unidadContratante)
                        <option value="{{ $unidadContratante->value }}">
                            ({{ $unidadContratante->value }}) {{ $unidadContratante->name }}
                        </option>
                    @endforeach
                </x-select>
            </x-field>
        </div>
        <div class="row">
            <x-field class="col-4">
                <x-label for="fk_cod_com">Comprador</x-label>
                <x-select
                    class="form-control-sm ml-1 {{ $errors->has('fk_cod_com') ? 'is-invalid' : 'is-valid' }}"
                    wire:model.lazy="fk_cod_com"
                    style="{{ $accion !== 'compra_comprador' && $accion !== 'contratacion_comprador' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'compra_comprador' && '{{ $accion }}' !== 'contratacion_comprador'"
                >
                    <option value=""></option>
                    @foreach ( $this->compradores as $index => $comprador)
                        <option
                            value="{{ $index }}"
                        >
                            ({{ $index }}) {{ $comprador }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('fk_cod_com') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="row">
            <x-field class="col-4">
                <x-label for="licita">Tipo de Compra</x-label>
                <x-select
                    class="form-control-sm {{ $errors->has('licita') ? 'is-invalid' : 'is-valid' }}"
                    wire:model.lazy="licita"
                    style="{{ $accion !== 'compra_comprador' && $accion !== 'contratacion_comprador' ? 'pointer-events: none' : '' }}"
                    x-bind:readonly="'{{ $accion }}' !== 'compra_comprador' && '{{ $accion }}' !== 'contratacion_comprador'"
                >
                    <option value=""></option>
                    @foreach ( $this->tipodecompras as $index => $tipoDeCompra)
                        <option
                            value="{{ $index }}"
                        >
                            ({{ $index }}) {{ $tipoDeCompra }}
                        </option>
                    @endforeach
                </x-select>
                <div class="invalid-feedback">
                    @error('licita') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
    </x-slot>
</x-card>
