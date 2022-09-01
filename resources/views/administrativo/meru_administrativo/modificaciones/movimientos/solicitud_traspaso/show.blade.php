@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('modificaciones.movimientos.solicitud_traspaso.index') }}">Solicitud de Traspaso</a></li>
					<li class="breadcrumb-item active text-bold">Consultar</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<x-card>
                    <x-slot name="header">
                        <h3 class="card-title text-bold">Solicitud de Traspaso</h3>
                    </x-slot>
                
                    <x-slot name="body">
                        @include('administrativo/meru_administrativo/modificaciones/movimientos/solicitud_traspaso/partials/_body')
                    </x-slot>

                    <x-slot name="footer">
                    </x-slot>
                </x-card>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection

@push('scripts')
    <script type="text/javascript">
        $(function () {
            $('.select2bs4').select2();
        });
    </script>
@endpush