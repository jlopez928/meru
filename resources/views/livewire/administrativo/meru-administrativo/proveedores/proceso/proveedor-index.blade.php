<x-datatable :list="$proveedores">

    @if (count($proveedores))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($proveedores as $proveedor)
                    <tr>
                        <td align="center">
                            <a href="{{ route('proveedores.proceso.proveedor.show', $proveedor) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $proveedor->rif_prov }}
                            </a>
                        </td>
                        <td align="left">{{ $proveedor->nom_prov }}</td>
                        <td align="left">{{ $proveedor->sta_con->name }}</td>
                        <td align="center">
                            <a href="{{ route('proveedores.proceso.proveedor.edit', $proveedor) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
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
