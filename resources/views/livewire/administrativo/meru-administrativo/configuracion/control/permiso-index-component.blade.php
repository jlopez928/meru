<x-datatable :list="$permiso">
    @if(count($permiso))
      <div class="table-responsive">
          <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
              @foreach ($permiso as $permisoItem)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.control.permiso.show', $permisoItem->id) }}">
                            {{ $permisoItem->id}}
                        </td>
                        <x-td  >{{ $permisoItem->name}}</x-td>
                        <x-td  >{{ $permisoItem->route_name}}</x-td>
                        <x-td  >{{ $permisoItem->modulo?->nombre}}</x-td>
                        <td class="text-center" style="vertical-align: middle;">
							<span class="text-bold {{ $permisoItem->status == '1' ? 'text-success' : 'text-danger' }}">
							    {{ $permisoItem->status == '1' ? 'Activo' : 'Inactivo'}}
							</span>
						</td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.control.permiso.edit', $permisoItem->id) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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
