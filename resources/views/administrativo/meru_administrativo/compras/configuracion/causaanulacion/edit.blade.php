@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('compras.configuracion.causaanulacion.index') }}">Causa de Anulacion</a></li>
					<li class="breadcrumb-item active text-bold">Editar</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">

			 	 <x-form method="put" action="{{ route('compras.configuracion.causaanulacion.update', $causaanulacion->id) }}">
				     @include('administrativo/meru_administrativo/compras/configuracion/causaanulacion/partials/_form', ['submit_text' => 'Guardar'])
				</x-form>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection

@section('js')
    <script type="text/javascript">
        window.livewire.on('alert', param => {
			toastr[param['type']](param['message']);
		});
    </script>
@endsection
