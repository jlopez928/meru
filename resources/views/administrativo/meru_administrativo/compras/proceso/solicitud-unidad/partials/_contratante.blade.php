<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>
        <div class="row">
            <x-field class="col-4">
                <x-label for="contratante">Unidad Contratante</x-label>
                <x-select
                    name="contratante"
                    class="form-control-sm"
                    {{--  style="{{ $accion !== 'anular' ? 'pointer-events: none' : '' }}"  --}}
                    {{--  x-bind:readonly="'{{ $accion }}' !== 'anular'"  --}}
                    x-model="contratante"
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
                    name="fk_cod_com"
                    class="form-control-sm ml-1 {{ $errors->has('fk_cod_com') ? 'is-invalid' : 'is-valid' }}"
                    x-model="fk_cod_com"
                    style="pointer-events: none"
                    readonly
                >
                    <option value=""></option>
                    @foreach ( $compradores as $index => $comprador)
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
                    name="licita"
                    class="form-control-sm {{ $errors->has('licita') ? 'is-invalid' : 'is-valid' }}"
                    x-model="licita"
                    x-on:change="evaluarTipoCompra"
                    style="pointer-events: none"
                    readonly
                >
                    <option value=""></option>
                    @foreach ( $tipoDeCompras as $index => $tipoDeCompra)
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
