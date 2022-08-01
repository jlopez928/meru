<x-datatable :list="$roles">
    @if(count($roles))
      <div class="table-responsive">
          <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
              @foreach ($roles as $rolItem)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.control.rol.show', $rolItem->id) }}">
                            {{ $rolItem->id}}
                        </td>
                        <x-td  >{{ $rolItem->name}}</x-td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.control.asignarpermiso.asignarpermiso', $rolItem->id) }}"  aria-label="Center Align" data-toggle="tooltip"  title="Actualizar permiso">
                               Actualizar Permiso ({{ $rolItem->permissions->count() }})
                            </a>
                         </td>
                        <td class="text-center" style="vertical-align: middle;">
							<span class="text-bold {{ $rolItem->status == '1' ? 'text-success' : 'text-danger' }}">
								{{ $rolItem->status == '1' ? 'Activo' : 'Inactivo'}}
							</span>
						</td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.control.rol.edit', $rolItem->id) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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
