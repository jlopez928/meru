@extends('layouts.aplicacion')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-bold"><a href="{{ route('compras.proceso.solicitud_unidad.index') }}">Solicitudes Unidad</a></li>
                        <li class="breadcrumb-item active text-bold">Copiar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-form name="formulario" method="post" action="{{ route('compras.proceso.solicitud_unidad.copiar_solicitud') }}">
                        @include('administrativo/meru_administrativo/compras/proceso/solicitud-unidad/partials/_form')
                    </x-form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
