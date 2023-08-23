@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('proveedores.configuracion.ramo.index') }}">Ramos</a></li>
                    <li class="breadcrumb-item active text-bold">Registrar Ramos</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                    <x-form method="post" action="{{ route('proveedores.configuracion.ramo.store') }}">
                        @include('administrativo/meru_administrativo/proveedores/configuracion/ramo/partials/_form')
                    </x-form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection
