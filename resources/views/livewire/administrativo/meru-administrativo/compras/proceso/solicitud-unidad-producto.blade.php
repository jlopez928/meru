<div>
    <div class="row d-flex justify-content-between">
        <x-field class="col-5">
            <x-label>Producto</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cod_prod"
                readonly
            />
        </x-field>

        <x-field class="col-7">
            <x-label>Descripción</x-label>
            <textarea
                class="form-control"
                wire:model.defer="prod_des_prod"
                cols="40"
                rows="2"
                readonly
            ></textarea>
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-2">
            <x-label>Cod. Unidad</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cod_uni"
                readonly
            />
        </x-field>

        <x-field class="col-7">
            <x-label>Unidad</x-label>
            <textarea
                class="form-control"
                wire:model.defer="prod_des_uni"
                cols="50"
                rows="2"
                readonly
            ></textarea>
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-2">
            <x-label>Cantidad</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cantidad"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label>Cantidad Orden</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cant_ord"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label>Cantidad Cerrada</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cant_sal"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label>Precio</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_precio"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label>Total</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_total"
                readonly
            />
        </x-field>
    </div>

    <div class="row d-flex justify-content-between">
        <x-field class="col-2">
            <x-label>Partida</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cod_par"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label>Genérica</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cod_gen"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label>Específica</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cod_esp"
                readonly
            />
        </x-field>

        <x-field class="col-2">
            <x-label>SubEspecífica</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cod_sub"
                readonly
            />
        </x-field>
    </div>

    <div class="row">
        <x-field class="col-2">
            <x-label>Status</x-label>
            <x-input
                class="form-control-sm"
                wire:model.defer="prod_cod_status"
                readonly
            />
        </x-field>
    </div>

    <hr>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm text-center" >
            <thead>
                <tr class="table-success">
                    <th style="width:10%">Producto</th>
                    <th style="width:30%">Descripción</th>
                    <th style="width:5%">Cod. Unidad</th>
                    <th style="width:5%">Unidad</th>
                    <th style="width:5%">Cantidad</th>
                    <th style="width:5%">Cant. Orden</th>
                    <th style="width:5%">Cant. Cerrada</th>
                    <th style="width:5%">Precio</th>
                    <th style="width:10%">Total</th>
                    <th style="width:5%">Pa</th>
                    <th style="width:5%">Gn</th>
                    <th style="width:5%">Esp</th>
                    <th style="width:5%">Sub</th>
                    <th style="width:5%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $index => $detalle)
                    @if ($accion == 'mostrar')
                        <tr>
                    @else
                        <tr style="cursor:pointer" wire:key="detalle-producto-{{ $index }}" wire:click.stop="mostrarProducto({{ $index }})">
                    @endif
                        <td>{{ $detalle['fk_cod_mat'] }}</td>
                        <td>{{ $detalle['des_bien'] }}</td>
                        <td>{{ $detalle['fk_cod_uni'] }}</td>
                        <td>{{ $detalle['des_uni_med'] }}</td>
                        <td>{{ $detalle['cantidad'] }}</td>
                        <td>{{ $detalle['cant_ord'] }}</td>
                        <td>{{ $detalle['cant_sal'] }}</td>
                        <td>{{ $detalle['pre_ref'] }}</td>
                        <td>{{ $detalle['tot_ref'] }}</td>
                        <td>{{ $detalle['cod_par'] }}</td>
                        <td>{{ $detalle['cod_gen'] }}</td>
                        <td>{{ $detalle['cod_esp'] }}</td>
                        <td>{{ $detalle['cod_sub'] }}</td>
                        <td>{{ $detalle['sta_reg'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="text-center"></td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <input type="hidden" name="productos" id="productos" />
</div>

@push('scripts')
    <script>
        Livewire.on('cargarProducto', param => {
			$("#productos").val(JSON.stringify(param['productos']));
        });
    </script>
@endpush
