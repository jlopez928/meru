<x-card>
    <x-slot:header>
        <h3 class="card-title text-bold">SubGrupo de Productos</h3>
    </x-slot>
<x-slot:body>
    <div x-data="{ isDisabled: {{ $accion === 'edit' ? true : false }} }">

        <div class="row col-12">
            <x-field class="col-8 offset-1">
                <x-label for="grupo">Grupo</x-label>
                <x-select 
                    name="grupo" 
                    class="form-control-sm {{ $errors->has('grupo') ? 'is-invalid' : '' }}"
                    x-bind:readonly="isDisabled"
                    style="{{ $accion === 'edit' ? 'pointer-events: none' : '' }}"
                >
                    @foreach ($grupos as $grupo)
                        <option value="{{ $grupo->grupo }}" @selected(old('grupo', $subgrupoproducto->grupo) === $grupo->grupo) >
                            ({{$grupo->grupo}}) {{ $grupo->des_grupo }}
                        </option>
                    @endforeach
                </x-select>
            </x-field>
        </div>
        <div class="row col-12">
            <x-field class="col-2 offset-1">
                <x-label for="subgrupo">SubGrupo</x-label>
                <x-input 
                    name="subgrupo" 
                    class="text-center form-control-sm {{ $errors->has('subgrupo') ? 'is-invalid' : '' }}" 
                    style="text-transform: uppercase" 
                    value="{{ old('subgrupo', $subgrupoproducto->subgrupo) }}" 
                    maxlength="5"
                    x-bind:readonly="isDisabled"
                />
                <div class="invalid-feedback">
                    @error('subgrupo') {{ $message }} @enderror
                </div>
            </x-field>

            <x-field class="col-6">
                <x-label for="des_subgrupo">Descripción SubGrupo</x-label>
                <x-input 
                    name="des_subgrupo" 
                    class="form-control-sm {{ $errors->has('des_subgrupo') ? 'is-invalid' : '' }}" 
                    style="text-transform: uppercase"
                    value="{{ old('des_subgrupo', $subgrupoproducto->des_subgrupo) }}" 
                    maxlength="150"
                />
                <div class="invalid-feedback">
                    @error('des_subgrupo') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="row col-12">
            <x-field class="col-2 offset-1">
                <x-label for="sta_reg">{{ __('Status') }}</x-label>
                <x-select name="sta_reg" class="form-control-sm {{ $errors->has('sta_reg') ? 'is-invalid' : '' }}">
                    @foreach (\App\Enums\Administrativo\Meru_Administrativo\Estado::cases() as $estado)
                        <option value="{{ $estado->value }}" @selected(old('sta_reg', $subgrupoproducto->sta_reg?->value) === $estado->value) >
                            {{ $estado->name }}
                        </option>
                    @endforeach
                </x-select>
            </x-field>
        </div>

    </div>

</x-slot>

<x-slot:footer>
    <x-input type="submit" class="float-right" value="Guardar" />
</x-slot>

</x-card>
