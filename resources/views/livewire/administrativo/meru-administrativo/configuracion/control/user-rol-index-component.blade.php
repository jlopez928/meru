<x-datatable :list="$user">
    @if(count($user))
      <div class="table-responsive">
          <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
              @foreach ($user as $userItem)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.control.userrol.show', $userItem->id) }}">
                            {{ $userItem->id}}
                        </td>
                        <x-td  >{{ $userItem->name}}</x-td>
                        <x-td >{{ $userItem->cedula }} </x-td>
                        <x-td >{{ $userItem->email }} </x-td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.control.userrol.edit', $userItem->id) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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

