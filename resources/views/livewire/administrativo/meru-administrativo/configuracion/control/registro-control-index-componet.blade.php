<x-datatable :list="$registrocontrol">
    @if(count($registrocontrol))
      <div class="table-responsive">
          <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
              @foreach ($registrocontrol as $registrocontrolItem)
                    <tr>
                        <td align="center" >
                            <a href="{{ route('configuracion.control.registrocontrol.show', $registrocontrolItem->id ) }}" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Mostrar">
                                {{ $registrocontrolItem->id }}
                            </a>
                        </td>

                        <td align="center" >{{ $registrocontrolItem->ano_pro }} </td>

                        <td align="center" >
                            <span class="text-bold {{ $registrocontrolItem->sta_con == 'A' ? 'text-success' : 'text-danger' }}" >
                                {{  $registrocontrolItem->sta_con == 'A' ? 'Abierto':'Cerrado' }}
                            </span>
                        </td>
                        <td align="center" >{{ $registrocontrolItem->des_emp1 }} </td>
                        <td align="center" >{{ $registrocontrolItem->ult_mes }} </td>
                        <td align="center" >{{ $registrocontrolItem->con_con }} </td>
                        <td align="center" >{{ $registrocontrolItem->ctaresultado }} </td>
                        <td align="center" >{{ $registrocontrolItem->ciudad }} </td>
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
