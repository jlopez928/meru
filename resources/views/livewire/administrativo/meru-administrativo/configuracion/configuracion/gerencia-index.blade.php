<x-datatable :list="$gerencias">

    @if (count($gerencias))
        <div class="table-responsive">

            <x-table-headers class="py-2" :sortby="$sort" :order="$direction" :headers="$headers">

                @foreach ($gerencias as $gerenciaItem)

                    <tr>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.configuracion.gerencia.show', $gerenciaItem->cod_ger) }}"> {{ $gerenciaItem->cod_ger }} </a>
                        </td>
                        <td style="vertical-align: middle;">
                            {{ $gerenciaItem->des_ger }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $gerenciaItem->centro_costo }}
                        </td>
                        <td class="text-enter" style="vertical-align: middle;">
                            {{ $gerenciaItem->nom_jefe }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            {{ $gerenciaItem->nomenclatura }}
                        </td>
                        <td class="text-center" style="vertical-align: middle;">
                            <a href="{{ route('configuracion.configuracion.gerencia.edit', $gerenciaItem->cod_ger) }}" type="button" aria-label="Left Align" data-toggle="tooltip" data-placement="left" title="Editar">
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