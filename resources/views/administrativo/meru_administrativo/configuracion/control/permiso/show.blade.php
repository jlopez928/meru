@extends('layouts.aplicacion')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('permiso.index') }}">Pagina Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Ver Permiso</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<x-card>
    <x-slot name="header">
        <h3 class="card-title text-bold">Permiso</h3>
    </x-slot>
<x-slot name="body">
    <div class="row col-12">
        <x-field class="col-1">
            <x-label for="id">{{ __('ID') }}</x-label>
            <x-input name="id" value="{{ old('id', $permiso->id) }}" readonly/>
        </x-field>
        <x-field class="col-5">
            <x-label for="modulo">Modulo</x-label>
            <x-select disabled readonly disable name="modulo_id" style="{{ '' }}" class="{{ $errors->has('modulo_id') ? 'is-invalid' : 'is-valid' }}">

                @foreach($modulo as $moduloItem)
                    <option value="{{ $moduloItem->id  }}" {{ old('modulo_id',$permiso->modulo_id) == $moduloItem->id  ? 'selected' : ''}}>
                        {{ $moduloItem->name }}
                    </option>
                @endforeach
            </x-select>
            <div class="invalid-feedback">
                @error('modulo_id') {{ $message }} @enderror
            </div>
        </x-field>
        <x-field class="col-2">
            <x-label for="status">Estado</x-label>
            <x-select    disabled readonly name="Estado" class="{{ $errors->has('status') ? 'is-invalid' : 'is-valid' }}">
                <option value="{{ old('status', $permiso->status) == '0' ? '0' : '1' }}" selected>{{ old('status', $permiso->status) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                <option value="{{ old('status', $permiso->status) == '0' ? '1' : '0'}}"> {{ old('status', $permiso->status) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
         </x-select>
        </x-field>

        </div>
            <div class="row col-12">
            <x-field class="col-8">
                <x-label for="name">Nombre</x-label>
                <x-input readonly name="name" class="{{ $errors->has('name') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese Descripción" value="{{ old('name', $permiso->name) }}"  />
                <div class="invalid-feedback">
                    @error('name') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="row col-12">
            <x-field class="col-8">
                <x-label for="route_name">Nombre de la Ruta</x-label>
                <x-input readonly name="route_name" class="{{ $errors->has('route_name') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese Nombre de la Ruta" value="{{ old('name', $permiso->route_name) }}"  />
                <div class="invalid-feedback">
                    @error('route_name') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        <div class="row col-12">
            <x-field class="col-8">
                <x-label for="guard_name">Nombre del Guard Name</x-label>
                <x-input readonly  name="guard_name" class="{{ $errors->has('guard_name') ? 'is-invalid' : 'is-valid' }}" placeholder="Ingrese Nombre del Guard Name" value="{{ old('name', $permiso->guard_name) }}"  />
                <div class="invalid-feedback">
                    @error('guard_name') {{ $message }} @enderror
                </div>
            </x-field>
        </div>
        @if (count($permiso->roles ))

        <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
        <h><b>Roles Asociados al Permiso </b> </h>
            <div class="tbl-header">
                <table  class="table table-striped table-bordered  table-sm text-center" width="100%">
                    <thead>
                        <tr align="center" scope="col" class="table-success" >
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permiso->roles  as $roles)
                            <tr>
                                <td>
                                    <a href="{{ route('configuracion.control.rol.show', $roles) }}">
                                        {{ $roles->id }}
                                    </a>
                                </td>
                                <td class="text-center" >
                                    {{ $roles->name}}
                                </td>
                                <td class="text-center" >
                                    {{ $roles->status}}
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

</section>
@endsection

@section('sidebar')
@include('layouts.sidebar')
@endsection
