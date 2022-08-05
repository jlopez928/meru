@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('configuracion.control.modulo.index') }}">Pagina Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Registrar Modulo</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                    <x-form method="post" action="{{ route('configuracion.control.modulo.store') }}">
                        @include('administrativo/meru_administrativo/configuracion/control/modulo/partials/_form', ['submit_text' => 'Guardar','accion' => 'nuevo'])
                    </x-form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection

