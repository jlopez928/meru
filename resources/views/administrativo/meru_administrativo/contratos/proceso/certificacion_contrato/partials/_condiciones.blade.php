<br>    <br>
<div class="row col-12">

    <x-field class="text-center col-4 offset-2">
        <x-label for="motivo">Causa de Anulación/Cierre</x-label>
        <textarea readonly  wire:model.defer="cau_anu"   id="cau_anu" name="cau_anu" class="form-control {{ $errors->has('cau_anu') ? 'is-invalid' : '' }}" rows="3">
        </textarea>
        <div class="invalid-feedback">
            @error('cau_anu') {{ $message }} @enderror
        </div>
    </x-field>

</div>
