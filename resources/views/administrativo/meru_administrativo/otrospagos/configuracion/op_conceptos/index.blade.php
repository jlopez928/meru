
@extends('layouts.aplicacion')

@section('content')


<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-12">
            <div class="col-sm-6">
				<x-button class="btn-success" href="{{ route('otrospagos.configuracion.conceptoservicio.create') }}" title="Nuevo"><i class="fas fa-plus-circle"></i> Nuevo</x-button>
                <x-button href="{{ route('otrospagos.configuracion.print_conceptos_servicios')}}" target="_blank" class="btn-primary" title="Generar PDF"><i class="fas fa-download"> Generar PDF</i></i></x-button>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item text-bold"><a href="{{ route('home') }}">Página principal</a></li>
					<li class="breadcrumb-item active text-bold">Listar Conceptos de Servicios</li>
				</ol>
			</div>
		</div>
	</div>

    <button id="button">Click me!</button>
    <canvas id="custom_canvas"></canvas>
</section>
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
                <livewire:administrativo.meru-administrativo.otros-pagos.configuracion.op-conceptos-index />
            </div>
		</div>
	</div>

    @push('scripts')
	<script type="text/javascript">

        const canvas = document.getElementById('custom_canvas')
const button = document.getElementById('button')

const jsConfetti = new JSConfetti({ canvas })

setTimeout(() => {
  jsConfetti.addConfetti()
}, 500)

button.addEventListener('click', () => {
  jsConfetti.addConfetti()
})
	</script>

@endpush
</section>

@endsection

@section('sidebar')
    @include('layouts.sidebar')
@endsection
