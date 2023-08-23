@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('modificaciones.movimientos.traspaso_presupuestario.index') }}">Traspaso Presupuestario</a></li>
					<li class="breadcrumb-item active text-bold">Anular</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <x-form method="put" action="{{ route('modificaciones.movimientos.traspaso_presupuestario.anular.update', $traspaso) }}">
                    <x-card>
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Traspaso Presupuestario</h3>
                        </x-slot>
                    
                        <x-slot name="body">
                            @include('administrativo/meru_administrativo/modificaciones/movimientos/traspaso/partials/_body')
                        </x-slot>

                        <x-slot name="footer">
                            <button type="submit" class="btn btn-sm btn-primary text-bold float-right">Anular</button>
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