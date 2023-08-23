<x-card x-data="{ accion: '{{$accion}}' }">
        <x-slot:header>
            <h3 class="card-title text-bold">Compradores</h3>
        </x-slot>
    <x-slot:body>
        <div class="row col-12">
            <x-field class="col-2 offset-1">
                <x-label for="cod_com">Código</x-label>
                <x-input
                    name="cod_com"
                    class="text-center form-control-sm {{ $errors->has('cod_com') ? 'is-invalid' : '' }}"
                    value="{{ old('cod_com', $comprador->cod_com) }}"
                    x-bind:disabled="accion === 'show'"
                />
                <div class="invalid-feedback">
                    @error('cod_com') {{ $message }} @enderror
                </div>
            </x-field>
        </div>

        <div class="row col-12" x-show="accion === 'show'">
            <x-field class="col-2 offset-1">
                <x-label for="usu_com">Usuario</x-label>
                <x-input
                    class="form-control-sm"
                    value="{{ $comprador->usu_com }}"
                    x-bind:disabled="accion === 'show'"
                />
            </x-field>
        </div>

        <div class="row col-12">
            <x-field class="col-4 offset-1">
                <x-label for="nombre">Nombre</x-label>
                <x-input
                    name="nombre"
                    class="form-control-sm"
                    value="{{ $comprador->usuariot->nombre }}"
                    x-bind:disabled="accion === 'show'"
                />
            </x-field>
        </div>

        <div class="row col-12">
            <x-field class="col-4 offset-1">
                <x-label for="cedula">Cédula</x-label>
                <x-input
                    name="cedula"
                    class="form-control-sm"
                    value="{{ $comprador->usuariot->cedula }}"
                    x-bind:disabled="accion === 'show'"
                />
            </x-field>
        </div>

        <div class="row col-12">
            <x-field class="col-4 offset-1">
                <x-label for="correo">Correo Electrónico</x-label>
                <x-input
                    name="correo"
                    class="form-control-sm"
                    value="{{ $comprador->usuariot->correo }}"
                    x-bind:disabled="accion === 'show'"
                />
            </x-field>
        </div>

        <x-field class="form-group col-2 offset-1">
            <x-label for="sta_reg">{{ __('Status') }}</x-label>
            <x-select
                name="sta_reg"
                class="form-control-sm"
                style="{{ $accion === 'show' ? 'pointer-events: none' : '' }}"
                x-bind:readonly="accion === 'show'"
            >
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Estado::cases() as $estado)
                    <option value="{{ $estado->value }}" @selected(old('sta_reg', $comprador->sta_reg?->value) === $estado->value) >
                        {{ $estado->name }}
                    </option>
                @endforeach
            </x-select>
        </x-field>

    </x-slot>

    <x-slot:footer>
        <x-input
            type="submit"
            class="float-right"
            value="Guardar"
            x-show="accion !== 'show'"
        />
    </x-slot>

</x-card>
