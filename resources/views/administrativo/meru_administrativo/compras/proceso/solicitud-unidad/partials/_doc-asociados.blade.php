<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>
        <div class="row">
            <x-field class="col-8">
                <x-label for="cotizacion">Cotización</x-label>
                <textarea
                    style="text-transform: uppercase"
                    title="Cotizaciones Asociadas"
                    class="form-control"
                    name="cotizaciones"
                    x-model="cotizaciones"
                    maxlength="100"
                    cols="40"
                    rows="2"
                    readonly
                ></textarea>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-8">
                <x-label for="ofertas">Ofertas</x-label>
                <textarea
                    style="text-transform: uppercase"
                    title="Ofertas Asociadas"
                    class="form-control"
                    name="ofertas"
                    x-model="ofertas"
                    maxlength="100"
                    cols="40"
                    rows="2"
                    readonly
                ></textarea>
            </x-field>
        </div>

        <div class="row">
            <x-field class="col-8">
                <x-label for="ordenes">Ordenes</x-label>
                <textarea
                    style="text-transform: uppercase"
                    title="Ordenes Asociadas"
                    class="form-control"
                    name="ordenes"
                    x-model="ordenes"
                    maxlength="100"
                    cols="40"
                    rows="2"
                    readonly
                ></textarea>
            </x-field>
        </div>
    </x-slot>
</x-card>
