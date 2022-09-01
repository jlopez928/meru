<x-datatable :list="$permisos">

    @if (count($permisos))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($permisos as $permisoItem)     
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $permisoItem->usuario }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $permisoItem->maxut }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <span class="text-bold {{ $permisoItem->multicentro == 'true' ? 'text-success' : 'text-danger' }}">
                                {{ $permisoItem->multicentro ? 'SI' : 'NO' }}
                            </span>
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('modificaciones.configuracion.permiso_traspaso.edit', $permisoItem) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                <span class="fas fa-edit" aria-hidden="true"></span>
                            </a>
                            &nbsp;
                            <a href="#" class="confirm-delete" form-target="delete-form-{{ $permisoItem->usuario_id }}"
                                title="Eliminar" onclick="event.preventDefault();" >
                                <i class="far fa-trash-alt text-danger"></i>
                            </a>
                            <form id="delete-form-{{ $permisoItem->usuario_id }}" action="{{ route('modificaciones.configuracion.permiso_traspaso.destroy', $permisoItem) }}"
                                method="POST" style="display: none;">
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