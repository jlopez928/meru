<div wire:init="cargar_emit">
    <div class="row d-flex justify-content-between">
        <x-field class="col-5">
            <x-label for="cod_prod">Producto</x-label>
            <x-select
                class="form-control-sm {{ $errors->has('cod_prod') ? 'is-invalid' : 'is-valid' }}"
                wire:model="cod_prod"
                style="{{ $accion !== 'nuevo' && $accion !== 'editar' ? 'pointer-events: none' : '' }}"
                x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{  $accion }}' !== 'editar'"
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
                x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
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
                class="form-control-sm {{ $errors->has('cantidad') ? 'is-invalid' : 'is-valid' }}"
                title="Indique la cantidad"
                wire:model.debounce.250ms="cantidad"
                x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                dir="rtl"
            />
            <div class="invalid-feedback">
                @error('cantidad') {{ $message }} @enderror
            </div>
        </x-field>

        <x-field class="col-2">
            <x-label for="ult_pre">Precio</x-label>
            <x-input
                class="form-control-sm {{ $errors->has('ult_pre') ? 'is-invalid' : 'is-valid' }}"
                title="Indique el Precio Negociado"
                {{--  wire:model.debounce.250ms="ult_pre"  --}}
                wire:model.lazy="ult_pre"
                x-mask:dynamic="$money($input, ',')"
                x-bind:readonly="'{{ $accion }}' !== 'nuevo' && '{{ $accion }}' !== 'editar'"
                dir="rtl"
            />
            <div class="invalid-feedback">
                @error('ult_pre') {{ $message }} @enderror
            </div>
        </x-field>

        <x-field class="col-2">
            <x-label for="mon_sub_tot">Total</x-label>
            <x-input
                class="form-control-sm {{ $errors->has('mon_sub_tot') ? 'is-invalid' : 'is-valid' }}"
                title="Indique el Total Negociado"
                wire:model.lazy="mon_sub_tot"
                readonly
            />
            <div class="invalid-feedback">
                @error('mon_sub_tot') {{ $message }} @enderror
            </div>
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-2">
            <x-label for="cod_par">Partida</x-label>
            <x-input
                class="form-control-sm"
                title="Indique Código de Partida de Gastos"
                wire:model.lazy="cod_par"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label for="cod_gen">Genérica</x-label>
            <x-input
                class="form-control-sm"
                title="Indique Código de Genérica de Gastos"
                wire:model.lazy="cod_gen"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label for="cod_esp">Específica</x-label>
            <x-input
                class="form-control-sm"
                title="Indique Código de Especifica de Gastos"
                wire:model.lazy="cod_esp"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label for="cod_sub">SubEspecífica</x-label>
            <x-input
                class="form-control-sm"
                title="Indique Código de Especifica de Gastos"
                wire:model.lazy="cod_sub"
                readonly
            />
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-3">
            <x-label for="cod_status">Status</x-label>
            <x-input
                class="form-control-sm {{ $errors->has('cod_status') ? 'is-invalid' : 'is-valid' }}"
                wire:model.lazy="cod_status"
                readonly
            />
            <span>{{ $des_status }}</span>
            <div class="invalid-feedback">
                @error('cod_status') {{ $message }} @enderror
            </div>
        </x-field>

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

    <div class="row col-12 d-flex justify-content-center">

        @if (!$mostrar && ($accion == 'nuevo' || $accion == 'editar'))
            <button class="btn-primary btn-sm" wire:click.prevent="agregarRenglon"><i class="fas fa-plus-circle"></i> Agregar</button>
        @endif
        @if ($mostrar && ($accion == 'nuevo' || $accion == 'editar'))
            <button class="btn-success btn-sm" wire:click.prevent="modificarRenglon"><i class="fas fa-edit"></i> Modificar</button>
            <button class="btn-danger btn-sm ml-2" wire:click.prevent="eliminarRenglon"><i class="fas fa-trash"></i> Eliminar</button>
            <button class="btn-secondary btn-sm ml-2" wire:click.prevent="cancelar"><i class="fas fa-window-close"></i> Cancelar</button>
        @endif
    </div>

    <hr>

    {{--  Detalle de la Tabla  --}}
    <div>
        <div class="mt-4 table-responsive">
            <table class="table table-bordered table-striped table-sm text-center">
                <thead>
                    <tr class="table-success">
                        <th style="width:10%">Producto</th>
                        <th style="width:30%">Descripción</th>
                        <th style="width:5%">Cod. Unidad</th>
                        <th style="width:5%">Unidad</th>
                        <th style="width:5%">Cantidad</th>
                        <th style="width:5%">Precio</th>
                        <th style="width:10%">Total</th>
                        <th style="width:5%">Pa</th>
                        <th style="width:5%">Gn</th>
                        <th style="width:5%">Esp</th>
                        <th style="width:5%">Sub</th>
                        <th style="width:5%">Status</th>
                        <th style="width:5%">Reng. Det.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detalle_productos as $index => $detalle)

                        @if ($accion == 'mostrar')
                            <tr>
                        @else
                            <tr style="cursor:pointer" wire:key="detalle-{{ $index }}" wire:click.stop="mostrarDetalle({{ $index }})">
                        @endif
                            <td>{{ $detalle['fk_cod_mat'] }}</td>
                            <td>{{ $detalle['descripcion'] }}</td>
                            <td>{{ $detalle['fk_cod_uni'] }}</td>
                            <td>{{ $detalle['des_uni_med'] }}</td>
                            <td>{{ $detalle['cantidad'] }}</td>
                            <td>{{ $detalle['precio'] }}</td>
                            <td>{{ $detalle['total'] }}</td>
                            <td>{{ $detalle['cod_par'] }}</td>
                            <td>{{ $detalle['cod_gen'] }}</td>
                            <td>{{ $detalle['cod_esp'] }}</td>
                            <td>{{ $detalle['cod_sub'] }}</td>
                            <td>{{ $detalle['sta_reg'] }}</td>
                            <td>{{ $detalle['nro_ren'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center"></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-2">
        <x-field class="col-2">
            <x-label for="monto_tot">Monto total</x-label>
            <x-input
                class="form-control-sm {{ $errors->has('monto_tot') ? 'is-invalid' : 'is-valid' }}"
                name="monto_tot"
                wire:model.lazy="monto_tot"
                title="Indique Monto total de la Solicitud"
                readonly
            />
            <div class="invalid-feedback">
                @error('monto_tot') {{ $message }} @enderror
            </div>
        </x-field>
    </div>

    {{--  <input type="hidden" name="detalle_productos" id="detalle_productos" value="{{ old('detalle_productos') }}" />  --}}
    <input type="hidden" name="detalle_productos" id="detalle_productos" />
</div>

@push('scripts')
    <script>
        Livewire.on('cargarDetalle', param => {
			$("#detalle_productos").val(JSON.stringify(param['detalle_productos']));
        });

        Livewire.on('swal:alert', param => {
            Swal.fire({
                html: param['mensaje'],
                icon: param['tipo'],
            })
        })
    </script>
@endpush
