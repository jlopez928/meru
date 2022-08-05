<x-datatable :list="$modulo">
    @if(count($modulo))
      <div class="table-responsive">
          <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
              @foreach ($modulo as $moduloItem)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.control.modulo.show', $moduloItem->id) }}">
                            {{ $moduloItem->id}}
                        </td>
                        <x-td  >{{ $moduloItem->nombre}}</x-td>
                        <td class="text-center" style="vertical-align: middle;">
							<span class="text-bold {{ $moduloItem->status == '1' ? 'text-success' : 'text-danger' }}">
								{{ $moduloItem->status == '1' ? 'Activo' : 'Inactivo'}}
							</span>
						</td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.control.modulo.edit', $moduloItem->id) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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

