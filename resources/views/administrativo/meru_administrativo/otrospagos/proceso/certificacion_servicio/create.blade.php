@extends('layouts.aplicacion')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-bold"><a href="{{ route('otrospagos.proceso.certificacionservicio.index') }}">Página Principal</a></li>
                    <li class="breadcrumb-item active text-bold">Registrar Certificación de Servicio</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <livewire:administrativo.meru-administrativo.otros-pagos.proceso.tab-certificacion :certificacionservicio="$opsolservicio" :accion="'create'" :actprovision="$accion" />
			</div>
		</div>
	</div>
</section>

@endsection

@section('sidebar')
	@include('layouts.sidebar')
@endsection

