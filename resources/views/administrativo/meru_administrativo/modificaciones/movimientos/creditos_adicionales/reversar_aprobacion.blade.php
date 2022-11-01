@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('modificaciones.movimientos.credito_adicional.index') }}">Crédito Adicional</a></li>
					<li class="breadcrumb-item active text-bold">Reversar Aprobación</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <x-form method="put" action="{{ route('modificaciones.movimientos.credito_adicional.reversar_aprobacion.update', $creditoAdicional) }}">
                    <x-card>
                        <x-slot name="header">
                            <h3 class="card-title text-bold">Crédito Adicional</h3>
                        </x-slot>
                    
                        <x-slot name="body">
                            @include('administrativo/meru_administrativo/modificaciones/movimientos/creditos_adicionales/partials/_body')
                        </x-slot>

                        <x-slot name="footer">
                            <button type="submit" class="btn btn-sm btn-primary text-bold float-right">Reversar Aprobación</button>
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