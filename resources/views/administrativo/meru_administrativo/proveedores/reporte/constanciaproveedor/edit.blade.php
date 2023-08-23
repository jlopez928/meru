@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('proveedores.reporte.constanciaproveedores.index') }}">Proveedores</a></li>
					<li class="breadcrumb-item active text-bold">Imprimir</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">

			 	 <x-form method="get" action="{{ route('proveedores.reporte.print_consproveedor') }}">
				     @include('administrativo.meru_administrativo.proveedores.reporte.constanciaproveedor/partials/_form', ['submit_text' => 'Guardar'])
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
