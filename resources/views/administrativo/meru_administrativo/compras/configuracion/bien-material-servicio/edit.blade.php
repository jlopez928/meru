@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('compras.configuracion.bien_material_servicio.index') }}">Bienes/Materiales/Servicios</a></li>
                    <li class="breadcrumb-item active text-bold">Editar Bienes/Materiales/Servicios</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <livewire:administrativo.meru-administrativo.compras.configuracion.bien-material-servicio :producto="$producto" :accion="'edit'" />
            </div>
        </div>
    </div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
