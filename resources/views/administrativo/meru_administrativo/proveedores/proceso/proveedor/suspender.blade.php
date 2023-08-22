@extends('layouts.aplicacion')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-bold"><a href="{{ route('proveedores.proceso.proveedor.index') }}">Proveedor</a></li>
                        <li class="breadcrumb-item active text-bold">Suspender</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-form name="formulario" method="put" action="{{ route('proveedores.proceso.proveedor.suspender', $proveedor) }}">
                        @include('administrativo/meru_administrativo/proveedores/proceso/proveedor/partials/_form',
                                [
                                    'message'  => '¿Está seguro de Suspender este Proveedor?',
                                    'btnTitle' => 'Suspender el Proveedor',
                                    'btnName'  => 'Suspender'
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
