
@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-12">
            <div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">P&aacute;gina principal</a></li>
					<li class="breadcrumb-item active text-bold">Reporte</li>
				</ol>
            </div>
		</div>
	</div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                    <x-form method="get" target="_blank" action="{{ route('proveedores.reporte.print_proveedormunicipio') }}">
                        <x-card>
                            <x-slot:header>
                                <h3 class="card-title text-bold">Proveedores por Municipio</h3>
                            </x-slot>
                            <x-slot:body>
                                <livewire:administrativo.meru-administrativo.proveedores.reporte.proveedor-municipio-index-componet />
                            </x-slot>
                            <x-slot:footer>
                                <button type="submit" class="btn btn-sm btn-primary text-bold float-right" title="Generar PDF"><i class="fas fa-download"> Generar PDF</i></button>
                            </x-slot>
                        </x-card>
                    </x-form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection

