<x-datatable :list="$solicitudesUnidad">

    @if (count($solicitudesUnidad))
        <div class="table-responsive">
            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">
                @foreach ($solicitudesUnidad as $solicitud)
                    <tr>
                        <td align="left">
                            {{ $solicitud->ano_pro }}
                        </td>
                        <td align="left">{{ $solicitud->nro_req }}</td>
                        <td align="left">{{ $solicitud->grupo }}</td>
                        <td align="left">{{ $solicitud->fec_emi }}</td>
                        <td align="left">{{ $solicitud->monto_tot }}</td>
                        <td align="center">
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
