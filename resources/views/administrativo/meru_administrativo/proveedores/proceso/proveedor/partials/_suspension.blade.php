<x-card class="card-secondary col-12 mt-3">
    <x-slot:body>
        <div class="row">
            <x-field class="col-5">
                <x-label for="cau_sus">Causa Suspensión</x-label>
                <textarea
                    style="text-transform: uppercase"
                    class="form-control {{ $errors->has('cau_sus') ? 'is-invalid' : 'is-valid' }}"
                    name="cau_sus"
                    maxlength="200"
                    cols="40"
                    rows="2"
                    x-bind:readonly="'{{ $accion }}' !== 'suspender'"
                >{{ old('cau_sus', $proveedor->cau_sus) }}</textarea>
                <div class="invalid-feedback">
                    @error('cau_sus') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
    </x-slot>
</x-card>
