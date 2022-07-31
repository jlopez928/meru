@extends('layouts.aplicacion')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-bold"><a href="{{ route('proveedores.proceso.proveedor.index') }}">Proveedor</a></li>
                        <li class="breadcrumb-item active text-bold">Mostrar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

    </section>
@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection
