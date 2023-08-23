<x-card>
    <x-slot:header>
        <h3 class="card-title text-bold">Codificación de Ramos</h3>
    </x-slot>
<x-slot:body>
    <div class="row col-12">

        <x-field class="form-group col-2 offset-1">
            <x-label for="cod_ram">{{ __('Code') }}</x-label>
            <x-input class="text-center form-control-sm " name="cod_ram" value="{{ old('cod_ram', $ramo->cod_ram) }}" readonly/>
        </x-field>

        <x-field class="form-group col-6">
            <x-label for="des_ram">{{ __('Description') }}</x-label>
            <x-input name="des_ram" class="form-control-sm {{ $errors->has('des_ram') ? 'is-invalid' : '' }}" placeholder="Ingrese descripción" value="{{ old('des_ram', $ramo->des_ram) }}" />
            <div class="invalid-feedback">
                @error('des_ram') {{ $message }} @enderror
            </div>
        </x-field>

        <x-field class="form-group col-2">
            <x-label for="sta_reg">{{ __('Status') }}</x-label>
            <x-select name="sta_reg" class="form-control-sm {{ $errors->has('sta_reg') ? 'is-invalid' : '' }}">
                @foreach (\App\Enums\Administrativo\Meru_Administrativo\Estado::cases() as $estado)
                    <option value="{{ $estado->value }}" @selected(old('sta_reg', $ramo->sta_reg?->value) === $estado->value) >
                        {{ $estado->name }}
                    </option>
                @endforeach
            </x-select>
        </x-field>

    </div>

</x-slot>

<x-slot:footer>
    <x-input type="submit" class="btn btn-sm btn-primary text-bold float-right" value="Guardar" />
</x-slot>

</x-card>
