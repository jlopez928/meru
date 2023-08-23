@extends('layouts.aplicacion')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-bold"><a href="{{ route('compras.proceso.solicitud.unidad.index') }}">{{ $descripcionModulo }}</a></li>
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
                    <livewire:administrativo.meru-administrativo.compras.proceso.solicitud :accion="$accion" :solicitud="$solicitud" :anoPro="$anoPro" :fechaGuardar="$fechaGuardar" :modulo="$modulo" :descripcionModulo="$descripcionModulo" />
                </div>
            </div>
        </div>
    </section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
