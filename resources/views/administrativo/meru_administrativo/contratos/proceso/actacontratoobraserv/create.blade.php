@extends('layouts.aplicacion')

@section('content')


<section class="content-header">
	<div class="container-fluid">
		<div class="row sm-12">
			<div class="col-12">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('contratos.proceso.actacontratobraserv.index') }}">Página Principal</a></li>
					<li class="breadcrumb-item active text-bold">Registrar Actas a Contratos de Obras/Servicios</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <livewire:administrativo.meru-administrativo.contratos.proceso.tab-acta-contrato-obra-serv :actaencnotaentrega="$encnotaentrega" :accion="'create'"/>
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection

