<div>
    <x-card class="card-secondary col-12 mt-3">
        <x-slot:body>
            <h5 class="text-bold">Centro de Costo</h5>
            <hr>

            <div class="row">
                <x-field class="col-1">
                    <x-label for="tip_cod">Tipo</x-label>
                    <x-input
                        name="tip_cod"
                        class="form-control-sm"
                        x-model="tip_cod"
                        dir="rtl"
                        readonly
                    />
                </x-field>

                <x-field class="col-1">
                    <x-label for="cod_pryacc">Proyecto</x-label>
                    <x-input
                        name="cod_pryacc"
                        class="form-control-sm"
                        x-model="cod_pryacc"
                        dir="rtl"
                        readonly
                    />
                </x-field>

                <x-field class="col-1">
                    <x-label for="cod_obj">Objetivo</x-label>
                    <x-input
                        name="cod_obj"
                        class="form-control-sm"
                        x-model="cod_obj"
                        dir="rtl"
                        readonly
                    />
                </x-field>

                <x-field class="col-1">
                    <x-label for="gerencia">Gerencia</x-label>
                    <x-input
                        name="gerencia"
                        class="form-control-sm"
                        x-model="gerencia"
                        dir="rtl"
                        readonly
                    />
                </x-field>

                <x-field class="col-1">
                    <x-label for="unidad">Unidad</x-label>
                    <x-input
                        name="unidad"
                        class="form-control-sm"
                        x-model="unidad"
                        dir="rtl"
                        readonly
                    />
                </x-field>

            </div>

            <hr>
            <livewire:administrativo.meru-administrativo.compras.proceso.solicitud-unidad-detalle :solicitudUnidad="$solicitudUnidad" :accion="$accion" />

        </x-slot>
    </x-card>
</div>
