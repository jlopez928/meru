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

    <hr>

    <div class="row">
        <table class="table table-bordered table-sm text-center" >
            <thead>
                <tr class="table-success">
                    <th style="width:10%">Producto</th>
                    <th style="width:60%">Descripción</th>
                    <th style="width:10%">Cod. Unidad</th>
                    <th style="width:20%">Unidad</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $index => $detalle)
                    <tr style="cursor:pointer" wire:key="detalle-{{ $index }}" wire:click.stop="mostrarProducto({{ $index }})">
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
