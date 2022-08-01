@extends('layouts.aplicacion')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('modulo.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Ver Modulo</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div>
    <x-card>
        <x-slot name="header">
            <h3 class="card-title text-bold">Datos del Modulo</h3>
        </x-slot>
        <x-slot name="body">
            <div class="row col-12">
                <x-field class="col-1">
                    <x-label for="id">{{ __('ID') }}</x-label>
                    <x-input name="id" value="{{ old('id', $modulo->id) }}" readonly/>
                </x-field>
                <x-field class="col-4">
                    <x-label for="name">Descripción</x-label>
                    <x-input readonly name="name" class="{{ $errors->has('name') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese Descripción" value="{{ old('name', $modulo->name) }}"  />
                    <div class="invalid-feedback">
                        @error('name') {{ $message }} @enderror
                    </div>
                </x-field>
                <x-field class="col-2">
                    <x-label for="status">Estado</x-label>
                    <x-select  disabled readonly name="status" class="{{ $errors->has('status') ? 'is-invalid' : 'is-valid' }}">
                        <option value="{{ old('status', $modulo->status) == '0' ? '0' : '1' }}" selected>{{ old('status', $modulo->status) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                        <option value="{{ old('status', $modulo->status) == '0' ? '1' : '0'}}"> {{ old('status', $modulo->status) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                 </x-select>
                    <div class="invalid-feedback">
                        @error('status') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
        </x-slot>
    </x-card>
</div>
</section>
@endsection

@section('sidebar')
@include('layouts.sidebar')
@endsection



