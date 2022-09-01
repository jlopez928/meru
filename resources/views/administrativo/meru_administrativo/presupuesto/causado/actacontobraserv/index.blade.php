@extends('layouts.aplicacion')

@section('content')

    <section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-5">

				</div>
				<div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
                        <li class="breadcrumb-item active text-bold">Actas a Contratos de Obras/Servicios</li>
                    </ol>
				</div>
			</div>
		</div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                      <livewire:administrativo.meru-administrativo.presupuesto.causado.acta-cont-obra-desv-index />
                </div>
            </div>
        </div>
    </section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection
