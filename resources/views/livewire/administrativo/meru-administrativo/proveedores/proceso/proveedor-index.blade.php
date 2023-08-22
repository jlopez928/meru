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

                            {{--  Boton Editar - Modificar Proveedor  --}}
                            <a href="{{ route('proveedores.proceso.proveedor.edit', $proveedor) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Modificar Registro de Proveedor">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>

                            {{--  Boton Suspender - Suspender Proveedor  --}}
                            @if ($proveedor->sta_con->value == '0')
                                <a href="{{ route('proveedores.proceso.proveedor.suspender', $proveedor) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Suspender Proveedor">
                                    <span class="fas fa-times" aria-hidden="true"></span>
                                </a>
                            @endif

                            {{--  Boton Reactivar - Reactivar Proveedor  --}}
                            @if ($proveedor->sta_con->value == '2')
                                <a href="{{ route('proveedores.proceso.proveedor.reactivar', $proveedor) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Reactivar Proveedor">
                                    <span class="fas fa-check" aria-hidden="true"></span>
                                </a>
                            @endif

                            {{--  Boton Eliminar - Eliminar Proveedor  --}}
                            <a class="btn-sm confirm-delete" type="button" form-target="delete-form-{{ $proveedor->rif_prov }}"
                                onclick="event.preventDefault();" title="Eliminar">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                            <form
                                id="delete-form-{{ $proveedor->rif_prov }}"
                                action="{{ route('proveedores.proceso.proveedor.destroy', $proveedor) }}"
                                method="POST"
                                style="display: none;"
                            >
                                @csrf
                                @method('DELETE')
                            </form>
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

@push('scripts')
    {!! Helper::swalConfirm('.confirm-delete') !!}
@endpush
