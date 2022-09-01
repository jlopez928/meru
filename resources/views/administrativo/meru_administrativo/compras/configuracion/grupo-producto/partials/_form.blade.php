<x-card>
    <x-slot:header>
        <h3 class="card-title text-bold">Grupos de Productos</h3>
    </x-slot>
<x-slot:body>
    <div class="row col-12" x-data="{ isDisabled: {{ $accion === 'edit' ? true : false }} }">
        <x-field class="form-group col-2 offset-1">
            <x-label for="grupo">{{ __('Code') }}</x-label>
            <x-input
                name="grupo"
                class="text-center form-control-sm {{ $errors->has('grupo') ? 'is-invalid' : '' }}"
                style="text-transform: uppercase"
                value="{{ old('grupo', $grupoproducto->grupo) }}"
                maxLength="3"
                x-bind:readonly="isDisabled"
            />
            <div class="invalid-feedback">
                @error('grupo') {{ $message }} @enderror
            </div>
        </x-field>

        <x-field class="form-group col-6">
            <x-label for="des_grupo">{{ __('Description') }}</x-label>
            <x-input
                name="des_grupo"
                class="form-control-sm {{ $errors->has('des_grupo') ? 'is-invalid' : '' }}"
                style="text-transform: uppercase"
                value="{{ old('des_grupo', $grupoproducto->des_grupo) }}"
                maxLength="180"
            />
            <div class="invalid-feedback">
                @error('des_grupo') {{ $message }} @enderror
            </div>
        </x-field>

        <x-field class="form-group col-2">
            <x-label for="sta_reg">{{ __('Status') }}</x-label>
            <x-select name="sta_reg" class="form-control-sm {{ $errors->has('sta_reg') ? 'is-invalid' : '' }}">
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Estado::cases() as $estado)
                    <option value="{{ $estado->value }}" @selected(old('sta_reg', $grupoproducto->sta_reg?->value) === $estado->value) >
                        {{ $estado->name }}
                    </option>
                @endforeach
            </x-select>
            <div class="invalid-feedback">
                @error('sta_reg') {{ $message }} @enderror
            </div>
        </x-field>

    </div>

</x-slot>

<x-slot:footer>
    <x-input type="submit" class="float-right" value="Guardar" />
</x-slot>

</x-card>
