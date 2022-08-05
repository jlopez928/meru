@extends('layouts.aplicacion')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.control.userrol.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Ver Usuario</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div>
    <x-card>
        <x-slot name="header">
            <h3 class="card-title text-bold">Datos del Usuario</h3>
        </x-slot>
    <x-slot name="body">
        <div class="row col-12">
            <x-field class="form-group col-1 ">
                <x-label for="id">{{ __('ID') }}</x-label>
                <x-input name="id" class="form-control-sm" value="{{ old('id', $userrol->id) }}" readonly/>
            </x-field>

            <x-field class="form-group col-4">
                <x-label for="id">{{ __('Nombre') }}</x-label>
                <x-input name="id" class="form-control-sm" value="{{ old('id', $userrol->name) }}" readonly/>
            </x-field>
            <x-field class="form-group col-2">
                <x-label for="id">{{ __('Cédula') }}</x-label>
                <x-input name="id" class="form-control-sm" value="{{ old('id', $userrol->cedula) }}" readonly/>
            </x-field>
            <x-field class="form-group col-5">
                <x-label for="id">{{ __('Email') }}</x-label>
                <x-input name="id" class="form-control-sm" value="{{ old('id', $userrol->email) }}" readonly/>
            </x-field>
        </div>
        @if (count($rol))
            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
                <div class="tbl-header">
                    <table  class="table table-striped table-bordered  table-sm text-center" width="100%">
                        <thead>
                            <tr align="center" scope="col" class="table-success" >
                                <th>Códigos</th>
                                <th>Nombre del Rol</th>
                                <th>Incluir </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rol as $rolesItem)
                                <tr>
                                    <td>
                                        <a href="{{ route('configuracion.control.rol.show', $rolesItem) }}">
                                            {{ $rolesItem->id }}
                                        </a>
                                    </td>
                                    <td class="text-center" >
                                        {{ $rolesItem->name}}
                                    </td>
                                    {{-- Checkbox --}}
                                    <td>
                                        <div class="form-check">
                                            <input disabled readonly type="checkbox"   class="{{ $errors->has('rolesItem_id') ? 'is-invalid' : 'is-valid' }}" value="{{ $rolesItem->id }}"   {{ $userrol->hasrole($rolesItem->id) ? 'checked' : '' }}   name="rolesItem_id[]" >
                                            <div class="invalid-feedback">
                                                @error('rolesItem_id') {{ $message }} @enderror
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
       </x-slot>
    </x-card>
</div>
</section>
@endsection

@section('sidebar')
@include('layouts.sidebar')
@endsection
