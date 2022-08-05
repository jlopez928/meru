@extends('layouts.aplicacion')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.control.rol.index') }}">Pagina Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Ver Rol</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<div>
    <x-card>
        <x-slot name="header">
            <h3 class="card-title text-bold">Rol</h3>
        </x-slot>
        <x-slot name="body">
            <div class="row col-12">
                <x-field class="form-group col-2 offset-1">
                    <x-label for="id">{{ __('ID') }}</x-label>
                    <x-input id="id" name="id" class="text-center form-control-sm " value="{{ old('id', $rol->id) }}" readonly/>
                </x-field>

                <x-field class="form-group col-4 ">
                    <x-label for="name">Descripción</x-label>
                    <x-input readonly  name="name" class="form-control-sm  {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Ingrese Descripción" value="{{ old('name', $rol->name) }}"  />
                    <div class="invalid-feedback">
                        @error('name') {{ $message }} @enderror
                    </div>
                </x-field>
                <x-field class="form-group col-2 ">
                    <x-label for="Estado">Estado</x-label>
                    <x-select readonly  disabled  name="status" class="form-control-sm  {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <option value="{{ old('status', $rol->status) == '0' ? '0' : '1' }}" selected>{{ old('status', $rol->status) == '0' ? 'INACTIVO' : 'ACTIVO' }}</option>
                            <option value="{{ old('status', $rol->status) == '0' ? '1' : '0'}}"> {{ old('status', $rol->status) == '0' ? 'ACTIVO' : 'INACTIVO' }}</option>
                     </x-select>
                    <div class="invalid-feedback">
                        @error('status') {{ $message }} @enderror
                    </div>
                </x-field>
            </div>
            <div class="dropdown-divider col-12" style="border-color:#84b7e0 !important; padding-bottom: 20px !important;"></div>
                <h><b>Permisos Asociados al Rol  </b> </h>
                <div class="mb-2">
                    <table  class="table table-striped table-bordered  table-sm text-center" width="100%">
                        <thead>
                            <tr align="center" scope="col" class="table-success" >
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Ruta</th>
                                <th>Guard</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rol->permissions as $permission)
                                <tr>
                                    <td>
                                        <a href="{{ route('configuracion.control.permiso.show', $permission) }}">
                                            {{ $permission->id }}
                                        </a>
                                    </td>
                                    <td class="text-center" >
                                        {{ $permission->name}}
                                    </td>
                                    <td class="text-center" >
                                        {{ ($permission->route_name ) }}
                                    </td>
                                    <td class="text-center">
                                        {{ ($permission->guard_name  ) }}
                                    </td>
                                    <td class="text-center" >
                                        {{ ($permission->status ) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No se encontraron registros.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>
    </x-card>
</div>
</section>
@endsection
@section('sidebar')
@include('layouts.sidebar')
@endsection
