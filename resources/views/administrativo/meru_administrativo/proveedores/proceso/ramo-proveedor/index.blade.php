@extends('layouts.aplicacion')

@section('content')

    <section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2 d-flex justify-content-end">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
                    <li class="breadcrumb-item active text-bold">Ramos de Proveedor</li>
                </ol>
			</div>
		</div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <livewire:administrativo.meru-administrativo.proveedores.proceso.ramo-proveedor-index />
                </div>
            </div>
        </div>
    </section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection
