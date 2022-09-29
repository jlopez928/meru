<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th class="text-center">
                ({{ count($selectedVehiculos) }} / 16)
            </th>
            <th class="text-center">
                Cód. Correlativo
            </th>
            <th class="text-center">
                Placa
            </th>
            <th class="text-center">
                Modelo
            </th>
            <th class="text-center">
                Marca
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vehiculos as $vehiculo)
            <tr>
                <td class="text-center">
                    <input
                        type="checkbox"
                        name="selectedVehiculos[]"
                        wire:model='selectedVehiculos'
                        value="{{ $vehiculo->cod_corr }}"
                    >
                </td>
                <td>{{ $vehiculo->cod_corr }}</td>
                <td>{{ $vehiculo->placa }}</td>
                <td>{{ $vehiculo->modelo }}</td>
                <td>{{ $vehiculo->marca }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
    <script>
        Livewire.on('swal:alert', param => {
            Swal.fire({
                title: param['titulo'],
                text: param['mensaje'],
                icon: param['tipo'],
            })
        })
    </script>
@endpush
