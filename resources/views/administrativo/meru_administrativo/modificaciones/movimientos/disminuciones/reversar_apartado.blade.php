@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('modificaciones.movimientos.disminucion.index') }}">Disminución</a></li>
					<li class="breadcrumb-item active text-bold">Reversar Apartado</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <x-form method="put" action="{{ route('modificaciones.movimientos.disminucion.reversar_apartado.update', $disminucion) }}">
                    <x-card>
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Disminución</h3>
                        </x-slot>
                    
                        <x-slot name="body">
                            @include('administrativo/meru_administrativo/modificaciones/movimientos/disminuciones/partials/_body')
                        </x-slot>

                        <x-slot name="footer">
                            <button type="submit" class="btn btn-sm btn-primary text-bold float-right">Reversar Apartado</button>
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