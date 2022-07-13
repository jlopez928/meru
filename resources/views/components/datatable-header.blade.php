<div class="px-6 py-2 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <span>Mostrar</span>
        <x-select wire:model="paginate" class="custom-select-sm mx-1">
            <option value="10" selected>10</option>
            <option value="30">30</option>
            <option value="{{ $dataheader->total() }}">Todos</option>
        </x-select>
        <span>registros</span>
    </div>
    <div class="d-flex align-items-center">
        <span>Buscar</span>
        <x-input wire:model.debounce.500ms="search" name="search" class="form-control-sm mx-1" />
    </div>
</div>
