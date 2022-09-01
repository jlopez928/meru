<br>    <br>
<div class="row col-12">

    <x-field class="text-center col-4 offset-3">
        <x-label for="tiempo_entrega">Tiempo de Entrega</x-label>
        <x-input   readonly   name="tiempo_entrega" class="text-center form-control-sm  {{ $errors->has('tiempo_entrega') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('tiempo_entrega') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-4 offset-3">
        <x-label for="certificados">Lugar de Entrega</x-label>
        <x-input  readonly    name="certificados" class="text-center form-control-sm  {{ $errors->has('certificados') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('certificados') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-4 offset-3">
        <x-label for="lugar_entrega">lugar_entrega</x-label>
        <x-input    readonly  name="lugar_entrega" class="text-center form-control-sm  {{ $errors->has('lugar_entrega') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('lugar_entrega') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-4 offset-3">
        <x-label for="forma_pago">Forma de Pago</x-label>
        <x-input   readonly    name="forma_pago" class="text-center form-control-sm  {{ $errors->has('forma_pago') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('forma_pago') {{ $message }} @enderror
        </div>
     </x-field>
     <x-field class="text-center col-4 offset-3">
        <x-label for="flete">Flete</x-label>
        <x-input    readonly   name="flete" class="text-center form-control-sm  {{ $errors->has('flete') ? 'is-invalid' : '' }}" />
        <div class="invalid-feedback">
            @error('flete') {{ $message }} @enderror
        </div>
     </x-field>
</div>
