@extends('layouts.aplicacion')

@section('content')


<section class="content-header">
	<div class="container-fluid">
		<div class="row sm-12">
			<div class="col-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('cuentasxpagar.proceso.recepfactura.index') }}">Página Principal</a></li>
					<li class="breadcrumb-item active text-bold">Modificar Facturas</li>
				</ol>
			</div>
		</div>
	</div>
</section>


<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                 <livewire:administrativo.meru-administrativo.cuentas-por-pagar.proceso.modificar-factura :recepfactura="$recepfactura" :accion="'modificar'" :gerencias="$gerencias" :valor="$valor" :cxptipodocumento="$cxptipodocumento" :ano_pro="$ano_pro" :faccausadevolucion="$faccausadevolucion"/>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection

