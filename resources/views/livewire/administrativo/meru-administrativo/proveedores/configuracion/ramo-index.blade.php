<x-datatable :list="$ramos">

    @if (count($ramos))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($ramos as $ramo)
                    <tr>

                        <td align="center" >
                            <a href="{{ route('proveedores.configuracion.ramo.show', $ramo->cod_ram ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $ramo->cod_ram }}
                            </a>
                        </td>
                        <td align="left">{{ $ramo->des_ram }}</td>
                        <td align="center">{{ $ramo->sta_reg->name }}</td>
                        <td align="center">
                                <a href="{{ route('proveedores.configuracion.ramo.edit', $ramo) }}" type="button" class="btn-sm" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
                                    <span class="fas fa-edit" aria-hidden="true"></span>
                                </a>
                                @if ($ramo->ramoproveedores_count === 0)
                                    <a class="btn-sm confirm-delete" type="button" form-target="delete-form-{{ $ramo->cod_ram }}"
                                        onclick="event.preventDefault();" title="Eliminar">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                    <form 
                                        id="delete-form-{{ $ramo->cod_ram }}" 
                                        action="{{ route('proveedores.configuracion.ramo.destroy', $ramo->cod_ram) }}"
                                        method="POST" 
                                        style="display: none;"
                                    >
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
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
