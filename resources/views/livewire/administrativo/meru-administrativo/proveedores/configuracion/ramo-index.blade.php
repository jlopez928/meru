<x-datatable :list="$ramos">

    @if (count($ramos))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($ramos as $ramo)
                    <tr>
                        <td align="center">{{ $ramo->cod_ram }}</td>
                        <td align="left">{{ $ramo->des_ram }}</td>
                        <td align="center">{{ $ramo->sta_reg->name }}</td>
                        <td align="center">
                            <div x-data>
                                <a href="{{ route('proveedores.configuracion.ramo.edit', $ramo) }}" type="button" class="btn btn-primary btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                    <span class="fas fa-edit" aria-hidden="true"></span>
                                </a>
                                @if ($ramo->ramoproveedores_count === 0)
                                    <a x-on:click="confirm('Seguro que desea eliminar este Registro?') ? @this.deleteRamo({{ $ramo->cod_ram }}) : false" type="button" class="btn btn-danger btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Eliminar">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table-headers>
        </div>
    @else
        <div class="px-6 py-2">
                <span>No se encontraron registros.</span>
        </div>
    @endif

</x-datatable>