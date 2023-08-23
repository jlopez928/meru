@extends('layouts.aplicacion')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-bold"><a href="{{ route('proveedores.proceso.proveedor.index') }}">Proveedor</a></li>
                        <li class="breadcrumb-item active text-bold">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-form name="formulario" method="put" action="{{ route('proveedores.proceso.proveedor.update', $proveedor) }}">
                        @include('administrativo/meru_administrativo/proveedores/proceso/proveedor/partials/_form',
                        [
                            'message'  => '¿Está seguro de Modificar este Proveedor?',
                            'btnTitle' => 'Actualizar Datos del Proveedor',
                            'btnName'  => 'Actualizar'
                        ])
                    </x-form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
