@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('compras.configuracion.grupo_producto.index') }}">Grupos de Productos</a></li>
                    <li class="breadcrumb-item active text-bold">Registrar Grupos de Productos</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <x-form method="post" action="{{ route('compras.configuracion.grupo_producto.store') }}">
                    @include('administrativo/meru_administrativo/compras/configuracion/grupo-producto/partials/_form', ['accion' => 'new'])
                </x-form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
